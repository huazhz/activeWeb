<?php
include ("database.php");
//确认订单(报名详细)

	
	
$uid = htmlspecialchars(trim($_POST['uid']));//报名人id
$goodid = $_POST['hid'];//活动id
$qq= htmlspecialchars(trim($_POST['qq']));//默认
$real_name= htmlspecialchars(trim($_POST['real_name']));//真实姓名
$email= htmlspecialchars(trim($_POST['email']));//email
$card= htmlspecialchars(trim($_POST['card']));//身份证号
$tel= htmlspecialchars(trim($_POST['tel']));//tel
$address= htmlspecialchars(trim($_POST['address']));//住址
$unit= htmlspecialchars(trim($_POST['unit']));//单位地址
$beizhu= htmlspecialchars(trim($_POST['beizhu']));//备注
$jiage= htmlspecialchars(trim($_POST['jiage']));//单位地址
$order_code= htmlspecialchars(trim($_POST['order_code']));//备注
$sex= $_POST['sex'];//性别
$bm_time=date('Y-m-d H:i:s',time());
$cues=''; //信息提示
//$last_id=""//返回插入 最新id
$ip=$_SERVER["REMOTE_ADDR"];//获取ip
//获得该IP所在的地理位置
function getIPLoc_QQ($queryIP){
	$url = 'http://ip.qq.com/cgi-bin/searchip?searchip1='.$queryIP;
	$ch = curl_init($url);
	curl_setopt($ch,CURLOPT_ENCODING ,'gb2312');
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
	$result = curl_exec($ch);
	$result = mb_convert_encoding($result, "utf-8", "gb2312"); // 编码转换，否则乱码
	curl_close($ch);
	preg_match("@<span>(.*)</span></p>@iU",$result,$ipArray);
	$loc = $ipArray[1];
	return $loc;
}
$city=getIPLoc_QQ($ip);

if($real_name==''){
	$cues='姓名不能为空，必填项';
}else {
 if($tel==''){
	$cues=" 手机号不能为空"; 
 }else{
	$pattern="/^1[34578]{1}\d{9}$/";
							if(!preg_match($pattern, $tel)){
								$cues='请输入正确的手机号..';
							   }else {
									 	$sql = mysql_query("
										INSERT INTO `yershop_baoming` (uid,goodid,real_name,sex,qq,email,tel,user_ip,bm_time,address,unit,card,city_ip,beizhu,price,order_code)
										VALUES( '{$uid}'  , ' {$goodid}' ,'{$real_name}','{$sex}' ,'{$qq}', '{$email}'  , '{$tel}'  , '{$ip}'  ,'{$bm_time}' ,'{$address}' ,' {$unit}'  , '{$card}'  ,'{$city_ip}','{$beizhu}' ,'{$jiage}','{$order_code}');
									 ");
							 	if($sql){
							 		$cues=1;
							 		$sql2 = mysql_query("							 			
							 			  select  if_pay from  yershop_document where id='{$goodid}';
							 				");
							 		$rows=mysql_fetch_assoc($sql2);
							 	
							 		if( $rows['if_pay']==0){
							 			$sql3 = mysql_query("
							 				UPDATE `yershop_baoming` SET `if_baoming`='1'  where order_code ='{$order_code}' AND uid='{$uid}'"
							 							);
							 	      	}
							 		//$last_id=mysql_insert_id($conn); 
							 		//var_dump(mysql_insert_id($conn));
							 	}else{
							 		$cues = '报名添加失败..';
							 	        }
						            }    
						       }
						}

$b='[{"tishi":"'.$cues.'"}]';
echo $b;
mysql_close();
?>