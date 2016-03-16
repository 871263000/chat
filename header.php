<?php 
require_once('config.inc.php');
require_once('lib/mesages.class.php');
$uid = $_SESSION['staffid'];
$oms_id = $_SESSION['oms_id'];
$uid = 4;
$oms_id = 2;
$pageload = 10;//消息显示的条数
$session_no = 0;//会话id
$mesNum = 0;
//消息类型
$mes_type = 'message';
//实例化消息
$mes = new messageList($uid, $oms_id);
//最近联系人
if (isset($uid)) {
  	$recentContact = $mes->recentContact();
}
// print_r($arrMes);
//自己的信息
$userinfo = $mes->userinfo();
$name = $userinfo['name'];//自己名字
$card_image = $userinfo['card_image'];//头像的url
 ?>