<?php 
//首页热门推荐
include("database.php");
//$page=htmlspecialchars(trim($_POST['page']));//用户id
$page=$page*3;//每页显示数目3
if($page=='')
{
	$page=0;
}
$qres=mysql_query("
SELECT  a.id as hid,a.title,a.place,
DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d') AS st,
DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d') AS de,
a.place,b.path,c.id,c.area 
FROM (`yershop_document` AS a LEFT JOIN `yershop_picture` AS b ON a.cover_id=b.id) 
LEFT JOIN `yershop_document_product` AS c ON a.id=c.id
ORDER BY a.id  DESC LIMIT 0,10;
");

$i=0;
while($row = mysql_fetch_assoc($qres)){
$reslist[$i] = $row;
$i++;
}
	echo json_encode($reslist);
	mysql_close();
?>