<?php
include ("database.php");
//活动详情
$doc_id=htmlspecialchars(trim($_POST['hid']));//获取活动ID
$qres = mysql_query("
		SELECT a.id as hid,a.title,a.place,a.price,a.baoming,c.area,
DATE_FORMAT(FROM_UNIXTIME(a.starttime),'%Y-%m-%d') AS st,
DATE_FORMAT(FROM_UNIXTIME(a.deadline),'%Y-%m-%d') AS de,b.path,c.id,c.content
FROM (`yershop_document` AS a LEFT JOIN `yershop_picture` AS b ON a.cover_id=b.id)	
LEFT JOIN `yershop_document_product` AS c ON a.id=c.id
		WHERE a.id={$doc_id};
		");
$i=0;
while($row=mysql_fetch_assoc($qres)){
	$reslist[$i]=$row;
	$i++;
}
echo json_encode($reslist);
 mysql_close();
?>