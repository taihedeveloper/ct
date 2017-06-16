<?php
/**
 * @name Action_Index
 * @desc index 首页
 * @author 冯新(fengxin@taihe.com)
 */

class Action_Index extends Cttask_Base_Action  {

	public function execute(){
		
        $this->init();
		$tpl = Bd_TplFactory::getInstance();

		$arrParams = Saf_SmartMain::getCgi();
		$get = $arrParams['get'];

		$query = [];
		$query['ip'] = $get['ip'];
		$query['task_name'] = $get['task_name'];

		$page = isset($get['page']) ? $get['page'] : 1;
		$size = isset($get['size']) ? $get['size'] : 20;
		$offset = ($page - 1) * $size;

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

		for($i=0;$i<count($list);$i++){
			$service_node = explode(',',$list[$i]['service_node']);
			if($list[$i]['service_node_type'] == '1'){
				$groupInfo = $groupObj->getGroupNameByConds([
						'conds' => array("id in" => $service_node,)
				]);
				$list[$i]['group_name'] = '';
				foreach($groupInfo['list'] as $key => $value){
					$list[$i]['group_name'] .= $value['group_name']."<br>";
				}

				$groupHostInfo = $groupObj->getHostNameByConds([
						'conds' => array("id in" => $service_node,)
				]);
				$list[$i]['host_name'] = '';
				foreach($groupHostInfo as $key => $value){
					$list[$i]['host_name'] .= $value['ip']."<br>";
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
		$this->tpl->assign('tplDir',Bd_AppEnv::getEnv('template'));
		$this->tpl->assign('pagination', $pagination);
		$this->tpl->assign('list', $list);
		$this->tpl->display('cttask/task/index.tpl');
	}
}
