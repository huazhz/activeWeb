<?php 
include("database.php");
/*
 * 按照时间方式排序显示管理员发布活动分类
 * */
$page=htmlspecialchars(trim($_POST['page']));//页码
$list_id=htmlspecialchars(trim($_POST['ftime']));//获取分类排序ID 今天day/一周week/一月month
$classid=htmlspecialchars(trim($_POST['cat_id']));//获取分类ID
$page=$page*20;//每页显示数目20
date_default_timezone_set('PRC');//使用PHP的date函数获取时间之前，先将时区设置为北京时区
$sdate=date('Y-m-d h:i:s');//获取当前时间
$wdate = date('Y-m-d H:i:s', strtotime('-1day'));//前一天
$ddate = strtotime($wdate);

$wdate = date('Y-m-d H:i:s', strtotime('-1week'));//前一周
$wdate = strtotime($wdate);

$wdate = date('Y-m-d H:i:s', strtotime('-1month'));//前一月
$mdate = strtotime($wdate);
$date = date('Y-m-d H:i:s');
$date = strtotime($date);
//$where['date_time'] =
//where('date_time > $wdate &&  $date_time < $date');
if($page=='')
{
	$page=0;
}
if($list_id==1)
{
$qres=mysql_query("
SELECT a.id as hid,a.starttime,a.title,a.starttime,a.deadline,a.place,b.path  
FROM `yershop_document` AS a 
LEFT JOIN `yershop_picture` AS b 
ON a.cover_id=b.id 
WHERE ('a.starttime > {$ddate} &&  a.starttime < {$date}'); 
ORDER BY a.starttime_time 
DESC LIMIT {$page}, 20;
");	
}
else if($list_id==2){
$qres=mysql_query("
SELECT a.id as hid,a.starttime,a.title,a.starttime,a.deadline,a.place,b.path  
FROM `yershop_document` AS a 
LEFT JOIN `yershop_picture` AS b 
ON a.cover_id=b.id 
WHERE ('a.starttime > {$wdate} &&  a.starttime < {$date}'); 
ORDER BY a.starttime_time 
DESC LIMIT {$page}, 20;
");	
}
else if($list_id==3){
$qres=mysql_query("
SELECT a.id as hid,a.starttime,a.title,a.starttime,a.deadline,a.place,b.path  
FROM `yershop_document` AS a 
LEFT JOIN `yershop_picture` AS b 
ON a.cover_id=b.id 
WHERE ('a.starttime > {$mdate} &&  a.starttime < {$date}'); 
ORDER BY a.starttime_time 
DESC LIMIT {$page}, 20;
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