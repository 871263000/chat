<?php 
require_once('config.inc.php');
require_once('lib/mesages.class.php');
$uid = $_SESSION['staffid'] = 6;
$oms_id = $_SESSION['oms_id'] = 1;
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
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script src='./js/jquery-1.8.2.min.js'></script>
    <script type="text/javascript" src = "/chat/js/web_message.js"></script>
    <link rel="stylesheet" href="/chat/css/style.css">
</head>
<script>
	 var chat_uid;
    $(function (){
        connect();
    })
    var custom = 1;//客服id
    var mes_online = 1;
    var oms_id = "<?php echo $oms_id;?>";
    chat_uid = "<?php echo $uid;?>"; // 发送人id
    var name = "<?php echo $name;?>";// 发送人name
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
</script>
<style>
	@font-face {
      	font-family: 'iconfont';
      	src: url('//at.alicdn.com/t/font_1457932407_5732756.eot'); /* IE9*/
      	src: url('//at.alicdn.com/t/font_1457932407_5732756.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
      	url('//at.alicdn.com/t/font_1457932407_5732756.woff') format('woff'), /* chrome、firefox */
      	url('//at.alicdn.com/t/font_1457932407_5732756.ttf') format('truetype'), /* chrome、firefox、opera、Safari, Android, iOS 4.2+*/
      	url('//at.alicdn.com/t/font_1457932407_5732756.svg#iconfont') format('svg'); /* iOS 4.1- */
    }
                    
    .staff-refresh, .staff-close{
        font-family: 'iconfont';
    }
</style>
<body>
	
</body>
</html>