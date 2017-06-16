package main

import (
    "io/ioutil"
    "net/http"
    "strings"
    "time"
    l4g "github.com/hellomyboy/log4go"
)

type SentLogInfo struct {
    Task_id     string
    Host_name   string
    Begin_time  string
    End_time    string
    Run_time    string
    Return_info string
    Exec_status string
}



func SendLog(taskid string, sendmsg []byte) (retMsg string, err error){
   //l4g.Info("ready to send log for task: %s", taskid)
   for i :=0 ; i < 5; i++ {
       str, err := sendonce_post(sendmsg)
       if err==nil {
           return str, err
       } else {
           waitMillSec := genRandMillSec()
           l4g.Error("task (taskid:%s) send fail for %d time, wait:%d MillSecond", taskid, (i+1), waitMillSec)
           time.Sleep(time.Duration(waitMillSec) * time.Millisecond) 
       }
   }

   return "", err
}

func sendonce(sendMsg string) (retMsg string, err error){
    logurl := confmap["api.log.url"]
    resp, err := http.Get(logurl + sendMsg)
    if err != nil {
        l4g.Error("request url [%s] failed,the error msg is %s", logurl, err.Error())
        return "", err
    }

    defer func() {
        if resp!=nil {
            resp.Body.Close()
        }
    }()

    body, err := ioutil.ReadAll(resp.Body)
    if err != nil {
        l4g.Error("parse response package error for:%s\n", string(body))
        return "", err
    }

    return string(body), err
}

func sendonce_post(sendMsg []byte) (retMsg string, err error){
    logurl := confmap["api.log.url"]
    //body := bytes.NewBuffer([]byte(sendMsg))
    resp, err := http.Post(logurl, "application/x-www-form-urlencoded", strings.NewReader("post=" + string(sendMsg)))
    //resp, err := http.NewRequest("POST", logurl, strings.NewReader(string(sendMsg)))
    if err != nil {
        l4g.Error("request url [%s] failed,the error msg is %s", logurl, err.Error())
        return "", err
    }

    defer func() {
        if resp!=nil {
            resp.Body.Close()
        }
    }()

    body, err := ioutil.ReadAll(resp.Body)
    if err != nil {
        l4g.Error("parse response package error for:%s\n", string(body))
        return "", err
    }

    return string(body), err
}
