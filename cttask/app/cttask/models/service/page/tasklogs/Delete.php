<?php
/**
 * @name Service_Page_TaskLogs_Update
 * @desc service_page_tasklogs_update
 * @author 冯新(fengxin@taihe.com)
 */
class Service_Page_TaskLogs_Delete {

    private $dataServiceObj;

    public function __construct(){
        $this->dataServiceObj = new Service_Data_TaskLogs();
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
