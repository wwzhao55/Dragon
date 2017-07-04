<?php
require 'init.php';

if (isGet()) {
    if (isAjax()) {
    	$page = isset($_GET['page'])?$_GET['page']:0;
    	$count = $db->count('users',['id[>]'=>0]);
    	$userLists = $db->select('users',['name','openid','mobile','QQ','address'], ['id[>]' => 0, 'LIMIT' => [$page * PAGE_NOTE_USER, PAGE_NOTE_USER]]);
    	if($userLists){
    		echo json_encode(array(
				'status'=>'success',
				'message'=>'获取数据成功',
				'data'=>$userLists,
				'count'=>$count,
				));
			exit; 
    	}else{
    		echo json_encode(array(
				'status'=>'fail',
				'message'=>'获取数据失败',
				));
			exit; 
    	}
    } else {
        $main_html = file_get_contents('../resource/cms/userList.html');
        require '../resource/cms/layer.html';
        exit;
    }
} else if (isPost()) {    
	echo json_encode(array(
		'status'=>'fail',
		'message'=>'错误的请求方式',
		));
	exit; 
}
