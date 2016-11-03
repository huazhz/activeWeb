<?php
include("database.php");
$qres=mysql_query("SELECT a.id,b.hid,a.title,b.path 
		FROM `yershop_document` AS a 
		RIGHT  JOIN `app_lunbotu` AS b 
		ON a.id=b.hid LIMIT 0,2;
		");
$i=0;
while($row = mysql_fetch_assoc($qres)){
$reslist[$i] = $row;
$i++;
}
	echo json_encode($reslist);
	mysql_close();
?>