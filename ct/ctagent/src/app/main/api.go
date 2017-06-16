package main

import (
    "encoding/json"
    "errors"
    "fmt"
    "io/ioutil"
    //"log"
    "os/exec"
    //"sync"
    "strings"
    "time"
    "cttask"
    l4g "github.com/hellomyboy/log4go"
    "github.com/hellomyboy/cron"
)


type TaskContext struct {
    task    *cttask.TaskInfo
    crontab *cron.Cron
}

type CtAgentServiceImpl struct {
    taskMap map[string]TaskContext
}


//执行命令
func execCmd(taskinfo *cttask.TaskInfo) (retMsg string, err error) {
	retryCount := int(*taskinfo.RetryCounter)
	execTimeout := int(*taskinfo.ExecTimeout)
	if retryCount < 1 {
		retryCount = 1
	}

	var result string = "success"
	for i := 0; i < retryCount; i++ {
		starttime := time.Now()
		cmdStr := fmt.Sprintf("su - %s -c  \"source ~/.bashrc && %s\"", *taskinfo.Account, taskinfo.CmdLine)
        cmdStr +=`;echo "status:$?"`
        l4g.Info("task (taskid:%s) ready execute, starttime (%s), cmd (%s),", taskinfo.TaskId, starttime.Format("2006-01-02 15:04:05"), cmdStr)
		cmd := exec.Command("/bin/sh", "-c", cmdStr)
		stdout, err := cmd.StdoutPipe()
		if err != nil {
            result = fmt.Sprintf("stdout pipe:%s", err.Error())
			l4g.Error("task (taskid:%s) exec fail for stdout, %d time, reason:%s", taskinfo.TaskId, (i+1), err.Error())
            continue
		}

		stderr, err := cmd.StderrPipe()
		if err != nil {
            result = fmt.Sprintf("stderr pipe:%s", err.Error())
			l4g.Error("task (taskid:%s) exec fail for stderr, %d time, reason:%s", taskinfo.TaskId, (i+1), err.Error())
            continue
		}

        //执行命令
		if err := cmd.Start(); err != nil {
            result = fmt.Sprintf("start err:%s", err.Error())
			l4g.Error("task (taskid:%s) exec fail for start, %d time, reason:%s", taskinfo.TaskId, (i+1), err.Error())
		    continue
		}

        //设置超时回调函数(kill)
		var timer *time.Timer
		if execTimeout > 0 {
			result = "failed,timeout"
			l4g.Info("task (taskid:%s) timeout is %d seconds", taskinfo.TaskId, execTimeout)
			timer = time.AfterFunc(time.Duration(execTimeout)*time.Second, func() {
				l4g.Error("tast (taskid :%s) exec timeout , ready killed...", taskinfo.TaskId)
				cmd.Process.Kill()
				l4g.Error("tast (taskid :%s) kill finish", taskinfo.TaskId)
			})
		}

        //检查执行过程是否有异常
		bytesErr, err := ioutil.ReadAll(stderr)
		if err != nil {
			l4g.Error("task (taskid:%s) ReadAll stderr occur error:%s", taskinfo.TaskId, err.Error())
            continue
		}

		var errInfo string = ""
		if len(bytesErr) != 0 {
            strErr := string(bytesErr)
            idx := len(strErr)
            if idx > 200 {
                idx = 200       
            }
            result = fmt.Sprintf("stderr:%s", strErr[:idx])
			errInfo = fmt.Sprintf("task (taskid:%s) exec (cmdline:%s) has error:%s", taskinfo.TaskId, taskinfo.CmdLine, string(bytesErr))
            l4g.Error(errInfo)
		}

        //读取执行的正常输出
		byteStdOut, err := ioutil.ReadAll(stdout)
		if err != nil {
			l4g.Error("task (taskid:%s) ReadAll stdout occur error:%s", taskinfo.TaskId, err.Error())
            continue
		}
        strStdOut := string(byteStdOut)
        execStatus := fmt.Sprintf("%s", strings.TrimRight(strStdOut[strings.LastIndex(strStdOut, "status")+7:], "\n"))

        //等待执行结果
		if err := cmd.Wait(); err != nil {
            result = fmt.Sprintf("wait err:%s", err.Error())
			l4g.Error("task (taskid:%s) Wait result occur error:%s", taskinfo.TaskId, err.Error())
            continue
		}

		if timer != nil {
			timer.Stop()
		}

        //发送执行情况信息
		end_time := time.Now()
		run_time := end_time.UnixNano() - starttime.UnixNano()
		loginfo := new(SentLogInfo)
		loginfo.Task_id = taskinfo.TaskId
		loginfo.Host_name = getLocalIp()
		loginfo.Run_time = fmt.Sprintf("%.3f", float64(run_time)/1000000000)
		loginfo.Begin_time = starttime.Format("2006-01-02 15:04:05")
		loginfo.End_time = end_time.Format("2006-01-02 15:04:05")
        loginfo.Exec_status = execStatus
		loginfo.Return_info = result

		//l4g.Info("task (taskid:%s) end at %s", taskinfo.TaskId, end_time.Format("2006-01-02 15:04:05"))
		txJson, err := json.Marshal(loginfo)
		if err != nil {
			l4g.Error("task (taskid:%s) parse json error:%s ", taskinfo.TaskId, err)
            return "", err
		}

        l4g.Info("task (taskid:%s) ready send loginfo:%s", taskinfo.TaskId, string(txJson))
        go func() {
            rcvMsg, err := SendLog(taskinfo.TaskId, txJson)
                if err == nil {
                    l4g.Info("task (taskid:%s) send log success, receive msg:%s", taskinfo.TaskId, rcvMsg)        
                } else {
                    l4g.Error("task (taskid:%s) send log fail", taskinfo.TaskId)
                }
        }()

        return "", nil
	}

    return "", errors.New("extend max retry time")
}


//老接口(新建上线)
func (this *CtAgentServiceImpl) AddTask(taskEntity *cttask.TaskEntity) (retVal cttask.RetVal, err error) {
    return this.OnlineTask(taskEntity) 
}

//老接口(下线、暂停、恢复、删除)
func (this *CtAgentServiceImpl) ModifyTask(taskEntity *cttask.TaskEntity) (retVal cttask.RetVal, err error) {
    taskInfo := taskEntity.TaskInfo
    taskId := taskInfo.TaskId
    taskAction := taskEntity.Action
    l4g.Info("task (taskid:%s) revoke ModifyTask, action:%s", taskId, *taskAction)

    switch *taskAction {
        case cttask.TaskAction_OFFLINE:
            return this.OfflineTask(taskId)
        case cttask.TaskAction_PAUSE:
            return this.PauseTask(taskId)
        case cttask.TaskAction_RESUME:
            return this.ResumeTask(taskId)
        case cttask.TaskAction_REMOVE:  //remove：等价于offline
            return this.OfflineTask(taskId)
        default:
            return cttask.RetVal_FAILED, errors.New("task action not valid")
    }
    return cttask.RetVal_FAILED, errors.New("ModifyTask Error")
}

//立即执行
func (this *CtAgentServiceImpl) Execute(taskEntity *cttask.TaskEntity) (cttask.RetVal, error) {
    taskInfo := taskEntity.TaskInfo
    l4g.Info("task (taskid:%s) revoke Execute...", taskInfo.TaskId)
    go execCmd(taskInfo) 
	return cttask.RetVal_SUCCESS, nil
}


//上线任务
func (this *CtAgentServiceImpl) OnlineTask(taskEntity *cttask.TaskEntity) (retVal cttask.RetVal, err error) {
    retVal = cttask.RetVal_SUCCESS
    taskInfo := taskEntity.TaskInfo
    taskId := taskInfo.TaskId 
    l4g.Info("task (taskid:%s) revoke OnlineTask...", taskId)

    //map中可能存在该task,删除
    if tc, ok := this.taskMap[taskId]; ok {
        l4g.Error("task (taskId:%s) exists", taskId)
        if tc.crontab != nil {
            tc.crontab.Stop()        
            delete(this.taskMap, taskId)
            l4g.Info("task (taskId:%s) first stop and deleted", taskId)
        }
    } 

    //创建新的cron
    newCrontab, err := createTask(taskInfo)
    if err != nil {
        return cttask.RetVal_FAILED, err        
    }
    this.taskMap[taskId] = TaskContext{task:taskInfo, crontab:newCrontab}
    l4g.Info("task (taskid:%s) create task with new cron, finish", taskId)
    return retVal, err
}

func createTask(taskInfo *cttask.TaskInfo) (crontab *cron.Cron, err error) {
    crontab, err = NewAndStartCron(*taskInfo.TrigerTime, func() { 
            execCmd(taskInfo) 
            }, taskInfo.TaskId)
    return crontab, err
}

//下线任务
func (this *CtAgentServiceImpl) OfflineTask(taskId string) (retVal cttask.RetVal, err error){
    l4g.Info("task (taskid:%s) revoke OfflineTask...", taskId)
    retVal = cttask.RetVal_SUCCESS
    if tc, ok := this.taskMap[taskId]; ok {
        if tc.crontab != nil {
            tc.crontab.Stop()
            delete(this.taskMap, taskId)
            l4g.Info("task (taskId:%s) stop and deleted, finish", taskId)
        } else {
            l4g.Error("task (taskId:%s) crontab is nil when offline, finish", taskId) 
        }
    } else {
        l4g.Error("task (taskId:%s) not exist when offline", taskId) 
        return cttask.RetVal_FAILED, errors.New("task not exist")
    }

    return retVal, nil
}

//暂停任务
func (this *CtAgentServiceImpl) PauseTask(taskId string) (retVal cttask.RetVal, err error){
    l4g.Info("task (taskid:%s) revoke PauseTask...", taskId)
    retVal = cttask.RetVal_SUCCESS
    if tc, ok := this.taskMap[taskId]; ok {
        if tc.crontab != nil {
            tc.crontab.Stop()
            l4g.Info("task (taskId:%s) paused, finish", taskId)
        } else {
            l4g.Error("task (taskId:%s) crontab is nil when pause, finish", taskId)        
        }
    } else {
        l4g.Error("task (taskId:%s) not exist when pause, finish", taskId) 
        return cttask.RetVal_FAILED, errors.New("task not exist")
    }

    return retVal, nil
}

//恢复任务
func (this *CtAgentServiceImpl) ResumeTask(taskId string) (retVal cttask.RetVal, err error){
    l4g.Info("task (taskid:%s) revoke ResumeTask...", taskId)
    retVal = cttask.RetVal_SUCCESS
    if tc, ok := this.taskMap[taskId]; ok {
        if tc.crontab != nil {
            if tc.crontab.IsRunning() == false {
                tc.crontab.Start()
                l4g.Info("task (taskId:%s) resumed, finish", taskId)
            } else {
                l4g.Info("task (taskId:%s) is still running, donot resume, finish", taskId)        
                return cttask.RetVal_SUCCESS, errors.New("still running")
            }
        } else {
            l4g.Error("task (taskId:%s) crontab is nil when resume, finish", taskId)        
        }
    } else { 
        l4g.Error("task (taskId:%s) not exist when resumed, finish", taskId) 
        return cttask.RetVal_FAILED, errors.New("task not exist")
    }

    return retVal, nil
}


