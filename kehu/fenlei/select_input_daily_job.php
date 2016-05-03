<?php
// error_reporting(0);
header("Content-type:text/html;charset=utf-8");
//require_once('/www/web/default/omp/conn.php');
require_once("../config.inc.php");
include("../lib/pdo.class.php");
if($_POST["depname"]){
    $p=new db("oms_input_general_list");
    if($_POST['dep1']){
        $res=$p->where("department_name='".$_POST['depname']."' and department_name2='".$_POST['dep1']."' and state=0 ")->select("name_of_routine_input_work,program_file_link_the_name");
    }else{
        $res=$p->where("department_name='".$_POST['depname']."' and department_name2='' and state=0 ")->select("name_of_routine_input_work,program_file_link_the_name");
    }
    die(json_encode($res));
}

if($_POST["depname1"]){
    $p=new db("oms_select_general_list");
    if($_POST['dep1']){
        $res = $p->where("department_name='" . $_POST['depname1']. "' and department_name2='".$_POST['dep1']."' and state=0 ")->select("name_of_routine_select_work,program_file_link_the_name");
    }else {
        $res = $p->where("department_name='" . $_POST['depname1']."' and department_name2='' and state=0 ")->select("name_of_routine_select_work,program_file_link_the_name");
    }
die(json_encode($res));
}
?>
