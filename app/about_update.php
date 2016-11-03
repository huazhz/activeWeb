<?php
include ("database.php");
date_default_timezone_set("PRC");
//修改个人资料
	
$userid = $_POST['uid'];//用户id
$nickname = $_POST['nickname'];//昵称
$username = $_POST['username'];//姓名
$sex=$_POST['sex'];//性别


$birthday = $_POST['birthday'];//生日
$pic_path = $_POST['face'];//获取图片
$cues = '';//提示语
if($nickname == ''){//昵称不为空
		$cues='昵称不能为空';
	}else if($username == ''){//姓名不为空
		$cues = '请输入姓名';
	}else if($birthday == ''){//生日不为空
		$cues = '请输入您的生日';
	}else {
	
					// $face='/app/'."{$pic_path}";							
								$result1= mysql_query("		
UPDATE `yershop_member` SET nickname='{$nickname}',sex='{$sex}' ,face='{$pic_path}', birthday=UNIX_TIMESTAMP('{$birthday}')  WHERE uid='{$userid}';
								");
//								$result2=mysql_query("UPDATE `yershop_ucenter_member` SET username='{$username}', reg_time=UNIX_TIMESTAMP() WHERE id='{$userid}';")
						if($result1){
							$cues = '1';	//成功了
						}else{
							$cues = '资料更新失败';
						}
					
	
		}

$b='[{"tishi":"'.$cues.'"}]';
echo $b;
mysql_close();
?>