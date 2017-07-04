<?php
require 'init.php';
//获取活动信息
if(isGet()){
	exit('错误的请求方式');
}else if(isPost()){
	$new_password = isset($_POST['new_password'])?$_POST['new_password']:null;
	$re_new_password = isset($_POST['re_new_password'])?$_POST['re_new_password']:null;
	$old_password = isset($_POST['old_password'])?$_POST['old_password']:null;
	if($new_password==null){
		echo json_encode(array(
			'status'=>'fail',
			'message'=>'密码不能为空',
			));
		exit; 
	}
	if($old_password==null){
		echo json_encode(array(
			'status'=>'fail',
			'message'=>'原密码不能为空',
			));
		exit; 
	}
	if($re_new_password==null){
		echo json_encode(array(
			'status'=>'fail',
			'message'=>'重复密码不能为空',
			));
		exit; 
	}
	if($new_password != $re_new_password){
		echo json_encode(array(
			'status'=>'fail',
			'message'=>'两次密码输入不一致',
			));
		exit; 
	}

	$check_password = $db->count('admin',['password'=>md5($old_password)]);

	if($check_password==0){
		echo json_encode(array(
			'status'=>'fail',
			'message'=>'密码错误',
			));
		exit; 
	}

	$result = $db->update('admin',['password'=>md5($new_password)],['id[>]'=>0]);
	if($result){
		echo json_encode(array(
			'status'=>'success',
			'message'=>'修改成功',
			));
		exit; 
	}else{
		echo json_encode(array(
			'status'=>'fail',
			'message'=>'修改失败',
			));
		exit; 
	}
}