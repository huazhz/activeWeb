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
a.starttime as st,
a.stoptime as de,
a.titlepage as path
FROM `yershop_document_user` AS a 
WHERE a.category_id>160 and a.title like '%{$key}%'
ORDER BY a.create_time  DESC LIMIT {$page},20;
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