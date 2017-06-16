<?php

class Cttask_Log{
	public function write_log($operator, $action, $task, $msg){

		$ret = Bd_Conf::getConf('/log/log_path');
		$filepath = $ret.'task/log_'.date('Y-m-d').'.php';

		$message = '';

		if(!file_exists($filepath)){
			$message .= "<"."?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?".">\n\n";
		}

		if(!$fp = @fopen($filepath, 'ab')){
			return FALSE;
		}

		$message .= '['.date("Y-m-d H:i:s").'] ';
		$message .= $action.' ';
		$message .= '[user_name='.$operator.'&';
		$message .= 'task_id='.$task['args']['taskId'].'&';
		$message .= 'ip='.$task['ip'].'&';
		$message .= 'port='.$task['port'].'&';
		$message .= 'function='.$task['function'].'&';
		$message .= 'action='.$task['action'].'] ';
		$message .= "--> [".$msg."] ";
		$message .= "\n";

		flock($fp, LOCK_EX);
		fwrite($fp, $message);
		flock($fp, LOCK_UN);
		fclose($fp);

		@chmod($filepath, 0666);
		return TRUE;
	}
}
