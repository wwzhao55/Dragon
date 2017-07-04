<?php
require 'init.php';

if (isGet()) {
    if (isAjax()) {
        $page = isset($_GET['page'])?$_GET['page']:0;
        $count = $db->count('user_award',['id[>]'=>0]);
        $userAwards = $db->select('user_award',['openid','award_id','created_at'], ['id[>]' => 0, 'LIMIT' => [$page * PAGE_NOTE_USER_AWARD, PAGE_NOTE_USER_AWARD]]);
        if($count>0){
            foreach ($userAwards as &$user_award) {
                $user = $db->get('users','*',['openid'=>$user_award['openid']]);
                $award = $db->get('awards',['name','type'],['id'=>$user_award['award_id']]);
                $user_award['user_name'] = $user['name'];
                $user_award['award_name'] = $award['name'];
                $user_award['user_QQ'] = $user['QQ'];
                $user_award['user_mobile'] = $user['mobile'];
                $user_award['user_address'] = $user['address'];
                
            }
        }
        if($userAwards){
            echo json_encode(array(
                'status'=>'success',
                'message'=>'获取数据成功',
                'data'=>$userAwards,
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
        $main_html = file_get_contents('../resource/cms/userAward.html');
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
