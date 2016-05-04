<?php
require_once('config.inc.php');
require_once('lib/mesages.class.php');
$chat_uid = $_SESSION['staffid'];
$oms_id = $_SESSION['oms_id'];
$chat_uid = 554;
$oms_id = 3;
$pageload = 10;//消息显示的条数
$session_no = 0;//会话id
$mesNum = 0;
$messageSessionList = [];
//消息类型
$mes_type = 'message';
//实例化消息
$mes = new messageList($chat_uid, $oms_id);
//最近联系人
if (isset($chat_uid)) {
  	$recentContact = $mes->recentContact();
}
//最近联系人session_no集合;
$ContactManSession = [];


foreach ($recentContact as $key => $value) {
	$ContactManSession[] = $value['session_no'];
}
//提示消息列表
if (isset($chat_uid)) {
  	$arrMes = $mes->mesAlertList();
  	if (!empty($arrMes)) {
	    foreach ($arrMes as $key => $value) {
	      $mesNum  += $value['mes_num'];
	      $messageSessionList[] = $value['session_no'];
	    }
  	}
}
//自己的信息
$userinfo = $mes->userinfo();
$chat_name = $userinfo['name'];//自己名字
$card_image = $userinfo['card_image'];//头像的url
//群聊列表
$arrGroup = $mes->groupChatList();

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script type="text/javascript" src="js/swfobject.js"></script>
	<script type="text/javascript" src="js/web_socket.js"></script>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/touchSwipe.js?"></script>
	<script src="js/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="css/jquery-ui.css">
	<script src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/web_message1.js"></script>
	<link rel="stylesheet" href="css/webRight.css" type="text/css"/>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<link rel="stylesheet" href="css/font-awesome.min.css" type="text/css"/>
	<link rel="stylesheet" href="css/app.css" type="text/css"/>
</head>
  <script type="text/javascript">
    var chat_uid;
    $(function (){
        connect();
    })
    var custom = 1;//客服id
    var mes_online = 1;
    var oms_id = "<?php echo $oms_id;?>";
    chat_uid = "<?php echo $chat_uid;?>"; // 发送人id
    var chat_name = "<?php echo $chat_name;?>";// 发送人name
    var accept_name ="<?php echo $accname?>";//接收人名字
    var room_id = "<?php echo isset($oms_id) ? $oms_id : 1?>";//房间id
    var to_uid = <?php echo isset($_GET['staffid']) ? $_GET['staffid'] : 0;?>;// 接收人id
    // var db_id;//indexeddb
    var to_uid_header_img ='';// 接收人id
    var mes_type = "<?php echo $mes_type?>";
    var session_no = "<?php echo $session_no?>";//会话id
    var document_url = "<?php echo DOCUMENT_URL?>";
    var groupId = 0;
    var header_img_url = "<?php echo $card_image?>";
    nearestContact = new Object();
    nearestContact = <?php echo !empty($ContactManSession) ? json_encode( $ContactManSession ): json_encode([]);?>;

  </script>
<body>
	<h3>实时在线人数：<span id="allOnlineNum">0</span>人</h3><br/>
</body>
</html>