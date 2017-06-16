<?php
/**
 * @name Action_Index
 * @desc index 分组
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
		$query['group_name'] = $get['group_name'];

		$page = isset($get['page']) ? $get['page'] : 1;
		$size = isset($get['size']) ? $get['size'] : 20;
		$offset = ($page - 1) * $size;

		if(!empty($get['group_name'])){
			$conds['group_name like'] = '%'.$get['group_name'].'%';
		}
		if(!empty($get['ip'])){
			$hostObj = new Service_Page_Host_Edit();
			$hostInfo = $hostObj->getHostListByGroupIp($get['ip']);
			$conds['host_name like'] = '%'.$hostInfo['id'].'%';
		}

		$groupObj = new Service_Page_Group_Index();
		$pageInfo = $groupObj->execute([
				'conds' => $conds,
				'appends' => [
					'offset' => $offset,
					'size' => $size,
				],
		]);
		$list = $pageInfo['data']['list'];

		$hostObj = new Service_Page_Host_Index();

		for($i=0;$i<count($list);$i++){
			$hostInfo = $hostObj->getHostListByConds([
					'conds' => array("id in" => explode(',',$list[$i]['host_name']),)
			]);
			unset($list[$i]['host_name']);
			foreach($hostInfo as $key => $value){
				$list[$i]['host_name'].=$value['ip']."<br>";
			}
		}

		$pagination = array(
			'page' => $page,
			'size' => $size,
			'total' => $pageInfo['data']['count'],
		);
		
		$tpl->assign('query', $query);
		$tpl->assign('tplDir',Bd_AppEnv::getEnv('template'));
		$tpl->assign('pagination', $pagination);
		$tpl->assign('list', $list);
		$tpl->display('cttask/group/list.tpl');
	}

}
