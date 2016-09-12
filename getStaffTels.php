<?php 
require_once("../config.inc.php");
$d = new database();
$SESSION['oms_id'] = 3;
$r=isset($_POST['staffid'])?$d->find('SELECT  a.`staffid`,a.`mobile_phone`, a.`tel`, a.`tel_branch`, b.`org_name` FROM `oms_hr` a left join  `oms_general_admin_user` b on a.`oms_id` = b.`oms_id` WHERE a.`staffid`='.$_POST['staffid']):$d->findAll('SELECT  `staffid`,`mobile_phone`, `tel`, `tel_branch` FROM `oms_hr` where oms_id='.$SESSION['oms_id']);
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