<?php 

$database = 'oms';
function list_tables($database) 
{ 
	$rs = mysql_query("SHOW TABLES FROM ".$database); 
	$tables = array(); 
	while ($row = mysql_fetch_row($rs)) { 
	$tables[] = $row[0]; 
	} 
	mysql_free_result($rs); 
	return $tables; 
} 
var_dump(list_tables($database));
 ?>