<?php

class Action_Edit extends Cttask_Base_Action  {

	public function execute(){

		$this->init();

		$errnoConf = Bd_Conf::getAppConf('errno');
		$errno = $errnoConf['errno'];

		$arrParams = Saf_SmartMain::getCgi();
		$post = $arrParams['post'];
		$id = $post['id'];

		$taskObj = new Service_Page_TaskGroup_Edit();
		$taskInfoTmp = $taskObj->execute([
			'id' => $id,
		]);
		$taskInfo = $taskInfoTmp['data']['taskinfo'];

		if($taskInfo){
			return Cttask_Output::json([
					'errno' => 0,
					'message' => '',
					'data' => $taskInfo,
			]);
		}

		return Cttask_Output::json([
				'errno' => $errno['param_error'],
				'message' => '系统错误',
		]);

	}
}
