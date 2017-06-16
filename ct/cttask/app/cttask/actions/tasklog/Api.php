<?php
/**
 * @name Action_Api
 * @desc tasklog 任务日志api
 * @author 冯新(fengxin@taihe.com)
 */

class Action_Api extends Ap_Action_Abstract  {

	public function execute(){
		$errnoConf = Bd_Conf::getAppConf('errno');
		$errno = $errnoConf['errno'];

		$arrParams = Saf_SmartMain::getCgi();
		$post = $arrParams['post'];

		$taskMsg = json_decode($post['post'],TRUE);
		//$taskMsg = $post;

		$taskObj = new Service_Page_Task_Edit();
		$taskInfoTmp = $taskObj->execute([
			'id' => $taskMsg['Task_id'],
		]);
		$taskInfo = $taskInfoTmp['data']['taskinfo'];

		$data['uuid'] 			= '1';//uuid
		$data['task_id'] 		= $taskMsg['Task_id'];//任务id
		$data['task_name'] 		= $taskInfo['task_name'];//任务名
		$data['host_name'] 		= $taskMsg['Host_name'];//机器名称
		$data['begin_time'] 	= $taskMsg['Begin_time'];//开始时间
		$data['end_time'] 		= $taskMsg['End_time'];//结束时间
		$data['run_time'] 		= $taskMsg['Run_time'];//运行耗时
		$data['return_info'] 	= $taskMsg['Return_info'];//返回信息
		$data['run_status'] 	= $taskMsg['Exec_status'];//执行状态

        $data1 = $data;
        $data1['create_time'] = time();

		//保存日志信息
        $taskLogsStoreObj = new Service_Page_TaskLogs_Store();
        //$taskLogsStoreObj = new Service_Page_TaskLog_Store();
		$rs = $taskLogsStoreObj->execute($data1);

		if($taskMsg['Exec_status'] != 0){



			if($taskInfo['task_level'] == 1){
				$str = '【一级任务】';
			}elseif($taskInfo['task_level'] == 2){
				$str = '【二级任务】';
			}elseif($taskInfo['task_level'] == 3){
				$str = '【三级任务】';
			}

			$subject = "=?UTF-8?B?".base64_encode("CT任务中心报警".$str)."?=";
			$message = "
				<html>
				<head>
				<title>CT任务中心报警</title>
				</head>
				<style type='text/css'>
					body,table{
						font-size:12px;
					}
					table{
						table-layout:fixed;
						empty-cells:show;
						border-collapse: collapse;
						margin:0 auto;
					}
					td{
						height:30px;
					}
					h1,h2,h3{
						font-size:12px;
						margin:0;
						padding:0;
					}
					.table{
						border:1px solid #cad9ea;
						color:#666;
					}
					.table th {
						background-repeat:repeat-x;
						height:30px;
					}
					.table td,.table th{
						border:1px solid #cad9ea;
						padding:0 1em 0;
					}
					.table tr.alter{
						background-color:#f5fafe;
					}
				</style>
				<body>
				<p>CT任务中心错误信息</p>
				<table width='90%' class='table'>
				<tr>
				<th>任务id</th>
				<th>任务名</th>
				<th>机器名称</th>
				</tr>
				<tr>
				<td>".$taskMsg['Task_id']."</td>
				<td>".$taskInfo['task_name']."</td>
				<td>".$taskMsg['Host_name']."</td>
				</tr>
				</table>
				</body>
				</html>
				";

			//当发送HTML电子邮件时,请始终设置content-type
			$headers = "MIME-Version: 1.0"."\r\n";
			$headers.= "Content-type:text/html;charset=utf-8"."\r\n";

			//更多报头
			$headers.= "From: <ct@taihe.com>"."\r\n";

            //根据失败次数,任务级别发送报警邮件

			$to_mail = '';


            if($taskInfo['fail_num']+1 >= $taskInfo['run_fail_num']){

				$alarm_email_list = explode(',',$taskInfo['alarm_email']);
				$to_alarm_email = '';
				for($i=0;$i<count($alarm_email_list);$i++){
					if(strstr($alarm_email_list[$i],"@taihe.com")){
						$to_alarm_email.=$alarm_email_list[$i].',';
					}else{
						$to_alarm_email.=$alarm_email_list[$i].'@taihe.com,';
					}
				}

				$to_mail = substr($to_alarm_email,0,-1);
            }
			if($taskInfo['fail_num']+1 >= $taskInfo['run_fail_num_leader']){
				$alarm_email_leader_list = explode(',',$taskInfo['alarm_email_leader']);
				$to_alarm_email_leader = '';
				for($i=0;$i<count($alarm_email_leader_list);$i++){
					if(strstr($alarm_email_leader_list[$i],"@taihe.com")){
						$to_alarm_email_leader.=$alarm_email_leader_list[$i].',';
					}else{
						$to_alarm_email_leader.=$alarm_email_leader_list[$i].'@taihe.com,';
					}
				}
				$to_leader = substr($to_alarm_email_leader,0,-1);

				if($to_mail){
					$to_mail = $to_mail.','.$to_leader;
				}else{
					$to_mail = $to_leader;
				}
			}
			if($taskInfo['fail_num']+1 >= $taskInfo['run_fail_num_op'] && ($taskInfo['task_level'] == 1 || $taskInfo['task_level'] == 2)){
				$alarm_email_op_list = explode(',',$taskInfo['alarm_email_op']);
				$to_alarm_email_op = '';
				for($i=0;$i<count($alarm_email_op_list);$i++){
					if(strstr($alarm_email_op_list[$i],"@taihe.com")){
						$to_alarm_email_op.=$alarm_email_op_list[$i].',';
					}else{
						$to_alarm_email_op.=$alarm_email_op_list[$i].'@taihe.com,';
					}
				}
				$to_op = substr($to_alarm_email_op,0,-1);

				if($to_mail){
					$to_mail = $to_mail.','.$to_op;
				}else{
					$to_mail = $to_op;
				}
			}

			mail($to_mail,$subject,$message,$headers);

			$taskUpdateObj = new Service_Page_Task_Update();
			$info['id'] = $taskMsg['Task_id'];
			$info['fail_num'] = $taskInfo['fail_num']+1;
			$taskUpdateObj->execute($info);

		}else{
			$taskUpdateObj = new Service_Page_Task_Update();
			$info['id'] = $taskMsg['Task_id'];
			$info['fail_num'] = 0;
			$taskUpdateObj->execute($info);
		}

		if ($rs['errno'] == $errno['success']) {
			return Cttask_Output::json([
				'errno' => 0,
				'message' => 0,
			]);
		}
		return Cttask_Output::json([
			'errno' => 1,
			'message' => $rs['errno'],
		]);
	}
}
