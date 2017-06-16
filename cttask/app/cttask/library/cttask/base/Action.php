<?php
/**
 * @name Apollo_Base_Action
 * @desc 基础action类 实例化模板类,封装权限验证以及公共的模板变量
 * @author 孙槐(sunhuai@baidu.com)
 */

class Cttask_Base_Action extends Ap_Action_Abstract
{

    protected $tpl = null;
    /**
     * @brief 初始化 注入变量,校验权限等
     *
     * @param $checkPower boolean 是否校验权限
     * @return array
     */
    public function init($checkPower = true){

        $this->tpl = Bd_TplFactory::getInstance();
		$UUAPCAS = Cttask_UUAPCAS::getInstance();
		$UUAPCAS -> authenticate();
		$_SESSION['USER'] = $UUAPCAS->getUserName();
    }

    public function logout(){
        $UUAPCAS = Cttask_UUAPCAS::getInstance();
        $UUAPCAS->logout();
    }

    public function execute(){}

}
