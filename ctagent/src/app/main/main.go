package main

import (
    "errors"
    "flag"
    "log"
    "os"
    "runtime"
    "time"
    "cttask"
    "github.com/hellomyboy/cron"
    "github.com/larspensjo/config"
    "git.apache.org/thrift.git/lib/go/thrift"
    l4g "github.com/hellomyboy/log4go"
)

const VERSION = 1.6
var (
    configFile = flag.String("conf", "ct.conf", "cttask configuration file")
    confmap    = make(map[string]string)
)


func readConf() {
	l4g.Info("begin to read conf file [%s]", *configFile)
	cfg, err := config.ReadDefault(*configFile)
	if err != nil {
		l4g.Error("can not find conf file %s, error:%s", *configFile, err.Error())
		panic(err)
	}

	if cfg.HasSection("conf") {
		section, err := cfg.SectionOptions("conf")
		if err == nil {
			for _, v := range section {
				options, err := cfg.String("conf", v)
				if err == nil {
					confmap[v] = options
				}
			}
		}
	} else {
		l4g.Info("cannot find ctagent config info.")
        panic(errors.New("not ctagent config info"))
	}
}


func calcTaskNum(m map[string]TaskContext) {
	c := cron.New()
	//spec := "*/10 * * * * *"
    logSpec := confmap["log.interval.spec"]
	var mapkeys string = ""
	c.AddFunc(logSpec, func() {
		mapkeys = ""
        croninfo := ""
        i := 0
		for k, v := range m {
            if i > 0 {
			    mapkeys += ","
                croninfo += ","
            }
            i += 1
            mapkeys += k
            croninfo += v.crontab.ToString()
		}
		//l4g.Info("Heartbeat , the running task ids is [%s] ", mapkeys)
        l4g.Info("taskids:%s; cronlists:%s", mapkeys, croninfo)
	}, "heart")
	c.Start()
}


func init() {
	var logconf string
	flag.StringVar(&logconf, "log", "log4go.xml", "help message for flagname")
	flag.Parse()
	l4g.LoadConfiguration(logconf)
	time.Sleep(time.Second)
}

func main() {
    l4g.Info("ct(version:%v) start...", VERSION)
	defer func() {
		if err := recover(); err != nil {
			l4g.Error("the cttaskagent system has a error. the message is %s", err)
			if str, ok := err.(string); ok {
				l4g.Error("the error msg is %s", str)
			}
		}
		time.Sleep(time.Second)
		l4g.Close()
	}()

	runtime.GOMAXPROCS(runtime.NumCPU() * 2)
	readConf()
	transportFactory := thrift.NewTFramedTransportFactory(thrift.NewTTransportFactory())
	protocolFactory := thrift.NewTBinaryProtocolFactoryDefault()

	NetworkAddr := "0.0.0.0:" + confmap["server.port"]
	l4g.Info("the NetworkAddr is %s", NetworkAddr)
	serverTransport, err := thrift.NewTServerSocket(NetworkAddr)
	if err != nil {
		log.Println("Error!", err)
		os.Exit(1)
	}

	handler := &CtAgentServiceImpl{}
	handler.taskMap = make(map[string]TaskContext)
	localip := getLocalIp()
	url := confmap["api.task.url"] + localip
	tasks, err := getTaskList(url)
    if err !=nil {
        l4g.Error("get task list error:%s", err)        
        panic(err)
    }

	for _, v := range tasks {
        ti := cttask.TaskInfo{}
        ti = v
        crontab, err := createTask(&ti)
        if err != nil {
            l4g.Error("create task fail...") 
            panic("create task fail")
        }
		taskContext := TaskContext{&ti, crontab}
		handler.taskMap[ti.TaskId] = taskContext
		l4g.Info("create task [taskid:%s] succ...", taskContext.task.TaskId)
	}
	l4g.Info("put all receive tasks into map, length is:%d", len(tasks))

	//单独开启一个协程，用于查询当前在线的任务数，也是一个心跳检测机制
	go calcTaskNum(handler.taskMap)

	processor := cttask.NewCTTaskServiceProcessor(handler)
	server := thrift.NewTSimpleServer4(processor, serverTransport, transportFactory, protocolFactory)
	l4g.Info("thrift server started in [%s]", NetworkAddr)
	server.Serve()
	select {}
}
