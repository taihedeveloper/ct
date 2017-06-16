<?php
/**
 * @name Service_Page_Group_Index
 * @desc service_page_group_index
 * @author 冯新(fengxin@taihe.com)
 */
class Service_Page_Group_Index {

    private $dataServiceObj;

    public function __construct(){
        $this->dataServiceObj = new Service_Data_Group();
    }

    /**
     * @brief 机器组列表
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

    /**
     * @brief 仅机器组列表
     *
     * @param array $params 参数数据
     * @return array or boolean 返回结果
     */
    public function getGroupList(){
        Bd_Log::debug(__CLASS__ . ' page service called');
        return $this->dataServiceObj->getList();
    }

    /**
     * @brief 获取机器名称
     *
     * @param array $params 参数数据
     * @return array or boolean 返回结果
     */
    public function getHostNameByConds($params = []){
        Bd_Log::debug(__CLASS__ . ' page service called');
        $info = $this->dataServiceObj->getListInfo($params);
        $groupInfo = $info['list'];

        $host_id = '';

        for($i=0;$i<count($groupInfo);$i++){
            $host_id.=$groupInfo[$i]['host_name'].',';
        }

        $hostIdArr = explode(',',substr($host_id,0,-1));
        $hostIdArrUnique = array_unique($hostIdArr);

        $hostObj = new Service_Page_Host_Index();
        $hostInfo = $hostObj->getHostListByConds([
            'conds' => array("id in" => $hostIdArrUnique,)
        ]);

        return $hostInfo;
    }

    public function getGroupNameByConds($arrParams){
        return $this->dataServiceObj->getOneByConds([
            'conds' => [
                'group_name' => $arrParams['group_name'],
            ]
        ]);
    }
}
