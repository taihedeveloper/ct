<?php
/**
 * @name Action_Import
 * @desc import 导入crontab数据
 * @author 冯新(fengxin@taihe.com)
 */

class Action_Import extends Ap_Action_Abstract  {

	public function execute(){

		$errnoConf = Bd_Conf::getAppConf('errno');
		$errno = $errnoConf['errno'];

		$arrParams = Saf_SmartMain::getCgi();
		$post = $arrParams['post'];

		if(true){

			$dir = "/home/work/ct_task/app/cttask/script/ct_file/";
			$path = 'cttask_2.xlsx';
			$list = Cttask_Excel::read($dir.$path,'utf-8');

			$task_group = array(
					'api' => '11',
					'base' => '12',
					'data_crm' => '13',
					'data_melody' => '14',
					'data_monkey' => '15',
					'data_personas' => '16',
					'data_warehouse' => '17',
					'heyinliang' => '18',
					'leboapi' => '19',
					'mall' => '20',
					'push' => '21',
					'quku' => '22',
					'tag' => '23',
					'tpass' => '24',
					'web' => '25',
					'广告&积分' => '26',
			);

			$task_level = array(
				'一级' => '1',
				'二级' => '2',
				'三级' => '3',
			);

			for($i=0;$i<count($list);$i++){
				$data = array();
				$id = $list[$i]['id'];
				$data['id'] = $list[$i]['id'];
				$data['task_name'] = $list[$i]['任务名称'];
				$data['task_desc'] = $list[$i]['任务简介'];
				$data['task_level'] = $task_level[$list[$i]['报警级别']];
				$data['run_fail_num'] = $list[$i]['报警频率'];
				$data['run_fail_num_leader'] = $list[$i]['报警频率'];
				$data['run_fail_num_op'] = $list[$i]['报警频率'];
				$data['alarm_email'] = $list[$i]['一级'];
				$data['alarm_email_leader'] = $list[$i]['二级'];
				$data['alarm_email_op'] = $list[$i]['三级'];
				$data['task_group'] = $task_group[$list[$i]['分组']];
				$data['auth'] = 2;

				$taskObj = new Service_Page_Task_Update();
				$pageInfo = $taskObj->execute($data);

				if ($pageInfo['errno'] == $errno['success']) {

				}else{
					echo '<pre/>';print_r($data);
				}
			}
		}



		if(false){
			$path = '/home/fengxin/CTtask/app/cttask/data/ip.xls';
			$list = Cttask_Excel::Import($path,'utf-8');

			$hostStoreObj = new Service_Page_Host_Store();
			for($i=0;$i<count($list);$i++){
				$hostEditObj = new Service_Page_Host_Edit();
				$ip = $hostEditObj->getHostListByGroupIp($list[$i][0]);
				if(!$ip){
					$post['host_name'] = $list[$i][0];
					$post['ip'] = $list[$i][0];
					$post['create_time'] = date('Y-m-d H:i:s',time());
					$rs = $hostStoreObj->execute($post);
				}
			}
		}else{
			$path = '/home/fengxin/CTtask/app/cttask/data/crontab.xls';
			$list = Cttask_Excel::Import($path,'utf-8');

			$hostArr = array();
			foreach($list as $key => $value){
				$hostArr[$key] = $value[1];
			}
			$hostArrUniqe = array_unique($hostArr);

			$hostObj = new Service_Page_Host_Store();
			foreach($hostArrUniqe as $key => $value){
				$post['host_name'] = $value;
				$post['ip'] = $value;
				$post['create_time'] = date('Y-m-d H:i:s',time());
				$rs = $hostObj->execute($post);
				$hostList[$value] = $rs['data']['id'];
			}

			$taskObj = new Service_Page_Task_Store();
			foreach($list as $key => $value){
				$data['task_name'] = $value[0];
				$data['run_command'] = $value[3];
				$data['crontab_time'] = $value[2];
				$data['run_user'] = 'work';
				$data['service_node'] = $hostList[$value[1]];
				$data['service_node_type'] = '2';
				$data['host_name'] = $hostList[$value[1]];
				$data['run_condition'] = 0;
				$data['wait_timeout_time'] = 0;
				$data['run_timeout_time'] = 0;
				$data['run_fail_num'] = 1;
				$data['alarm_email'] = $value[4];
				$data['alarm_note'] = $value[4];
				$data['manager'] = $value[4];
				$data['status'] = 2;
				$data['create_time'] = date('Y-m-d H:i:s',time());
				$taskObj->execute($data);
			}
		}
	}
}
