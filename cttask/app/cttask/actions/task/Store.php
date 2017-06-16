<?php
/**
 * @name Action_Store
 * @desc store 保存
 * @author 冯新(fengxin@taihe.com)
 */

class Action_Store extends Cttask_Base_Action  {

	public function execute(){

		$this->init();

		$errnoConf = Bd_Conf::getAppConf('errno');
		$errno = $errnoConf['errno'];

		$arrParams = Saf_SmartMain::getCgi();
		$post = $arrParams['post'];

		$taskObj = new Service_Page_Task_Store();

		$taskInfo = $taskObj->verify($post);

		$tmp = '';
		foreach($post['crontab_time'] as $value){
			if(isset($value)){
				$tmp.= $value.' ';
			}else{
				$tmp.= '* ';
			}
		}
		unset($post['crontab_time']);
		unset($post['host_id']);
		unset($post['group_id']);
		$post['crontab_time'] = $tmp;
		$post['service_node_type'] = 1;
		$post['create_time'] = date('Y-m-d H:i:s',time());
		$post['create_user'] = $_SESSION['USER'];
		$post['update_user'] = $_SESSION['USER'];
		$post['auth'] = 1;

		//非空验证
		if(!strlen($post['task_name'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '任务名称不能为空!',
			]);
		}elseif($taskInfo){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '任务名称已经存在!',
			]);
		}elseif(!strlen($post['run_fail_num'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '运行失败次数不能为空(RD接收)!',
			]);
		}elseif(!strlen($post['run_fail_num_leader'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '运行失败次数不能为空(Leader接收)!',
			]);
		}elseif(!strlen($post['run_fail_num_op']) && ($post['task_level'] == 1 || $post['task_level'] ==2)){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '运行失败次数不能为空(OP接收)!',
			]);
		}elseif(!strlen($post['alarm_email'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '报警邮箱不能为空(RD邮箱)!',
			]);
		}elseif(!strlen($post['alarm_email_leader'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '报警邮箱不能为空(Leader邮箱)!',
			]);
		}elseif(!strlen($post['alarm_email_op']) && ($post['task_level'] == 1 || $post['task_level'] ==2)){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '报警邮箱不能为空(OP邮箱)!',
			]);
		}elseif($post['task_level'] == 1 || $post['task_level'] ==2){
			if($post['run_fail_num'] > $post['run_fail_num_leader']){
				return Cttask_Output::json([
						'errno' => $errno['param_error'],
						'message' => 'RD接收次数不能大于Leader接收次数!',
				]);
			}elseif($post['run_fail_num_leader'] > $post['run_fail_num_op']){
				return Cttask_Output::json([
						'errno' => $errno['param_error'],
						'message' => 'Leader接收次数不能大于OP接收次数!',
				]);
			}elseif($post['run_fail_num'] > $post['run_fail_num_op']){
				return Cttask_Output::json([
						'errno' => $errno['param_error'],
						'message' => 'RD接收次数不能大于OP接收次数!',
				]);
			}
		}elseif($post['task_level'] == 3){
			if($post['run_fail_num'] > $post['run_fail_num_leader']){
				return Cttask_Output::json([
						'errno' => $errno['param_error'],
						'message' => 'RD接收次数不能大于Leader接收次数!',
				]);
			}
		}elseif(!strlen($post['run_command'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '运行命令不能为空!',
			]);
		}elseif(!strlen($post['run_user'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '执行帐号不能为空!',
			]);
		}elseif(!strlen($post['service_node'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '服务节点不能为空!',
			]);
		}elseif(!strlen($post['run_condition'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '运行成功判断条件不能为空!',
			]);
		}elseif(!strlen($post['wait_timeout_time'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '等待超时时间不能为空!',
			]);
		}elseif(!strlen($post['run_timeout_time'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '运行超时时间不能为空!',
			]);
		}elseif(!strlen($post['manager'])){
			return Cttask_Output::json([
					'errno' => $errno['param_error'],
					'message' => '管理者不能为空!',
			]);
		}

		/*
		$groupObj = new Service_Page_Group_Edit();
		$groupInfoTmp = $groupObj->execute([
				'id' => $post['service_node'],
		]);
		$groupInfo = $groupInfoTmp['data']['groupinfo'];

		$host_name = '';
		foreach($groupInfo as $key => $value){
			$host_name.=$value['host_name'].',';
		}
		$host_name_arr = explode(',',substr($host_name,0,-1));
		$host_name_arr_unique = array_unique($host_name_arr);
		$post['host_name'] = implode(',',$host_name_arr_unique);
		*/

		$shield_host_list = $post['shield_host_list'];
		unset($post['shield_host_list']);
		unset($post['group_id']);

		if($post['task_level'] == 3){
			$post['auth'] = 2;
		}

		$rs = $taskObj->execute($post);

		//保存屏蔽机器
        if($shield_host_list){
            $shieldhostObj = new Service_Page_ShieldHost_Store();
            $str = substr($shield_host_list,0,-1);
            $shield_host_list = explode("\n",$str);

            foreach($shield_host_list as $key => $value){
                $info['ip'] = $value;
                $info['task_id'] = $rs['data']['id'];
                $info['group_id'] = $post['service_node'];
                $shieldhostObj->execute($info);
            }
        }

		if ($rs['errno'] == $errno['success']) {

			//发送邮件
			$to_mail_list = explode(',',$post['alarm_email']);
			$to_mail = '';
			for($i=0;$i<count($to_mail_list);$i++){
				if(strstr($to_mail_list[$i],"@taihe.com")){
					$to_mail.=$to_mail_list[$i].',';
				}else{
					$to_mail.=$to_mail_list[$i].'@taihe.com,';
				}
			}
			//$to = $to_mail.'alert@taihe.com';
			$to = $to_mail;
			Cttask_Mail::send_mail($to, $post['task_name']."(".$rs['data']['id'].")");

			return Cttask_Output::json([
				'errno' => 0,
				'message' => '保存成功',
				'data' => $rs['data'],
			]);
		}
		return Cttask_Output::json([
			'errno' => 1,
			'message' => $rs['errno'],
		]);
	}

}
