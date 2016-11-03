<?php 
include ("database.php");
//我的活动订单详细
$uid = htmlspecialchars(trim($_POST['uid']));//报名人id
$order_code = htmlspecialchars(trim($_POST['order_code']));
$goodid =htmlspecialchars(trim( $_POST['hid']));//活动id

if($uid!='' && $goodid!=''){
	$sql = mysql_query("
SELECT a.if_baoming,a.goodid AS hid,a.order_code,a.price,b.title,c.path,a.real_name,a.email,a.tel,a.address,a.unit,a.card,a.sex,a.qq,a.beizhu
FROM (yershop_baoming AS a
LEFT JOIN yershop_document AS b 
ON a.goodid=b.id )
LEFT JOIN yershop_picture AS c
ON b.cover_id=c.id
WHERE (a.order_code='{$order_code}'  AND   a.goodid='{$goodid}' AND if_delete=0)
			");
}
$i=0;
while($row = mysql_fetch_assoc($sql)){
	$reslist[$i] = $row;
	$i++;	
}


echo json_encode($reslist);
mysql_close();

?>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
