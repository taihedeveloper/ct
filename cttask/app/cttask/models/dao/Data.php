<?php
/**
 * @name Dao_Sample
 * @desc sample dao, 可以访问数据库，文件，其它系统等
 * @author 刘重量(v_liuzhongliang@baidu.com)
 */
class Dao_Data {
    private $_db;
    private $tbl;
    /**
     *构造函数
     *@param string
     *@return obj 对表操作相应的对象
     **/
    public function __construct($table){
        $this->_db = Bd_Db_ConnMgr::getConn('ClusterOne');
        $this->tbl = $table;
    }
    /**
     * 查询记录
     *@param $fields array
     *@return mix
     **/
    public function select($fields, $conds=null, $options=null, $appends=null, $fetchType=Bd_DB::FETCH_ASSOC, $bolUseResult=false) {
        return $this->_db->select($this->tbl, $fields, $conds, $options, $appends, $fetchType, $bolUseResult);
    }
    
    /**
     * 插入记录
     * @param $row array
     * @return mix ( 成功:num   失败： 返回false)
     **/
    public function insert($row, $options=null, $onDup=null) {
        return $this->_db->insert($this->tbl, $row, $options, $onDup);
    }
    
    /**
     * 更新记录
     * @param $row array
     * @return mix 成功： 影响行数
     * 失败： 返回false
     **/
    public function update($row, $conds=null, $options=null, $appends=null) {
        $ret = $this->_db->update($this->tbl, $row, $conds, $options, $appends);
        return $ret;
    }
    
    /**
     * 删除记录
     * @param $conds array
     * @return 成功： 影响行数
     * 失败： 返回false
     **/
    public function delete($conds=null, $options=null, $appends=null) {
        $ret = $this->_db->delete($this->tbl, $conds, $options, $appends);
        return $ret;
    }

    /**
     * 执行sql语句
     * @param $sql string
     * @return 成功：有返回值的返回执行结果，没有的返回bool
     **/
    public function query($sql,$fetchType=Bd_DB::FETCH_ASSOC, $bolUseResult=false) {
        $ret = $this->_db->query($sql,$fetchType=Bd_DB::FETCH_ASSOC, $bolUseResult=false);
        return $ret;
    } 

    /**
     * 获取刚插入记录id
     *
     * @return 成功： 新记录ID
     * 失败： 返回false
     **/
    public function getInsertId() {
        return $this->_db->getInsertID();
    }

    /**
     * 获取一条记录
     * @param $fields array
     * @return 成功：查询结果
     * 失败： 返回false;
     **/
    public function getrow($fields, $conds=null, $options=null) {
        $res = $this->_db->select($this->tbl, $fields, $conds, $options, array('limit 1'));
        if($res === false ){
            return false;
        }
        if(isset($res[0]) && is_array($res[0])){
            return $res[0];
        }
        return null; 
    }

    /**
     * 获取单个字段值
     * @param $field string
     * @return 成功：查询结果
     * 失败： 返回false;
     **/
    public function getfield($fieldname, $conds=null, $options=null) {
        $fields = array($fieldname);
        $res = $this->getrow($fields, $conds, $options);
        if(isset($res[$fieldname])){
            return $res[$fieldname];
        }
        return $res;  
    }

    /**
     * 返回最近一条sql语句
     * @return string
     **/
    public function getLastSql() {
        $ret = $this->_db->getLastSQL();
        return $ret;
    }   

    /**
     * 获取符合条件的行数
     * @param $cond array
     * @return mix 成功：查询结果
     * 失败： 返回false;
     **/
    public function getcount($conds=null, $options=null, $appends=null){
        return $this->_db->selectCount($this->tbl,$conds, $options, $appends);
    }

    /**
     * 开启一个事务
     * @param 
     * @return bool
     **/
    public function startTransaction(){
        return $this->_db->startTransaction();
    }
     
     /**
     * 提交当前事务
     * @param 
     * @return bool
     **/
    public function commit(){
        return $this->_db->commit();
    }
     
    /**
     * 回滚当前事务
     * @param
     * @return bool
     */
    public function rollback(){
        return $this->_db->rollback();
    }

    /** 
     * 
     * @param 
     * @return 成功：查询结果
     * 失败： 返回false;
     **/
    public function errno(){
        return $this->_db->errno();
    }
    
    /**
     * 
     * @param 
     * @return 成功：查询结果
     * 失败： 返回false;
     **/
    public function error(){
        return $this->_db->error();
    }
}
