<?php
include ("database.php");
//我的发布
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
SELECT id AS hid,title,titlepage AS path,place AS area,starttime AS st ,stoptime AS de 
FROM `yershop_document_user` 
WHERE uid='{$userid}'
ORDER BY create_time DESC
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