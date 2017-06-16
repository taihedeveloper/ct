<?php
/**
 * @name Service_Page_Group_Update
 * @desc service_page_group_update
 * @author 冯新(fengxin@taihe.com)
 */
class Service_Page_Group_Update {

    private $dataServiceObj;

    public function __construct(){
        $this->dataServiceObj = new Service_Data_Group();
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

    public function getList($arrParams){

        return $this->dataServiceObj->getOneByConds([
            'conds' => [
                'group_name' => $arrParams['group_name'],
                'id <>' => $arrParams['id'],
            ]
        ]);

    }
}
