<?php 
require_once '../config.inc.php';

$uid = $_SESSION['staffid']; 
$oms_id = $_SESSION['oms_id'];

$otherUid = $_GET['staffid']; 

if ( empty($otherUid) ) {
	$uid = $otherUid;
}

$sql = 'SELECT `staffid`,`name`, `card_image`, `oms_id` FROM oms_hr WHERE staffid = "'.$uid.'" AND oms_id ='.$oms_id;


$d = new database();
$res = $d->find($sql);
echo json_encode($res);

 ?>