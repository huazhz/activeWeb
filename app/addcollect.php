<?php
//商家活动添加收藏&&取消收藏
include ("database.php");
date_default_timezone_set("PRC");
$userid = $_POST['uid'];//用户id
$goodid = $_POST['hid'];//活动id
$coid = $_POST['sid'];//点击收藏传的值
$price = $_POST['pic'];//活动价格
$cues = '';
 if($userid == ''){
	$cues = '您还没登录，请登录';
}else if($coid ==1){
	$sql = mysql_query("
			INSERT INTO `yershop_collect`(id,uid,goodid,create_time,price)
			VALUE('','{$userid}','{$goodid}',NOW(),'{$price}');	
		");
	if($sql){
		$cues = '收藏成功';
	} 
}else if($coid ==2){
		//var_dump($userid);
		$sql = mysql_query("
			DELETE FROM `yershop_collect` WHERE uid='{$userid}' && 
			goodid='{$goodid}';	
			");
		 //var_dump($sql);
		if($sql){
			$cues = "取消收藏成功";
		}
	}

$b='[{"tishi":"'.$cues.'"}]';
echo $b;
mysql_close();
?>