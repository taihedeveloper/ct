<?php
/**
 * @name Action_Create
 * @desc create 创建
 * @author 冯新(fengxin@taihe.com)
 */

class Action_Create extends Cttask_Base_Action  {

	public function execute(){

		$this->init();

		$arrParams = Saf_SmartMain::getCgi();
		$get = $arrParams['get'];
		$type = $get['type'];

		if(isset($type) && $type == 'group_list'){
			//获取机器组列表
			$groupObj = new Service_Page_Group_Index();
			$groupList = $groupObj->getGroupList();

			return Cttask_Output::json([
					'list' => $groupList,
			]);
		}

		//获取机器组列表
		$groupObj = new Service_Page_Group_Index();
		$groupList = $groupObj->getGroupList();
		$taskGroupObj = new Service_Page_TaskGroup_Index();

		$taskGroupList = $taskGroupObj->getList();

		$tpl = Bd_TplFactory::getInstance();
		$tpl->assign('group_list', $groupList);
		$tpl->assign('task_group_list', $taskGroupList);
		$tpl->assign('tplDir',Bd_AppEnv::getEnv('template'));
		$tpl->display('cttask/task/create.tpl');
	}
}
