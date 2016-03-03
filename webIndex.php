<?php 
require_once('lib/mesages.class.php');
$uid = $_SESSION['staffid'];
$oms_id = $_SESSION['oms_id'];
$uid = 409;
$oms_id = 1;
//消息类型
//实例化消息
$mes = new messageList($uid, $oms_id);

//提示消息列表
if (isset($uid)) {
  //
  $arrMes = $mes->mesAlertList();
  $name = $mes->name;//自己的名字
  $mesNum = $mes->mesNum;//提示消息的个数
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<script type="text/javascript" src="js/swfobject.js"></script>
  	<script type="text/javascript" src="js/web_socket.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/web_message1.js"></script>
	<script type="text/javascript" src="js/web_index.js"></script>
	<link rel="stylesheet" href="css/webRight.css" type="text/css"/>
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css"/>
	<script type="text/javascript">
    var uid;
    $(function (){
        connect();
    })
    var mes_online = 1;
    var oms_id = "<?php echo $oms_id;?>";
    uid = "<?php echo $uid;?>"; // 发送人id
    var name = "<?php echo $name;?>";// 发送人name
    var room_id = "<?php echo isset($_GET['room_id']) ? $_GET['room_id'] : 1?>";//房间id
    var document_url = "<?php echo DOCUMENT_URL?>";
    // 右边消息的个数
  </script>
	<title>Document</title>
</head>
<body>
	<div class="mes_fixed_big">
      <div class="mes_abs">
        <div class="mes_fixed">
          <div class="mes_ico_box" cata-box='ren'>
            <div class="mes_ico mes_hide" style="background-position:0 -50px"></div>
            <div class="mes_ico mes_hide" style="background-position:-100px -100px;text-align: center;color:#fff;">
              <span class="mes_hide" style='line-height: 50px;'><span class='online_ren'>0</span>人</span>
            </div>
          </div>
          <div class="mes_ico_box" cata-box='mes'>
            <div class="mes_ico mes_hide" style="background-position:0 0"></div>
            <div class="mes_ico mes_hide" style="background-position:-100px -100px">
              <div class="mes_radio mes_hide"><?php echo $mesNum;?></div>
            </div>
          </div>
        </div>
        <div class="online_man" >
          <div class="man_tittle">
            <span>人员列表</span>
            <span class ="close" style='cursor: pointer; margin-right: 5px;background: #000;color: #fff; position:absolute;width:20px;height:20px;border-radius: 50%;right: 0; top: 7px; '>&times;</span>
          </div>
          <!-- 人员列表 -->
           <ul> </ul>
        </div>
        <div class="mes_con" style="display: none;">
            <div class="mes_tittle">
              <span>消息列表</span>
              <span class ="close" style='cursor: pointer; margin-right: 5px;background: #000;color: #fff; position:absolute;width:20px;height:20px;border-radius: 50%;right: 0; top: 7px; '>&times;</span>
            </div>
            <!-- 消息列表 -->
            <?php foreach ($arrMes as $key => $value) :?>
            <?php
              $rest =preg_replace('/%6b/', ' ',$value['message_content']);
              $rest = preg_replace('/%5C/', '\\',$rest);
              if ($value['message_type'] == 'message') {
                  $sender_name = $value['sender_name'];
                  $message_url = 'index.php?staffid='.$value['sender_id'];//消息连接地址
              } else {
                  $sender_name = $value['accept_name'];
                  $message_url = $value['message_url'];//消息连接地址
              }
            ?>
            <div class="mes_box">
                <span class='mex_con'><?php echo $sender_name.":".$rest;?></span>
                <div style='height:30px'>
                  <a href="<?php echo $message_url?>" class="mes_chakan_close" mestype="<?php echo $value['message_type'];?>" mesid="<?php echo $value['id'];?>" session_no='<?php echo $value['session_no'];?>' target="_blank">
                    <span class='mes_chakan' session_no='<?php echo $value['session_no'];?>' >
                      查看
                    </span>
                  </a>
                </div>
                <span class='mes_close' mestype='<?php echo $value['message_type'];?>' mesid='<?php echo $value['id'];?>'  session_no='<?php echo $value['session_no'];?>'>X</span>
            </div>
          <?php endforeach;?>
            <!-- end -->
          </div>
        </div>
    </div>
</body>
<script>
	var mesnum = parseInt($('.mes_radio').html());//消息的个数
</script>
</html>