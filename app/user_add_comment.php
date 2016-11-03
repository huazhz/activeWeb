<?php
include ("database.php");
date_default_timezone_set("PRC");
//添加用户活动评论
//$userinfo = $_POST['userinfo'];//获取用户是否登录
$goodid=$_POST['hid'];//获取活动ID
$uid = $_POST['uid'];//获取评论人的ID
$content=$_POST['content'];//获取评论内容
$cues = '';
//获取客户端真实IP地址*********
function getip() {
	$unknown = 'unknown';
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	/*
	 处理多层代理的情况
	或者使用正则方式：$ip = preg_match("/[\d\.]{7,15}/", $ip, $matches) ? $matches[0] : $unknown;
	*/
	if (false !== strpos($ip, ',')){ $ip = reset(explode(',', $ip)); }
	return $ip;
}
if($uid == ''){
	$cues = '您还没有登录..';
}else if($content == ''){
	$cues='请输入评论..';
}else{
	$ip=ip2long(getip());
$sql=mysql_query("
		INSERT INTO `yershop_comment_user`(goodid,create_time,content,uid,ip) 
		VALUE('{$goodid}',UNIX_TIMESTAMP() ,'{$content}','{$uid}','{$ip}');
		");
	if($sql){
		$cues = '添加评论成功..';
	}else{
		$cues = '添加评论失败..';
	}  	
}
$b='[{"tishi":"'.$cues.'"}]';
echo $b;
mysql_close();

?>