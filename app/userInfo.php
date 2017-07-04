<?php
require dirname(__FILE__).'/common.php';
//获取活动信息
if(isGet()){
	exit('错误的请求方式');
}else if(isPost()){
	$name = isset($_POST['name'])?$_POST['name']:null;
	$mobile = isset($_POST['mobile'])?$_POST['mobile']:null;
	$address = isset($_POST['address'])?$_POST['address']:null;
	$QQ = isset($_POST['QQ'])?$_POST['QQ']:null;

	if($name==null){
		echo json_encode(array(
			'status'=>'fail',
			'message'=>'姓名不能为空',
			));
		exit; 
	}
	if($mobile==null){
		echo json_encode(array(
			'status'=>'fail',
			'message'=>'手机号不能为空',
			));
		exit; 
	}
	if($address==null){
		echo json_encode(array(
			'status'=>'fail',
			'message'=>'地址不能为空',
			));
		exit; 
	}
	if($QQ==null){
		echo json_encode(array(
			'status'=>'fail',
			'message'=>'QQ号不能为空',
			));
		exit; 
	}

	$has_openid = $db->count('users',['openid'=>$_SESSION['openid']]);
	if($has_openid){
		echo json_encode(array(
			'status'=>'fail',
			'message'=>'已经提交过信息',
			));
		exit; 
	}

	$result = $db->insert('users',[
		'name'=>$name,
		'openid'=>$_SESSION['openid'],
		'mobile'=>$mobile,
		'address'=>$address,
		'QQ'=>$QQ,
		'created_at'=>time(),
		'updated_at'=>time(),
		]);
	if($result){
		echo json_encode(array(
			'status'=>'success',
			'message'=>'新增成功',
			));
		exit; 
	}else{
		echo json_encode(array(
			'status'=>'fail',
			'message'=>'新增失败',
			));
		exit; 
	}

}