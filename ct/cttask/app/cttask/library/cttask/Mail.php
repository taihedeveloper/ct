<?php

class Cttask_Mail{
	public function send_mail($to, $msg){
		//发送邮件

		$subject = "=?UTF-8?B?".base64_encode("CT任务中心")."?=";
		$message = "
				<html>
				<head>
				<title>CT任务中心</title>
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
				<p>CT任务中心任务信息</p>
				任务 : ".$msg." 已经提交到OP审核,请耐心等待,
				</body>
				</html>
				";
		//当发送HTML电子邮件时,请始终设置content-type
		$headers = "MIME-Version: 1.0"."\r\n";
		$headers.= "Content-type:text/html;charset=utf-8"."\r\n";
		//更多报头
		$headers.= "From: <system@taihe.com>"."\r\n";
		mail($to,$subject,$message,$headers);
	}
}
