package main

import (
    l4g "github.com/hellomyboy/log4go"
    "github.com/hellomyboy/cron"
)


func NewAndStartCron(inputTrigTime string, cb func(), taskName string) (*cron.Cron, error){
    l4g.Info("create cron[trigtime:%s, func:%s, taskName:%s]", inputTrigTime, cb, taskName)
	trigerTime := "0 " + inputTrigTime
    crontab := cron.New()
	crontab.AddFunc(trigerTime, cb, taskName)
	crontab.Start()

    return crontab, nil
}


