<?php
include ("database.php");
//是否收藏页
$doc_id=htmlspecialchars(trim($_POST['hid']));//获取活动ID
$uid=htmlspecialchars(trim($_POST['uid']));//获取用户ID
$cues = '';
if ($doc_id != ''){
$sql = mysql_query("
SELECT a.id AS hid
FROM `yershop_document_user` AS a  
LEFT  JOIN `yershop_collect_user` AS b 
ON a.id=b.goodid 
WHERE b.goodid='{$doc_id}' && b.uid='{$uid}';
	");
	if(mysql_fetch_array($sql) == true){
		$cues = '1';//有收藏
	}else {
		$cues = '2';//没收藏
	}
}
$b='[{"tishi":"'.$cues.'"}]';
echo $b;
mysql_close();
?>