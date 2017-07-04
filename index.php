<?php
require 'api/api.php';
$api = new api();
$db = $api->get_db();
if($db==null){
    exit('数据库连接失败');
}
$upload = $api->get_upload();
session_start();
$destination = isset($_GET['destination'])?$_GET['destination']:'main';
$right_destination = array('main','userInfo','awardList','getAward');
if(!in_array($destination, $right_destination)){
	$destination = 'main';
}

if(isset($_SESSION['openid']) ){

	require "app/".$destination.".php";
	
}else{   
    $redirect_url = 'https://'.DOMAIN.'/auth.php';

    $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".APPID."&redirect_uri=".$redirect_url."&response_type=code&scope=snsapi_base&state=".$destination."#wechat_redirect";

    Header("Location:$url");
}


