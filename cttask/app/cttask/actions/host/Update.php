<?php
/**
 * @name Action_Update
 * @desc update 更新
 * @author 冯新(fengxin@taihe.com)
 */

class Action_Update extends Ap_Action_Abstract  {

	public function execute(){
		$errnoConf = Bd_Conf::getAppConf('errno');
		$errno = $errnoConf['errno'];

		$arrParams = Saf_SmartMain::getCgi();
		$post = $arrParams['post'];

		$hostObj = new Service_Page_Host_Index();
		$conds =  array(
				"ip" => $post['ip'],
				"id not in" => array($post['id'])
		);
		$hostInfo = $hostObj->getHostListByConds([
				'conds' => $conds,
		]);

		if(!strlen($post['host_name'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '机器名称不能为空!',
			]);
		}elseif(!strlen($post['ip'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => 'IP地址不能为空!',
			]);
		}elseif(!empty($hostInfo)){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => 'IP地址已经存在!',
			]);
		}

		//更新机器信息
		$taskObj = new Service_Page_Host_Update();
		$pageInfo = $taskObj->execute($post);

		if ($pageInfo['errno'] == $errno['success']) {
			return Cttask_Output::json([
				'errno' => 0,
				'message' => '保存成功',
				'data' => $pageInfo['data'],
			]);
		}
		return Cttask_Output::json([
			'errno' => 1,
			'message' => $pageInfo['errno'],
		]);
	}

}
