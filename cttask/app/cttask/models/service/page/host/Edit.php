<?php
/**
     * @name Service_Page_Host_Edit
 * @desc service_page_hsot_edit
 * @author 冯新(fengxin@taihe.com)
 */
class Service_Page_Host_Edit {

    private $dataServiceObj;

    public function __construct(){
        $this->dataServiceObj = new Service_Data_Host();
    }

    public function execute($arrInput){

        Bd_Log::debug(__CLASS__ . ' page service called');
        $errnoConf = Bd_Conf::getAppConf('errno');
        $errno = $errnoConf['errno'];
        if (!isset($arrInput['id']) || !$arrInput['id']){
            return [
                'errno' => $errno['param_error'],
            ];
        }
        $id = $arrInput['id'];

        //处理
        $query = $this->dataServiceObj->getOne($id);

        if(!$query){
            return [
                'errno' => $errno['system_error'],
            ];
        }

        return [
            'errno' => $errno['success'],
            'data' => [
                'groupinfo' => $query,
            ],
        ];
    }

    //根据ip地址获取机器信息
    public function getHostListByGroupIp($ip){
        return $this->dataServiceObj->getOneByConds([
            'conds' => [
                'ip' => $ip,
            ]
        ]);
    }
}
