<?php
include ("database.php");
//我的评论(根据用户id找到参与了哪些活动，根据活动取出活动名称及取出图片)
//用户活动
$userid = $_POST['uid'];
$page=htmlspecialchars(trim($_POST['page']));//用户id
$page=$page*20;//每页显示数目20
if($page=='')
{
	$page=0;
}
if($userid !==''){
	$sql = mysql_query("
SELECT a.id AS hid,a.title,b.face,c.content,
			DATE_FORMAT(FROM_UNIXTIME(c.create_time),'%Y-%m-%d %H:%i:%s') AS st
			FROM (`yershop_document_user` AS a LEFT JOIN `yershop_ucenter_member` AS b ON a.uid = b.id)
			LEFT JOIN `yershop_comment` AS c ON a.uid=c.uid
			WHERE a.uid='{$userid}'
	     	ORDER BY st  DESC LIMIT {$page},20;");
}
$i=0;
while($row = mysql_fetch_assoc($qres)){
$reslist[$i] = $row;
$i++;
}
var_dump($reslist);
echo json_encode($reslist);
mysql_close();