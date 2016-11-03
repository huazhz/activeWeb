<?php 
//支付完成插入数据库
include ("database.php");
date_default_timezone_set("PRC");

$uid = htmlspecialchars(trim($_POST['uid']));//报名人id
$order_code = htmlspecialchars(trim($_POST['order_code']));
$goodid =htmlspecialchars(trim( $_POST['hid']));//活动id
$cues='';//提示信息
// var_dump($uid);
// var_dump($order_code);
// var_dump($goodid);


if($uid!='' && $goodid!=''){
	$sql = mysql_query("
			UPDATE  `yershop_baoming` SET  if_pay='1' ,  if_baoming='1'  where   order_code='{$order_code}'  AND   goodid='{$goodid}' ;");
}
if($sql){
	$cues=1;//插入成功
}else{
	$cues = 0;//插入失败
}

$b='[{"tishi":"'.$cues.'"}]';
echo $b;
mysql_close();
?>