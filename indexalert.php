<?php
require_once('lib/mesages.class.php');
$uid = $_SESSION['staffid'];
$oms_id = $_SESSION['oms_id'];
$uid = 4;
$oms_id = 1;
$pageload = 10;//消息显示的条数
$session_no = 0;//会话id
//消息类型
$mes_type = 'message';
//实例化消息
$mes = new messageList($uid, $oms_id);
//接受人名字
$accname = $mes->accname;
//提示消息列表
if (isset($uid)) {
  $arrMes = $mes->mesAlertList();
  $name = $mes->name;//自己的名字
  $mesNum = $mes->mesNum;//提示消息的个数
}
//自己的信息
$userinfo = $mes->userinfo();
$name = $userinfo['name'];//自己名字
$card_image = $userinfo['card_image'];//头像的url
?>
<html><head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>聊天</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <!-- Include these three JS files: -->
  <script type="text/javascript" src="js/swfobject.js"></script>
  <script type="text/javascript" src="js/web_socket.js"></script>
  <script type="text/javascript" src="js/jquery.js"></script>
  <script type="text/javascript" src="js/touchSwipe.js"></script>
  <script type="text/javascript" src="js/web_message.js"></script>
  <link rel="stylesheet" href="css/webRight.css" type="text/css"/>
  <link rel="stylesheet" href="css/app.css" type="text/css"/>
<style type="text/css">
  *{font-family: 'Microsoft YaHei', SimSun, sans-serif !important;}
  .mes_radio{ width: 21px; height: 21px; display: block; border-radius: 50%; background: #ed145b; text-align: center; line-height: 21px; margin: 9px 0 0 10px; cursor: pointer; color: #fff; text-align: center; line-height: 21px; margin: 10px 0 0 14px; }
  .online_man{ text-align: center;margin: 0 auto;box-shadow: 0 0 5px #999;}
  .online_man ul li, .group_pep li{line-height: 30px;text-align: center;list-style: none;padding: 5px;border-bottom: 1px solid #dedede;}
  .online_man ul{margin-left: auto;margin-right: auto;padding: 0;}
  #name_box{
    position:absolute;background:#fff;display:none;width: 100%;margin-top:200px;
    z-index: 111;
  }
  .mes_dclose{display: block !important;}
  .container{ margin: auto !important;}
  .mes_chakan_close{line-height: 30px; cursor: pointer;}
</style>
  <script type="text/javascript">
    var uid;
    $(function (){
        connect();
    })
    var mes_online = 1;
    var oms_id = "<?php echo $oms_id;?>";
    uid = "<?php echo $uid;?>"; // 发送人id
    var name = "<?php echo $name;?>";// 发送人name
    var accept_name ="<?php echo $accname?>";//接收人名字
    var room_id = "<?php echo isset($oms_id) ? $oms_id : 1?>";//房间id
    var to_uid = <?php echo isset($_GET['staffid']) ? $_GET['staffid'] : 0;?>;// 接收人id
    var mes_type = "<?php echo $mes_type?>";
    var session_no = "<?php echo $session_no?>";//会话id
    var document_url = "<?php echo DOCUMENT_URL?>";
    var groupId = 0;
    var header_img_url = "<?php echo $card_image?>";
  </script>
</head>
<body>
<div class="chat-container">
  <div class="container">
    <div class="mes_title">
      <h2 class="mes_title_con"><?php echo $accname;?></h2><span aria-hidden="true" class="mes_dclose">&times;</span>
    </div>
  <div class="mes_con_box">
      <div class="row clearfix">
             <div class="">
                 <div class="">
                 <div class="loader">
                    <div id="mes_load" style="display:none;"><?php echo $pageload;?></div>
                    <div class="loading-3">
                        <i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i>
                    </div>
                  </div>
                  <ul class="he_ov"></ul> 
                 </div>
             </div>
          <div class="mes_footer">
              <!-- <form onsubmit="onSubmit(); return false;"> -->
                  <div class="mes_input">
                    <i class="header_icon"></i>
                    <div class="mes_inout textarea" id="textarea" style="height:auto;" contenteditable="true"></div>
                    <textarea style="display:none" class="mes_inout" ></textarea>
                    <input type="submit" class="btn btn-default" id="submit" value="发送" />
                    <div style="clear:both"></div>
                  </div>
                  <div class="emoticons"></div>
               <!-- </form> -->
            <div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
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
       <ul class="list-group"> </ul>
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
              $addClass = "chat_people";
          } else {
              $sender_name = $value['accept_name'];
              $addClass = "session_no";
          }
        ?>
        <div class="mes_box">
            <span class='mex_con'><?php echo $sender_name.":".$rest;?></span>
            <div style='height:30px'>
              <!-- <a href="javascript:void(0)" class="mes_chakan_close"  mesid="<?php echo $value['id'];?>" session_no='<?php echo $value['session_no'];?>' target="_blank"> -->
                <span class='mes_chakan_close <?php echo $addClass;?>' id="<?php echo $value['sender_id'];?>" mesid="<?php echo $value['id'];?>" mestype="<?php echo $value['message_type'];?>" group-name="<?php echo $sender_name;?>" group-all = "<?php echo $value['accept_id'];?>" session_no='<?php echo $value['session_no'];?>' >
                  查看
                </span>
              <!-- </a> -->
            </div>
            <span class='mes_close' mestype='<?php echo $value['message_type'];?>' mesid='<?php echo $value['id'];?>'  session_no='<?php echo $value['session_no'];?>'>X</span>
        </div>
      <?php endforeach;?>
        <!-- end -->
      </div>
    </div>
  </div>
  <!-- 弹出框 -->
  <div class="cd-popup" role="alert">
    <div class="cd-popup-container">
      <p class="mes_alert_con">请选择聊天对象</p>
      <ul class="cd-buttons">
        <li><a href="#" class="clo_alert">确定</a></li>
        <!-- <li><a href="#">否</a></li> -->
      </ul>
      <a href="#" class="cd-popup-close img-replace">关闭</a>
    </div> <!-- cd-popup-container -->
  </div> <!-- cd-popup -->
</div>
<script type="text/javascript" src="js/web_index.js"></script>
</body>
</html>
