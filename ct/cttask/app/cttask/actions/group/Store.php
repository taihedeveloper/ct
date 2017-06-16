<?php
/**
 * @name Action_Store
 * @desc store 保存
 * @author 冯新(fengxin@taihe.com)
 */

class Action_Store extends Ap_Action_Abstract  {

	public function execute(){
		$errnoConf = Bd_Conf::getAppConf('errno');
		$errno = $errnoConf['errno'];

		$arrParams = Saf_SmartMain::getCgi();
		$post = $arrParams['post'];

		$post['host_name'] = substr($post['host_name'],0,-1);
		$post['create_time'] = date('Y-m-d H:i:s',time());

		$groupObj = new Service_Page_Group_Index();
		$groupInfo = $groupObj->getGroupNameByConds($post);

		if(!strlen($post['group_name'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '机器组名称不能为空!',
			]);
		}elseif(!strlen($post['host_name'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '机器不能为空!',
			]);
		}elseif($groupInfo){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '机器组名已经存在!',
			]);
		}

		//新建组
		$groupObj = new Service_Page_Group_Store();
		$groupRs = $groupObj->execute($post);

		$groupHostObj = new Service_Page_GroupHost_Store();
		$groupHostList = explode(',',$post['host_name']);
		foreach($groupHostList as $value){
			$info['group_id'] = $groupRs['data']['id'];
			$info['host_id'] = $value;
			$groupHostObj->execute($info);
		}

		if ($groupRs['errno'] == $errno['success']) {
			return Cttask_Output::json([
				'errno' => 0,
				'message' => '保存成功',
				'data' => $groupRs['data'],
			]);
		}
		return Cttask_Output::json([
			'errno' => 1,
			'message' => $groupRs['errno'],
		]);
	}
}
