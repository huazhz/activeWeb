<?php
//商家活动搜索页面
include("database.php");
$page=htmlspecialchars($_POST['page']);
$key=htmlspecialchars($_POST['result']);
if($page == "")
{
	$page=0;
}
$apage=$page*20;//每页显示数目20

// $key=iconv('gbk', 'utf-8//IGNORE', $key);
$qres=mysql_query("
		SELECT  a.id as hid,a.title,a.place,
DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d') AS st,
DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d') AS de,
b.path,c.id,c.area 
FROM (`yershop_document` AS a LEFT JOIN `yershop_picture` AS b ON a.cover_id=b.id) 
LEFT JOIN `yershop_document_product` AS c ON a.id=c.id
WHERE a.category_id>160 and a.title like '%{$key}%'
ORDER BY a.view   DESC LIMIT  {$page},20;
");
// var_dump($qres);
$i=0;
while($row = mysql_fetch_assoc($qres)){
// 	echo 'wwwwwwwwww';
	$reslist[$i] = $row;
	$i++;
}

echo json_encode($reslist);
mysql_close();
?>