<?php
include ("database.php");
date_default_timezone_set('PRC');

//用户添加活动
$userid = $_POST['uid'];//获取用户id
$title = $_POST['title'];//获取文章标题
$category_id = $_POST['cid'];//分类id
$str_time = $_POST['sta'];//开始时间
$stp_time = $_POST['stp'];//结束时间
$pic_path = $_POST['url'];//封面图片
$place = $_POST['place'];//地点
$tel =$_POST['tel'];//手机号
$QQ = $_POST['qq'];//QQ
$content = $_POST['content'];//内容
$cues = '';
//获取客户端真实IP地址*********
function getip() {
	$unknown = 'unknown';
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	/*
	处理多层代理的情况
	或者使用正则方式：$ip = preg_match("/[\d\.]{7,15}/", $ip, $matches) ? $matches[0] : $unknown;
			*/
			if (false !== strpos($ip, ',')){ $ip = reset(explode(',', $ip)); }
			return $ip;
	}
if($userid == ''){
	$cues='您还没登录..';
  }else if($title == ''){
		$cues='请输入标题..';
	}else if($str_time == ''){
			$cues='请输入开始时间..';
		}else if($stp_time == ''){
				$cues='请输入结束时间..';
			}else if($pic_path == ''){
					$cues='请插入封面图..';
			}else if($pic_path==''){
							$cues ='请上传图片' ;//图片路径
				}else if($place == ''){
						$cues='请输入活动地址..';
					}else if($QQ == ''){
						$cues='请输入QQ号..';/*****/
					     }   
else {
						if($tel == ''){
							$cues='请输入手机号..';
						}else{
							$pattern="/^1[34578]{1}\d{9}$/";
							if(!preg_match($pattern, $tel)){
								$cues='请输入正确的手机号..';
							   }else{
							    	if($QQ == ''){
								    	$cues='请输入QQ号..';
								   }else{
									   if($content == ''){
										  $cues='请输入文章内容..';
									     }else{
										    $face ='/app/'."{$pic_path}";
										    $ip=getip();
									     	$sql = mysql_query("

											INSERT INTO `yershop_document_user`(title,content,titlepage,qq,tel,starttime,stoptime,user_ip,create_time,place,category_id,uid) 
											VALUES('{$title}','{$content}','{$face}','{$QQ}','{$tel}','{$str_time}','{$stp_time}','{$ip}',UNIX_TIMESTAMP(),'{$place}','{$category_id}','{$userid}');
										");
										if($sql){
											$cues = '1';	//成功了
										}else{
											$cues='添加活动失败,请重试..';
									}
									}
								}
							}
						}
}
					/***********/	


$b='[{"tishi":"'.$cues.'"}]';
echo $b;
mysql_close();
?>