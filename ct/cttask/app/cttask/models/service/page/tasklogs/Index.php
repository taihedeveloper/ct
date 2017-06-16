<?php
/**
 * @name Service_Page_TaskLogs_Index
 * @desc service_page_tasklogs_index
 * @author 冯新(fengxin@taihe.com)
 */
class Service_Page_TaskLogs_Index {

    private $dataServiceObj;

    public function __construct(){
        $this->dataServiceObj = new Service_Data_TaskLogs();
    }

    /**
     * @brief 任务日志列表
     *
     * @param array $params 参数数据
     * @return array or boolean 返回结果
     */
    public function execute($params = []){

        Bd_Log::debug(__CLASS__ . ' page service called');
        $errnoConf = Bd_Conf::getAppConf('errno');
        $errno = $errnoConf['errno'];

        if (!isset($params['appends']['offset'], $params['appends']['size']))
        {
            return [
                'errno' => $errno['param_error'],
            ];
        }

        $info = $this->dataServiceObj->getListInfo($params);

        return [
            'errno' => $errno['success'],
            'data' => [
                'list' => $info['list'],
                'count' => $info['count'],
            ],
        ];
    }
}
