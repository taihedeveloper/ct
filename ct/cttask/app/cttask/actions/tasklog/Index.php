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
		$query['task_id'] = $get['task_id'];
		$query['task_name'] = $get['task_name'];
		$query['host_name'] = $get['host_name'];
		$query['begin_time'] = $get['begin_time'];
		$query['end_time'] = $get['end_time'];

		$BeginDate = date('Y-m-01', strtotime(date("Y-m-d")));
		$EndDate = date('Y-m-d', strtotime("$BeginDate +1 month -1 day"));

		$page = isset($get['page']) ? $get['page'] : 1;
		$size = isset($get['size']) ? $get['size'] : 20;
		$offset = ($page - 1) * $size;

		if(!empty($get['task_id'])){
			$conds['task_id'] = $get['task_id'];
		}
		if(!empty($get['task_name'])){
			$conds['task_name like'] = '%'.$get['task_name'].'%';
		}
		if(!empty($get['host_name'])){
			$conds['host_name like'] = '%'.$get['host_name'].'%';
		}
		if(!empty($get['begin_time'])){
			$conds['begin_time >='] = $get['begin_time'].' 00:00:00';
		}
		if(!empty($get['end_time'])){
			$conds['end_time <='] = $get['end_time'].' 23:59:59';
		}

		$groupObj = new Service_Page_TaskLog_Index();
		$pageInfo = $groupObj->execute([
			'conds' => $conds,
			'appends' => [
				'offset' => $offset,
				'size' => $size,
				'orderBy' => 'create_time desc',
			],
		]);
		$list = $pageInfo['data']['list'];

		$pagination = array(
			'page' => $page,
			'size' => $size,
			'total' => $pageInfo['data']['count'],
		);

		$tpl->assign('query', $query);
		$tpl->assign('tplDir',Bd_AppEnv::getEnv('template'));
		$tpl->assign('pagination', $pagination);
		$tpl->assign('list', $list);
		$tpl->display('cttask/tasklog/list.tpl');
	}
}
