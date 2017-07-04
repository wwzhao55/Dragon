<?php
require dirname(__FILE__).'/common.php';
//获取活动信息
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
		function getAward($redis,$db){
			$rand = rand(1,1000);
			$awards = require('api/awardConfig.php');
			$flag = 0;


			function getCouponAward($redis,$awards,$db){
				
				$get_award = $awards[6];
				intoDB($get_award,$db,$redis);
				$redis->sAdd('dragon_openid_has_get_coupon_'.($get_award['id']).'_award',$_SESSION['openid']);
				return $get_award;

			}	

			function intoDB($get_award,$db,$redis){
				if(!$redis->sIsMember('dragon_openid_has_get_coupon_'.($get_award['id']).'_award',$_SESSION['openid'])){
					return $db->insert('user_award',[
						'openid'=>$_SESSION['openid'],
						'award_id'=>$get_award['id'],
						'created_at'=>time(),
						'updated_at'=>time()
					]);
				}
				
			}	

			function real_award_into_db($get_award,$db,$redis){
			    // TODO : 更新award表里的count
				return $db->insert('user_award',[
					'openid'=>$_SESSION['openid'],
					'award_id'=>$get_award['id'],
					'created_at'=>time(),
					'updated_at'=>time()
				]);
			}		

			if((time()-$redis->lIndex('dragon_one_day_real_award_limit',0))> 24*60*60){
				$redis->lSet('dragon_one_day_real_award_limit',0,strtotime(date('Y-m-d')));
				// TODO : 每种奖品的每日发放数量都要重置为0
				$redis->lSet('dragon_one_day_real_award_limit',1,0);
				$redis->lSet('dragon_one_day_real_award_limit',2,0);
				$redis->lSet('dragon_one_day_real_award_limit',3,0);
				$redis->lSet('dragon_one_day_real_award_limit',4,0);
				$redis->lSet('dragon_one_day_real_award_limit',5,0);
				$redis->lSet('dragon_one_day_real_award_limit',6,0);
			}

			for($i=0;$i<count($awards);$i++){
				$flag += $awards[$i]['chance'];
				if($flag*1000>=$rand) break;
			}

			$get_award = $awards[$i];

			if($get_award['type']==1 && strtotime(date('Y-m-d')) < 1496419200){
				if($redis->sIsMember('dragon_openid_has_get_real_award',$_SESSION['openid'])){
					$get_award = getCouponAward($redis,$awards,$db);
					return $get_award;
				}	

				$ok = $redis->set($_SESSION['openid'].'_dragon', 'dg', array('nx', 'ex' => 20));

				if ($ok) {
                    $redis->watch('dragon_award_lists','dragon_one_day_real_award_limit');
				    $dayLimit = $redis->lIndex('dragon_one_day_real_award_limit',$i+1);
				    if($dayLimit >= $get_award['count']/7-1){
						$get_award = getCouponAward($redis,$awards,$db);
						return $get_award;
					}

					$award_count = $redis->lIndex('dragon_award_lists',$get_award['order']);
					if($award_count>0){
                        $ret = $redis->multi()
                            ->Lset('dragon_award_lists',$get_award['order'],$award_count-1)
                        ->sAdd('dragon_openid_has_get_real_award',$_SESSION['openid'])
                        ->lSet('dragon_one_day_real_award_limit',$i+1, $dayLimit+1)
                        ->exec();
					    // TODO : 更新当天的限制数量
                        if ($ret) {
                            real_award_into_db($get_award,$db,$redis);
                        } else {
                            $get_award = getCouponAward($redis,$awards,$db);
                        }
						return $get_award;
					}else{
						$get_award = getCouponAward($redis,$awards,$db);
						return $get_award;
					}
				}else{
					return null;
				}			
				
				
			}else{
				intoDB($get_award,$db,$redis);
				$redis->sAdd('dragon_openid_has_get_coupon_'.($get_award['id']).'_award',$_SESSION['openid']);
				return $get_award;
			}			
		}

		$get_award = getAward($redis,$db);
		 
		if($get_award){
			echo json_encode(array(
				'status'=>'success',
				'message'=>'获取成功',
				'data'=>$get_award['id'],
				));
			exit; 
		}else{
			echo json_encode(array(
				'status'=>'fail',
				'message'=>'获取失败,请求过快',
				));
			exit; 
		}
	}else{
		exit('错误的请求方式');
	}
} else if(isPost()){
	exit('错误的请求方式');
}