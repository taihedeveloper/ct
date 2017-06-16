<?php
/**
 * @name Action_Api
 * @desc api 获取任务列表api
 * @author 冯新(fengxin@taihe.com)
 */

class Action_Api extends Ap_Action_Abstract  {

	public function execute(){
		$errnoConf = Bd_Conf::getAppConf('errno');
		$errno = $errnoConf['errno'];

		$arrParams = Saf_SmartMain::getCgi();
		$get = $arrParams['get'];
		$post = $arrParams['post'];
		$host_ip = $get['host_ip'];

		$hostObj = new Service_Page_Host_Edit();
		$hostInfo = $hostObj->getHostListByGroupIp($host_ip);

		$json_arr = array();

		$groupHostObj = new Service_Page_GroupHost_Index();
		$groupHostList = $groupHostObj->execute([
				'conds' => [
						'host_id' => $hostInfo['id'],
				]
		]);

		if($groupHostList){

			for($i=0;$i<count($groupHostList);$i++){
				$groupIdArrTmp[$i] = $groupHostList[$i]['group_id'];
			}
			$groupIdArr = array_unique($groupIdArrTmp);


			$shieldhostObj = new Service_Page_ShieldHost_Index();
			$shieldhostList = $shieldhostObj->execute([
					'conds' => [
							'ip' => $host_ip,
					]
				]
			);

			$taskObj = new Service_Page_Task_Index();
			$taskList = $taskObj->getTaskList([
				'conds' => [
					'service_node in' => $groupIdArr,
					'status' => 1,
				]
			]);

			for($i=0;$i<count($shieldhostList);$i++){
				$taskIdArr[$i] = $shieldhostList[$i]['task_id'];
			}

			for($i=0;$i<count($taskList);$i++){
				if(in_array($taskList[$i]['id'], $taskIdArr)){
					continue;
				}

				$json_arr[$i]['TaskId'] = $taskList[$i]['id'];						//任务id
				$json_arr[$i]['CmdLine'] = $taskList[$i]['run_command'];			//运行命令
				$json_arr[$i]['TrigerTime'] = $taskList[$i]['crontab_time'];		//调度时间
				$json_arr[$i]['RetryCounter'] = $taskList[$i]['run_fail_num'];		//运行失败次数
				$json_arr[$i]['WaitTime'] = $taskList[$i]['wait_timeout_time'];		//等待超时时间
				$json_arr[$i]['ExecTimeout'] = $taskList[$i]['run_timeout_time'];	//执行超时时间
				$json_arr[$i]['Account'] = $taskList[$i]['run_user'];				//账号
			}
		}
		echo json_encode($json_arr);
	}
}
