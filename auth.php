<?php
require 'api/api.php';
session_start();
$api = new api();
$code = isset($_GET['code'])?$_GET['code']:'';
$destination = isset($_GET['state'])?$_GET['state']:'main';

if(!$code){
	Header("Location:index.php");
}else{

	$url =  "http://wxapi.dgdev.cn/getOpenID.php?wxCode=$code&state=123";
	//$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.APPID.'&secret='.APPSECRET.'&code='.$code.'&grant_type=authorization_code';


	$jsonp_raw = http_get($url);
	preg_match("/(\{[\w\":,_-]+\})/i", $jsonp_raw ,$matches);
	$json_raw = $matches[0];
	$json_data = json_decode($json_raw);

	if(isset($json_data->openid)){

		$_SESSION['openid'] = $json_data->openid;

		Header("Location:index.php?destination=".$destination);
		
		
	}else{
		exit('获取unionid失败');
	}
	
}
