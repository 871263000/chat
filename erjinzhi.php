<?php
require_once('config.inc.php');
print_r($_POST);
file_put_contents("/", $_POST);
 ?>