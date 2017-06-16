<?php
/**
 * @name Service_Page_Host_Store
 * @desc service_page_host_store
 * @author 冯新(fengxin@taihe.com)
 */
class Service_Page_Host_Store {

    private $dataServiceObj;

    public function __construct(){
        $this->dataServiceObj = new Service_Data_Host();
    }

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
}
