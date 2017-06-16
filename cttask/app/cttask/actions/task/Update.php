<?php
/**
 * @name Action_Update
 * @desc update 更新
 * @author 冯新(fengxin@taihe.com)
 */

class Action_Update extends Cttask_Base_Action  {

	public function execute(){

		$this->init();

		$rpcConf = Bd_Conf::getAppConf('rpc');
		$port = $rpcConf['config']['port'];

		$errnoConf = Bd_Conf::getAppConf('errno');
		$errno = $errnoConf['errno'];

		$arrParams = Saf_SmartMain::getCgi();
		$post = $arrParams['post'];

		if(!$post['default_email']){
			$post['default_email'] = 0;
		}

		$taskObj = new Service_Page_Task_Update();
		$taskVerifyInfo = $taskObj->verify($post);

		$taskEditObj = new Service_Page_Task_Edit();
		$taskInfo = $taskEditObj->execute([
				'id' => $post['id'],
		]);
		$taskInfo = $taskInfo['data']['taskinfo'];

		//获取更新之前机器列表
		$groupHostObj = new Service_Page_GroupHost_Index();
		$groupHostList = $groupHostObj->execute([
				'conds' => [
						'group_id' => $taskInfo['service_node'],
				]
		]);
		for($i=0;$i<count($groupHostList);$i++){
			$host_name_arr[$i] = $groupHostList[$i]['host_id'];
		}
		$hostObj = new Service_Page_Host_Index();
		$hostInfo = $hostObj->getHostListByConds([
				'conds' => array("id in" => $host_name_arr,)
		]);

		$host_name_update_before = array();
		foreach($hostInfo as $key => $value){
			$host_name_update_before[] = $value['ip'];
		}


		$tmp = '';
		foreach($post['crontab_time'] as $value){
			if(isset($value)){
				$tmp.= $value.' ';
			}else{
				$tmp.= '* ';
			}
		}
		unset($post['crontab_time']);
		unset($post['host_id']);
		unset($post['group_id']);
		unset($post['task']);
		$post['crontab_time'] = $tmp;
		$post['service_node_type'] = 1;
		$post['create_time'] = date('Y-m-d H:i:s',time());
		$post['update_user'] = $_SESSION['USER'];

		//非空验证
		if(!strlen($post['task_name'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '任务名称不能为空!',
			]);
		}elseif($taskVerifyInfo){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '任务名称已经存在!',
			]);
		}elseif(!strlen($post['run_fail_num'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '运行失败次数不能为空(RD接收)!',
			]);
		}elseif(!strlen($post['run_fail_num_leader'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '运行失败次数不能为空(Leader接收)!',
			]);
		}elseif(!strlen($post['run_fail_num_op']) && ($post['task_level'] == 1 || $post['task_level'] ==2)){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '运行失败次数不能为空(OP接收)!',
			]);
		}elseif(!strlen($post['alarm_email'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '报警邮箱不能为空(RD邮箱)!',
			]);
		}elseif(!strlen($post['alarm_email_leader'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '报警邮箱不能为空(Leader邮箱)!',
			]);
		}elseif(!strlen($post['alarm_email_op']) && ($post['task_level'] == 1 || $post['task_level'] ==2)){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '报警邮箱不能为空(OP邮箱)!',
			]);
		}elseif($post['task_level'] == 1 || $post['task_level'] ==2){
			if($post['run_fail_num'] > $post['run_fail_num_leader']){
				return Cttask_Output::json([
						'errno' => $errno['param_error'],
						'message' => 'RD接收次数不能大于Leader接收次数!',
				]);
			}elseif($post['run_fail_num_leader'] > $post['run_fail_num_op']){
				return Cttask_Output::json([
						'errno' => $errno['param_error'],
						'message' => 'Leader接收次数不能大于OP接收次数!',
				]);
			}elseif($post['run_fail_num'] > $post['run_fail_num_op']){
				return Cttask_Output::json([
						'errno' => $errno['param_error'],
						'message' => 'RD接收次数不能大于OP接收次数!',
				]);
			}
		}elseif($post['task_level'] == 3){
			if($post['run_fail_num'] > $post['run_fail_num_leader']){
				return Cttask_Output::json([
						'errno' => $errno['param_error'],
						'message' => 'RD接收次数不能大于Leader接收次数!',
				]);
			}
		}elseif(!strlen($post['run_command'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '运行命令不能为空!',
			]);
		}elseif(!strlen($post['run_user'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '执行帐号不能为空!',
			]);
		}elseif(!strlen($post['service_node'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '服务节点不能为空!',
			]);
		}elseif(!strlen($post['run_condition'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '运行成功判断条件不能为空!',
			]);
		}elseif(!strlen($post['wait_timeout_time'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '等待超时时间不能为空!',
			]);
		}elseif(!strlen($post['run_timeout_time'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '运行超时时间不能为空!',
			]);
		}elseif(!strlen($post['manager'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '管理者不能为空!',
			]);
		}

		$shield_host_list = $post['shield_host_list'];
		unset($post['shield_host_list']);

		$sign = false;

		if($taskInfo['auth'] == 3){
			$post['auth'] = 1;
			$sign = true;

		}

		//如果是任务状态是上线,重新发送任务
		if($taskInfo['status'] == '1'){

			//任务信息
			$args_before['taskId'] = $taskInfo['id'];						//任务id
			$args_before['cmdLine'] = $taskInfo['run_command'];			//运行命令
			$args_before['trigerTime'] = $taskInfo['crontab_time'];		//调度时间
			$args_before['waitTime'] = $taskInfo['wait_timeout_time'];		//等待超时时间
			$args_before['account'] = $taskInfo['run_user'];				//帐号
			$args_before['taskType'] = '1';								//任务类型
			$args_before['execTimeout'] = $taskInfo['run_timeout_time'];	//执行超时时间
			$args_before['retryCounter'] = $taskInfo['run_condition'];		//执行条件
			$args_before['retValue'] = '0';

			$args_later['taskId'] = $post['id'];						//任务id
			$args_later['cmdLine'] = $post['run_command'];			//运行命令
			$args_later['trigerTime'] = $post['crontab_time'];		//调度时间
			$args_later['waitTime'] = $post['wait_timeout_time'];		//等待超时时间
			$args_later['account'] = $post['run_user'];				//帐号
			$args_later['taskType'] = '1';								//任务类型
			$args_later['execTimeout'] = $post['run_timeout_time'];	//执行超时时间
			$args_later['retryCounter'] = $post['run_condition'];		//执行条件
			$args_later['retValue'] = '0';

			//获取更新之后的机器列表
			$groupHostObj = new Service_Page_GroupHost_Index();
			$groupHostList = $groupHostObj->execute([
					'conds' => [
							'group_id' => $post['service_node'],
					]
			]);
			for($i=0;$i<count($groupHostList);$i++){
				$host_name_arr[$i] = $groupHostList[$i]['host_id'];
			}
			$hostObj = new Service_Page_Host_Index();
			$hostInfo = $hostObj->getHostListByConds([
					'conds' => array("id in" => $host_name_arr,)
			]);
			$host_name_update_later = array();
			foreach($hostInfo as $key => $value){
				$host_name_update_later[] = $value['ip'];
			}

			//删除更新之前的任务
			$task['function'] = 'ModifyTask';
			$task['action'] = Cttask_Task::REMOVE;
			$task['args'] = $args_before;
			$task['port'] = $port;
			$result = Cttask_Task::execute($host_name_update_before, '/cttask/update', $task);

			//上线更新之后的任务
			$task['function'] = 'AddTask';
			$task['action'] = Cttask_Task::ONLINE;
			$task['args'] = $args_later;
			$task['port'] = $port;
			$result = Cttask_Task::execute($host_name_update_later, '/cttask/update', $task);

			if(!empty($result)){
				return Cttask_Output::json([
						'errno' => 22001,
						'message' => '以下机器未操作成功,如有问题请联系OP',
						'data' => $result,
				]);
			}
		}

		$pageInfo = $taskObj->execute($post);

		//删除屏蔽机器
		$shieldhostDeleteObj = new Service_Page_ShieldHost_Delete();
		$shieldhostDeleteObj->execute([
				'task_id' => $post['id'],
		]);

		if($shield_host_list){
			
			//添加屏蔽机器
			$shieldhostObj = new Service_Page_ShieldHost_Store();
			$str = substr($shield_host_list,0,-1);
			$shield_host_list = explode("\n",$str);

			foreach($shield_host_list as $key => $value){
				$info['ip'] = $value;
				$info['task_id'] = $post['id'];
				$info['group_id'] = $post['service_node'];
				$shieldhostObj->execute($info);
			}
		}

		if ($pageInfo['errno'] == $errno['success']) {

			if($sign){
				//发送邮件
				$to_mail_list = explode(',',$post['alarm_email']);
				$to_mail = '';
				for($i=0;$i<count($to_mail_list);$i++){
					if(strstr($to_mail_list[$i],"@taihe.com")){
						$to_mail.=$to_mail_list[$i].',';
					}else{
						$to_mail.=$to_mail_list[$i].'@taihe.com,';
					}
				}
				//$to = $to_mail.'alert@taihe.com';
				$to = $to_mail;
				Cttask_Mail::send_mail($to, $post['task_name']."(".$pageInfo['data']['id'].")");
			}

			return Cttask_Output::json([
				'errno' => 0,
				'message' => '保存成功',
				'data' => $pageInfo['data'],
			]);
		}
		return Cttask_Output::json([
			'errno' => 1,
			'message' => $pageInfo['errno'],
		]);
	}
}
