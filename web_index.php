<?phprequire_once(__DIR__.'/../config.inc.php');require_once(DOCUMENT_ROOT.'/lib/mesages.class.php');$chat_uid = $_SESSION['staffid'];$oms_id = $_SESSION['oms_id'];$chat_uid = 4;$oms_id = 1;$pageload = 10;//消息显示的条数$session_no = 0;//会话id$mesNum = 0;$messageSessionList = [];//消息类型// $mes_type = 'message';//实例化消息$mes = new messageList($chat_uid, $oms_id);//最近联系人if (isset($chat_uid)) {    $recentContact = $mes->recentContact();}//最近联系人session_no集合;$ContactManSession = [];// 最近 联系人  集合 。。。。foreach ($recentContact as $key => $value) {  $ContactManSession[] = $value['session_no'];}//自己的信息$userinfo = $mes->userinfo();$chat_name = $userinfo['name'];//自己名字$card_image = $userinfo['card_image'];//头像的url$isAdmin = $userinfo['general_admin'];//提示消息列表if (isset($chat_uid)) {    $arrMes = $mes->mesAlertList($isAdmin);    if (!empty($arrMes)) {      foreach ($arrMes as $key => $value) {        $mesNum  += $value['mes_num'];        $messageSessionList[] = $value['session_no'];      }    }}//群聊列表$arrGroup = $mes->groupChatList();//管理员的所有信息 // $chatAdminList = $mes->getAdmin();?><!doctype html><html lang="en"><head>  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">  <meta http-equiv="X-UA-Compatible" content="IE=edge">  <meta name="viewport" content="width=device-width, initial-scale=1">  <title>聊天</title>  <!-- Include these  JS files: -->  <script type="text/javascript" src="js/swfobject.js"></script>  <script type="text/javascript" src="js/web_socket.js"></script>  <script type="text/javascript" src="js/jquery.js"></script>  <script type="text/javascript" src="js/touchSwipe.js?"></script>  <script src="js/jquery-ui.min.js"></script>  <link rel="stylesheet" href="css/jquery-ui.css">  <script src="js/bootstrap.min.js"></script>    <script src="js/chatViewer.min.js"></script>  <link rel="stylesheet" href="css/viewer.min.css">  <script type="text/javascript" src="js/web_message.js"></script>  <link rel="stylesheet" href="css/webRight.css" type="text/css"/>  <link href="css/bootstrap.min.css" rel="stylesheet">  <link href="css/style.css" rel="stylesheet">  <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css"/>  <link rel="stylesheet" href="css/app.css" type="text/css"/><style type="text/css">  html, body{ height: 100%; overflow: hidden;}  .mes_dclose{ display: block !important; color: #000 !important; }  *{font-family: 'Microsoft YaHei', SimSun, sans-serif !important;}  .online_man{ text-align: center;margin: 0 auto;box-shadow: 0 0 5px #999;}  .online_man ul{margin-left: auto;margin-right: auto;padding: 0;}  .mes_chakan_close{ line-height: 20px; cursor: pointer; }  .mes_dclose{ cursor: pointer; position: absolute;  top: 3px;  font-size: 33px;  color: #fff;  right: 0;}  .tab-content{min-height: 300px;height: 80% !important; max-height: 600px; overflow: auto;}  #chat_submit{height: 50px;}  .container-box{ width: auto; }  .chat-container{margin: 0 auto;}</style>  <script type="text/javascript">    var chat_uid;    $(function (){        connect();    })    var custom = 1;//客服id    var mes_online = 1;    var oms_id = "<?php echo $oms_id;?>";    chat_uid = "<?php echo $chat_uid;?>"; // 发送人id    var chat_name = "<?= !empty( $name ) ? $name : '匿名';?>";// 发送人name    var accept_name ="<?php echo $accname?>";//接收人名字    var room_id = "<?php echo isset($oms_id) ? $oms_id : 1?>";//房间id    var to_uid = <?php echo isset($_GET['staffid']) ? $_GET['staffid'] : 0;?>;// 接收人id    // var db_id;//indexeddb    var to_uid_header_img ='';// 接收人id    var mes_type = "<?php echo $mes_type?>";    var session_no = "<?php echo $session_no?>";//会话id    var document_url = "<?php echo DOCUMENT_URL?>";    var groupId = 0;    var header_img_url = "<?php echo $card_image?>";    //保存输入的数据    var inputSave = "";    nearestContact = new Object();        // 加载图片的 index    var imgIndex = 0;    nearestContact = <?php echo !empty($ContactManSession) ? json_encode( $ContactManSession ): json_encode([]);?>;    var arrMessageList = new Array();    arrMessageList = <?php echo !empty($messageSessionList) ? json_encode($messageSessionList) : json_encode([]);?>;    var sessionList = new Array();    console.log(nearestContact);  </script></head><body><!-- 聊天框最小化 --><div class="chatMin" onclick="chatMax()">  <span></span>  <ul style="display:none">    <li></li>  </ul></div><!-- 加载图片的保存 --><div class="loadImg-box" >  <ul class="loadImg loadImging" style="display:none">  </ul></div><!-- 消息提示 --><div class="mesNoticeContainer"></div><!--  end --><div class="chat_voice_box" voice_url="">	<audio src="" preload></audio></div><!-- <div class="alert alert-success" role="alert">    <button class="close"  data-dismiss="alert" type="button" >&times;</button>    <p>恭喜您操作成功！</p></div> --><div class="chat_notice_container alert">	<div class="chat_notice_close close">&times;</div>  <div class="chat_notice_list_box">		<div class="chat_notice_list">			<div class="chat_notice_img">				<img src="/chat/images/chatNotice.png" alt="">			</div>			<div class="chat_notice_con">				<span></span>				<span></span>			</div>      <div class="chat_notice_agree chat_notice_sel"></div>      <div class="chat_notice_unagree chat_notice_sel"></div>		</div>  </div></div>    <div class="bagimg">      <img src="images/2.jpg"  alt="">    </div>    <div class="container-box">      <div class="self-info">              </div>	     <div class="chat-container">       <div class="session-tab">         <ul></ul>       </div>       <div class="mb-chat-tab-content chat-tab-content chating-content">        <div class="mes_title">          <h2 class="mes_title_con">请选择人<i title="群聊添加人" class="add-groupMan"></i></h2><span aria-hidden="true" onclick="chatMin()" class="mes_dMinimize">-</span><span aria-hidden="true" class="mes_dclose">&times;</span>        </div>        <div class="mes_con_box">    	    <div class="">    	           <div class="he-ov-box mes-scroll pc_he-ov-box chat_initial">    	               <div class="">                     <div class="loader">                        <div class="mes_load" style="display:none;"><?php echo $pageload;?></div>                        <div class="loading-3">                            <i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i>                        </div>                      </div>                      <ul class="he_ov"></ul>                      </div>    	           </div>              <div class="pc_mes_input_box">                <div class="pc_emoji_box">                                  </div>                <div class="pc_mes_tool">                  <ul>                    <li class="pc_mes_tool_emoji pc_mes_tool_list"></li>                    <li class="pc_mes_tool_img pc_mes_tool_list"></li>                    <li class="pc_mes_tool_file pc_mes_tool_list"></li>                  </ul>                </div>                <div class="pc_mes_input" contenteditable="true" id="pc_mes_input"></div>                <div class="pc_mes_send">                <span style = "color: #aaa">按Shift + Enter 换行， Enter提交</span>                  <div class="chat_btn">发送</div>                </div>              </div>              <div class="mes_footer mb_mes_footer mb_mes_footer">              <div class="plus_menu_box">                <div class="plus_menu">                  <span class="header_icon plus-list"><img src="images/header_icon.png" alt=""></span><span class="plus-list"><img src="images/uploadimg1.png" id="upclick" alt=""><input style="display:none" type="file" multiple id="send-upimg"></span><span id="cli-upFile"><img src="images/uploadfile.png" alt=""></span><span class="plus-list" id="mesChat_audio"><img src="images/iconfont-yuyin.png" alt=""></span>                </div>                <i class="icon-caret-down"></i>              </div>                  <!-- <form onsubmit="onSubmit(); return false;"> -->                      <div class="mes_input">                        <i class="plus_icon"></i>	                        <div class="mes_inout textarea chat_text_voice" id="mes_textarea" style="height:auto;" contenteditable="true"></div>	                        <textarea style="display:none" class="mes_inout" ></textarea>	                        <input type="submit" class="btn btn-primary chat_text_voice" id="chat_submit" value="发送" />                        	<div class="keydown_voice chat_voice_input">按下开始说话</div>                        	<div class="chat_input_key chat_voice_input"></div>                        <div style="clear:both"></div>                      </div>                      <div class="emoticons"></div>                   <!-- </form> -->                <div>              </div>            </div>    	    </div>        </div>       </div>    </div>   <!-- 右边人数 和 消息数 -->    <div class="mes_fixed_big">      <div class="mes_abs">        <div class="mes_fixed">          <div class="mes_ico_box" cata-box='ren'>            <div class="mes_ico mes_hide" style="background-position:-6px -50px"></div>            <div class="mes_ico mes_hide" style="background-position:-100px -100px;text-align: center;color:#fff;">              <span class="mes_hide" style='line-height: 50px;'><span class='online_ren'>0</span>人</span>            </div>          </div>          <div class="mes_ico_box" cata-box='mes'>            <div class="mes_ico mes_hide" style="background-position:-6px 0"></div>            <div class="mes_ico mes_hide" style="background-position:-100px -100px">              <div class="mes_radio mes_hide"><?= $mesNum;?></div>            </div>          </div>          <div class="kefu-icon">          	<div class="kefu" mes_id="customer" group-name ="客服">联系客服</div>          </div>          <!-- <div class="mes_move"><i class="icon-sort"></i></div> -->        </div>        <div class="online_man" >          	<div class="man_tittle">            	<span>人员列表</span>            	<span class ="close" style='cursor: pointer; margin-right: 5px;background: #000;color: #fff; position:absolute;width:20px;height:20px;border-radius: 50%;right: 0; top: 7px; '>&times;</span>          	</div>          	<div class="onlinesSroll-box">            	<div class="onlinesSroll"></div>          	</div>          <!-- 人员列表 -->          <!-- 搜索框 -->          	<div class="search_box">          		<input type="text" class= "search_in" id="search_in" placeholder= "搜索">           		<span class="search_staff"></span>           		<ul class="staff-list-group search_result">           			           		</ul>	           </div>           <ul class="list-group oms_onlineNum"> 	                      </ul>        </div>        <div class="mes_con" style="display: none;">            <div class="mes_tittle">              <span>消息列表</span>              <span class ="close" style='cursor: pointer; margin-right: 5px;background: #000;color: #fff; position:absolute;width:20px;height:20px;border-radius: 50%;right: 0; top: 7px; '>&times;</span>            </div>            <!-- 消息列表 -->            <?php if (!empty($arrMes)):?>            <?php foreach ($arrMes as $key => $value) :?>            <?php             	if ($value['message_type'] == 'message') {                  	$sender_name = $value['sender_name'];                  	$addClass = "chat_people";                  	$chat_header_img = $value['chat_header_img'];              	} else {                  	$sender_name = $value['accept_name'];                 	$addClass = "session_no";                 	$chat_header_img = '/chat/images/rens.png';              	}	             switch ($value['mesages_types']) {	                case 'text':	                  	$rest =preg_replace('/%6b/', '<br/>',$value['message_content']);	                  	$content = preg_replace('/%5C/', '\\',$rest);	                  	break;	                case 'image':	                  	$content = '【图片】';	                  	break;	                case 'file':	                  	$content = '【文件】';	                  	break;	                case 'voice':	                  	$content = '【语音】';	                  	break;	                case 'notice':	                	$sender_name = $sender_name.'【申请对话】';	                	$content = $value['message_content'];	                	$chat_header_img = "/chat/images/chatNotice.png";	                	$addClass = 'chat_notice';	                 	break;                  case 'notice_respond':                    $sender_name = $sender_name;                    $content = '已同意可以会话了';                    $chat_header_img = "/chat/images/chatNotice.png";                    $addClass = 'chat_people';                  break;                  case 'sysNotice':                    $content = $sender_name.$value['message_content'];                    $sender_name = '系统通知';                    $chat_header_img = "/chat/images/chatNotice.png";                    $addClass = 'chat_customer_notice';                    break;	                default:	                  	$content = '出错了';	                  	break;	            }              	            ?>            <div class="mes_box mes_chakan_close <?php echo $addClass;?>" chat_mes_num= "<?php echo $value['mes_num'];?>"  mes_id="<?php echo $value['sender_id'];?>" session_no='<?php echo $value['session_no'];?>' mesid="<?php echo $value['id'];?>" mestype="<?php echo $value['message_type'];?>" group-name="<?php echo $sender_name;?>" group-all = "<?php echo $value['accept_id'];?>" session_no='<?php echo $value['session_no'];?>'>	            <div class="mes_header">	            	<img src="<?php echo $chat_header_img;?>" alt="">	            </div>                <span class='mex_con'><?php echo $sender_name;?></span>                <div class="mes_content_list" style=''>                    <span class="chat_mes_content">                      <?php echo $content;?>                    </span>                </div>                <span class="mes_num"><?php echo $value['mes_num'];?></span>                <span class='mes_close' mestype='<?php echo $value['message_type'];?>' mes_id='<?php echo $value['sender_id'];?>'  session_no='<?php echo $value['session_no'];?>'>X</span>            </div>            <?php $mesNum += $value['mes_num'];?>          <?php endforeach;?>        <?php endif;?>            <!-- end -->          </div>        </div>    </div></div><!-- img放大 --><div class="send-img-box">  <span class="send-img-close com-close">&times;</span>  <!-- <canvas id="canvas"></canvas> --></div><!-- 弹出框 --><div class="cd-popup" role="alert">  <div class="cd-popup-container">    <p class="mes_alert_con">请选择聊天对象</p>    <ul class="cd-buttons">      <li><a href="#" class="clo_alert alertvalue">确定</a></li>      <!-- <li><a href="#">否</a></li> -->    </ul>    <a href="#" class="cd-popup-close img-replace alertvalue">关闭</a>  </div> <!-- cd-popup-chat-container --></div> <!-- cd-popup --><!-- Resource jQuery --><div class="img-box"><div class="img-box-title"><span>发送的图片</span><span style="color: #000;" class="com-close com-close-act">&times;</span></div>  <div class="sending-img-box"></div>  <div class="img-box-act"><span class="btn btn-success com-close-act">取消</span><span class="btn btn-info send-clipboard-img">发送</span></div></div><div class="file-main">  <ul>      <li>          <input id="token" name="token" class="ipt" value="">      </li>      <li>          <input id="key" name="key" class="ipt" value="">      </li>      <li>          <input id="file_zdl" name="file" class="ipt" type="file" />      </li>      <li>          <input id="filename" type="text" value="">      </li>  </ul></div><!-- 上传进度 --><div id="progressbar"><div class="progress-label"></div><div id="formatSpeed"></div></div><script type="text/javascript" src="js/webChataAudio.js"></script><script>  //判断是不是移动端function IsPC()  {      var userAgentInfo = navigator.userAgent;      var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod");      var flag = true;      for (var v = 0; v < Agents.length; v++) {         if (userAgentInfo.indexOf(Agents[v]) > 0) { flag = false; break; }      }      return flag;  }   </script><script type="text/javascript" src="js/web_index.js"></script></body>    <script src="js/chatViewer.min.js"></script>    <?php require_once("footer.php"); ?></html>