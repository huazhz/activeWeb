<?php
include ("database.php");
//我的收藏
//获取用户id(根据用户id查到活动id，根据活动id查到活动名及图片)
$userid = $_POST['uid'];
$page=htmlspecialchars(trim($_POST['page']));
//设置分页
if($page == '')
{
	$page=0;
}
$page=$page*20;//每页显示数目20
$sql = mysql_query(" 
SELECT a.id AS hid,a.title,b.path,
DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d') AS st,
DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d') AS de,c.area
From (`yershop_document` AS a LEFT JOIN `yershop_picture` AS b ON a.cover_id=b.id)
LEFT JOIN `yershop_document_product` AS c ON a.id=c.id
LEFT JOIN `yershop_collect` AS d ON a.id=d.goodid WHERE d.uid='{$userid}'
ORDER BY d.create_time DESC 
LIMIT {$page},20;
");

$i=0;
while ($row = mysql_fetch_assoc($sql)){
	$reslist[$i] = $row;
	$i++;
}
echo json_encode($reslist);
mysql_close();
?>