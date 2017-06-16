<?php
/**
 * @name Group_Controller
 * @desc 机器组管理控制器
 * @author 冯新(fengxin@taihe.com)
 */
class Controller_Group extends Ap_Controller_Abstract {
	public $actions = array(
		'create' => 'actions/group/Create.php',
		'delete' => 'actions/group/Delete.php',
		'edit' => 'actions/group/Edit.php',
		'index' => 'actions/group/Index.php',
		'store' => 'actions/group/Store.php',
		'update' => 'actions/group/Update.php',
		'api' => 'actions/group/Api.php',
	);
}
