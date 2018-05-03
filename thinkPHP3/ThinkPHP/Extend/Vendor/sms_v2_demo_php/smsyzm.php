<?php
//载入ucpass类
require_once('lib/Ucpaas.class.php');
require_once('serverSid.php');


$appid = "1aee4c39f16a4a95adb22be2bff9c483";	//应用的ID，可在开发者控制台内的短信产品下查看
$templateid = "275801";    //可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID
$param = "哈哈哈"; //多个参数使用英文逗号隔开（如：param=“a,b,c”），如为参数则留空
// $mobile = session('yzmtel');
$mobile=I('yzmtel');
$uid = "";


//70字内（含70字）计一条，超过70字，按67字/条计费，超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。

echo $ucpass->SendSms($appid,$templateid,$param,$mobile,$uid);
// {"code":"000000","count":"1","create_date":"发送时间","mobile":"手机号码","msg":"OK","smsid":"2ad83cfcb68268a0f181bdc800623a22","uid":""}