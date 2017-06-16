<?php


class Action_Check extends Ap_Action_Abstract  {

	public function execute(){

		$arrParams = Saf_SmartMain::getCgi();
		$post = $arrParams['post'];
		$get = $arrParams['get'];

		$errnoConf = Bd_Conf::getAppConf('errno');
		$errno = $errnoConf['errno'];


		$taskObj = new Service_Page_Task_Update();
		$pageInfo = $taskObj->execute($post);


		if ($pageInfo['errno'] == $errno['success']) {
			return Cttask_Output::json([
					'errno' => 0,
					'message' => '操作成功',
					'data' => $pageInfo['data'],
			]);
		}
		return Cttask_Output::json([
				'errno' => 1,
				'message' => $pageInfo['errno'],
		]);



	}
}
