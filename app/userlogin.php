<?php
include ("database.php");
//用户登录
$username=htmlspecialchars(trim($_POST['username']));//用户名
$password=htmlspecialchars(trim($_POST['password']));//获取用户ID
$password=md5(sha1($password).'Mh-(Sg3b8wj?;nDaHlm`)PRIcz>@0}f^/%"|C][:');

//md5(sha1($_POST['password']);//密码
//echo $password;
$cues = '';
if($username == ''){//检测用户名
	$cues = '请输入用户名..';
}else{
	if($password == ''){//检测密码
		$cues = '请输入密码..';
	}else{
		$result = mysql_query("SELECT a.id AS uid,a.username,b.face FROM `yershop_ucenter_member` AS a 
				LEFT JOIN `yershop_member` AS b ON a.id=b.uid WHERE username='{$username}' AND password='{$password}';");
		 if($result){//mysql_affected_rows()>0){
		 	$cues = '1';
// 		 if($result['0']['username']==$username && $result['0']['password']==$password){
// 				SELECT uid,nickname,face FROM `yershop_member` WHERE nickname='{$username}';		
// 			");	 	
		}else{
			$cues = '2';
		} 

	}
}


$i=0;
while ($row=mysql_fetch_assoc($result)){
	$reslist[$i] = $row;
	$reslist[$i]['tishi'] = $cues;
		if($cues==1){
			$nickname=mysql_query('SELECT nickname FROM `yershop_member` where uid='.$row['uid']);
			$nick=mysql_fetch_assoc($nickname);
			$reslist[$i]['nickname']=$nick['nickname'];
		}
		
		
	
		
		
	
	if($reslist[$i]['face']==null||$reslist[$i]['face']==''){
		echo $reslist[$i]['face'];
		$reslist[$i]['face']='1';
	}
	
	$i++;
}
// array_push($reslist['tishi'],"{$cues}","{$cues}");
//$b='[{"tishi":"'.$cues.'"}]';
//echo $b;

// if($reslist){
// 	$reslist[0]['tishi']="1";
// // 	if($reslist[0]['face']==null){
// // 		$reslist[0]['face']='1';
// //  }
// }else{
// 	$reslist[0]['tishi']='2';
// }
//echo $reslist['0']['uid'].$reslist['0']['username'];
echo json_encode($reslist);
mysql_close();

//echo md5("123456");
?>

