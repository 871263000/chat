<?php 
require_once('config.inc.php');
header("Content-type:text/html;charset=utf-8");
$staffid = $_SESSION['staffid'];
$staffid = 4;
//创建人id
$_POST['group_founder'] = $staffid;
$arrStaffid = explode(",", $_POST['group_participants']);
if (!in_array($staffid, $arrStaffid)) {
	$_POST['group_participants'] = $_POST['group_participants'].",".$staffid;
	$arrStaffid[] = $staffid;
}
$d = new database();
//新增一个群聊
if($d->create('oms_group_chat') > 0){
	$insertId = $d->lastInsertId();
	// 有多少参加人 就插入几条数据
	foreach ($arrStaffid as $k => $val) {
		$arrValue[] = "($insertId, $val, '".$_POST['group_participants']."', '".$_POST['group_name']."',".time().", ".time().")";
	}
	$value = implode(",", $arrValue);
	$sql = "INSERT INTO `oms_groups_people` (`pid`, `staffid`, `all_staffid`, `group_name`,`create_time`, `update_time`) values".$value;
	$d->query($sql);
	echo "<script>alert('恭喜您创建成功')</script>";
	echo "<script>location.href='index.php'</script>";
}else{
	echo "<script>alert('对不起，添加失败')</script>";
	//跳转
	echo "<script>history.back();</script>";
}
 ?>