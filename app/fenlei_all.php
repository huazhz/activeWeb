<?php 
include("database.php");
header("Content-type: text/html; charset=utf-8");
/*
 * 按照时间方式排序显示管理员发布活动分类
 * */
$page=htmlspecialchars(trim($_POST['page']));//页码
$timeid=htmlspecialchars(trim($_POST['ftime']));//获取分类排序ID 今天day/一周week/一月month
$classid=htmlspecialchars(trim($_POST['classid']));//获取分类ID
date_default_timezone_set('PRC');//使用PHP的date函数获取时间之前，先将时区设置为北京时区

//今天的
$today = strtotime(date('Y-m-d', time()));//今天0点
$todayend = $today + 24 * 60 * 60;//明天0点
//一周之内的
$weday = $today- 24 * 60 * 60 * 3;//当天至三天前的0点
$wedayend = $today + 24 * 60 * 60 * 4;//今天至后4天的0点
//一月之内的
$meday = $today - 24 * 60 * 60 * 15;//今天至前15天的0点
$medayend = $today + 24 * 60 * 60 * 15;//今天至后15天的0点
$clue='';
if($page == "")
{
	$page=0;
}
$apage=$page*20;//每页显示数目20

if($timeid == ''&& $classid==''){
	$qres = mysql_query("
		SELECT  a.id as hid,a.title,
			DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d') AS st,
			DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d') AS de,
			a.place,b.path,c.id,c.area 
			FROM (`yershop_document` AS a LEFT JOIN `yershop_picture` AS b ON a.cover_id=b.id) 
			LEFT JOIN `yershop_document_product` AS c ON a.id=c.id WHERE a.category_id>160
			ORDER BY a.deadline  DESC   LIMIT  {$apage} , 20;  
			");
}
 elseif($timeid == ''&& $classid!==''){
	$qres = mysql_query("
			SELECT  a.id as hid,a.title,
			DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d') AS st,
			DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d') AS de,
			a.place,b.path,c.id,c.area
			FROM (`yershop_document` AS a LEFT JOIN `yershop_picture` AS b ON a.cover_id=b.id)
			LEFT JOIN `yershop_document_product` AS c ON a.id=c.id 
			WHERE  (a.category_id={$classid}) and a.category_id>160
			ORDER BY a.deadline  DESC   LIMIT  {$apage} , 20;
			");
}
else if($timeid =='1' &&  $classid==''){
	$qres=mysql_query("
		SELECT a.id as hid,a.title,a.place,
			DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d') AS st,
			DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d') AS de,
			a.place,b.path,c.id,c.area
			FROM (`yershop_document` AS a LEFT JOIN `yershop_picture` AS b ON a.cover_id=b.id)
			LEFT JOIN `yershop_document_product` AS c ON a.id=c.id
			WHERE (a.starttime  >  '{$today}'  AND   a.starttime  <  '{$todayend}' and a.category_id>160)  
			ORDER BY a.deadline  DESC   LIMIT  {$apage} , 20
			"); 
}
else if ($timeid =='2' &&  $classid==''){
	$qres=mysql_query("
			SELECT a.id as hid,a.title,a.place,
			DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d') AS st,
			DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d') AS de,
			a.place,b.path,c.id,c.area
			FROM (`yershop_document` AS a LEFT JOIN `yershop_picture` AS b ON a.cover_id=b.id)
			LEFT JOIN `yershop_document_product` AS c ON a.id=c.id
			WHERE (a.starttime > '{$weday}'   AND  a.starttime  <  '{$wedayend}' and a.category_id>160)  
			ORDER BY a.deadline  DESC   LIMIT   {$apage} , 20;
			");
}
else if ($timeid =='3' &&  $classid==''){
	$qres=mysql_query("
			SELECT a.id as hid,a.title,a.place,
			DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d') AS st,
			DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d') AS de,
			a.place,b.path,c.id,c.area
			FROM (`yershop_document` AS a LEFT JOIN `yershop_picture` AS b ON a.cover_id=b.id)
			LEFT JOIN `yershop_document_product` AS c ON a.id=c.id
			WHERE  (a.starttime >  '{$meday}' AND   a.starttime   < '{$medayend}' and a.category_id>160)  
			ORDER BY a.deadline  DESC   LIMIT  {$apage} , 20;
			");
}
else if($timeid=='1'&& $classid!=='')
{
$qres=mysql_query("
        SELECT a.id as hid,a.title,a.place,
		DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d') AS st,
		DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d') AS de,
		a.place,b.path,c.id,c.area 
		FROM (`yershop_document` AS a LEFT JOIN `yershop_picture` AS b ON a.cover_id=b.id) 
		LEFT JOIN `yershop_document_product` AS c ON a.id=c.id 
		WHERE (a.category_id={$classid}) AND  (a.starttime > '{$today}' AND  a.starttime  <  '{$todayend}' and a.category_id>160)
		ORDER BY a.deadline  DESC   LIMIT   {$apage} , 20;
");	
}
else if($timeid=='2' &&  $classid !=''){
$qres=mysql_query("
        SELECT a.id as hid,a.title,a.place,
		DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d') AS st,
		DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d') AS de,
		a.place,b.path,c.id,c.area 
		FROM (`yershop_document` AS a LEFT JOIN `yershop_picture` AS b ON a.cover_id=b.id) 
		LEFT JOIN `yershop_document_product` AS c ON a.id=c.id 
		WHERE (a.category_id={$classid})   AND   (a.starttime > '{$weday}'  AND   a.starttime  <  '{$wedayend}' and a.category_id>160)
		ORDER BY a.deadline  DESC   LIMIT  {$apage} , 20;
");	
}
else if($timeid=='3' &&  $classid !=''){
$qres=mysql_query("
        SELECT a.id as hid,a.title,a.place,
		DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d') AS st,
		DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d') AS de,
		a.place,b.path,c.id,c.area 
		FROM (`yershop_document` AS a LEFT JOIN `yershop_picture` AS b ON a.cover_id=b.id) 
		LEFT JOIN `yershop_document_product` AS c ON a.id=c.id 
		WHERE (a.category_id={$classid})   AND   (a.starttime > '{$meday}'  AND  a.starttime  <  '{$medayend}' and a.category_id>160)
		ORDER BY a.deadline  DESC   LIMIT   {$apage} ,  20;
"); 
}else{
	   $clue="很抱歉！没有相关记录";
	echo   json_encode($clue);
}

$i=0;
while($row=mysql_fetch_assoc($qres)){
	$reslist[$i]=$row;
	$i++; 
}
	echo json_encode($reslist);
// }
mysql_close();
?>