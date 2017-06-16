<?php
/**
 * @name Service_Page_GroupHost_Delete
 * @desc service_page_grouphost_delete
 * @author 冯新(fengxin@taihe.com)
 */
class Service_Page_GroupHost_Delete {

    private $dataServiceObj;

    public function __construct(){
        $this->dataServiceObj = new Service_Data_GroupHost();
    }

    public function execute($arrInput){

        Bd_Log::debug(__CLASS__ . ' page service called');
        $group_id = $arrInput['group_id'];
        //处理
        $this->dataServiceObj->deleteByConds($conds = [
            'group_id' => $group_id,
        ]);
    }
}
