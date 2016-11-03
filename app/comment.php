<?php
include ("database.php");
//活动详情评论
$goodid=htmlspecialchars(trim($_POST['hid']));//获取活动ID，根据ID获得详情内容评论
$qres=mysql_query("
		SELECT a.id as cid,a.content,b.nickname,b.face,
		DATE_FORMAT(FROM_UNIXTIME(a.create_time),'%Y-%m-%d %H:%i:%s') AS cr
		FROM (`yershop_comment`  AS a LEFT JOIN `yershop_member` AS b
		ON a.uid=b.uid) LEFT JOIN `yershop_document` AS c ON a.goodid=c.id
		WHERE a.goodid='{$goodid}' ;
		");
$i=0;
while ($row=mysql_fetch_assoc($qres)){
	$reslist[$i]=$row;
	$i++;
}
echo json_encode($reslist);
mysql_close();
?>