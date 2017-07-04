<?php
ini_set('date.timezone','Asia/Shanghai');
session_start();
require '../api/api.php';
$api = new api();

$db = $api->get_db();

if($db==null){
	exit('数据库错误');
}


if(!isset($_SESSION['uid'])){
	if(isAjax()){
		echo json_encode(array(
			'status'=>'fail',
			'message'=>'您还未登陆',
			));
		exit; 
	}else{
		Header("Location: login.php");
	}
}


