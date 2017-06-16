<?php
/**
 * @name Main_Controller
 * @desc 主控制器,也是默认控制器
 * @author 冯新(fengxin@taihe.com)
 */
class Controller_Main extends Ap_Controller_Abstract {
	public $actions = array(
		'index' => 'actions/task/Index.php',
		'logout' => 'actions/Logout.php',
	);
}
