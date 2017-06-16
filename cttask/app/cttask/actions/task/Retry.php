<?php


class Action_Retry extends Cttask_Base_Action  {

	public function execute(){

		$this->init();
		$user_name = $_SESSION['USER'];

		set_time_limit(0);

		$rpcConf = Bd_Conf::getAppConf('rpc');
		$port = $rpcConf['config']['port'];

		$errnoConf = Bd_Conf::getAppConf('errno');
		$errno = $errnoConf['errno'];

		$arrParams = Saf_SmartMain::getCgi();
		$get = $arrParams['get'];
		$post = $arrParams['post'];

		//获取任务信息
		$taskObj = new Service_Page_Task_Edit();
		$taskInfoTmp = $taskObj->execute([
				'id' => $post['task_id'],
		]);
		$taskInfo = $taskInfoTmp['data']['taskinfo'];

		//任务信息
		$args['taskId'] = $taskInfo['id'];						//任务id
		$args['cmdLine'] = $taskInfo['run_command'];			//运行命令
		$args['trigerTime'] = $taskInfo['crontab_time'];		//调度时间
		$args['waitTime'] = $taskInfo['wait_timeout_time'];		//等待超时时间
		$args['account'] = $taskInfo['run_user'];				//帐号
		$args['taskType'] = '1';
		$args['execTimeout'] = $taskInfo['run_timeout_time'];	//执行超时时间
		$args['retryCounter'] = $taskInfo['run_condition'];		//执行条件
		$args['retValue'] = '0';

		$task['function'] = $post['function'];
		$task['action'] = $post['rpc_action'];
		$task['args'] = $args;
		$task['port'] = $port;

		$result = Cttask_Task::execute(array($post['ip']), '/cttask/task', $task);

		if(!empty($result)){
			return Cttask_Output::json([
				'errno' => 22003,
				'message' => '重试失败,请联系OP处理',
			]);
		}else{
			return Cttask_Output::json([
				'errno' => 0,
				'message' => '操作成功',
			]);
		}
	}
}
