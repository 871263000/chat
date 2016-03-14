<?php
require_once('config.inc.php');
require_once('lib/mesages.class.php');
$uid = $_SESSION['staffid'];
$oms_id = $_SESSION['oms_id'];
$uid = 6;
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
//最近联系人session_no集合;
$ContactManSession = [];
foreach ($recentContact as $key => $value) {
	$ContactManSession[] = $value['session_no'];
}
print_r($ContactManSession);
//提示消息列表
if (isset($uid)) {
  	$arrMes = $mes->mesAlertList();
  	if (!empty($arrMes)) {
	    foreach ($arrMes as $key => $value) {
	      $mesNum  += $value['mes_num'];
	    }
  	}
}
// print_r($arrMes);
//自己的信息
$userinfo = $mes->userinfo();
$name = $userinfo['name'];//自己名字
$card_image = $userinfo['card_image'];//头像的url
//群聊列表
$arrGroup = $mes->groupChatList();
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>聊天</title>
  <!-- Include these three JS files: -->
  <script type="text/javascript" src="js/swfobject.js"></script>
  <script type="text/javascript" src="js/web_socket.js"></script>
  <script type="text/javascript" src="js/jquery.js"></script>
  <script type="text/javascript" src="js/touchSwipe.js"></script>
  <script src="js/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="css/jquery-ui.css">
  <script src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/web_message.js"></script>
  <link rel="stylesheet" href="css/webRight.css" type="text/css"/>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css"/>
  <link rel="stylesheet" href="css/app.css" type="text/css"/>
<style type="text/css">
  *{font-family: 'Microsoft YaHei', SimSun, sans-serif !important;}
  .online_man{ text-align: center;margin: 0 auto;box-shadow: 0 0 5px #999;}
  .online_man ul{margin-left: auto;margin-right: auto;padding: 0;}
  #name_box{ position:absolute;background:#fff;display:none;width: 100%;margin-top:200px; z-index: 111;}
  .mes_chakan_close{ line-height: 30px; cursor: pointer; }
  .mes_dclose{ cursor: pointer; position: absolute;  top: 3px;  font-size: 33px;  color: #fff;  right: 0;}
  .tab-content{min-height: 300px;height: 80% !important; max-height: 600px; overflow: auto;}
  #submit{height: 50px;}
</style>
  <script type="text/javascript">
    var uid;
    $(function (){
        connect();
    })
    var custom = 1;//客服id
    var mes_online = 1;
    var oms_id = "<?php echo $oms_id;?>";
    uid = "<?php echo $uid;?>"; // 发送人id
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
    nearestContact = new Object();

    var nearestContact = <?php echo !empty($ContactManSession) ? json_encode( $ContactManSession ): '没有';?>;
    console.log(nearestContact);
  </script>
</head>
<body>
<div class="chat_voice_box" voice_url="">
	<audio src="" preload></audio>
</div>
<div class="alert alert-success" role="alert">
    <button class="close"  data-dismiss="alert" type="button" >&times;</button>
    <p>恭喜您操作成功！</p>
</div>
    <div id="name_box">
        <?php
            include('fenlei2/OS.php');
        ?>
        <div style="clear:both;width:100%;margin:50px 10px 0px 30%"><button style="display: inline-block;" id="b_no" class="btn btn-sm btn-info" >取&nbsp;消</button><button style="margin-left:50px;display: inline-block;" id="b_is"  class="btn btn-sm btn-info">确&nbsp;定</button></div>
    </div>
    <div class="bagimg">
      <img src="images/2.jpg"  alt="">
    </div>
    <div class="container-box">
      <div class="details-list">
        <div class="container-title">
          <div class="container-header">
            <img  src="<?php echo $card_image;?>" width="50px" height="50px" alt="<?php echo $name;?>">
            <h3><?php echo $name;?></h3>
          </div>
        </div>
        <div class="container-tab">
          <ul>
          <li class="tab-title" ind= '0' title="最近联系人"><img src="images/mesage.png" alt="最近联系人"></li>
            <li title="选择人聊天"><img id="s_man" src="images/rens1.png" alt="选择人聊天"></li>
            <li class="tab-title" ind= '1' title="选择群聊"><img src="images/rens.png" alt="选择群聊"></li>
            <a href="new_groupChat.php" target="_blank" title="新建群聊"><li title="新建群聊"><img src="images/addMan.png" alt="新建群聊"></li></a>
            <i class="sanjiao"></i>
          </ul>
        </div>
        <!-- 最近联系人 -->
        <div class="tab-content con-tab-content">
          <ul class="list-group">
          <li class="recent-contact chat_people recent-hover" style="display:none"  group-name="客服" session_no="customer"  mes_id="1" mestype ="message"></li>
          <?php if (!empty($recentContact)): foreach ($recentContact as $key => $value) :?>
            <?php
              $herder_img = $value['card_image'];
              $contacts_name = $value['contacts_name'];
              if ($value['mestype'] !== "message") {
                $contacts_name = $value['accept_name'];
                $herder_img = '/chat/images/rens.png';
              }
              $cont_id = $value['mes_id'];
            ?>
            <?php if ($value['mestype'] !== "message") :?>
             <li class="session_no recent-hover" data-placement="right" group-name="<?php echo $value['accept_name']; ?>" delConId = <?php echo $value['id'];?>  session_no="<?php echo $value['session_no'];?>" mes_id="<?php echo $cont_id;?>" mestype ="<?php echo $value['mestype'];?>" ><span class="header-img"><img src="<?php echo $herder_img?>" alt=""></span><i><?php echo $contacts_name;?></i><span title = "从列表中删除" mestype="<?php echo $value['mestype'];?>" delConId = <?php echo $value['id'];?>  session="<?php echo $value['session_no'];?>" class="recent-close">&times;</span></li>
            <?php else: ?>
            <li class="recent-contact content-staff-info chat_people recent-hover" data-placement="right" group-name="<?php echo $contacts_name;?>" delConId = <?php echo $value['id'];?>  session_no="<?php echo $value['session_no'];?>"  mes_id="<?php echo $cont_id;?>" mestype ="<?php echo $value['mestype'];?>" ><span class="header-img"><img src="<?php echo $herder_img;?>" alt=""></span><i><?php echo $contacts_name;?></i><span title = "删除聊天记录" mestype="<?php echo $value['mestype'];?>" delConId = <?php echo $value['id'];?>  session="<?php echo $value['session_no'];?>" class="recent-close">&times;</span></li>
            <?php endif; ?>
          <?php endforeach; ?>
          <?php endif; ?>
          </ul>
        </div>
        <div class="tab-content group-content" style="margin-top: 8px;border-top: 1px solid #ccc;">
        <!-- 群列表 -->
            <div class="panel-group" id="accordion">
                <?php if (!empty($arrGroup)): foreach ($arrGroup as $key => $value): ?>
                      <div class="panel panel-default">
                          <div class="panel-heading session_no db_session_no" group-name="<?php echo $value['group_name']?>" groupId="<?php echo $value['id'];?>" group-all="<?php echo $value['all_staffid']?>" session_no='<?php echo $value['pid']?>'>
                              <h4 class="panel-title"><a groupid = "<?php echo $value['pid']?>"  data-toggle="collapse" class="group-man-show" data-parent="#accordion" href="#collapse<?php echo $key;?>"><?php echo $value['group_name']?></a></h4>
                          </div>
                          <div id="collapse<?php echo $key;?>" class="panel-collapse collapse">
                              <div class="panel-body">
                                <ul class="list-group">
                                <!-- 群聊参加人 -->
                                </ul>
                              </div>
                          </div>
                          <div class="dropdown">
                            <i id="dLabel<?php echo $key;?>" data-target="#" href="http://example.com" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                              <i class="icon-list"></i>
                              <span class="caret"></span>
                            </i>
                            <ul class="dropdown-menu" aria-labelledby="dLabel<?php echo $key;?>">
                            <?php if ($uid == $value['group_founder']):?>
                              <li groupId="<?php echo $val['pid'];?>" class="dropdown-menu-man dissolve-group">解散该群</li>
                            <?php else:?>
                              <li groupId="<?php echo $val['pid'];?>" class="dropdown-menu-man esc-group">退出该群</li>
                            <?php endif;?>
                            </ul>
                          </div>
                      </div>
                <?php endforeach;?>
                <?php endif;?>
            </div>
            <!-- end群 -->
        </div>
      </div>
	<div class="chat-container">
        <div class="mes_title">
          <h2 class="mes_title_con"><?php echo $accname;?><i title="群聊添加人" class="add-groupMan"></i></h2><span aria-hidden="true" class="mes_dclose">&times;</span>
        </div>
        <div class="mes_con_box">
    	    <div class="row clearfix">
    	           <div class="he-ov-box mes-scroll">
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
   <!-- 右边人数 和 消息数 -->
    <div class="mes_fixed_big">
      <div class="mes_abs">
        <div class="mes_fixed">
          <div class="mes_ico_box" cata-box='ren'>
            <div class="mes_ico mes_hide" style="background-position:-6px -50px"></div>
            <div class="mes_ico mes_hide" style="background-position:-100px -100px;text-align: center;color:#fff;">
              <span class="mes_hide" style='line-height: 50px;'><span class='online_ren'>0</span>人</span>
            </div>
          </div>
          <div class="mes_ico_box" cata-box='mes'>
            <div class="mes_ico mes_hide" style="background-position:-6px 0"></div>
            <div class="mes_ico mes_hide" style="background-position:-100px -100px">
              <div class="mes_radio mes_hide"><?php echo $mesNum;?></div>
            </div>
          </div>
          <div class="kefu-icon">
          	<div class="kefu" mes_id="customer" group-name ="客服">联系客服</div>
          </div>
          <!-- <div class="mes_move"><i class="icon-sort"></i></div> -->
        </div>
        <div class="online_man" >
          <div class="man_tittle">
            <span>人员列表</span>
            <span class ="close" style='cursor: pointer; margin-right: 5px;background: #000;color: #fff; position:absolute;width:20px;height:20px;border-radius: 50%;right: 0; top: 7px; '>&times;</span>
          </div>
          <div class="onlinesSroll-box">
            <div class="onlinesSroll"></div>
          </div>
          <!-- 人员列表 -->
           <ul class="list-group oms_onlineNum"> </ul>
        </div>
        <div class="mes_con" style="display: none;">
            <div class="mes_tittle">
              <span>消息列表</span>
              <span class ="close" style='cursor: pointer; margin-right: 5px;background: #000;color: #fff; position:absolute;width:20px;height:20px;border-radius: 50%;right: 0; top: 7px; '>&times;</span>
            </div>
            <!-- 消息列表 -->
            <?php if (!empty($arrMes)):?>
            <?php foreach ($arrMes as $key => $value) :?>
            <?php 
	             switch ($value['mesages_types']) {
	                case 'text':
	                  	$rest =preg_replace('/%6b/', '<br/>',$value['message_content']);
	                  	$content = preg_replace('/%5C/', '\\',$rest);
	                  	break;
	                case 'image':
	                  	$content = '【图片】';
	                  	break;
	                case 'file':
	                  	$content = '【文件】';
	                  	break;
	                case 'voice':
	                  	$content = '【语音】';
	                  	break;
	                default:
	                  	$content = '出错了';
	                  	break;
	            }
              	if ($value['message_type'] == 'message') {
                  	$sender_name = $value['sender_name'];
                  	$addClass = "chat_people";
              	} else {
                  	$sender_name = $value['accept_name'];
                 	$addClass = "session_no";
              	}
            ?>
            <div class="mes_box">
                <span class='mex_con'><?php echo $sender_name.":".$content;?></span>
                <div style='height:30px'>
                    <span class='mes_chakan_close <?php echo $addClass;?>' mes_id="<?php echo $value['sender_id'];?>" session_no='<?php echo $value['session_no'];?>' mesid="<?php echo $value['id'];?>" mestype="<?php echo $value['message_type'];?>" group-name="<?php echo $sender_name;?>" group-all = "<?php echo $value['accept_id'];?>" session_no='<?php echo $value['session_no'];?>' >
                      查看
                    </span>
                </div>
                <span class="mes_num"><?php echo $value['mes_num'];?></span>
                <span class='mes_close' mestype='<?php echo $value['message_type'];?>' mes_id='<?php echo $value['sender_id'];?>'  session_no='<?php echo $value['session_no'];?>'>X</span>
            </div>
            <?php $mesNum += $value['mes_num'];?>
          <?php endforeach;?>
        <?php endif;?>
            <!-- end -->
          </div>
        </div>
    </div>
</div>
<!-- img放大 -->
<div class="send-img-box">
  <span class="send-img-close com-close">&times;</span>
  <!-- <canvas id="canvas"></canvas> -->
</div>
<!-- 弹出框 -->
<div class="cd-popup" role="alert">
  <div class="cd-popup-container">
    <p class="mes_alert_con">请选择聊天对象</p>
    <ul class="cd-buttons">
      <li><a href="#" class="clo_alert alertvalue">确定</a></li>
      <!-- <li><a href="#">否</a></li> -->
    </ul>
    <a href="#" class="cd-popup-close img-replace alertvalue">关闭</a>
  </div> <!-- cd-popup-chat-container -->
</div> <!-- cd-popup -->
<!-- Resource jQuery -->
<div class="img-box">
<div class="img-box-title"><span>发送的图片</span><span style="color: #000;" class="com-close com-close-act">&times;</span></div>
  <div class="sending-img-box"></div>
  <div class="img-box-act"><span class="btn btn-success com-close-act">取消</span><span class="btn btn-info send-clipboard-img">发送</span></div>
</div>
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
<!-- 上传进度 -->
<div id="progressbar"><div class="progress-label"></div><div id="formatSpeed"></div></div>
<script type="text/javascript" src="js/webChataAudio.js"></script>
<script type="text/javascript">
    // $('#mesChat_audio').click(function () {
    //     // console.log(2)
    // })
//客服
//判断当前会话在最近联系人哪里有没有 
var  addContact =  function (session_id) {
	for (var i in nearestContact) {
		if ( nearestContact[i] == session_id) {
			return true;
		};
	}
	return false;
}
$('.kefu-icon').hover(function (e){
	$('.kefu-icon .kefu').animate({
		top: 0,
	})
},function (e){
	$('.kefu-icon .kefu').animate({
		top: 40,
	})
});
// $('.kefu').on('click', function (){
// 	session_no = $(this).attr('mes_id');
// 	$('.he-ov-box').unbind('scroll');
// 	$(".he_ov").html('');
//   //会话id的改变
//   $('.mes_title_con').html($(this).attr('group-name'));
// 	// ws.send('{"type": "customer"}');
// })
//判断是不是移动端
function IsPC()  
{  
   	var userAgentInfo = navigator.userAgent;  
   	var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod");  
   	var flag = true;  
   	for (var v = 0; v < Agents.length; v++) {  
       if (userAgentInfo.indexOf(Agents[v]) > 0) { flag = false; break; }  
   	}  
   	return flag;  
} 
//判断是不是移动端
if (IsPC()) {
	  //在线人员滚动条滚动
	var _move = false;
	var ismove = false; //移动标记
	var _x, _y, __y; //鼠标离控件左上角的相对位置
	//在线人员滚动
	var ScrollDistance = 47;//每次滑动距离；
	var onlineTop = parseInt($(".online_man .list-group").css('top'));
	var maxScroll = docuHeight - onlineTop; //滚动条的最大高度；
	var onlineTopCh = onlineTop;
	var onlinesSroll = onlineTopCh;//滚动条top;
	var onlineScrollHeight = 0;//滚动条可以滚动的距离；
	var onlineHeight = 0; // 在线人数的高度; 
	var hideOnlineHeightPro = 0; // 在线人数隐藏的高度； 
	var onlineSeeHeight = docuHeight - onlineTop;//在线人数可视化的高度；
	var docuHeight = $(window).height();
	var proScroll =  0; //在线人数的高度和文档高度的比例；
	var mousewheelevt=(/Firefox/i.test(navigator.userAgent))?"DOMMouseScroll": "mousewheel"//FF doesn't recognize mousewheel as of FF3.x
	var mousedir=(/Firefox/i.test(navigator.userAgent))?"detail": "deltaY"//FF doesn't recognize mousewheel as of FF3.x
	  //拖动
	$(document).on('mouseover', ".online_man", function (){
	    if (proScroll < 1) {
	      $(".onlinesSroll-box").css('display', 'block');
	    };
	})
	$(document).on('mouseout', ".online_man", function (){
	    $(".onlinesSroll-box").css('display', 'none');
	})
	jQuery(document).ready(function ($) {
    $(".onlinesSroll-box").mousedown(function (e) {
        _move = true;
        __y = e.pageY;
        _y = e.pageY - parseInt($(".onlinesSroll-box").css("top"));
       });
    $(".onlinesSroll-box").mousemove(function (e) {
        if (_move) {
            var y = e.pageY - _y;
            var my = e.pageY - __y;
            // var wx = $(window).width() - $('#spig').width();
            var dy = $(document).height() - $('.onlinesSroll-box').height();
            if( y >= onlineTopCh && y < onlineTopCh + onlineScrollHeight ) {
                onlineTop -= hideOnlineHeightPro * ((e.pageY -__y )/onlineScrollHeight);
                onlinesSroll +=( e.pageY -__y);
                $('.online_man .list-group').css('top',onlineTop );
                $(".onlinesSroll-box").css({
                  top: onlinesSroll,
                }); //控件新位置
              	ismove = true;
              	__y = e.pageY;
            }
          }
      }).mouseup(function () {
          	_move = false;
      });
  });
  $(document).on(mousewheelevt,".online_man",function (event){
  	e = event || window.event;
  	e.preventDefault();
  	var wheeldir = e.originalEvent.deltaY;
  	if (mousedir == "detail") {
  	    var wheeldir = e.originalEvent.detail;

  	};
    if(wheeldir > 0 && onlineTop+onlineHeight > docuHeight ){
        onlinesSroll += onlineScrollHeight * ( ScrollDistance/hideOnlineHeightPro );
        if ( 0 < onlineTop+onlineHeight - docuHeight &&  onlineTop+onlineHeight - docuHeight < ScrollDistance ) {
            onlinesSroll += onlineScrollHeight * ( (onlineTop+onlineHeight - docuHeight)/hideOnlineHeightPro ) ;
            onlineTop -= onlineTop+onlineHeight - docuHeight
        } else {
            onlineTop -=ScrollDistance;
        }

    } else if (wheeldir < 0 &&  onlineTop < onlineTopCh ) {
        if (onlineTopCh - onlineTop < ScrollDistance ) {
          onlinesSroll = onlineTopCh;
          onlineTop = onlineTopCh;
        } else {
          onlinesSroll -= onlineScrollHeight * ( ScrollDistance/hideOnlineHeightPro );
          onlineTop +=ScrollDistance;
        }
    }
    $('.online_man .list-group').css('top',onlineTop );
    $('.onlinesSroll-box').css('top',onlinesSroll );
  });
};
var onlineTopT = 0;
var onlinesSrollT = 0;
$(".oms_onlineNum").swipe({
	swipeStatus:function(event, phase, direction, distance, duration,fingerCount){
	 	if (phase == 'start') {
	 		onlineTopT = onlineTop;
	 		onlinesSrollT = onlinesSroll;
	 	}
	 	if (direction == 'down' && onlineTopT < onlineTopCh) {
	 		onlineTopT = onlineTop +  distance;
	 	 	onlinesSrollT = onlinesSroll - onlineScrollHeight * ( distance/hideOnlineHeightPro );
	 	} else if (direction == 'up' && onlineTopT+onlineHeight > docuHeight ){
	 		onlineTopT = onlineTop -  distance;
			onlinesSrollT = onlinesSroll + onlineScrollHeight * ( distance/hideOnlineHeightPro );
	 	}
	 	$('.online_man .list-group').css('top',onlineTopT );
      	$('.onlinesSroll-box').css('top',onlinesSrollT );
      	if (phase == 'cancel' || phase == 'end')  {
      		onlineTop = onlineTopT;
      		onlinesSroll = onlinesSrollT;
      	};
	 },
	 click : function (){

	 }
})
//群聊人显示
var gpManSh = function (groupid){
  	ws.send('{"type": "groupManShow", "session_no":"'+groupid+'"}');
}

//群聊名字点击
$('.group-man-show').click(function (e){
  	var obj = $(this);
  	var showMansessionNo = obj.attr('groupid');
  	if (!$(e.target).is('.groupSign')) {
    	gpManSh(showMansessionNo);
  	};
  	$('.groupAct').removeClass('groupAct');
  	obj.addClass('groupAct');
  	obj.addClass('groupSign');

})

//你确定要离开此页面
// $(window).bind('beforeunload',function(){
//   return '您确定离开此页面吗？';
// });
//最近联系人删除
$(document).on('mouseover', '.list-group .recent-hover', function (){
  	$('.recent-action').removeClass('recent-action');
  	$(this).find('.recent-close').addClass('recent-action');
})
$(document).on('mouseout', '.list-group .recent-hover', function (){
  	$('.recent-action').removeClass('recent-action');
})
var mes_bottom = parseInt($('.mes_fixed').css('bottom'));
$('.mes_fixed').swipe( {
	swipeStatus:function(event, phase, direction, distance, duration, fingerCount) {
	  	if (direction == 'down' && mes_bottom > 0) {
	    	// console.log(distance)
	      $('.mes_fixed').css('bottom', mes_bottom - distance);
	      	// console.log($('.mes_fixed').css('bottom'))
	  	} else if (direction == 'up') {
	    	// console.log(distance)
	      	$('.mes_fixed').css('bottom', distance + mes_bottom);
	  	};
	  	if (phase == 'cancel' || phase == 'end') {
	   		mes_bottom = parseInt($('.mes_fixed').css('bottom'));
	  	};
	},
});
//indexeddb 初始化
// H5AppDB.indexedDB.open();
//右边隐藏
//图片旋转
// window.onload = function(){
//   var param = {
//     right: document.getElementById("rotateRight"),
//     left: document.getElementById("rotateLeft"),
//     cv: document.getElementById("canvas"),
//     rot: 0
//   };
//   var rotate = function(canvas,img,rot){
//     //获取图片的高宽
//     var w = img.width;
//     var h = img.height;
//     //角度转为弧度
//     if(!rot){
//       rot = 0;  
//     }
    
//   }
//   var fun = {
//     right: function(obj){
//       param.rot += 90;
//       rotate(param.cv, obj, param.rot);
//       if(param.rot === 270){
//         param.rot = -90;  
//       } 
//     },
//     left: function(obj){
//       param.rot -= 90;
//       if(param.rot === -90){
//         param.rot = 270;  
//       }
//       rotate(param.cv, obj, param.rot);     
//     }
//   };
//   param.right.onclick = function(){
//     var obj = document.getElementById('rotimg');
//     fun.right(obj);
//     return false;
//   };
//   param.left.onclick = function(){
//     var obj = document.getElementById('rotimg');
//     fun.left(obj);
//     return false;
//   };
// };
// $('.rotateLeft').click(function (){
//   $('.send-img-box img').
// })
//上传图片点击
//触发文件点击
var trig = function (obj){
    obj.trigger('click');
}
$('#upclick').click( function(){
  	trig($('#send-upimg'));
});
$('#cli-upFile img').click(function(){
  	trig($('#file'));
  	return;
});
  // 选择图片的改变
$('#send-upimg').on('change', function(){
  	//检验是否为图像文件
  	var obj = $(this) ;
  	// var file = $(this)[0].files[0];                                                                                                                          
  	$.each( $(this)[0].files, function (i, file){
	    if(!/image\/\w+/.test(file.type)){  
	        alert("只能发送图片");  
	        return false;  
	    }  
    	var reader = new FileReader();  
    	//将文件以Data URL形式读入页面  
    	reader.readAsDataURL(file);  
    	reader.onload=function(e){
            var img = new Image();
            img.src = this.result;
            img.className = "send-img";
            $('.sending-img-box').html('');
            $('.sending-img-box').append( img );
            $('.send-clipboard-img').trigger('click');
            $('.plus_menu_box').hide();
        	//显示文件  
    	}
  	});
})
//粘贴发送的图片取消
$('.com-close-act').click(function(){
  	$('.img-box').hide();
})
//群聊参加人滑过事件
$('.group-people').live('mouseover', function(){
  	$(this).find('.delgroupman').show();
})
$('.group-people').live('mouseout', function(){
  	$(this).find('.delgroupman').hide();
})

//群聊删除人
$('.delgroupman').live('click', function(){
  	var obj = $(this);
  	var groupid = obj.attr("groupid");
  	var id = obj.attr("id");
  	$('.alertvalue').attr('groupid', groupid);
  	$('.alertvalue').attr('atype', 'del');
  	$('.alertvalue').attr('id', id);
  	$(this).parent().addClass('mandel');
  	$('.mes_alert_con').html('你确定要删除他吗？');
  	$('.cd-popup').addClass('is-visible'); return false;

});
//退出群
$('.esc-group').live('click', function (){
  	alert('还没有开放！');
  	return;
})
//群聊解散
$('.dissolve-group').live('click', function (){
  	var groupId = $(this).attr('groupId');
  	ws.send('{"type": "dissolve_group", "groupId": "'+groupId+'"}');
})
$('.alertvalue').on('click', function (){
  	var obj = $(this);
  	if (obj.attr('atype') == "del") {
    	$('.mandel').remove();
    	var groupid = obj.attr("groupid");
    	var id = obj.attr("id");
    	ws.send('{"type":"delgroupman", "groupid": "'+groupid+'", "id":"'+id+'"}'); 
  	};
})
//对话框的高度
var mesHeight = 0;
//查看更多
$('.onload').live("click", function(){
    $('.he-ov-box').trigger("scroll");
})
//联系人tab
$('.tab-title').click(function(){
  	var index = $(this).index();
  	var ind = $(this).attr('ind');
  	$('.tab-content').hide();
  	$('.tab-content').eq(ind).show()
  	//小三角移动
  	$('.sanjiao').animate({left:((index+1)*2-1)*12.5+"%"}, 'linear');
})
//对话框关闭
$('.mes_dclose').on('click', function (){
  	$('.chat-container').hide();
  	$('.details-list').show();
})
//滚动条滚动事件
var mesScroll = function (){
  	if ($(".he-ov-box").scrollTop() <= 10 && $(".he-ov-box").scrollTop() >= 0) {
    	var mes_loadnum = $('#mes_load').html();
    	$('.loader').show()
    	mesHeight = $('.he_ov').height()
    	ws.send('{"type":"mes_load","mes_loadnum":"'+mes_loadnum+'", "message_type":"'+mes_type+'", "to_uid":"'+to_uid+'","session_no": "'+session_no+'"}');
  	};
}
//删除最近联系人
var arr = [1,2,3];
var delContactFun = function( id, mestype, session_id){
	delete arr[1];
	console.log(arr);
	console.log(nearestContact);
   	$('.recent-action').parent('li').remove()
   	ws.send('{"type": "delContact" ,"mestype":"'+mestype+'", "id": "'+id+'"}');
}

// 选择人聊天
$('.chat_people').live('click', function( e ){
  	to_uid = $(this).attr('mes_id');
  	to_uid_header_img = $(this).find('img').attr('src');
  	//会话id的改变
  	session_no = to_uid < uid ? to_uid+"-"+uid : uid+"-"+to_uid;
  	mes_type = "message";
  	if (!$(e.target).is('.recent-action')) {
    	if ($(window).width() < 700) {
      		$('.chat-container').show();
      		$('.details-list').hide();  
    	};
	  	//end
	    // groupId = $(this).attr('groupId');
	    if (!$(e.target).is('.mes_chakan_close')) {
	      	if ($(".mes_chakan_close[session_no='"+session_no+"']").length > 0) {
	         	var con_mes_num =  parseInt($(".mes_chakan_close[session_no='"+session_no+"']").parent().next('.mes_num').html());
	         	mes_chakan_close('message', session_no, con_mes_num);
	      	};
	    };
	    ws.send('{"type":"mes_chat", "mes_para":"'+to_uid+'"}');
	    $('#mes_load').html(10);
	    $('.mes_title_con').html($(this).attr('group-name'));
	    //消息向上滚动
	    $('.he-ov-box').unbind('scroll');
	    $('.he-ov-box').bind("scroll", function (){
	      mesScroll();
	    })
  	} else if ($(e.target).is('.recent-action')) {
    	var delconid = ($(this).attr('delconid'));
    	var session_id = ($(this).attr('session_no'));
    	delContactFun( delconid, mes_type, session_id);
    	// H5AppDB.indexedDB.deleteTodo($(this).attr('db_id'))
  };
})

//选择群列表显示对话内容
$('.session_no').live('click', function ( event ){
  	session_no = $(this).attr('session_no')//会话id
  	mes_type = "groupMessage";//消息类型
  	if (!$(event.target).is('.recent-action')) {
    	// if (session_no == $(this).attr('session_no')) { return ; };
    	//在手机上交替显示
    if ($(window).width() < 700) {
      	$('.chat-container').show();
    	$('.details-list').hide();  
    };
    //end
    var valName = $(this).attr('group-name');//会话名字
    // groupId = $(this).attr('groupId');
    //消息向上滚动
    $('.he-ov-box').unbind('scroll');
    $('.he-ov-box').bind("scroll", function (){
      mesScroll();
    })
    $('.mes_title_con').html(valName);
    $('.mes_title_con').append('<i title="群聊添加人" class="add-groupMan"></i>');
    $('.add-groupMan').show();
    if (!$(event.target).is('.mes_chakan_close')) {
      	if ($(".mes_chakan_close[session_no='"+session_no+"']").length > 0) {
         	var con_mes_num =  parseInt($(".mes_chakan_close[session_no='"+session_no+"']").parent().next('.mes_num').html());
         	mes_chakan_close('groupMessage', session_no, con_mes_num);
      	};
    };
    ws.send('{"type":"mes_groupChat", "session_no":"'+session_no+'" }');
    $('#mes_load').html(10);
  } else if ($(event.target).is('.recent-action')) {
    var delconid = ($(this).attr('delconid'));
    var session_id = ($(this).attr('session_no'));
    delContactFun( delconid, mes_type, session_id );
  };
})
//表情的添加
function addempath() {
  	var emPath = "<?php echo DOCUMENT_URL?>/chat/emoticons/images/";//表情路径
  	var total = 134;//表情的个数
  	var newTotal = 14;//新增表情的个数
  	for(var i=0; i < newTotal ; i++) {
    	$('.emoticons').append('<div class="em_gif"><img width="24px" class="cli_em" src="'+emPath+'f'+i+'.gif"></div>');
  	}
  	for(var i=0; i < total ; i++) {
    	$('.emoticons').append('<div class="em_gif"><img class="cli_em" src="'+emPath+i+'.gif"></div>');
  	}
}
//加号的单击
$(".plus_icon").click( function (){
  	$(".plus_menu_box").toggle();
});
//输入框聚焦
$('#mes_textarea').focus( function(){
  	$(".emoticons").hide();
  	var inputHeight = $('.mes_footer').height();// 输入框的高度
  	$(".he-ov-box").css("bottom", inputHeight);
  	$(".he-ov-box").scrollTop($(".he-ov-box")[0].scrollHeight);
})
//表情的点击事件
$(".emoticons .cli_em").live('click',function (){
  	$(this).clone().append().appendTo('.textarea');
  	$('textarea').val($('.textarea').html())
})
//表情的显示
$('.header_icon').click(function () {
  	addempath();
  	$(".emoticons").toggle();
  	$(".plus_menu_box").hide();
  	var inputHeight = $('.mes_footer').height();// 输入框的高度
  	$(".he-ov-box").css("bottom", inputHeight);
  	$(".he-ov-box").scrollTop($(".he-ov-box")[0].scrollHeight);
})

// 右边消息的个数
var mesnum = parseInt($('.mes_radio').html());//消息的个数
//消息的高度
$('.loader').hide();

//消息向上滚动
// $('.he-ov-box').bind("scroll", function (){
//   if ($(".he-ov-box").scrollTop() <= 10 && $(".he-ov-box").scrollTop() >= 0) {
//     var mes_loadnum = $('#mes_load').html();
//     $('.loader').show()
//     mesHeight = $('.he-ov-box').height()
//     ws.send('{"type":"mes_load","mes_loadnum":"'+mes_loadnum+'", "message_type":"'+mes_type+'", "to_uid":"'+to_uid+'","session_no": "'+session_no+'"}');
//   };

// })
//消息定位的底部
$(".he-ov-box").scrollTop($(".he_ov")[0].scrollHeight);
//close popup
$('.cd-popup').on('click', function(event){
	if( $(event.target).is('.cd-popup-close') || $(event.target).is('.cd-popup') || $(event.target).is('.clo_alert') ) {
	    event.preventDefault();
	    $(this).removeClass('is-visible');
	}
});
//close popup when clicking the esc keyboard button
$(document).keyup(function(event){
    if(event.which=='27'){
      	$('.cd-popup').removeClass('is-visible');
    }
});
//shift+enter 换行
//enter 提交
$(".mes_footer").keydown(function(e){
	var e = e || event,
	keycode = e.which || e.keyCode;
	if(e.shiftKey && (e.keyCode==13)){
	   	$('#mes_textarea').append('<br/>')
	} else  if (keycode==13) {
	   	$("#submit").trigger("click");
	}
});
 //消息提交
var mesParam = {
  mes_obj: function (){
    if (to_uid == 0 && session_no == 0) {
        $('.mes_alert_con').html('请选择聊天对象！')
        $('.cd-popup').addClass('is-visible'); return false;
    } else { return true; }
  },
  mes_empty: function (){
    if ($('#mes_textarea').html() =='') {
        $('.mes_alert_con').html('消息不能为空！')
        $('.cd-popup').addClass('is-visible'); return false;
    } else { return true; }
  },
};
//消息提交
var Qiniu_UploadUrl = "http://up.qiniu.com";
var progressbar = $("#progressbar"),
progressLabel = $(".progress-label");
progressbar.progressbar({
    value: false,
    change: function() {
      	$('#progressbar').show();
        // progressLabel.text(progressbar.progressbar("value") + "%");
    },
    complete: function() {
      	$('#progressbar').hide();
      	// progressLabel.hide();
        // progressLabel.text("Complete!");
    }
});
 //发送文件
$('#file').on('change', function (){
  	var nowTime = new Date().getTime();
  	if (!mesParam.mes_obj()) {
    	$(this).val('');
    	return false;
  	};
  	$key = $file.value.split(/(\\|\/)/g).pop();
  	document.getElementById('filename').value = $key;
  	$key ='file/'+uid+'/'+to_uid+'/'+nowTime+'/'+$key;
  	document.getElementById('key').value = $key;
  	//普通上传
  	var Qiniu_upload = function(f, token, key) {
	    var xhr = new XMLHttpRequest();
	    xhr.open('POST', Qiniu_UploadUrl, true);
	    var formData, startDate;
	    formData = new FormData();
	    if (key !== null && key !== undefined) formData.append('key', key);
	    formData.append('token', token);
	    formData.append('file', f);
	    var taking;
	    xhr.upload.addEventListener("progress", function(evt) {
	      if (evt.lengthComputable) {
	        var nowDate = new Date().getTime();
	        taking = nowDate - startDate;
	        var x = (evt.loaded) / 1024;
	        var y = taking / 1000;
	        // var uploadSpeed = (x / y);
	        // var formatSpeed;
	        // if (uploadSpeed > 1024) {
	        //     formatSpeed = (uploadSpeed / 1024).toFixed(2) + "Mb\/s";
	        //     // $('#formatSpeed').html(formatSpeed);
	        // } else {
	        //     formatSpeed = uploadSpeed.toFixed(2) + "Kb\/s";
	        //     // $('#formatSpeed').html(formatSpeed);
	        // }
	        var percentComplete = Math.round(evt.loaded * 100 / evt.total);
	        progressbar.progressbar("value", percentComplete);
	        // console && console.log(percentComplete, ",", formatSpeed);
	      }
	    }, false);
	    xhr.onreadystatechange = function(response) {
		    if (xhr.readyState == 4 && xhr.status == 200 && xhr.responseText != "") {
		        var blkRet = JSON.parse(xhr.responseText);
		        onSubmit(to_uid, uid, groupId, mes_type, 'file',session_no);
		        // console && console.log(blkRet);
		        // $("#dialog").html(xhr.responseText).dialog();
		    } else if (xhr.status != 200 && xhr.responseText) {
		        var blkRet = JSON.parse(xhr.responseText);
		        // onSubmit(to_uid, uid, groupId, mes_type, 'file',session_no);
		        alert(blkRet);
		        // console.log(blkRet);
		    }
	    };
	    startDate = new Date().getTime();
	    $("#progressbar").show();
	    xhr.send(formData);
  	};
  	var token = $("#token").val();
  	if ($("#file")[0].files.length > 0 && token != "") {
     	Qiniu_upload($("#file")[0].files[0], token, $key);
  	} else {
      	console && console.log("form input error");
  	}
});
document.getElementById('submit').onclick = function (){
  //接收人名字
  	if (!mesParam.mes_obj() || !mesParam.mes_empty()) {
     	return false;
  	};
  	if (mes_type == 'groupMessage') {
    	to_uid_header_img = '/chat/images/rens.png'
  	} else {
    	// to_uid_header_img = 
  	}
    var data = {
	    "type": "addContact",
	   	"mestype": mes_type,
	   	"session_no" : session_no,
	   	"sender_name": name,
	   	"accept_name": $('.mes_title_con').text(),
	   	"mes_id": to_uid,
	  	"to_uid_header_img": to_uid_header_img,
	   	"timeStamp": new Date().getTime(),
  	};
  	// if ($('.recent-hover[session_no="'+session_no+'"]').length == 0) {
  	if ( addContact( session_no ) == false ) {
    	if (mes_type == "message") {
      		$('.con-tab-content .list-group').prepend('<li class="recent-contact staff-info chat_people recent-hover" data-placement="right" group-name="'+data.accept_name+'" session_no="'+data.session_no+'"  mes_id="'+data.mes_id+'" mestype ="'+data.mestype+'" ><span class="header-img"><img src="'+data.to_uid_header_img+'" alt=""></span><i>'+data.accept_name+'</i><span title = "删除聊天记录" mestype="'+data.mestype+'"  session="'+data.session_no+'" class="recent-close">&times;</span></div></li>')
    	} else {
      		$('.con-tab-content .list-group').prepend('<li class="session_no staff-info recent-hover" data-placement="right" group-name="'+data.accept_name+'" session_no="'+data.session_no+'" mes_id="'+data.mes_id+'" mestype ="'+data.mestype+'" ><div><span class="header-img"><img src="'+data.to_uid_header_img+'" alt=""></span><i>'+data.accept_name+'</i><span title = "删除聊天记录" mestype="'+data.mestype+'"  session="'+data.session_no+'" class="recent-close">&times;</span></div></li>')
    	}
      ws.send(JSON.stringify(data));
  	} else {
    	if ($('.recent-hover[session_no="'+session_no+'"]').parent('li').index() !=0 ) {
      		$('.con-tab-content .list-group').prepend($('span[session_no="'+session_no+'"]').parent('li'));
      		ws.send('{"type": "updContact", "session_no": "'+session_no+'"}')
    	};
  	}
  	// H5AppDB.indexedDB.selectData(data)
  	onSubmit(to_uid, uid, groupId, mes_type, 'text',session_no);
};
//消息提交
document.getElementsByClassName('send-clipboard-img')[0].onclick = function (){
  	//接收人名字
  	if (!mesParam.mes_obj()) {
      	return false;
  	};
  	onSubmit(to_uid, uid, groupId, mes_type, 'image',session_no);
};;
//textare 自适应高度
(function($){
  	$.fn.autoTextarea = function(options) {
    	var defaults={
        	maxHeight:null,
        	minHeight:$(this).height()
    	};
    	var opts = $.extend({},defaults,options);
    	$(this).each(function() {
      		$(this).bind("keyup",function(){
	        	var height,style=this.style;
	        	var inputHeight = $('.mes_footer').height();
	        	$(".mes_inout").val($(this).html());
	        	this.style.height =  opts.minHeight + 'px';
	        	if (this.scrollHeight > opts.minHeight) {
	          		if (opts.maxHeight && this.scrollHeight > opts.maxHeight) {
	              		height = opts.maxHeight;
	              		style.overflowY = 'scroll';
	          		} else {
	              		height = this.scrollHeight;
	              		style.overflowY = 'hidden';
	          		}
	          		style.height = height  + 'px';
	          		$(".he-ov-box").scrollTop($(".he-ov-box")[0].scrollHeight+45)
	          		$(".he-ov-box").css("bottom", inputHeight);
	        	}
      		});
    	});
  	};
})(jQuery);
$("#mes_textarea").autoTextarea({
   	maxHeight:260,
   	minHeight:50
});
//单聊选择人
$('#s_man').click(function(){
  	$('#b_is').attr('val', 'selman');
  	$('#name_box').show();
})
  //增加群聊人数选择人
$('.add-groupMan').live('click', function(){
  	$('#b_is').attr('val', 'addGroupMan');
  	$('#name_box').show();
})
//右边图标点击事件
$('.mes_ico_box').swipe( {
  	click:function(event, phase, direction, distance, duration,fingerCount) {
    	var mes_abs = $('.mes_abs').css('right');
    	var cata_box = $(this).attr('cata-box')
    	if (cata_box == 'ren') {
      		$('.online_man').show();
      		$('.mes_con').hide();
	      	//0px 消息隐藏
	      	if (mes_abs == '0px' || mes_abs == '200px') {
	          	$('.mes_abs').animate({
	                  right: 140
	            },
	            {
	                queue: true,
	                duration: 500
	            })
	      	} else {
		        $('.mes_abs').animate({
		          	right: 0
		        },
		        {
		          	queue: true,
		          	duration: 500
		        })
	      	}
    	} else {
      		$('.online_man').hide();
      		$('.mes_con').show();
      		if (mes_abs == '0px' || mes_abs == '140px') {
          		$('.mes_abs').animate({
            		right: 200
          		},
          		{
            		queue: true,
            		duration: 500
          		})
      		} else {
        		$('.mes_abs').animate({
          		right: 0
        		},
        		{
         			 queue: true,
          			duration: 500
        		})
      		}
    	}
  	}
})
// document点击事件
$(document).click(function (event){
  	if( !$(event.target).parents('.mes_fixed_big').is('.mes_fixed_big') && !$(event.target).is('.mes_close')) {
    	// event.preventDefault();
    	$('.mes_abs').animate({
      		right: 0
    	},
	    {
	      queue: true,
	      duration: 500
	    })
  	}
  	if (!$(event.target).parents('#name_box').is('#name_box')) {
    	if (!$(event.target).is('#s_man') && !$(event.target).is('.add-groupMan')) {
        	$('#name_box').hide();
    	};
  	};
})
//右边图标
$('.mes_ico_box').hover(function (){
  	$(this).css('background-color', '#9dd2e7')
}, function (){
  	$(this).css('background-color', '#444851')
})
//消息滑过
$(document).on('mouseenter' , '.mes_box',function(){
    $(this).css('background-color', '#9dd2e7')
    $(this).find('.mes_close').show()
  });
$(document).on('mouseleave', '.mes_box', function (){
    $(this).css('background-color', '#fff')
    $(this).find('.mes_close').hide()
});
//人员换过
//人员信息
var staffInfo = {
  	tel:null,
  	tel_branch:null,
  	mobile_phone:null,
};
//获取人员信息
var getStaffInfo = function (data){
  	staffInfo.tel = data.tel;
  	staffInfo.tel_branch = data.tel_branch;
  	staffInfo.mobile_phone = data.mobile_phone;
}
//请求人员信息
var ajaxGetStaffInfo = function (staffid, direction, css){
  	var arrDirectionCg =new Object();
  	arrDirectionCg = { 'left': 'right','right':'left', 'up': 'bottom', 'down': 'top' };
  	var directionChang = arrDirectionCg[direction];
  	var margin ="margin-"+directionChang;
  	$.ajax({
    	url:"getStaffTels.php",
    	type:"post",
    	data:"staffid="+staffid,
    	success: function ( data ){
    	  	var data = $.parseJSON(data);
      		getStaffInfo(data);
      		// var index = parseInt($(".infoCurrent").index());
      		$(".infoCurrent").append('<div class= "staff-info-box"><div class="arrow"></div><ul><li>座机：'+data.tel+'</li><li>分机：'+data.tel_branch+'</li><li>手机：'+data.mobile_phone+'</li></ul></div>');
      		$('.staff-info-box').css(css);
      		$('.staff-info-box').css(directionChang, "100%");
      		$('.staff-info-box').css(margin, "6");
      		$('.staff-info-box .arrow').css('border-'+direction,"8px solid #fff");
      		$('.staff-info-box .arrow').css(direction,"100%");

      		// $('.staff-info-box').css();
    	}
  	})
}
//消息滑过
$(document).on('mouseenter' , '.staff-info',function(){
  	var obj = $(this);
  	var staffid = obj.attr('mes_id');
  	obj.addClass('infoCurrent');
  	var direction = obj.attr('data-placement');
  	var height = obj.height();
  	var offtop = height/2-85/2;
  	ajaxGetStaffInfo(staffid, direction, { top: "-50%"});
});
$(document).on('mouseleave', '.staff-info', function (){
  	var obj = $(this);
  	obj.removeClass('infoCurrent');
  	$('.staff-info-box').remove();
});
//消息滑过
$(document).on('mouseenter' , '.content-staff-info',function(){
  	var obj = $(this);
  	var staffid = obj.attr('mes_id');
  	var _index = obj.index();
  	var height = obj.outerHeight();
  	var offtop = ( _index - 1 ) * height + 96 - $('.con-tab-content').scrollTop();
  	$('.details-list').addClass('infoCurrent');
  	var direction = obj.attr('data-placement');
  	ajaxGetStaffInfo(staffid, direction, {top: offtop});
});
$(document).on('mouseleave', '.content-staff-info', function (){
  	$('.details-list').removeClass('infoCurrent');
  	$('.staff-info-box').remove();
});
//打个消息的关闭
var mes_chakan_close = function (mestype, session_no, mes_num){
  	ws.send('{"type":"mes_close", "session_no":"'+session_no+'", "mestype":"'+mestype+'"}');
  	// console.log(mesnum);
  	mesnum = parseInt(mesnum) - parseInt(mes_num);
  	$('.mes_radio').html(mesnum);
  	$('.mes_chakan_close[session_no="'+session_no+'"]').parents('.mes_box').remove();
}
//单个消息关闭
$('.mes_chakan_close').live('click', function (){
  	var mestype = $(this).attr('mestype');
  	var mes_num = $(this).parent().next('.mes_num').html();
  	session_no = $(this).attr('session_no');
  	mes_chakan_close(mestype, session_no, mes_num);
})
$('.mes_close').live('click', function (){
  	var mestype = $(this).attr('mestype');
  	var mes_num = $(this).prev('.mes_num').html();
  	session_no = $(this).attr('session_no');
  	mes_chakan_close(mestype, session_no, mes_num);
})
//消息关闭
$('.close').click(function (){
    $('.mes_abs').animate({
    	right: 0
  	},
  	{
    	queue: true,
    	duration: 0
  	})
})
//粘贴图片
$(function(){
  	var imgReader = function( item ){
    	var blob = item.getAsFile(),
      	reader = new FileReader();
    	reader.onload = function( e ){
	      	var img = new Image();
	      	img.src = e.target.result;
	      	img.className = "send-img";
	      	$('.sending-img-box').html('');
	      	$('.sending-img-box').append( img );
	      	$('.img-box').show();
    	};
    	reader.readAsDataURL( blob );
  	};
  	document.getElementById( 'mes_textarea' ).addEventListener( 'paste', function( e ){
  		e.preventDefault();
    	var clipboardData = e.clipboardData,
      	i = 0,
      	items, item, types;
    	if( clipboardData ){
	      	items = clipboardData.items;
	      	if( !items ){
	        	return;
	      	}
	      	item = items[0];
	      	types = clipboardData.types || [];
	      	for( ; i < types.length; i++ ){
	        	if( types[i] === 'Files' ){
	          		item = items[i];
	          		break;
	        	}
	      	}
	      	if ( item && item.kind === 'string') {
	      		$('#mes_textarea').append( clipboardData.getData('text/plain') );
	      	};
	      	if( item && item.kind === 'file' && item.type.match(/^image\//i) ){
	        	imgReader( item );
	      	}
    	}
  	})
});
//聊天对话框img放大
$('.he_ov .send-img').live( 'click', function (){
  	$('.send-img-box img').remove();
  	$('.send-img-box').show();
  	$('.send-img-box').append($(this).clone().attr('id', 'rotimg'));
  	var imgHeight = $('.send-img-box img').height();
  	var imgWidth = $('.send-img-box img').width();
  	$('.send-img-box img').css('margin-top', -imgHeight/2);
  	$('.send-img-box img').css('margin-left', -imgWidth/2);
});
$('.send-img-close, .send-img-box').click( function (event){
  	if ($(event.target).is('i')) {
    	return false;
  	};
    $('.send-img-box').hide();
})
//得到人员头像
var getStaffHead = function (headImg){
  	to_uid_header_img = headImg;
};
//组织架构的引用
// console.log(sidList)
//button确定
$('#b_is').click(function (){
  	var val = $(this).attr('val');
  	if (val == "selman") {
    	//在手机上交替显示
    	if ($(window).width() < 500) {
      		$('.chat-container').show();
      		$('.details-list').hide();  
    	};
    	//end
    	var jsonText = JSON.stringify(sidList);
    	if (sidList.length >1) {
      		alert('只能选择一个人！');return false;
    	};
    	to_uid = sidList.join(',');
    	ws.send('{"type":"mes_chat", "mes_para":"'+to_uid+'"}')
    	$('#mes_load').html(10);
    	mes_type = "message";
    	//会话id的改变
    	if (session_no == 0) {
      		if (to_uid < uid) {
	        	session_no = to_uid+"-"+uid;
	      	} else {
	        	session_no = uid+"-"+to_uid;
	      	}
    	};
    	//消息向上滚动
    	$('.he-ov-box').unbind('scroll');
    	$('.he-ov-box').bind("scroll", function (){
     	 	mesScroll();
    	})
    	$.ajax({
	      	url:'getndp.php',
	      	data:'jsonText='+jsonText,
	      	type:'post',
	      	success:function(data){
	        	var d=eval('('+data+')')
	        	for (var i = 0; i < d.length; i++) {
	          	$('.mes_title_con').html(d[i].name);  
	          		getStaffHead(d[i].card_image);  
	        	}
	      	}
    	})
  	} else if (val == "addGroupMan") {
    	$('.alert').show(500);
    		setTimeout(function(){
      		$('.alert').hide(500);
    	},2000);
    	ws.send('{"type":"addGroupMan", "session_no":"'+session_no+'", "sidList":['+sidList+']}');
  	};
  	$('#name_box').hide();
  	$('.selected').find('div').html('<ul></ul>');
  	$('#No1').find('.ltclasscheckbox').attr('checked',false);
  	$('.select_member_num').html($('.selected').find('sid').length+'/'+$('#No1').find('sid').length);
  	sidList=[];
})
// 组织架构 button取消
$('#b_no').click(function(){
  	$('#name_box').hide();
})
//发送文件

//请求 token
function xmlhttp() {
    var $xmlhttp;

    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        $xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        $xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    return $xmlhttp;
}
window.onload = function() {

    $xmlhttp = xmlhttp();
    $xmlhttp.onreadystatechange = function() {
        if ($xmlhttp.readyState == 4) {
            if($xmlhttp.status == 200){
                document.getElementById('token').value = $xmlhttp.responseText;
            } else {
                alert('get uptoken other than 200 code was returned')
            }
        }
    }
    $upTokenUrl = '/chat/uptoken.php';
    $xmlhttp.open('GET', $upTokenUrl, true);
    $xmlhttp.send(); 

    $file = document.getElementById('file');
};
</script>
</body>
</html>
