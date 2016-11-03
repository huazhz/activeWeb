<?php
//我的评论(根据用户id找到参与了哪些活动，根据活动取出活动名称及取出图片和评论)
//商家活动
include ("database.php");
$userid = $_POST['uid'];//用户id
$page=htmlspecialchars(trim($_POST['page']));
$page=$page*20;//每页显示数目20
if($page=='1')
{
	$page=0;
}
$qres=mysql_query(" 
SELECT a.goodid  as hid,b.face,c.title,a.content,DATE_FORMAT(FROM_UNIXTIME(a.create_time),'%Y-%m-%d %H:%i:%s') AS st 
FROM ( yershop_comment as a
left  JOIN  yershop_member as b ON a.uid=b.uid)
left JOIN yershop_document as c on c.id=a.goodid
WHERE a.uid='{$userid}'
ORDER BY st DESC
LIMIT {$page},20
		");
$i=0;
while($row = mysql_fetch_assoc($qres)){
$reslist[$i] = $row;
$i++;
}
	echo json_encode($reslist);
	mysql_close();
?>