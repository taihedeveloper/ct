<?php

class Action_Index extends Cttask_Base_Action  {

	public function execute(){
		$this->init();

		$arrParams = Saf_SmartMain::getCgi();
		$get = $arrParams['get'];

		$query = [];
		$query['group_name'] = $get['group_name'];

		$page = isset($get['page']) ? $get['page'] : 1;
		$size = isset($get['size']) ? $get['size'] : 20;
		$offset = ($page - 1) * $size;

		if(!empty($get['group_name'])){
			$conds['group_name like'] = '%'.$get['group_name'].'%';
		}

		$taskObj = new Service_Page_TaskGroup_Index();
		$pageInfo = $taskObj->execute([
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

		$this->tpl->assign('query', $query);
		$this->tpl->assign('tplDir',Bd_AppEnv::getEnv('template'));
		$this->tpl->assign('pagination', $pagination);
		$this->tpl->assign('list', $list);
		$this->tpl->display('cttask/taskgroup/index.tpl');
	}

	public function getList(){
		$taskObj = new Service_Page_TaskGroup_Index();
		return $taskObj->getList();
	}
}
