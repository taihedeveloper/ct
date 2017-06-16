<?php
/**
 * @name Action_Store
 * @desc store 保存
 * @author 冯新(fengxin@taihe.com)
 */

class Action_Store extends Ap_Action_Abstract  {

	/**
	 * @brief 入口
	 *
	 * @return tpl
	 */
	public function execute(){
		$errnoConf = Bd_Conf::getAppConf('errno');
		$errno = $errnoConf['errno'];

		$arrParams = Saf_SmartMain::getCgi();
		$post = $arrParams['post'];
		$post['create_time'] = date('Y-m-d H:i:s',time());

		$hostObj = new Service_Page_Host_Edit();
		$ip = $hostObj->getHostListByGroupIp($post['ip']);

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
		}elseif(!empty($ip)){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => 'IP地址已经存在!',
			]);
		}

		//插入机器信息
		$hostObj = new Service_Page_Host_Store();
		$hsotRs = $hostObj->execute($post);

		if ($hsotRs['errno'] == $errno['success']) {
			return Cttask_Output::json([
				'errno' => $errno['success'],
				'message' => '保存成功',
				'data' => $hsotRs['data'],
			]);
		}
		return Cttask_Output::json([
			'errno' => $errno['system_error'],
			'message' => '系统错误',
		]);
	}
}
