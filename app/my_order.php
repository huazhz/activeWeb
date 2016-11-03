<?php 
include ("database.php");
//我的活动订单详细
$userid = htmlspecialchars(trim($_POST['uid']));
$codeid= htmlspecialchars(trim($_POST['code_id'])); //如果传值是1标识全部 ， 2 表示待付款  3表示完成付款
$page=htmlspecialchars(trim($_POST['page']));

//设置分页
$cues = '';//提示信息
if($page == '')
{
	$page=0;
}
$page=$page*20;//每页显示数目20
if($userid=='' ){
	exit;
}
    if($codeid==1){	

	$qres = mysql_query("
SELECT a.if_baoming,a.goodid AS hid,a.order_code,a.price,b.title,c.path
FROM (yershop_baoming AS a
LEFT JOIN yershop_document AS b 
ON a.goodid=b.id )
LEFT JOIN yershop_picture AS c
ON b.cover_id=c.id
WHERE (a.uid='{$userid}'  AND if_delete=0)
ORDER BY a.bm_time DESC
LIMIT  {$page},20
			");
    }  else if($codeid==2){

  	$qres = mysql_query("
SELECT a.if_baoming,a.goodid AS hid,a.order_code,a.price,b.title,c.path
FROM (yershop_baoming AS a
LEFT JOIN yershop_document AS b 
ON a.goodid=b.id )
LEFT JOIN yershop_picture AS c
ON b.cover_id=c.id
WHERE (a.uid='{$userid}'  AND a.if_baoming=0  AND if_delete=0)
ORDER BY a.bm_time DESC
LIMIT  {$page},20
  			");	
  }else if ($codeid==3){
 
  	$qres = mysql_query("
SELECT a.if_baoming,a.goodid AS hid,a.order_code,a.price,b.title,c.path
FROM (yershop_baoming AS a
LEFT JOIN yershop_document AS b 
ON a.goodid=b.id )
LEFT JOIN yershop_picture AS c
ON b.cover_id=c.id
WHERE (a.uid='{$userid}'  AND a.if_baoming=1  AND if_delete=0)
ORDER BY a.bm_time DESC
LIMIT  {$page},20	
  			");
           }	

$i=0;

while($row=mysql_fetch_assoc($qres)){
	$reslist[$i]=$row;
	$i++; 
}
echo json_encode($reslist);
mysql_close();

?>


	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
