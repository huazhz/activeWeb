<?php
include ("database.php");
date_default_timezone_set("PRC");//设置时区
$today = date('Y-m-d',time());//获取当前时间

$password=htmlspecialchars(trim($_POST["password"]));
$repassword=htmlspecialchars(trim($_POST["repassword"]));
$username =htmlspecialchars(trim($_POST["username"]));//用户名
$email=htmlspecialchars(trim($_POST["email"]));
$password=md5(sha1($password).'Mh-(Sg3b8wj?;nDaHlm`)PRIcz>@0}f^/%"|C][:');
$repassword=md5(sha1($repassword).'Mh-(Sg3b8wj?;nDaHlm`)PRIcz>@0}f^/%"|C][:');

 


 
 
$cues = '';
//获取客户端真实IP地址*********//
function getip() {
	$unknown = 'unknown';
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
		$tip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
		$tip = $_SERVER['REMOTE_ADDR'];
	}
	if (false !== strpos($tip, ',')){ $ip = reset(explode(',', $ip)); }
	return $tip;

}

/***********注册用户判断***************/
	if($username == ''){ //注册用户
		$cues='请输入用户名..';
	}else{
		if($password != $repassword){/* 检测密码 */
			$cues='两次密码不一致,请重新输入..';
		}else{
			if($email == ''){
				$cues = '请输入邮箱..';
			}else{
				if(isset($email)){
						$pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
				if(preg_match($pattern, $email)){			
					//此处sqt检测是否有重复的用户名或邮箱
					  $sqt=mysql_query("SELECT count(*) as shu FROM  `yershop_ucenter_member`  WHERE username='{$username}' OR email='{$email}';");
					  $row=mysql_fetch_assoc( $sqt);
// 					  var_dump($row['shu']);
					    if($row['shu'] > 0){
					  	$cues="存在相同的用户名或邮箱 ，请检查后提交";
					  }else{
					    $path = '/app/uploads/touxiang.png';
					    $tip=getip();
						$sql = mysql_query(
								"INSERT INTO `yershop_ucenter_member` (username,password,email,reg_time,reg_ip,face)
								VALUES('{$username}', '{$password}','{$email}',unix_timestamp(),'{$tip}','{$path}');
						");
						$sql2=mysql_query("
								INSERT INTO `yershop_member` (`uid`, `nickname`,`face`)  VALUES ((select    id   from  yershop_ucenter_member where username='{$username}' ) , '{$username}','{$path}');
								");
						if($sql){
							$cues='1';
						}else {
							$cues='注册失败';
						}
					}
				}else {
					$cues="邮箱格式不正确";
				}
			}
		}
	}
}
	/*$sql = mysql_query("INSERT INTO `yershop_ucenter_member`('username','password','email') 
			VALUE('{$username}',md5('{$password}'),'{$email}',);");
*/	
$b='[{"tishi":"'.$cues.'"}]';
echo $b;
mysql_close();
?>