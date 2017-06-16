<?php
/**
 * @name Action_Api
 * @desc group 分组api
 * @author 冯新(fengxin@taihe.com)
 */

class Action_Api extends Ap_Action_Abstract  {

	public function execute(){
		$errnoConf = Bd_Conf::getAppConf('errno');
		$errno = $errnoConf['errno'];

		$arrParams = Saf_SmartMain::getCgi();
		$get = $arrParams['get'];
		$post = $arrParams['post'];
		$data['group_name'] = $get['group_name'];

        $json_arr['ret_code'] = 0;
        $json_arr['ip_list'] = '';

		$groupObj = new Service_Page_Group_Index();
        $groupInfo = $groupObj->getGroupNameByConds($data);

        if($groupInfo){

            $host_name_list = explode(',',$groupInfo['host_name']);

            //获取机器列表
            $hostObj = new Service_Page_Host_Index();
            $hostInfo = $hostObj->getHostListByConds([
                'conds' => array("id in" => $host_name_list,)
            ]);

            //机器ip列表
            $host_name = array();
            foreach($hostInfo as $key => $value){
                $host_name[] = $value['ip'];
            }

            $json_arr['ret_code'] = 1;
            $json_arr['ip_list'] = $host_name;

        }

        echo json_encode($json_arr);

	}
}
