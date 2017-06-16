<?php
/**
 * @name Main_Controller
 * @desc 任务管理控制器
 * @author 冯新(fengxin@taihe.com)
 */
class Controller_Taskgroup extends Ap_Controller_Abstract {
	public $actions = array(
		'create' => 'actions/taskgroup/Create.php',
		'delete' => 'actions/taskgroup/Delete.php',
		'edit' => 'actions/taskgroup/Edit.php',
		'index' => 'actions/taskgroup/Index.php',
		'store' => 'actions/taskgroup/Store.php',
		'update' => 'actions/taskgroup/Update.php',
	);
}
