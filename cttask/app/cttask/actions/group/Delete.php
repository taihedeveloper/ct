<?php
/**
 * @name Action_Delete
 * @desc delete 删除
 * @author 冯新(fengxin@taihe.com)
 */

class Action_Delete extends Ap_Action_Abstract  {
	public function execute(){

		$errnoConf = Bd_Conf::getAppConf('errno');
		$errno = $errnoConf['errno'];

		$arrParams = Saf_SmartMain::getCgi();
		$post = $arrParams['post'];

		if(!isset($post['id']) || !is_numeric($post['id']))
		{
			return Cttask_Output::json([
				'errno' => $errno['param_error'],
			]);
		}

		$serviceObj = new Service_Page_Group_Delete();
		$pageInfo = $serviceObj->execute([
			'id' => $post['id'],
		]);

		$groupHostObj = new Service_Page_GroupHost_Delete();
		$groupHostObj->execute([
				'group_id' => $post['id'],
		]);

		if ($pageInfo['errno'] == $errno['success']) {
			return Cttask_Output::json([
				'errno' => $errno['success'],
				'message' => '操作成功',
				'data' => $pageInfo['data'],
			]);
		}
		return Cttask_Output::json([
			'errno' => $errno['system_error'],
			'message' => '系统错误',
		]);
	}

}
