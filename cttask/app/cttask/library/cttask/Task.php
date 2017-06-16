<?php
/**
 * @name Cttask_Task
 * @desc Cttask_Task
 * @author 冯新(fengxin@taihe.com)
 */
define("THRIFT_ROOT", dirname(__FILE__));
require_once(THRIFT_ROOT.'/Thrift/ClassLoader/ThriftClassLoader.php');
require_once 'Types.php';
require_once 'CtTaskService.php';

use Thrift\ClassLoader\ThriftClassLoader;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\TSocketPool;
use Thrift\Transport\TFramedTransport;
use Thrift\Transport\TBufferedTransport;

$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', THRIFT_ROOT);
$loader->registerDefinition('', THRIFT_ROOT);
$loader->register();

class Cttask_Task extends Cttask_Base_Action{

	//参数全部是： cttask.TaskEntity
	//=======================================================
	//上线   	function:AddTask		action: ONLINE		1
	//下线		function:ModifyTask		action: OFFLINE		2
	//暂停   	function:ModifyTask 	action: PAUSE		3
	//恢复		function:ModifyTask		action: RESUME		4
	//删除		function:ModifyTask		action: REMOVE		5
	//立即执行 	function:Execute		action: EXECUTE		6

	const ONLINE 	= 1;
	const OFFLINE 	= 2;
	const PAUSE 	= 3;
	const RESUME 	= 4;
	const REMOVE 	= 5;
	const EXECUTE 	= 6;

	//任务操作
	public function execute($host_name, $action, $task){
		$this->init();
		$result = array();
		foreach($host_name as $key => $value){
			$rs = Cttask_Task::CTtask($value, $task['function'], $task['action'], $task['args'], $task['port']);
			if($rs != 1){
				$result[$value]['code'] = md5($value.$task['args']['task_id']);
				$result[$value]['action'] = $action;
				$result[$value]['task'] = $task;
			}
			//日志
			$task['ip'] = $value;
			$user_name = $_SESSION['USER'];
			Cttask_Log::write_log($user_name, $action, $task, $rs);
		}
		return $result;
	}

	/**
	 * @brief CTtask	发送任务
	 *
	 * @param string $host_name 机器名称
	 * @param string $function 方法名
	 * @param string $action 动作
	 * @param array $args 参数
	 * @param int $port 端口号
	 * @return array or boolean 返回结果
	 */
	public function CTtask($host_name, $function, $action, $args, $port){

		$thriftHost = $host_name;
		$thriftPort = $port;

		//Bd_Log::debug($host_name."---".$function."---".$action."---".$args."---".$port);

		try{
			$socket = new TSocket($thriftHost,$thriftPort);
			$socket->setSendTimeout(1000);#Sets the send timeout.
			$socket->setRecvTimeout(1000);#Sets the receive timeout.
			//$transport = new TBufferedTransport($socket);#传输方式:这个要和服务器使用的一致[go提供后端服务,迭代10000次2.6~3s完成]
			$transport = new TFramedTransport($socket);#传输方式:这个要和服务器使用的一致[go提供后端服务,迭代10000次1.9~2.1s完成,比TBuffer快了点]
			$protocol = new TBinaryProtocol($transport);#传输格式：二进制格式
			$client = new \CtTaskServiceClient($protocol);#构造客户端

			$transport->open();
			$socket->setDebug(TRUE);

			$TaskEntity = new \TaskEntity();
			$TaskEntity->action = $action;
			$TaskEntity->taskInfo = new \TaskInfo();
			$TaskEntity->taskInfo->taskId = $args['taskId'];
			$TaskEntity->taskInfo->taskType = $args['taskType'];
			$TaskEntity->taskInfo->cmdLine = $args['cmdLine'];
			$TaskEntity->taskInfo->trigerTime = $args['trigerTime'];
			$TaskEntity->taskInfo->retValue = $args['retValue'];
			$TaskEntity->taskInfo->execTimeout = $args['execTimeout'];
			$TaskEntity->taskInfo->waitTime = $args['waitTime'];
			$TaskEntity->taskInfo->retryCounter = $args['retryCounter'];
			$TaskEntity->taskInfo->account = $args['account'];

			$result = $client->$function($TaskEntity);

			$transport->close();
		}catch (Exception $e){
			if($transport != NULL){
				$transport->close();
			}
			return $e->getMessage();
		}
		return $result;
	}
}
