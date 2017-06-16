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
		$type = $get['type'];

		$groupObj = new Service_Page_Group_Edit();
		$groupInfoTmp = $groupObj->execute([
			'id' => $id,
		]);
		$groupInfo = $groupInfoTmp['data']['groupinfo'];



		if( $type == 'host_list'){

			//获取机器列表
			$hostObj = new Service_Page_Host_Index();
			$hostList = $hostObj->getHostList();

			$host_id = explode(',',$groupInfo['host_name']);

			for($i=0;$i<count($hostList);$i++){
				if(in_array($hostList[$i]['id'],$host_id)){
					$dstList[] = $hostList[$i];
				}else{
					$srcList[] = $hostList[$i];
				}
			}

			return Cttask_Output::json([
					'srcList' => $srcList,
					'dstList'=>$dstList,
			]);
		}

		$tpl->assign('tplDir',Bd_AppEnv::getEnv('template'));
		$tpl->assign('group_info', $groupInfo);
		$tpl->display('cttask/group/edit.tpl');
	}

}
