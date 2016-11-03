<?php
$conn = mysql_connect("10.10.10.167","active","12345678");
  mysql_select_db('active');
 mysql_query("set names utf8");
if (!$conn)
{
  die('数据库连接失败: ' . mysql_error());
}
?>