<?php
/**
 * @name Action_Host
 * @desc host 机器
 * @author 冯新(fengxin@taihe.com)
 */

class Action_Host extends Ap_Action_Abstract  {

	public function execute(){

		$arrParams = Saf_SmartMain::getCgi();
		$post = $arrParams['post'];
		$group_id = $post['group_id'];

		if($group_id){
			$groupObj = new Service_Page_Group_Edit();
			$groupInfoTmp = $groupObj->execute([
					'id' => $group_id,
			]);
			$groupInfo = $groupInfoTmp['data']['groupinfo'];

			$hostObj = new Service_Page_Host_Index();
			$hostList = $hostObj->getHostListByConds([
					'conds' => array("id in" => explode(',',$groupInfo['host_name']),)
			]);

			return Cttask_Output::json([
					'errno' => '0',
					'message' => '成功',
					'list' => $hostList,
			]);
		}

		return Cttask_Output::json([
				'errno' => '22005',
				'message' => '参数错误',
		]);
	}


}
