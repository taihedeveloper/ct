<?php
/**
 * @name Host_Controller
 * @desc 机器管理控制器
 * @author 冯新(fengxin@taihe.com)
 */
class Controller_Host extends Ap_Controller_Abstract {
	public $actions = array(
		'create' => 'actions/host/Create.php',
		'delete' => 'actions/host/Delete.php',
		'edit' => 'actions/host/Edit.php',
		'index' => 'actions/host/Index.php',
		'store' => 'actions/host/Store.php',
		'update' => 'actions/host/Update.php',
	);
}
