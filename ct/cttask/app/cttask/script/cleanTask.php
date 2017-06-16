<?php

Bd_Init::init();
class Task_Cron{
    public function execute(){

		$task_tian = 7;//任务清理7天前
		$task_log_tian = 7;//日志清理7天前

		$task_date = date('Y-m-d', strtotime('-'.$task_tian.' days'));
		$task_log_date = date('Y-m-d', strtotime('-'.$task_log_tian.' days'));

        $taskObj = new Dao_Data("ct_task");

		//$sql = "SELECT id,`status`,update_time,is_del FROM ct_task WHERE `status` = 2 AND `update_time` < $date AND is_del = 1";
		//$task_info = $taskObj->query($sql);

		//清理任务
		$sql = "UPDATE ct_task SET is_del = 0 WHERE `status` = 2 AND `update_time` < $task_date AND is_del = 1";
		$taskObj->query($sql);

		//清理日志
		$sql = "DELETE FROM ct_task_logs WHERE end_time < '$task_log_date';";
		$taskObj->query($sql);

		//echo '<pre/>';print_r($task_info);print_r($sql);
    }
}
$obj = new Task_Cron();
$obj->execute();
exit(0);