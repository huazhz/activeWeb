<?php 
include ("database.php");
//是否报名
$hid=htmlspecialchars(trim($_POST['hid']));//获取活动ID

if(!empty($hid)){
	$qres=mysql_query(" SELECT    baoming_info,if_pay
			FROM    `yershop_document` 
			WHERE  id='{$hid}'; ");
}


$i=0;
while($row = mysql_fetch_assoc($qres)){
	$reslist[$i] = $row;
	//$reslist[$i] =explode(','   , $reslist[$i][0]);
	$i++;
	
}

echo json_encode($reslist);
mysql_close();

?>