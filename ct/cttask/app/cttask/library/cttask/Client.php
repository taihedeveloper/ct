<?php
/**
 * @name Cttask_Task
 * @desc Cttask_Task
 * @author 冯新(fengxin@taihe.com)
 */
define("THRIFT_ROOT", dirname(__FILE__));
require_once(THRIFT_ROOT.'/Thrift/ClassLoader/ThriftClassLoader.php');

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

require_once THRIFT_ROOT.'/Thrift/ClassLoader/ThriftClassLoader.php';
require_once 'Types.php';
require_once 'CtTaskService.php';

class Cttask_Client{

	public function CTtask($host_name, $function, $action, $args, $port){

		//header("Content-type: text/html; charset=utf-8");
		//$startTime = Cttask_Client::getMillisecond();//记录开始时间

		/*
		#$ROOT_DIR = realpath(dirname(__FILE__).'/home/leilei/odp/php/phplib');
		$ROOT_DIR = '/home/leilei/odp/php/phplib';
		#$GEN_DIR = realpath(dirname(__FILE__).'/').'./gen-php';
		$GEN_DIR = '.';
		require_once $ROOT_DIR.'/Thrift/ClassLoader/ThriftClassLoader.php';
		require_once './CtTaskService.php';
		require_once './Types.php';

		use Thrift\ClassLoader\ThriftClassLoader;
		use Thrift\Protocol\TBinaryProtocol;
		use Thrift\Transport\TSocket;
		use Thrift\Transport\TSocketPool;
		use Thrift\Transport\TFramedTransport;
		use Thrift\Transport\TBufferedTransport;

		$loader = new ThriftClassLoader();
		$loader->registerNamespace('Thrift',$ROOT_DIR);
		$loader->registerDefinition('', $GEN_DIR);
		$loader->register();
		*/

		$thriftHost = '192.168.217.10';
		$thriftPort = 9090;

		$socket = new TSocket($thriftHost,$thriftPort);
		$socket->setSendTimeout(10000);#Sets the send timeout.
		$socket->setRecvTimeout(20000);#Sets the receive timeout.
		//$transport = new TBufferedTransport($socket); #传输方式：这个要和服务器使用的一致 [go提供后端服务,迭代10000次2.6 ~ 3s完成]
		$transport = new TFramedTransport($socket); #传输方式：这个要和服务器使用的一致[go提供后端服务,迭代10000次1.9 ~ 2.1s完成，比TBuffer快了点]
		$protocol = new TBinaryProtocol($transport);  #传输格式：二进制格式
		//$protocol = new TCompactProtocol($transport);  #传输格式：二进制格式
		$client = new \CtTaskServiceClient($protocol);# 构造客户端

		$transport->open();
		$socket->setDebug(TRUE);

		$te = new \TaskEntity();
		$te->action = 1;
		$te->taskInfo = new \TaskInfo();
		$te->taskInfo->taskId = 1;
		$te->taskInfo->taskType = 1;
		$te->taskInfo->cmdLine = "ls";
		$te->taskInfo->trigerTime = "triger time";
		$te->taskInfo->retValue = "ret value";
		$te->taskInfo->waitTime = 10;
		$te->taskInfo->waitTime = 10;
		$te->taskInfo->retryCounter = 3;
		$te->taskInfo->account = "mp3";
		$ret = $client->AddTask($te);
		echo $ret;

		//$s = new \idoall\org\demo\Student();
		// $s->id = 2;
		// $s->title = '插入二篇测试文章';
		// $s->content = '我就是这篇文章内容';
		// $s->author = 'liuxinming';
		// $client->put($s);

		//$endTime = Cttask_Client::getMillisecond();

		//echo "本次调用用时: :".$endTime."-".$startTime."=".($endTime-$startTime)."毫秒<br>";

		$transport->close();
	}

	public function getMillisecond() {
		list($t1, $t2) = explode(' ', microtime());
		return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
	}
}
