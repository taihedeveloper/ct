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

		$hostObj = new Service_Page_Host_Edit();
		$hostInfoTmp = $hostObj->execute([
			'id' => $id,
		]);
		$hostInfo = $hostInfoTmp['data']['groupinfo'];

		$tpl->assign('tplDir',Bd_AppEnv::getEnv('template'));
		$tpl->assign('host_info', $hostInfo);
		$tpl->display('cttask/host/edit.tpl');
	}

}
