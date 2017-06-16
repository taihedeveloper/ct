<?php
/**
 * @name Service_Page_Task_Delete
 * @desc service_page_task_delete
 * @author 冯新(fengxin@taihe.com)
 */
class Service_Page_Task_Delete {

    private $dataServiceObj;

    public function __construct(){
        $this->dataServiceObj = new Service_Data_Task();
    }

    /**
     * @brief 处理页面数据
     *
     * @param array $arrInput 处理的数据
     * @return array or boolean 返回结果
     */
    public function execute($arrInput){

        Bd_Log::debug(__CLASS__ . ' page service called');
        $errnoConf = Bd_Conf::getAppConf('errno');
        $errno = $errnoConf['errno'];

        $id = $arrInput['id'];

        $insertId = $this->dataServiceObj->updateOne($id,array('is_del' => 0));

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
}
