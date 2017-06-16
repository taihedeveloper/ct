<?php
/**
 * @name Action_Create
 * @desc create 创建
 * @author 冯新(fengxin@taihe.com)
 */

class Action_Create extends Cttask_Base_Action  {
	public function execute(){

		$this->init();

		$tpl = Bd_TplFactory::getInstance();
		$tpl->assign('tplDir',Bd_AppEnv::getEnv('template'));
		$tpl->display('cttask/host/create.tpl');
	}

}
