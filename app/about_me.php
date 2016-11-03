<?php
include ("database.php");
//个人资料
$userid=$_POST['uid'];
//var_dump($userid);
$result = mysql_query("
		SELECT a.id AS uid,a.username,b.nickname,b.face,b.sex,
		DATE_FORMAT(FROM_UNIXTIME(b.birthday),'%Y-%m-%d') AS birthday,
		DATE_FORMAT(FROM_UNIXTIME(a.reg_time),'%Y-%m-%d') AS st
		FROM `yershop_ucenter_member` AS a LEFT JOIN `yershop_member` AS b
		ON a.id=b.uid WHERE a.id={$userid};	
	");
//var_dump($result);
$i=0;
while($row=mysql_fetch_assoc($result)){
	$reslist[$i] = $row;
	$i++;
}

//"st":"2016-05-11"
//http://active.bjxxw.com/Uploads/Picture/2016-05-11/5732df7890d58.jpg

echo json_encode($reslist);
mysql_close();
?>  