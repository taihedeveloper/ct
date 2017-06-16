<?php
/**
 * @name Service_Page_Task_Update
 * @desc service_page_task_update
 * @author 冯新(fengxin@taihe.com)
 */
class Service_Page_Task_Update {

    private $dataServiceObj;

    public function __construct(){
        $this->dataServiceObj = new Service_Data_Task();
    }

    public function execute($data){

        Bd_Log::debug(__CLASS__ . ' page service called');
        $errnoConf = Bd_Conf::getAppConf('errno');
        $errno = $errnoConf['errno'];

        $insertId = $this->dataServiceObj->updateOne($data['id'],$data);

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
                'id <>' => $arrParams['id'],
                'is_del' => 1,
            ]
        ]);
    }
}
