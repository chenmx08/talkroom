<?php
//载入ucpass类
require_once('lib/Ucpaas.php');

//初始化必填
//填写在开发者控制台首页上的Account Sid
$options['accountsid']='24f51a7c152b50ec1c10c36cde63556b';
//填写在开发者控制台首页上的Auth Token
$options['token']='7cf85142d47c2b8d4aa2eb279ee79ef0';

//初始化 $options必填
$ucpass = new Ucpaas($options);