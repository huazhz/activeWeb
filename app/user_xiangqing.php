<?php
/*
 * 用户发布活动的详情
 * */
date_default_timezone_set("PRC");
include ("database.php");
$goodid=htmlspecialchars(trim($_POST['hid']));//获取用户活动ID，根据ID获得详情内容
if($goodid !== ''){
$qres=mysql_query("
		SELECT  id as hid,title,place,content,
starttime as st,
stoptime as de,
titlepage as path,tel,qq
FROM `yershop_document_user`
WHERE id={$goodid};
		");
if($qres){
	mysql_query("UPDATE `yershop_document_user` SET htis=htis+1,update_time=UNIX_TIMESTAMP()  WHERE uid='{$goodid}';");
     }
}
$i=0;
while($row=mysql_fetch_assoc($qres)){
	$reslist[$i]=$row;
	$i++;
}

echo json_encode($reslist);
mysql_close();
?>