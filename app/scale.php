<?php
//首页8个小标
include("database.php");
$qres=mysql_query("
		SELECT a.id as hid,a.title,a.pic_id,b.path 
		FROM `yershop_category` AS a 
		LEFT JOIN `yershop_picture` AS b 
		ON a.pic_id=b.id
		WHERE a.pic_id=b.id
		LIMIT 0,8;
		");
$i=0;
while ($row=mysql_fetch_assoc($qres)){
	$reslist[$i] = $row;
	$i++;
}
echo json_encode($reslist);
mysql_close();
?>