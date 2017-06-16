<?php
/**
 * @name Service_Page_Task_Store
 * @desc service_page_task_store
 * @author 冯新(fengxin@taihe.com)
 */
class Service_Page_Task_Store {

    private $dataServiceObj;

    public function __construct(){
        $this->dataServiceObj = new Service_Data_Task();
    }

    /**
     * @brief 处理页面数据
     *
     * @param array $params 参数数据
     * @return array or boolean 返回结果
     */
    public function execute($arrParams){

        Bd_Log::debug(__CLASS__ . ' page service called');

        $errnoConf = Bd_Conf::getAppConf('errno');
        $errno = $errnoConf['errno'];

        $insertId = $this->dataServiceObj->add($arrParams);

        if ($insertId){
            return [
                'errno' => $errno['success'],
                'data' => [
                    'id' => $insertId,
                ],
            ];
        }
        return [
            'errno' => $errno['system_error'],
        ];
    }

    public function verify($arrParams){
        return $this->dataServiceObj->getOneByConds([
            'conds' => [
                'task_name' => $arrParams['task_name'],
            ]
        ]);
    }
}
