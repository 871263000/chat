<?php
require_once('config.inc.php');
header ( "Content-Type:'blob'") ;
print_r($_POST);
print_r($_FILES);
// file_put_contents("/", $_POST);
 ?>