<?php
include("database.php");
/*
 * 按照category表的ID分类显示
 * */
$page=htmlspecialchars(trim($_POST['page']));//页码
$cat_id=htmlspecialchars(trim($_GET['classid']));//获取分类ID

$page=$page*20;//每页显示数目20
if($page=='')
{
	$page=0;
}
if($cat_id == null){
	$qres=mysql_query("
			SELECT  a.id as hid,a.title,a.place,
			DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d') AS st,
			DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d') AS de,
			a.place,b.path,c.id,c.area 
			FROM (`yershop_document` AS a LEFT JOIN `yershop_picture` AS b ON a.cover_id=b.id) 
			LEFT JOIN `yershop_document_product` AS c ON a.id=c.id
			ORDER BY a.view  DESC LIMIT {$page},20;
");
}else{
$qres=mysql_query("
		SELECT  a.id as hid,a.title,a.place,
		DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d') AS st,
		DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d') AS de,
		a.place,b.path,c.id,c.area 
		FROM (`yershop_document` AS a LEFT JOIN `yershop_picture` AS b ON a.cover_id=b.id) 
		LEFT JOIN `yershop_document_product` AS c ON a.id=c.id WHERE a.category_id={$cat_id}
		ORDER BY a.view  DESC LIMIT {$page},20;
		");
}

$i=0;
while($row = mysql_fetch_assoc($qres)){
$reslist[$i] = $row;
$i++;
}
echo json_encode($reslist);
mysql_close();
?>