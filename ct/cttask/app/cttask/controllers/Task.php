<?php
/**
 * @name Main_Controller
 * @desc 任务管理控制器
 * @author 冯新(fengxin@taihe.com)
 */
class Controller_Task extends Ap_Controller_Abstract {
	public $actions = array(
		'create' => 'actions/task/Create.php',
		'delete' => 'actions/task/Delete.php',
		'edit' => 'actions/task/Edit.php',
		'index' => 'actions/task/Index.php',
		'store' => 'actions/task/Store.php',
		'update' => 'actions/task/Update.php',
		'task' => 'actions/task/Task.php',
		'api' => 'actions/task/Api.php',
		'import' => 'actions/task/Import.php',
		'host' => 'actions/task/Host.php',
		'check' => 'actions/task/Check.php',
		'retry' => 'actions/task/Retry.php',
	);
}
