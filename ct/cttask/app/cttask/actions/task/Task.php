<?php
/**
 * @name Action_Task
 * @desc task 执行任务
 * @author 冯新(fengxin@taihe.com)
 */

class Action_Task extends Cttask_Base_Action  {

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

		$task_action = $get['task_action'];
		$task_id = $get['task_id'];

		//获取任务信息
		$taskObj = new Service_Page_Task_Edit();
		$taskInfoTmp = $taskObj->execute([
			'id' => $task_id,
		]);
		$taskInfo = $taskInfoTmp['data']['taskinfo'];

		$groupHostObj = new Service_Page_GroupHost_Index();
		$groupHostList = $groupHostObj->execute([
				'conds' => [
						'group_id' => $taskInfo['service_node'],
				]
		]);

		for($i=0;$i<count($groupHostList);$i++){
			$host_name_arr[$i] = $groupHostList[$i]['host_id'];
		}

		//获取机器列表
		$hostObj = new Service_Page_Host_Index();
		$hostInfo = $hostObj->getHostListByConds([
				'conds' => array("id in" => $host_name_arr,)
		]);

		//机器ip列表
		$host_name = array();
		foreach($hostInfo as $key => $value){
			$host_name[] = $value['ip'];
		}

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

		//参数全部是： cttask.TaskEntity
		//=======================================================
		//上线   	function:AddTask		action: ONLINE		1
		//下线		function:ModifyTask		action: OFFLINE		2
		//暂停   	function:ModifyTask 	action: PAUSE		3
		//恢复		function:ModifyTask		action: RESUME		4
		//删除		function:ModifyTask		action: REMOVE		5
		//立即执行 	function:Execute		action: EXECUTE		6
		$status = '';//1-上线;2-下线;3-暂停
		switch($task_action){
			case 1://任务上线
				$action = Cttask_Task::ONLINE;
				$function = 'AddTask';
				$status = 1;
				break;
			case 2://任务下线
				$action = Cttask_Task::OFFLINE;
				$function = 'ModifyTask';
				$status = 2;
				break;
			case 3://任务暂停
				$action = Cttask_Task::PAUSE;
				$function = 'ModifyTask';
				//$status = 2;
				break;
			case 4://任务恢复
				$action = Cttask_Task::RESUME;
				$function = 'ModifyTask';
				//$status = 1;
				break;
			case 6://立即执行
				$action = Cttask_Task::EXECUTE;
				$function = 'Execute';
				//$status = 2;
				break;
		}

		$task['function'] = $function;
		$task['action'] = $action;
		$task['args'] = $args;
		$task['port'] = $port;

		$result = Cttask_Task::execute($host_name, '/cttask/task', $task);


//		//循环执行任务
//		foreach($host_name as $key => $value){
//			$result[$value] = Cttask_Task::CTtask($value, $function, $action, $args, $port);
//		}
//
//		$messge = '';
//		foreach($result as $key => $value){
//			if($value != 1){
//				$messge.= ' [ '.$key." : ".$value.' ] <br>';
//			}
//		}

		if(!empty($result)){
			return Cttask_Output::json([
				'errno' => 22003,
				'message' => '以下机器未操作成功,如有问题请联系OP',
				'data' => $result,
			]);
		}else{
			if(isset($status) && $status == 1){
				$data['id'] = $taskInfo['id'];
				$data['status'] = 1;
				$obj = new Service_Page_Task_Update();
				$obj->execute($data);
			}elseif(isset($status) && $status == 2){
				$data['id'] = $taskInfo['id'];
				$data['status'] = 2;
				$obj = new Service_Page_Task_Update();
				$obj->execute($data);
			}

			return Cttask_Output::json([
					'errno' => 0,
					'message' => '操作成功',
			]);
		}
	}
}
