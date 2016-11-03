<?php
/*
 * 按时间显示用户发布活动的分类显示
 * */
include("database.php");
date_default_timezone_set('PRC');//使用PHP的date函数获取时间之前，先将时区设置为北京时区
$page=htmlspecialchars(trim($_POST['page']));//页码
$list_id=htmlspecialchars(trim($_POST['ftime']));//传值为1（代表即将开始的活动）、2(代表即将进行的活动)、3(代表即将过去的活动)
// var_dump($list_id);

if($page == ''){
	$page=0;
}
$page=$page*20;//每页显示数目20
//time()+24 * 60 * 60
$today_0=date('Y-m-d', time());
$today_24=date('Y-m-d',strtotime("+1 day"));

if($list_id ==''){
// 	echo "我进来了啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊";
	$qres=mysql_query("
			SELECT a.id as hid,a.title,a.titlepage,a.place,a.shoucang,a.htis,
            (a.starttime) AS sta,
			(a.stoptime) AS stp
			FROM `yershop_document_user` AS a
			LEFT JOIN `yershop_category` AS b
			ON a.category_id=b.id
			ORDER BY a.starttime
			DESC LIMIT {$page}, 20;
			");
}
if($list_id == '1')//代表即将开始的活动
{  
// 	echo "我进11111111111111111111111111111";
	$qres=mysql_query("
			
			SELECT a.id as hid,a.title,a.titlepage,a.place,a.shoucang,a.htis,
		     (a.starttime) AS sta,
			(a.stoptime) AS stp
			FROM `yershop_document_user` AS a
			LEFT JOIN `yershop_category` AS  b
			ON a.category_id=b.id
			WHERE (a.starttime>='{$today_24}' AND   a.stoptime>='{$today_24}')
			ORDER BY a.starttime
			DESC LIMIT {$page}, 20;
			");
}else if($list_id == '2'){//(代表即将进行的活动
// 	echo "我进2222222222222222222222222222";
$qres=mysql_query("
			SELECT a.id as hid,a.title,a.titlepage,a.place,a.shoucang,a.htis,
	         (a.starttime) AS sta,
			(a.stoptime) AS stp
			FROM `yershop_document_user` AS a
			LEFT JOIN `yershop_category` AS b
			ON a.category_id=b.id
			WHERE (a.starttime<='{$today_0}'  AND  a.stoptime>='{$today_0}')
			ORDER BY a.starttime
			DESC LIMIT {$page}, 20;
			");
}else if($list_id == '3'){//(代表过去的活动
// 	echo "我进333333333333333333333333333333333333";
$qres=mysql_query("
			SELECT a.id as hid,a.title,a.titlepage,a.place,a.shoucang,a.htis,
			  (a.starttime) AS sta,
			(a.stoptime) AS stp
			FROM `yershop_document_user` AS a
			LEFT JOIN `yershop_category` AS b
			ON a.category_id=b.id
			WHERE (a.starttime<'{$today_0}'  AND  a.stoptime<'{$today_0}')
			ORDER BY a.starttime
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