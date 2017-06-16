<?php
/**
 * @name Service_Page_GroupHost_Index
 * @desc service_page_grouphost_index
 * @author 冯新(fengxin@taihe.com)
 */
class Service_Page_GroupHost_Index {

    private $dataServiceObj;

    public function __construct(){
        $this->dataServiceObj = new Service_Data_GroupHost();
    }

    public function execute($params = []){

        Bd_Log::debug(__CLASS__ . ' page service called');
        $errnoConf = Bd_Conf::getAppConf('errno');
        $errno = $errnoConf['errno'];

        return $this->dataServiceObj->getList($params);
    }
}
