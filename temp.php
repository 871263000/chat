<?php 
require_once('config.inc.php');
require_once('lib/mesages.class.php');
$chat_uid = $_SESSION['staffid'];
$oms_id = $_SESSION['oms_id'];

$oms_id = 3;

$_POST = [
'sender_id'=> $oms_id, //  插入oms_id
'sender_name' => '苹果',  //插入 公司的名字
'accept_id' => 5,  //插入 发给谁 的 staffid
'accept_name' => '傻逼',// 插入  发给谁 的 名字
'message_type'  => 'message',  // 固定的 不要改
'mesages_types' =>'sysNotice', //固定的 不要改
'message_content' => '收钱了',  // 消息的 内容  你要对他说的 东西
'room_id'=> $oms_id, //插入oms_id
'session_no' => '5sn',  // 插入 oms_id 拼接 sn
'dialog'=> 0, //固定的 不要改
'oms_id'=> $oms_id, //  插入oms_id
];

$db = new database();
$db->create('oms_string_message');

$insertid = $db->lastInsertId();

unset($_POST);

// $_POST = [
// 'session_no' => $oms_id.'sn',  // 插入 oms_id 拼接 sn
// 'pid'=> 4,  //插入 发给谁 的 staffid
// 'mes_id'=> $insertid,  //新插入的id
// 'oms_id'=> $oms_id, // 插入oms_id
// ];


$db->query('INSERT `oms_chat_message_ist`( `session_no`, `pid`, `mes_id`, `oms_id`) values("5sn", 5, '.$insertid.', '.$oms_id.')'); // 这条不要 用 create()  方法 就这样用
 ?>