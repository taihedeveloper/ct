<?php
/**
 * @name Tasklog_Controller
 * @desc 任务日志管理控制器
 * @author 冯新(fengxin@taihe.com)
 */
class Controller_Tasklog extends Ap_Controller_Abstract {
	public $actions = array(
		'create' => 'actions/tasklog/Create.php',
		'delete' => 'actions/tasklog/Delete.php',
		'edit' => 'actions/tasklog/Edit.php',
		'index' => 'actions/tasklog/Index.php',
		'store' => 'actions/tasklog/Store.php',
		'update' => 'actions/tasklog/Update.php',
		'api' => 'actions/tasklog/Api.php',
	);
}
