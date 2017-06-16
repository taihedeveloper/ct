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

		$type = $get['type'];

		$query = [];
		$query['ip'] = $get['ip'];
		$query['host_name'] = $get['host_name'];

		$page = isset($get['page']) ? $get['page'] : 1;
		if($type == 'select_list'){
			$size = isset($get['size']) ? $get['size'] : 10;
		}else{
			$size = isset($get['size']) ? $get['size'] : 20;
		}

		$offset = ($page - 1) * $size;

		if(!empty($get['host_name'])){
			$conds['host_name like'] = '%'.$get['host_name'].'%';
		}
		if(!empty($get['ip'])){
			$conds['ip'] = $get['ip'];
		}

		if(isset($type) && $type == 'host_list'){
			$hostObj = new Service_Page_Host_Index();
			$hostList = $hostObj->getHostListByConds([
					'conds' => $conds,
			]);
			$list = $hostList;

			return Cttask_Output::json([
					'list' => $list,
			]);
		}elseif(isset($type) && $type == 'select_list'){
			$hostObj = new Service_Page_Host_Index();
			$hostList = $hostObj->getHostListByConds([
					'conds' => $conds,
			]);
			$list = $hostList;
		}else{
			$hostObj = new Service_Page_Host_Index();
			$pageInfo = $hostObj->execute([
					'conds' => $conds,
					'appends' => [
							'offset' => $offset,
							'size' => $size,
					],
			]);
			$list = $pageInfo['data']['list'];

			$pagination = array(
					'page' => $page,
					'size' => $size,
					'total' => $pageInfo['data']['count'],
			);
		}

		$tpl->assign('query', $query);
		$tpl->assign('tplDir',Bd_AppEnv::getEnv('template'));
		$tpl->assign('pagination', $pagination);
		$tpl->assign('list', $list);
		if(isset($type) && $type == 'select_list'){
			$tpl->display('cttask/group/hostList.tpl');
		}else{
			$tpl->display('cttask/host/list.tpl');
		}
	}
}
