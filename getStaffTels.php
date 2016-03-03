<?php 
require_once("../config.inc.php");
$d = new database();
$r=isset($_POST['staffid'])?$d->find('SELECT  `staffid`,`mobile_phone`, `tel`, `tel_branch` FROM `oms_hr` WHERE `staffid`='.$_POST['staffid']):
$d->findAll('SELECT  `staffid`,`mobile_phone`, `tel`, `tel_branch` FROM `oms_hr` where oms_id='.$SESSION['oms_id']);
if(!isset($_POST['staffid'])){
	$arr=[];
	foreach($r as $k=>$v){
		$arr[$v['staffid']]=$v;
		unset($arr[$v['staffid']]['staffid']);
	}
	$res=json_encode($arr);
}else{
	$res=json_encode($r);
}
die($res);
 ?>
