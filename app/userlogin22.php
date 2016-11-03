<?php
include ("database.php");
//用户登录
$username=$_POST['username'];//用户名
$password=md5($_POST['password']);//密码
//echo $password;
$cues = '';
if($username == ''){//检测用户名
	$cues = '请输入用户名..';
}else{
	if($password == ''){//检测密码
		$cues = '请输入密码..';
	}else{
		$result = mysql_query("SELECT a.id AS uid,a.username,b.face FROM `yershop_ucenter_member` AS a 
				LEFT JOIN `yershop_member` AS b ON a.id=b.uid WHERE username='{$username}' AND password='{$password}'");
		  $num_rows = mysql_affected_rows();
		  // var_dump($num_rows);exit;
		 //echo "$num_rows Rows\n";
// 		 if($result > 0){
// 		 	$cues = '1';
// 		 if($result['0']['username']==$username && $result['0']['password']==$password){
// 			$rest=mysql_query("
// 				SELECT uid,nickname,face FROM `yershop_member` WHERE nickname='{$username}';		
// 			");	 	
// 		}else{
// 			$cues = '2';
// 		} 

	}
}
$i=0;
while ($row=mysql_fetch_assoc($result)){
	$reslist[$i] = $row;
	$i++;
}
 //var_dump($reslist);
//array_push($reslist['0'],'tishi',"{$cues}");
// $b='[{"tishi":"'.$cues.'"}]';
//echo $b;
if($reslist){
	$reslist[0]['tishi']="1";
}else{
	$reslist[0]['tishi']='2';
}
//echo $reslist['0']['uid'].$reslist['0']['username'];
echo json_encode($reslist);
mysql_close();

//echo md5("123456");
?>