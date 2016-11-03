<?php
include ("database.php");
/* 
 * 点击数
 *  */
date_default_timezone_set("PRC");
$goodid=htmlspecialchars(trim($_POST['hid']));//获取用户活动ID，增加点击量
if($goodid !== ''){
$qr=mysql_query("
		UPDATE `yershop_document_user` SET htis=htis+1 WHERE htis={$goodid} AND update_time=UNIX_TIMESTAMP() ;
		");
}
$qres=mysql_query("
		SELECT id as hid,htis FROM `yershop_document_user` WHERE id={$goodid};
		");
$i=0;
while ($row=mysql_fetch_assoc($qres)){
	$reslist[$i]=$row;
	$i++;
}
echo json_encode($reslist);
mysql_close();
?>