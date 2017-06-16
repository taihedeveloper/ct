<?php

class Action_Update extends Cttask_Base_Action  {

	public function execute(){

		$this->init();

		$errnoConf = Bd_Conf::getAppConf('errno');
		$errno = $errnoConf['errno'];

		$arrParams = Saf_SmartMain::getCgi();
		$post = $arrParams['post'];

		$taskGroupObj = new Service_Page_TaskGroup_Update();
		$info = $taskGroupObj->verify($post);

		$post['update_user'] = $_SESSION['USER'];

		//非空验证
		if(!strlen($post['group_name'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '任务分组不能为空!',
			]);
		}elseif($info){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '分组名称已经存在!',
			]);
		}

		$rs = $taskGroupObj->execute($post);

		if ($rs['errno'] == $errno['success']) {
			return Cttask_Output::json([
					'errno' => 0,
					'message' => '保存成功',
					'data' => $rs['data'],
			]);
		}
		return Cttask_Output::json([
				'errno' => 1,
				'message' => $rs['errno'],
		]);
	}
}
