<?php
//首页前广告
include("database.php");

class mysqlJi{//数据库
	//public static $sql;//数据库语句
	//public static $result;//数据库语句执行结果
	//public static $i=0;//变量i
	//public static $row;//返回查询结果集数组每一行		
	//public static $reslist=array();//接收结果集的数组
	//public static $mysqlRows;//查询结果集受影响的行数
	
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

 $res=mysqlJi::mysqlQu("SELECT miao,path,surl,title FROM app_lunbotu WHERE if_gg=1 ORDER BY  RAND() LIMIT 1");//sql语句
 $rli=mysqlJi::mysqlFeAs($res);//遍历结果
 mysqlJi::jsonEn($rli);//输出json
 mysqlJi::mysqlCl();//关闭数据库
?>