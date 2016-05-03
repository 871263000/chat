<?php 
	// header('content-type:text/html;charset=utf-8');
	mysql_connect('localhost','root','');
	mysql_select_db('oms');
	mysql_query('set names utf8');

	define('JQ', '/www/web/default/oms/fenlei2/js/jquery.js');
	define('JS', '/www/web/default/oms/fenlei2/js/js.js');
	define('PHP', '/www/web/default/oms/fenlei2/lt.php');
	define('COMPANY', '上海斯瑞科技有限公司');
 ?>