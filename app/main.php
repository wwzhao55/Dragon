<?php
require dirname(__FILE__).'/common.php';
//获取活动信息
if(isGet()){
	require 'game.html';
	exit;
}else if(isPost()){
	exit('错误的请求方式');
}