<?php
require_once('../config.inc.php');
$sidList=json_decode($_POST['jsonText']);
$d=new database();
// print_r($sidList);

$str = implode(',', $sidList);

$res=$d->findAll('select `staffid`, `new_department`,`new_department_two`, `new_position`,`name`, `card_image` from oms_hr where `staffid` in (' . $str . ')');

// print_r($res);exit;
foreach ($res as $key => $value) {
	// if(in_array($value['staffid'],$sidList)){
	$sql="select * from oms_employees_part_time_position_list where staffid=".$value['staffid'];
	$ss=$d->findAll($sql);
	// print_r($ss);exit();
	if (!empty($ss)) {
		foreach ($ss as $k => $v) {
			$value['pt'] .='兼"' . $v['jz_d'] . ':' . $v['jz_dx'] . ':' . $v['jz_z'] . '"';
			// $_v=array_merge($value,$v);
		}
	}else{
		$value['pt']='';
	}
	$data[]=$value;
	// }
}
echo json_encode($data);
exit();
