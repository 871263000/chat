<?php
header("Content-Type:text/html;charset=utf-8");
error_reporting(E_ALL ^ E_NOTICE^ E_DEPRECATED);
if(!session_id()){session_start();}
//路径配置
define('DOCUMENT_ROOT', dirname(__FILE__));
define('DOCUMENT_URL', 'http://'. $_SERVER['HTTP_HOST'] .'/chat/kehu/');

//数据库配置
define("DB_HOST","localhost");
define("DB_USER","root");
define("DB_PASS","");
define("DB_NAME","oms");

$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS);
mysql_select_db(DB_NAME);
mysql_query('set names utf8');

require_once('lib/360_safe3.php');

//数据库
require("lib/database.class.php");
$d = new database();

//分页
require("lib/page.class.php");