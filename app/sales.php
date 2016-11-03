<?php
//首页4个不规则 
include("database.php");
$qres=mysql_query("select  a.goodid AS hid,a.path,b.title from `yershop_pic_sales` AS a LEFT JOIN yershop_document AS b ON a.goodid=b.id
		");
$i=0;
while($row = mysql_fetch_assoc($qres)){
$reslist[$i] = $row;
$i++;
}
	echo json_encode($reslist);
	mysql_close();
?>