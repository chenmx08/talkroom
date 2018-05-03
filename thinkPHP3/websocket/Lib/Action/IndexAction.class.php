<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action
{

    public function index()
    {
        
        $where['isleave']=0;
        $user = M('user')->where($where)->field('name')->limit(0,10)->select();
        
        $this->assign('user', $user);
        $this->display();
    }

    public function ajaxChangeRoom(){
        $room_id=I('room_id');
        if(empty($room_id)){
            return false;
        }

        $where['room_id']=$room_id;
        $where['isleave']=0;
        $userList=M('user')->field('name')->where($where)->limit(0,10)->select();
        if($userList){
            $data = json_encode($userList);
            echo $data;
        }else{
            echo false;
        }
        
        
    }




}