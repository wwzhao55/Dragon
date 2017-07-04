<?php
require 'init.php';
require_once '../api/phpExcel/PHPExcel.php';
if(isGet()){
	if(isAjax()){
		echo json_decode(array(
			'status'=>'fail',
			'message'=>'请求方式错误'
			));
		exit;

	}else{
		$type = isset($_GET['type'])?$_GET['type']:"userList";

		if($type == 'userList'){
			$name = "用户列表";
        	$user_lists = $db->select('users',['name','openid','mobile','QQ','address']);
        	
            $api->get_excel_userList($user_lists,$name);
		}else{
			$name = "用户获奖列表";
        	$user_award_lists = $db->select('user_award',['openid','award_id','created_at']);
        	$data = array();
        	foreach($user_award_lists as &$user_award) {
        		$user = $db->get('users','*',['openid'=>$user_award['openid']]);
        		$award = $db->get('awards',['name','type'],['id'=>$user_award['award_id']]);
        		array_push($data, [
                    'user_name'=>$user['name'],
                    'openid'=>$user['openid'],
                    'award_name'=>$award['name'],
                    'user_QQ'=>$user['QQ'],
                    'user_mobile'=>$user['mobile'],
                    'user_address'=>$user['address'],
                    'time'=>date('Y-m-d H:i:s',$user_award['created_at'])
                    ]);
        	}
            $api->get_excel_awardList($data,$name);
		}        
	}
}else if(isPost()){
	echo json_decode(array(
		'status'=>'fail',
		'message'=>'请求方式错误'
		));
	exit;
}