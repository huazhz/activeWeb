<?php
//用户活动添加收藏&&取消收藏
include ("database.php");
date_default_timezone_set("PRC");
$userid = $_POST['uid'];//用户id
$goodid = $_POST['hid'];//活动id
$coid = $_POST['sid'];//点击收藏传的值


$cues = '';
if($userid == ''){
	$cues = '您还没登录，请登录';
}else if($coid == '1'){
	$sql = mysql_query("
			INSERT INTO `yershop_collect_user`(uid,goodid,create_time)
			VALUES('{$userid}','{$goodid}',UNIX_TIMESTAMP());
			");
	if($sql){mysql_query("
			UPDATE `yershop_document_user` SET shoucang=shoucang+1 WHERE id='{$goodid}';	
		");	}
	//var_dump(mysql_affected_rows($sql));
	if($sql){
				$cues = '收藏成功';
	        }
	}
	else if($coid == '2'){
	$sql2 = mysql_query("
	      DELETE   FROM `yershop_collect_user`  WHERE  uid='{$userid}'  AND  goodid='{$goodid}';
	");
	if($sql2){mysql_query("
	UPDATE `yershop_document_user` SET shoucang=shoucang-1 WHERE id='{$goodid}';
	");
	}
	if($sql2){
	      $cues = '取消收藏成功';
	          }
	}
	
	$b='[{"tishi":"'.$cues.'"}]';
echo $b;
mysql_close();