<?php
//首页轮播图
include("database.php");
$qres=mysql_query("SELECT path,surl,title FROM app_lunbotu where if_gg=0 ");
$i=0;
while($row = mysql_fetch_assoc($qres)){
$reslist[$i] = $row;
$i++;
}
	echo json_encode($reslist);
	mysql_close();
?>