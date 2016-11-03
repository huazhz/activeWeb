<?php
//列表页头
include("database.php");
$qres=mysql_query("SELECT id as hid,title FROM `yershop_category` WHERE if_class=1;");
$i=0;
while($row=mysql_fetch_assoc($qres)){
	$reslist[$i]=$row;
	$i++;
}
array_unshift($reslist,array('hid'=>'','title'=>'全部'));
echo json_encode($reslist);

mysql_close();
?>