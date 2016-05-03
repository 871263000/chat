<?php 
 require_once("config.inc.php");
header("Content-type: text/html; charset=utf-8");
$tabName='oms_department';
$m=new database();
if($_POST['getposition']){
   die(json_encode($m->findAll("select `name_of_post_and_position` from oms_post_and_position_list")));
}
if($_POST['getRen']){
	// echo "select `name` from oms_hr where `new_department`='".$_POST['dep']."' and `new_department_two`='".$_POST['dep1']."' and `new_position`='".$_POST['pos']."'";
die(json_encode($m->findAll("select `name` from oms_hr where `new_department`='".$_POST['dep']."' and `new_department_two`='".$_POST['dep1']."' and `new_position`='".$_POST['pos']."'")));
}
die(json_encode($arr));





 
 ?>