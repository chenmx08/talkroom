<?php

error_reporting(E_ALL ^ E_NOTICE);
set_time_limit(0);
ob_implicit_flush();

$sk=new Sock('0.0.0.0',4000);

$sk->run();

class Sock
{
    public $sockets; //socket的连接池，即client连接进来的socket标志
    public $users; //所有client连接进来的信息，包括socket、client名字等
    public $master; //socket的resource，即前期初始化socket时返回的socket资源
    public $real_path;//上传文件时获取的文件路径
    private $sda = array(); //已接收的数据
    private $slen = array(); //数据总长度
    private $sjen = array(); //接收数据的长度
    private $ar = array(); //加密key
    private $n = array();


    public function __construct($address,$port)
    {
        $this->master=$this->WebSocket($address,$port);
        
        $this->sockets=array($this->master);

    }

    public function run(){
        $len_content=0;

        while(true){
            $changes=$this->sockets;
            $write=NULL;
            $except=NULL;
            
            socket_select($changes,$write,$except,NULL);

            foreach($changes as $sock){
                if($sock == $this->master){
                    $client=socket_accept($this->master);
                    $key=uniqid();
                    $this->sockets[]=$client;
                    $this->users[$key]=array(
                        'socket'=$client,
                        'shou'=>false
                    );
                }else{
                    $len=0;
                    $buffer='';
                    while (($leng = socket_recv($sock, $buf, 1024, MSG_DONTWAIT)) > 0) {
                        // <span style = "font-size:10px;" ></span>  
                        $len += $leng;  
                        $buffer .= $buf;
                        $buf = null;  
                    }
                }
            }
        }
    }
}