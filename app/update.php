<?php 
$ver=htmlspecialchars(trim($_POST['ver']));//用户版本
$reslist['up_id'] = 212;//输入版本号 ，如1.2.0输入120
$reslist['up_aurl'] = "http://www.bjxxw.com/actioncenter/APP/download/index.html";
$reslist['up_iurl'] = "http://www.bjxxw.com/actioncenter/APP/download/index.html";
echo json_encode($reslist);

?>