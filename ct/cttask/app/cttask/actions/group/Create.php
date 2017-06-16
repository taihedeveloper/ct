<?php
/**
 * @name Action_Create
 * @desc create 创建
 * @author 冯新(fengxin@taihe.com)
 */

class Action_Create extends Cttask_Base_Action  {
	public function execute(){

		$this->init();

		//获取机器列表
		$hostObj = new Service_Page_Host_Index();
		$hostList = $hostObj->getHostList();

		$tpl = Bd_TplFactory::getInstance();
		$tpl->assign('tplDir',Bd_AppEnv::getEnv('template'));
		$tpl->assign('host_list', $hostList);
		$tpl->display('cttask/group/create.tpl');
	}

}
