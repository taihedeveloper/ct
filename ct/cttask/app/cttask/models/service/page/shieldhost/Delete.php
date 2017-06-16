<?php
/**
 * @name Service_Page_ShieldHost_Delete
 * @desc service_page_shieldhost_delete
 * @author 冯新(fengxin@taihe.com)
 */
class Service_Page_ShieldHost_Delete {

    private $dataServiceObj;

    public function __construct(){
        $this->dataServiceObj = new Service_Data_ShieldHost();
    }

    public function execute($arrInput){

        Bd_Log::debug(__CLASS__ . ' page service called');
        $errnoConf = Bd_Conf::getAppConf('errno');
        $errno = $errnoConf['errno'];
        $id = $arrInput['task_id'];
        //处理
        $query = $this->dataServiceObj->deleteByConds($conds = [
            'task_id' => $id,
        ]);
    }
}
