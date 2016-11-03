<?php
include ("database.php");
require_once ('email.class.php'); 
date_default_timezone_set("PRC");
class mysqlJi{//数据库
	
	static function mysqlQu($sql){//sql语句
		return mysql_query($sql);		
	}
	static function mysqlFeAs($result){//遍历结果
		$i=0;//变量i
		while($row = mysql_fetch_assoc($result)){
		$reslist[$i] =$row;
		$i++;
		}
		return $reslist;	
	}
	static function jsonEn($reslist){//输出json
		echo json_encode($reslist);
	}
	static function mysqlRows(){ //受影响的行数
		$info_str = mysql_info(); //函数返回最近一条查询的信息。如果成功，则返回有关该语句的信息，如果失败，则返回 false
		$a_rows = mysql_affected_rows(); //受影响的行数
		preg_match("([0-9]*)", $info_str, $r_matched); 
		return ($a_rows < 1)?($r_matched[1]?$r_matched[1]:0):$a_rows; 
		 
	}
	static function mysqlCl(){//关闭数据库
		mysql_close();
	}
}
//忘记密码
$email=htmlspecialchars(trim($_POST['email']));//获取邮箱
//密码生成
function get_password($length) 
{
    $str = substr(md5(time()), 0, $length);
    return $str;
}
$pass=get_password(8);
$password=md5(sha1($pass).'Mh-(Sg3b8wj?;nDaHlm`)PRIcz>@0}f^/%"|C][:');//设置密码
	//########################################## 
									$smtpserver = "smtp.qq.com";//SMTP服务器 
									$smtpserverport =25;//SMTP服务器端口 
									$smtpusermail = "vip@bjxxw.com";//SMTP服务器的用户邮箱 
									$smtpemailto = $email;//发送给谁 
									$smtpuser = "vip@bjxxw.com";//SMTP服务器的用户帐号 
									$smtppass = "12537ji";//SMTP服务器的用户密码 
									$mailsubject = "北京信息网活动-新密码";//邮件主题 
									$mailbody = '
									<table style="text-align:left;border:1px solid #ddd;padding:20px 0" cellpadding="0" cellspacing="0" width="600"><tbody><tr><td><table style="border-bottom:#f60 2px solid;padding-bottom:12px" align="center" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td style="line-height:22px;padding-left:20px"><h1 class="navbar-brand" style="box-sizing:border-box;margin:0;line-height:20px;height:50px;padding:15px 2px;text-transform:uppercase"><span style="box-sizing:border-box;background-color:transparent;text-decoration:none;-webkit-transition:all .3s ease-out 0s;transition:all .3s ease-out 0s;outline:0"><span style="font-size:xx-large;color:#333">北京信息网活动-密码重置</span></span></h1></td><td style="font-size:12px;padding-right:20px;padding-top:30px" align="right">&nbsp;</td></tr></tbody></table><table style="padding:0 20px" align="center" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td style="font-size:14px;color:#333;height:40px;line-height:40px;padding-top:10px">亲爱的<b style="color:#333;font-family:Arial">会员</b> ：</td></tr><tr><td style="font-size:12px;line-height:22px"><p style="text-indent:2em;padding:0;margin:0"><span style="color:#333">您好！您的新密码是（</span><span style="color:#f0f">'.$pass.'</span><span style="color:#333">）</span></p></td></tr><tr><td style="padding-top:15px"><span style="color:#000"><a href="http://active.bjxxw.com/" target="_blank" style="display:inline-block;padding:0 25px;height:28px;line-height:28px;text-align:center;background:#f70 none repeat scroll 0 0;font-size:12px;cursor:pointer;border-radius:2px;text-decoration:none" title="立即去官网怎么样">去看看新活动</a></span></td></tr><tr><td style="font-size:12px;color:#333;line-height:22px;padding-top:15px">如果您有任何疑问，请与我们联系。</td></tr><tr><td style="font-size:12px;color:#333;line-height:22px">客服热线：400-0098-987（周一至周五 9:00-18:00）</td></tr><tr><td style="font-size:12px;color:#333;line-height:22px">客服邮箱：<a href="mailto:vip@bjxxw.com" target="_blank" style="color:#2af">vip@bjxxw.com</a></td></tr></tbody></table><table style="margin-top:60px" align="center" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td style="font-size:12px;color:#777;line-height:22px;border-bottom:#2af 2px solid;padding-bottom:8px;padding-left:20px">此邮件由系统自动发出，请勿回复！</td></tr><tr><td style="font-size:12px;color:#333;line-height:22px;padding:8px 20px 0">感谢您对北京信息网（<a href="http://www.bjxxw.com/" target="_blank" style="color:#2af;font-family:Arial">http://www.bjxxw.com</a> ）的支持，祝您好运！</td></tr><tr><td style="font-size:12px;color:#333;line-height:22px;padding:0 20px">技术支持：北京信息网</td></tr></tbody></table></td></tr></tbody></table><table style="text-align:center" cellpadding="0" cellspacing="0" width="600"><tbody><tr><td style="font-size:12px;color:#999;line-height:30px" align="center">Copyright © 2005 - 2016, 版权所有 bjxxw.com</td></tr></tbody></table>
									';//邮件内容 								
									$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件 
									$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证. 
									$smtp->debug = false;//是否显示发送的调试信息 									
							
$cues = '';//提示语
if($email == ''){//邮箱不为空
		$cues='邮箱不为空';
	}else{
			$pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
			if(preg_match($pattern, $email)){
				$res=mysqlJi::mysqlQu("SELECT id FROM yershop_ucenter_member WHERE email='{$email}'");//sql语句
				$rows=mysqlJi::mysqlRows();//受影响的行数
				if($rows==1){
					$res2=mysqlJi::mysqlQu("UPDATE  `yershop_ucenter_member` SET `password`='{$password}' WHERE email='{$email}';");//sql语句
					$rows2=mysqlJi::mysqlRows();//受影响的行数
					if($rows==1){
						$cues =1;//成功了							
						$b=$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype); 
					}else{
						$cues = '失败';
					}
				}else{
					$cues = $email.'邮箱没有被注册';
				}
						
			}else{
				$cues='邮箱格式不正确';
			}		
		}



 //$rli=mysqlJi::mysqlFeAs($res);//遍历结果
 //mysqlJi::jsonEn($rli);//输出json
 mysqlJi::mysqlCl();//关闭数据库
$b='[{"tishi":"'.$cues.'"}]';
echo $b;

?>