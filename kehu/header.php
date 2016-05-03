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
  <script src="js/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="css/jquery-ui.css">
    <script type="text/javascript" src = "/chat/js/web_message.js"></script>
    <script type="text/javascript" src="js/touchSwipe.js?"></script>
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
    var chat_name  = "<?php echo $name;?>";// 发送人name
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
    .chat-container{
          display: none;
    z-index: 999999;
    position: fixed;
    top: 0;
    left: 50%;
    margin-left: -300px;
    height: 100%;
    }
</style>
<body>
<div class="chat-container">
        <div class="mes_title">
          <h2 class="mes_title_con">请选择人<i title="群聊添加人" class="add-groupMan"></i></h2><span aria-hidden="true" class="mes_dclose">&times;</span>
        </div>
        <div class="mes_con_box">
          <div class="">
                 <div class="he-ov-box mes-scroll pc_he-ov-box chat_initial">
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
              <div class="pc_mes_input_box">
                <div class="pc_emoji_box">
                  
                </div>
                <div class="pc_mes_tool">
                  <ul>
                    <li class="pc_mes_tool_emoji pc_mes_tool_list"></li>
                    <li class="pc_mes_tool_img pc_mes_tool_list"></li>
                    <li class="pc_mes_tool_file pc_mes_tool_list"></li>
                  </ul>
                </div>

                <div class="pc_mes_input" id="pc_mes_input" contenteditable="true"></div>
                <div class="pc_mes_send">
                <span style = "color: #aaa">按Shift + Enter 换行， Enter提交</span>
                  <div class="chat_btn" id="chat_btn">发送</div>
                </div>
              </div>
              <div class="mes_footer mb_mes_footer mb_mes_footer">
              <div class="plus_menu_box">
                <div class="plus_menu">
                  <span class="header_icon plus-list"><img src="images/header_icon.png" alt=""></span><span class="plus-list"><img src="images/uploadimg1.png" id="upclick" alt=""><input style="display:none" type="file" multiple id="send-upimg"></span><span id="cli-upFile"><img src="images/uploadfile.png" alt=""></span><span class="plus-list" id="mesChat_audio"><img src="images/iconfont-yuyin.png" alt=""></span>
                </div>
                <i class="icon-caret-down"></i>
              </div>
                  <!-- <form onsubmit="onSubmit(); return false;"> -->
                      <div class="mes_input">
                        <i class="plus_icon"></i>
                          <div class="mes_inout textarea chat_text_voice" id="mes_textarea" style="height:auto;" contenteditable="true"></div>
                          <textarea style="display:none" class="mes_inout" ></textarea>
                          <input type="submit" class="btn btn-primary chat_text_voice" id="submit" value="发送" />
                          <div class="keydown_voice chat_voice_input">按下开始说话</div>
                          <div class="chat_input_key chat_voice_input"></div>
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
<div class="img-box">
  <div class="img-box-title"><span>发送的图片</span><span style="color: #000;" class="com-close com-close-act">&times;</span></div>
  <div class="sending-img-box"></div>
  <div class="img-box-act"><span class="btn btn-success com-close-act">取消</span><span class="btn btn-info send-clipboard-img">发送</span></div>
</div>
	<input type="submit" id="submit" >
<div class="file-main">
  <ul>
      <li>
          <input id="token" name="token" class="ipt" value="">
      </li>
      <li>
          <input id="key" name="key" class="ipt" value="">
      </li>
      <li>
          <input id="file" name="file" class="ipt" type="file" />
      </li>
      <li>
          <input id="filename" type="text" value="">
      </li>
  </ul>
</div>
</body>
    <script type="text/javascript" src = "/chat/js/web_index.js"></script>
</html>