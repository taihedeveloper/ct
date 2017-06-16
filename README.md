**ct**全称crontab task，既常说的定时任务。

后端服务ctagent用很轻量级的go实现，主要作用是管理定时任务，并在该执行的时间点去执行，然后把执行结果发送给前端，而定时任务的增删改查由前端服务维护，有变更的时候给ctagent发消息，然后ctagent做相应的调整，它本身并不维护这些任务，而是每一次启动只要从前端拉取所有的定时任务信息。

启动只需要*bash start_prod.sh*，记得修改log的配置路径

配置文件解释

server.port=889  //端口
log.interval.spec=00 * * * *  //任务crontab格式
api.task.url=http://192.168.1.42:8888/cttask/cttask/task/api?host_ip=  //重启获取所有task链接  后面拼机器ip
api.log.url=http://192.168.1.42:8888/cttask/tasklog/api //post执行结果链接



前端是用php实现，目前运行环境需要odp环境，还需要数据库保存所有ct任务以及机器和机器组。界面很清晰简单。
