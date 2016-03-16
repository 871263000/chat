<?php 
require_once("../config.inc.php");
$d = new database();
$SESSION['oms_id'] = 1;
$r=isset($_POST['staffid'])?$d->find('SELECT  `staffid`,`mobile_phone`, `tel`, `tel_branch` FROM `oms_hr` WHERE oms_id='.$SESSION['oms_id'].' AND `staffid`='.$_POST['staffid']):
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
