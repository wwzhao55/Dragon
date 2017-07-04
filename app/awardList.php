<?php
require dirname(__FILE__).'/common.php';
//获取活动信息
if(isGet()){
	if(isAjax()){
		$awardLists = $db->select('user_award',['openid','award_id','created_at'],['openid'=>$_SESSION['openid']]);
		foreach ($awardLists as &$award) {
			$award['award_name'] = $db->get('awards','name',['id'=>$award['award_id']]);
			$award['user_name'] = $db->get('users','name',['openid'=>$award['openid']]);
		}
		if($awardLists){
			echo json_encode(array(
				'status'=>'success',
				'data'=>$awardLists,
				'message'=>'获取成功',
				));
			exit; 
		}else{
			echo json_encode(array(
				'status'=>'fail',
				'message'=>'获取失败',
				));
			exit;
		}
	}else{
		$main_html = file_get_contents('resource/app/awardList.html');
		require 'resource/app/layer.html';
		exit;
	}
}else if(isPost()){	
	echo json_encode(array(
		'status'=>'fail',
		'message'=>'获取失败',
		));
	exit; 
}