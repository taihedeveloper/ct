<?php
/**
 * @name Service_Page_TaskGroup_Store
 * @desc service_page_taskgroup_store
 * @author 冯新(fengxin@taihe.com)
 */
class Service_Page_TaskGroup_Store {

    private $dataServiceObj;

    public function __construct(){
        $this->dataServiceObj = new Service_Data_TaskGroup();
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
                'group_name' => $arrParams['group_name'],
            ]
        ]);
    }
}
