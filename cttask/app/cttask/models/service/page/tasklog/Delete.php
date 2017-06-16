<?php
/**
 * @name Service_Page_TaskLog_Delete
 * @desc service_page_tasklog_delete
 * @author 冯新(fengxin@taihe.com)
 */
class Service_Page_TaskLog_Delete {

    private $dataServiceObj;

    public function __construct(){
        $this->dataServiceObj = new Service_Data_TaskLog();
    }

    /**
     * @brief 删除信息
     *
     * @param array $params 参数数据
     * @return array or boolean 返回结果
     */
    public function execute($arrInput){

        Bd_Log::debug(__CLASS__ . ' page service called');
        $errnoConf = Bd_Conf::getAppConf('errno');
        $errno = $errnoConf['errno'];

        //处理
        $this->dataServiceObj->deleteByConds($conds = [
            'begin_time <' => $arrInput['date'],
        ]);

    }
}
