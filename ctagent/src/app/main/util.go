package main

import (
    "cttask"
    "encoding/json"
    "errors"
    "io/ioutil"
    "math/rand"
    "net/http"
    "time"
    l4g "github.com/hellomyboy/log4go"
    "github.com/toolkits/net"
)

//获取本机IP
func getLocalIp() (myip string) {
	ips, err := net.IntranetIP()
	if err != nil {
		l4g.Error("get local ip error:%s", err)
		panic(err)
	}

	var localip string
	for _, ip := range ips {
		localip = ip
		break
	}
	return localip
}

//获取任务列表
func getTaskList(url string) (tasks []cttask.TaskInfo, err error) {
	resp, err := http.Get(url)
	if err != nil {
		l4g.Error("http get url error ,the msg is %s", err)
        return nil, err
	} else {
        if resp != nil && resp.Body != nil {
            defer resp.Body.Close()
            if resp.StatusCode != http.StatusOK {
                l4g.Error("http get [url:%s] return error status, the error info is : ", url, errors.New(resp.Status).Error())
            }

            body, err := ioutil.ReadAll(resp.Body)
            if err != nil {
                l4g.Error("read http response error.the message is %s ", err.Error())
            }

            l4g.Info("request the url [%s],the http get resp is %s", url, string(body))
            s := make([]cttask.TaskInfo, 1)
            json.Unmarshal(body, &s)
            return s, nil
        } else {
            return nil, errors.New("resp or resp.Body is nil")        
        }
    }

    return nil, errors.New("error")
}

//获取随机值（毫秒数）
func genRandMillSec() int {
    r := rand.New(rand.NewSource(time.Now().UnixNano()))
    return int(r.Float32() * 10 * 1000)
}

