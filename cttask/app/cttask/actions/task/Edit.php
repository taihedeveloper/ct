<?php
/**
 * @name Action_Edit
 * @desc edit 编辑
 * @author 冯新(fengxin@taihe.com)
 */

class Action_Edit extends Cttask_Base_Action  {

	public function execute(){

		$this->init();

		$tpl = Bd_TplFactory::getInstance();

		$arrParams = Saf_SmartMain::getCgi();
		$get = $arrParams['get'];
		$id = $get['id'];
		$type = $get['type'];

		$taskObj = new Service_Page_Task_Edit();
		$taskInfoTmp = $taskObj->execute([
			'id' => $id,
		]);
		$taskInfo = $taskInfoTmp['data']['taskinfo'];
		for($i=0;$i<count($taskInfo);$i++){

			$taskInfo['run_command'] = str_replace('"','&quot;',$taskInfo['run_command']);//双引号
			$taskInfo['run_command'] = str_replace("'",'&#39;',$taskInfo['run_command']);//单引号
			$taskInfo['run_command'] = str_replace("{",'&#123;',$taskInfo['run_command']);//大括号左边
			$taskInfo['run_command'] = str_replace("|",'&#124;',$taskInfo['run_command']);//竖线
			$taskInfo['run_command'] = str_replace("}",'&#125;',$taskInfo['run_command']);//大括号右边
			$taskInfo['run_command'] = str_replace("[",'&#91;',$taskInfo['run_command']);//中括号左边
			$taskInfo['run_command'] = str_replace("]",'&#93;',$taskInfo['run_command']);//中括号右边
			$taskInfo['run_command'] = str_replace("(",'&#40;',$taskInfo['run_command']);//小括号左边
			$taskInfo['run_command'] = str_replace(")",'&#41;',$taskInfo['run_command']);//小括号右边

			$ct = explode(' ',$taskInfo['crontab_time']);
			$taskInfo['ct1'] = $ct[0];
			$taskInfo['ct2'] = $ct[1];
			$taskInfo['ct3'] = $ct[2];
			$taskInfo['ct4'] = $ct[3];
			$taskInfo['ct5'] = $ct[4];
		}

		//获取机器列表
		$hostObj = new Service_Page_Host_Index();
		$hostList = $hostObj->getHostList();

		//获取机器组列表
		$groupObj = new Service_Page_Group_Index();
		$groupList = $groupObj->getGroupList();

		//任务分组
		$taskGroupObj = new Service_Page_TaskGroup_Index();
		$taskGroupList = $taskGroupObj->getList();

		//获取屏蔽机器
        $shieldhostObj = new Service_Page_ShieldHost_Index();
        $shieldhostList = $shieldhostObj->execute([
                'conds' => [
                    'task_id' => $id,
                ]
            ]
        );
        $ip = '';
        foreach($shieldhostList as $key => $value){
            $ip.= $value['ip']."\n";
        }

		if($type && $type == 1){
			$result['shield_host_list'] = $ip;
			foreach($taskGroupList as $value){
				if($taskInfo['task_group'] == $value['id']){
					$result['task_group_info'] = $value;
				}
			}
			foreach($groupList as $value){
				if($taskInfo['service_node'] == $value['id']){
					$result['group_info'] = $value;
				}
			}
			$result['task_info'] = $taskInfo;

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
				$host_name[] = $value['ip']."\n";
			}
			$result['host_list'] = $host_name;

			return Cttask_Output::json([
				'errno' => 0,
				'message' => '成功',
				'data' => $result,
			]);
		}

		$tpl->assign('tplDir',Bd_AppEnv::getEnv('template'));
		$tpl->assign('shield_host_list', $ip);
		$tpl->assign('task_group_list', $taskGroupList);
		$tpl->assign('host_list', $hostList);
		$tpl->assign('group_list', $groupList);
		$tpl->assign('task_info', $taskInfo);
		$tpl->display('cttask/task/edit.tpl');
	}
}
