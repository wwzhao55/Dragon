<?php
require 'init.php';
if(isGet()){
	if(isAjax()){
		try{
			$redis = new Redis();
			$result = $redis->connect(REDIS_HOST);
			if(!$result){
				exit('redis服务没有开启！');
			}
		}catch(Exception $e){
			exit('没有redis扩展！');
		}
		$award_lists = $db->select('awards',['order','name','count'],['type'=>1]);
		foreach ($award_lists as &$list) {
			$list['count'] = $redis->lIndex('dragon_award_lists',$list['order']);
		}
		if($award_lists){
    		echo json_encode(array(
				'status'=>'success',
				'message'=>'获取数据成功',
				'data'=>$award_lists,
				));
			exit; 
    	}else{
    		echo json_encode(array(
				'status'=>'fail',
				'message'=>'获取数据失败',
				));
			exit; 
    	}
	}else{
		$main_html = file_get_contents('../resource/cms/datacenter.html');
		require '../resource/cms/layer.html';
		exit;
	}
}else if(isPost()){
	echo json_decode(array(
		'status'=>'fail',
		'message'=>'请求方式错误'
		));
	exit;
}