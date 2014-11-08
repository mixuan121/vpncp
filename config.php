<?php 
/*
 * MySQL 配置信息。
 */

//MYSQL主机信息
define('MYSQL_HOST', "localhost");
//MYSQL端口信息
define('MYSQL_PORT', "3306");
//MYSQL用户名
define('MYSQL_USER', "freeradius");
//MYSQL用户密码
define('MYSQL_PASS', "password");
//MYSQL数据库
define('MYSQL_DBNAME', "radius");

//连接数据库
$conn = mysql_connect(MYSQL_HOST.':'.MYSQL_PORT,MYSQL_USER,MYSQL_PASS) or die('MySQL connect error:'.mysql_error());
//选择数据库
$selctdb = mysql_select_db(MYSQL_DBNAME,$conn) or die('MySQL database select error:'.mysql_error());

?>
