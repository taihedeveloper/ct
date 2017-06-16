<?php
/**
 * @name Action_Index
 * @desc index 首页
 * @author 冯新(fengxin@taihe.com)
 */

class Action_Index extends Cttask_Base_Action  {

	public function execute(){
		$this->init();

		$authConf = Bd_Conf::getAppConf('auth');
		$opList = $authConf['op'];
		$auth = $authConf['auth'];
		$user_name = $_SESSION['USER'];

		$conds['is_del'] = 1;
		$conds['auth in'] = array(1,2,3);

		$operate = true;
		$is_op = false;

		if(in_array($user_name, $opList)){
			$is_op = true;
			$conds['auth in'] = array(1,2,3);
		}else{
			//$conds['create_user'] = $user_name;
			$conds['auth in'] = array(2,3);
		}

		$arrParams = Saf_SmartMain::getCgi();
		$get = $arrParams['get'];

		$query = [];
		$query['ip'] = $get['ip'];
		$query['task_name'] = $get['task_name'];
		$query['task_id'] = $get['task_id'];
		$query['status'] = $get['status'];
		$query['auth'] = $get['auth'];
		$query['task_group'] = $get['task_group'];

		$page = isset($get['page']) ? $get['page'] : 1;
		$size = isset($get['size']) ? $get['size'] : 20;
		$offset = ($page - 1) * $size;

		if(!empty($get['task_id'])){
			$conds['id'] = $get['task_id'];
		}
		if(!empty($get['task_name'])){
			$conds['task_name like'] = '%'.$get['task_name'].'%';
		}
		if(!empty($get['ip'])){
			$hostObj = new Service_Page_Host_Edit();
			$hostInfo = $hostObj->getHostListByGroupIp($get['ip']);

			$groupHostObj = new Service_Page_GroupHost_Index();
			$groupHostList = $groupHostObj->execute([
					'conds' => [
							'host_id' => $hostInfo['id'],
					]
			]);
			for($i=0;$i<count($groupHostList);$i++){
				$groupIdArr[$i] = $groupHostList[$i]['group_id'];
			}
			$conds['service_node in'] = $groupIdArr;
		}
		if(!empty($get['status'])){
			$conds['status'] = $get['status'];
		}
		if(!empty($get['auth'])){
			$conds['auth'] = $get['auth'];
		}
		if(!empty($get['task_group'])){
			$conds['task_group'] = $get['task_group'];
		}

		$taskObj = new Service_Page_Task_Index();
		$pageInfo = $taskObj->execute([
				'conds' => $conds,
				'appends' => [
					'offset' => $offset,
					'size' => $size,
				],
		]);
		$list = $pageInfo['data']['list'];

		$groupObj = new Service_Page_Group_Index();
		$hostObj = new Service_Page_Host_Index();

		$taskGroupObj = new Service_Page_TaskGroup_Index();
		$taskGroupList = $taskGroupObj->getList();


		for($i=0;$i<count($list);$i++){
			$service_node = explode(',',$list[$i]['service_node']);
			if($list[$i]['service_node_type'] == '1'){
				$groupHostInfo = $groupObj->getHostNameByConds([
						'conds' => array("id in" => $service_node,)
				]);
				$list[$i]['host_name'] = '';
				foreach($groupHostInfo as $key => $value){
					$list[$i]['host_name'] .= $value['ip']."<br>";
				}

				foreach($taskGroupList as $key => $value){
					if($value['id'] == $list[$i]['task_group']){
						$list[$i]['task_group_name'] = $value['group_name'];
					}
				}

				if($list[$i]['task_level'] == 1){
					$list[$i]['task_level_name'] = '一级任务';
				}elseif($list[$i]['task_level'] == 2){
					$list[$i]['task_level_name'] = '二级任务';
				}elseif($list[$i]['task_level'] == 3){
					$list[$i]['task_level_name'] = '三级任务';
				}

			}elseif($list[$i]['service_node_type'] == '2'){
				$hostInfo = $hostObj->getHostListByConds([
						'conds' => array("id in" => $service_node,)
				]);
				$list[$i]['host_name'] = '';
				foreach($hostInfo as $key => $value){
					$list[$i]['host_name'] .= $value['ip']."<br>";
				}
			}
		}

		$pagination = array(
			'page' => $page,
			'size' => $size,
			'total' => $pageInfo['data']['count'],
		);

		$this->tpl->assign('query', $query);
		$this->tpl->assign('is_op', $is_op);
		$this->tpl->assign('task_group_list', $taskGroupList);
		$this->tpl->assign('tplDir',Bd_AppEnv::getEnv('template'));
		$this->tpl->assign('pagination', $pagination);
		$this->tpl->assign('list', $list);
		$this->tpl->display('cttask/task/index.tpl');
	}
}
