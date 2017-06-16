<?php
/**
 * @name Service_Data_Base
 * @desc data service 数据服务基类
 * @author 孙槐(sunhuai@baidu.com) 
 */
class Service_Data_Base
{
    protected $daoObj;
    protected $table;
    protected $idColumn;

    public function __construct()
    {
        $this->daoObj = new Dao_Data($this->table);
    }

    /**
     * @brief 修改属性
     *
     * @param string property 属性
     * @param mixed value 值
     * @return null
     */
    public function __set($property = '', $value = null)
    {
        $this->$property = $value;
    }

    /**
     * @brief 判断是否存在
     *
     * @param int $id 主键值
     * @return boolean
     */
    public function isExist($id = 0)
    {
        return true;
    }

    /**
     * @brief 根据主键id获取某行数据
     *
     * @param int $id 主键id 
     * @param array $fields 需要的字段
     * @return array or boolean 返回结果 
     */
    public function getOne($id = 0, $fields = ['*'])
    {
        Bd_Log::debug(__CLASS__ . ' data service ' . __FUNCTION__ . ' called'); 
        return $this->daoObj->getrow($fields, [
            $this->idColumn . ' =' => $id,
        ]);
    }

    /**
     * @brief 根据条件获取某行数据
     *
     * @param array $conds 需要的字段
     * @return array or boolean 返回结果
     */
    public function getOneByConds($params = [])
    {
        Bd_Log::debug(__CLASS__ . ' data service ' . __FUNCTION__ . ' called'); 
        $default = [
            'fields' => ['*'],
            'conds' => [],
        ];
        $params = array_merge($default, $params);
        $params = $this->rebuildParams($params);
        return $this->daoObj->getrow($params['fields'], $params['conds']);
    }

    /**
     * @brief 根据条件获取某行数据
     *
     * @param array $conds 条件
     * @return array or boolean 返回结果
     */
    public function getRow($conds = [])
    {
        $default = [
            'fields' => '*',
        ];
        $conds = array_merge($default, $conds);
        Bd_Log::debug(__CLASS__ . ' data service ' . __FUNCTION__ . ' called'); 
        return $this->daoObj->getrow($conds['fields'], $conds['conds']);
    }

    /**
     * 根据条件返回id
     *
     * @param array
     * @return num
     */
    public function getId($conds){
        return $this->daoObj->getfield('id',$conds);
    }

    /**
     * @brief 添加数据
     *
     * @param array $data 添加的数据
     * @return int or boolean 返回结果 insertId
     */
    public function add($data = [])
    {
        Bd_Log::debug(__CLASS__ . ' data service ' . __FUNCTION__ . ' called'); 
        $effectedRowsNum = $this->daoObj->insert($data);
        if ($effectedRowsNum)
        {
            return $this->daoObj->getInsertId();
        }
        return 0;
    }

    /**
     * @brief 批量添加
     *
     * @param array $data 添加的数据
     * @return boolean 执行结果
     */
    public function batchAdd($data = [])
    {
        Bd_Log::debug(__CLASS__ . ' data service ' . __FUNCTION__ . ' called'); 
        foreach ($data as $row)
        {
            if (!$this->add($row))
            {
                return false;
            }
        }
        return true;
    }

    /**
     * @brief 根据主键id更新某行数据
     *
     * @param int $id 主键id
     * @param array $info 更新信息
     * @return boolean 执行结果
     */
    public function updateOne($id = 0, $info = [])
    {
        Bd_Log::debug(__CLASS__ . ' data service ' . __FUNCTION__ . ' called'); 

        $effectedRowsNum = $this->daoObj->update(
            $info,
            [
                $this->idColumn . ' =' => $id,
            ],
            null,
            ['limit 1']
        );
        if ($effectedRowsNum !== false)
        {
            return true;
        }
        return false;
    }
	
    /**
     * @brief 根据conds更新某行数据
     *
     * @param int $conds 条件
     * @param array $info 更新信息
     * @return boolean 执行结果
     */
    public function updateByConds($conds = [], $info = [])
    {
        Bd_Log::debug(__CLASS__ . ' data service ' . __FUNCTION__ . ' called'); 
		$params = $this->rebuildParams([
            'conds' => $conds,
        ]);
        $effectedRowsNum = $this->daoObj->update(
            $info,
            $params['conds'],
            null,
            null
        );
        if ($effectedRowsNum !== false)
        {
            return true;
        }
        return false;
    }

    /**
     * @brief (仅)获取列表信息
     *
     * @param array $params 条件 非dao层的select 做了简化
     * @return array 列表相关信息
     */
    public function getList($params = [])
    {
        Bd_Log::debug(__CLASS__ . ' data service ' . __FUNCTION__ . ' called'); 
        $default = [
            'fields' => ['*'],
            'conds' => [],
            'options' => [],
            'appends' => [],
        ];
        $list = [];
        $params = array_merge($default, $params);
        $params = $this->rebuildParams($params);
        return $this->daoObj->select(
            $params['fields'],
            $params['conds'],
            $params['options'],
            $params['appends']
        );
    }

    /**
     * @brief 获取列表信息
     *
     * @param array $params 条件 非dao层的select 做了简化
     * @return array 列表相关信息
     */
    public function getListInfo($params = [])
    {
        Bd_Log::debug(__CLASS__ . ' data service ' . __FUNCTION__ . ' called'); 
        $default = [
            'fields' => ['*'],
            'conds' => [],
            'options' => [],
            'appends' => [],
        ];
        $list = [];
        $params = array_merge($default, $params);
        $params = $this->rebuildParams($params);
        $count = $this->daoObj->getcount(
            $params['conds'],
            $params['options']
        );
        if ($count)
        {
            $list = $this->daoObj->select(
                $params['fields'],
                $params['conds'],
                $params['options'],
                $params['appends']
            );
        }
        return [
            'list' => $list,
            'count' => $count,
        ];
    }
	
    /**
     * @brief 获取列表信息
     *
     * @param array $params 条件 非dao层的select 做了简化
     * @return array 列表相关信息
     */
    public function getListCount($params = [])
    {
        Bd_Log::debug(__CLASS__ . ' data service ' . __FUNCTION__ . ' called'); 
        $default = [
            'fields' => ['*'],
            'conds' => [],
            'options' => [],
            'appends' => [],
        ];
        $list = [];
        $params = array_merge($default, $params);
        $params = $this->rebuildParams($params);
        $count = $this->daoObj->getcount(
            $params['conds'],
            $params['options']
        );
        return $count;
    }

    /**
     * @brief 重新构造查询相关参数 还原为dao层select方法需要的
     *
     * @param array $params 条件
     * @return array $rebuild
     */
    protected function rebuildParams($params = [])
    {
        $rebuild = [
            'fields' => $params['fields'],
            'options' => $params['options'] ? $params['options'] : null,
        ];
        //都是key => value的格式
        $conds = $params['conds'];
        if (!$conds)
        {
            $rebuild['conds'] = null;
        }
        else
        {
            foreach ($conds as $fieldDesc => $limitVal)
            {
                $desc = explode(' ', $fieldDesc);
                $field = $desc[0];
                $op = isset($desc[1]) ? $desc[1] : '';
                switch($op)
                {
                    case '':
                    $rebuild['conds'][$field . ' ='] = $limitVal;
                    break;
                    case 'in':
                    $rebuild['conds'][$field . ' in (' . join(',', $limitVal) . ') and'] = '1 = 1';
                    break;
                    case 'not':
                    $rebuild['conds'][$field . ' not in (' . join(',', $limitVal) . ') and'] = '1 = 1';
                    break;
                    default:
                    $rebuild['conds'][$fieldDesc] = $limitVal;
                    break;
                }
            }
        }

        $appends = $params['appends'];
        if (!isset($appends['orderBy']))
        {
            $appends['orderBy'] = $this->idColumn . ' DESC';
        }
        $rebuild['appends'][] = 'ORDER BY ' . $appends['orderBy'];

        if (isset($appends['size']))
        {
            $size = $appends['size'];
            $offset = isset($appends['offset']) ? $appends['offset'] : 0;
            $rebuild['appends'][] = 'LIMIT ' . $offset . ',' . $size;
        }
        return $rebuild;
    }

    /**
     * @brief 根据主键id删除某行数据
     *
     * @param int $id 主键id
     * @return boolean 执行结果
     */
    public function deleteOne($id = 0 )
    {
        Bd_Log::debug(__CLASS__ . ' data service ' . __FUNCTION__ . ' called');
    
        $effectedRowsNum = $this->daoObj->delete(
                [
                        $this->idColumn . ' =' => $id,
                ],
                null,
                null
        );
        if ($effectedRowsNum !== false)
        {
            return true;
        }
        return false;
    }

    /**
     * @brief 根据条件删除某行数据
     *
     * @param array $conds 条件
     * @return boolean 执行结果
     */
    public function deleteByConds($conds = [])
    {
        Bd_Log::debug(__CLASS__ . ' data service ' . __FUNCTION__ . ' called');
        $params = $this->rebuildParams([
            'conds' => $conds,
        ]);
        $effectedRowsNum = $this->daoObj->delete($params['conds']);
        if ($effectedRowsNum !== false)
        {
            return true;
        }
        return false;
    }

    /**
     * @brief 开启一个事务
     *
     * @param
     * @return bool
     */
    public function startTransaction(){
        return $this->daoObj->startTransaction();
    }
     
    /**
     * @brief 提交当前事务
     *
     * @param
     * @return bool
     */
    public function commit(){
        return $this->daoObj->commit();
    }
     
    /**
     * @brief 回滚当前事务
     *
     * @param
     * @return bool
     */
    public function rollback(){
        return $this->daoObj->rollback();
    }


}
