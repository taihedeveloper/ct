<?php
/**
 * @name Action_Update
 * @desc update 更新
 * @author 冯新(fengxin@taihe.com)
 */

class Action_Update extends Cttask_Base_Action  {

	public function execute(){

		$this->init();

		$errnoConf = Bd_Conf::getAppConf('errno');
		$errno = $errnoConf['errno'];

		$arrParams = Saf_SmartMain::getCgi();
		$post = $arrParams['post'];
		$post['host_name'] = substr($post['host_name'],0,-1);

		$groupObj = new Service_Page_Group_Update();
		$groupInfo = $groupObj->getList($post);

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

		$taskObj = new Service_Page_Group_Update();
		$pageInfo = $taskObj->execute($post);

		$groupHostObj = new Service_Page_GroupHost_Delete();
		$groupHostObj->execute([
				'group_id' => $post['id'],
		]);

		$groupHostObj = new Service_Page_GroupHost_Store();
		$groupHostList = explode(',',$post['host_name']);
		foreach($groupHostList as $value){
			$info['group_id'] = $post['id'];
			$info['host_id'] = $value;
			$groupHostObj->execute($info);
		}

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
