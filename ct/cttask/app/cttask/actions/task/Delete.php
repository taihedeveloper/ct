<?php
/**
 * @name Action_Delete
 * @desc delete 删除
 * @author 冯新(fengxin@taihe.com)
 */

class Action_Delete extends Cttask_Base_Action  {

	public function execute(){

		$rpcConf = Bd_Conf::getAppConf('rpc');
		$port = $rpcConf['config']['port'];

		$errnoConf = Bd_Conf::getAppConf('errno');
		$errno = $errnoConf['errno'];

		$arrParams = Saf_SmartMain::getCgi();
		$post = $arrParams['post'];

		if(!isset($post['id']) || !is_numeric($post['id']))
		{
			return Cttask_Output::json([
				'errno' => $errno['param_error'],
			]);
		}

		//获取任务信息
		$taskObj = new Service_Page_Task_Edit();
		$taskInfoTmp = $taskObj->execute([
				'id' => $post['id'],
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
		$args['taskType'] = '1';								//任务类型
		$args['execTimeout'] = $taskInfo['run_timeout_time'];	//执行超时时间
		$args['retryCounter'] = $taskInfo['run_condition'];		//执行条件
		$args['retValue'] = '0';

		//删除上线任务
		if($taskInfo['status'] == '1'){

			$task['function'] = 'ModifyTask';
			$task['action'] = Cttask_Task::REMOVE;
			$task['args'] = $args;
			$task['port'] = $port;

			$result = Cttask_Task::execute($host_name, '/cttask/delete', $task);

			if(!empty($result)){
				//return Cttask_Output::json([
				//	'errno' => 22003,
				//	'message' => '以下机器未操作成功,如有问题请联系OP',
				//	'data' => $result,
				//]);
			}
		}


		$serviceObj = new Service_Page_Task_Delete();
		$pageInfo = $serviceObj->execute([
			'id' => $post['id'],
		]);

		if ($pageInfo['errno'] == $errno['success']) {
			//删除ct_group_host
			$groupHostObj = new Service_Page_GroupHost_Delete();
			$groupHostObj->execute([
					'group_id' => $post['id'],
			]);

			return Cttask_Output::json([
				'errno' => $errno['success'],
				'message' => '操作成功',
				'data' => $pageInfo['data'],
			]);
		}
		return Cttask_Output::json([
			'errno' => $errno['system_error'],
			'message' => '系统错误',
		]);
	}
}
