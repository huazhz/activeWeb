<?php
//我的评论->删除(需要用户，删除评论这个id的内容)
include ("database.php");
$userid = $_POST['uid'];
$goodid = $_POST['hid'];
$cues = '';
if($goodid != ''){
		$sql = mysql_query("
		DELETE FROM `yershop_comment` WHERE uid='{$userid}' AND goodid='{$goodid}';		
		");
       	if($sql){
       	 $cues = '删除评论成功';
		}else{
         $cues = '删除评论失败!';
		}
}
$b='[{"tishi":"'.$cues.'"}]';
echo $b;
mysql_close();
?>