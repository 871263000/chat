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
//对话内容
if (isset($_GET['session_no'])) {
  $arrmesContent = $mes->groupMesContent();
  $mes_type = $mes->mes_type;
  $session_no = $mes->session_no;
} else {
  //根据staffid 获得对话内容
  if (isset($_GET['staffid'])) {
    $arrmesContent = $mes->MesContent();
    $mes_type = $mes->mes_type;
    $session_no = $mes->session_no;
  }
}
//接受人名字
$accname = $mes->accname;
//最近联系人
if (isset($uid)) {
  $recentContact = $mes->recentContact();
}
//提示消息列表
if (isset($uid)) {
  $arrMes = $mes->mesAlertList();
  $mesNum = $mes->mesNum;//提示消息的个数
}
//自己的信息
$userinfo = $mes->userinfo();
$name = $userinfo['name'];//自己名字
$card_image = $userinfo['card_image'];//头像的url
//群聊列表
$arrGroup = $mes->groupChatList();
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
  <script src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/web_message.js"></script>
  <link rel="stylesheet" href="css/webRight.css" type="text/css"/>
  <link rel="stylesheet" href="css/app.css" type="text/css"/>
  <link rel="stylesheet" href="css/phone.css" type="text/css"/>
<style type="text/css">
  *{font-family: 'Microsoft YaHei', SimSun, sans-serif !important;}
  .mes_radio{ width: 21px; height: 21px; display: block; border-radius: 50%; background: #ed145b; text-align: center; line-height: 21px; margin: 9px 0 0 10px; cursor: pointer; color: #fff; text-align: center; line-height: 21px; margin: 10px 0 0 14px; }
  .online_man{ text-align: center;margin: 0 auto;box-shadow: 0 0 5px #999;}
  .online_man ul{margin-left: auto;margin-right: auto;padding: 0;}
  #name_box{
    position:absolute;background:#fff;display:none;width: 100%;margin-top:200px;
    z-index: 111;
  }
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
            <img src="<?php echo $card_image;?>" width="50px" height="50px" alt="<?php echo $name;?>">
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
          <?php if (!empty($recentContact)): foreach ($recentContact as $key => $value) :?>
            <?php if ($value['sender_id'] != $uid && $value['message_type'] == "message") : ?>
            <?php  $recentName = $value['sender_name']; $staffid = $value['sender_id'];$card_image = $value['scard_image']?>
            <?php else : ?>
              <?php  $recentName = $value['accept_name']; $staffid = $value['accept_id'];$card_image = $value['acard_image']?>
            <?php endif; ?>
            <?php if ($value['message_type'] !== "message") :?>
                <li class="session_no" group-name="<?php echo $recentName?>" mestype ="<?php echo $value['message_type'];?>" group-all="<?php echo $value['accept_id'];?>" groupId="<?php echo $value['groupId'];?>" session_no="<?php echo $value['session_no'];?>" >
                	<span class="header-img"><img  src="/chat/images/group.jpg" alt=""></span>
                  <i class="session_no" group-name="<?php echo $recentName?>" mestype ="<?php echo $value['message_type'];?>" group-all="<?php echo $value['accept_id'];?>" groupId="<?php echo $value['groupId'];?>" session_no="<?php echo $value['session_no'];?>"><?php echo $recentName?></i>
                  <span title = "删除聊天记录" mestype="<?php echo $value['message_type'];?>" session="<?php echo $value['session_no'];?>" class="recent-close">&times;</span>
                </li>
            <?php else: ?>
                <li class="recent-contact chat_people" group-name="<?php echo $recentName?>" session_no="<?php echo $value['session_no'];?>" groupId="<?php echo $value['groupId'];?>"  group-all="<?php echo $staffid;?>" id="<?php echo $staffid;?>" mestype ="<?php echo $value['message_type'];?>" ><span class="header-img"><img src="<?php echo $card_image;?>" alt=""></span><i><?php echo $recentName?></i><span title = "删除聊天记录" mestype="<?php echo $value['message_type'];?>" session="<?php echo $value['session_no'];?>" class="recent-close">&times;</span></li>
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
                          <div class="panel-heading session_no" group-name="<?php echo $value['group_name']?>" groupId="<?php echo $value['id'];?>" group-all="<?php echo $value['all_staffid']?>" session_no='<?php echo $value['pid']?>'>
                              <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $key;?>"><?php echo $value['group_name']?></a></h4>
                          </div>
                          <div id="collapse<?php echo $key;?>" class="panel-collapse collapse">
                              <div class="panel-body">
                                <ul class="list-group">
                                <!-- 群聊参加人 -->
                                <?php if ($value['group_founder'] == $uid) {
                                	$addClass = "group-people";
                                } else {
                                	$addClass = "";
                                }
                                ?>
                                  <?php foreach ($value['group_people'] as $k => $val) :?>
                                    <li class="chat_people <?php echo $addClass;?>" group-name="<?php echo $val['name'];?>" groupId="<?php echo $val['id'];?>" id="<?php echo $val['staffid'];?>"><span class="header-img"><img src="<?php echo $val['card_image'];?>" alt="<?php echo $val['name'];?>"></span><i><?php echo $val['name'];?></i><span class="delgroupman" groupId="<?php echo $val['id'];?>" id="<?php echo $val['staffid'];?>">&times;</span></li>
                                    <?php endforeach;?>
                                </ul>
                              </div>
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
    	           <div class="he-ov-box">
    	               <div class="">
                     <div class="loader">
                        <div id="mes_load" style="display:none;"><?php echo $pageload;?></div>
                        <div class="loading-3">
                            <i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i>
                        </div>
                      </div>
                      <ul class="he_ov">
<!--                      <div class='onload'  style='text-align: center;'><span style='color: #fff;padding: 5px 0;'>---查看更多---</span></div>-->
                      <?php if (!empty($arrmesContent)) :foreach (array_reverse($arrmesContent) as $key => $value) :?>
                        <?php if ($value['sender_id'] == $uid):?>
                          <li class="Chat_ri he"><div class="user_ri he"><span class="ri head_ri"><img src="images/header.jpg" alt=""></span> <span class="ri name_ri"><span style="padding: 0 20px 0 0"><?php echo date('Y-m-d H:i:s', $value['create_time']);?></span><?php echo $name;?></span> <div class="ri content_ri"><span class="arrow ri"></span><span class="content_font_ri"><?php $rest =preg_replace('/%6b/', '<br/>',$value['message_content']); echo preg_replace('/%5C/', '\\',$rest);?></span> </div></div></li>
                        <?php else:?>
                          <li class="Chat_le"><div class="user"><span class="head le"><img src="images/header.jpg" alt=""></span> <span class="name le"><?php echo $value['sender_name'];?><span style="padding: 0 0 0 20px"><?php echo date('Y-m-d H:i:s', $value['create_time']);?></span></span></span><div class="mes_content le"><span class="jian le"></span> <span class="content-font le"><?php $rest =preg_replace('/%6b/', '<br/>',$value['message_content']); echo preg_replace('/%5C/', '\\',$rest);?></span></div></div></li>
                        <?php endif;?>
                        <?php endforeach;?>
                      <?php endif;?>
                      </ul> 
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
<script type="text/javascript">
//群聊参加人滑过事件
$('.group-people').hover(function(){
	$(this).find('.delgroupman').show();
}, function(){
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
//最近联系人删除聊天记录
// $(document).on('click' , '.recent-close' , function (){
//   var session = $(this).attr('session');
//   var mestype = $(this).attr('mestype');
//   $(this).parent().remove();
//   ws.send('{"type":"recentcClose", "session":"'+session+'", "mestype": "'+mestype+'"}');
// })
//最近联系人滑过事件
// $(document).on('mouseenter' , '.list-group li', function(){
//   $(this).find('span').css('display', 'inline-block')
// })
// $(document).on('mouseleave' , '.list-group li', function(){
//   $(this).find('span').hide()
// })
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
//选择人聊天和 手机滑动
$(function() {
  //swipe 的dome
  // $("#test").swipe( {
  // swipe:function(event, direction, distance, duration, fingerCount) {
  // console.log("你用"+fingerCount+"个手指以"+duration+"秒的速度向" + direction + "滑动了" +distance+ "像素 " );
  // },
  // });
  //swipeStatus的dome
  $('.session_no').swipe( {
    swipeStatus:function(event, phase, direction, distance, duration,fingerCount) {
      if (distance > 10 && direction == "left") {
          $(this).find('.recent-close').css('display', 'inline-block');
      } else if (distance > 10 && direction == "right") {
        $(this).find('.recent-close').hide();
      }
    },
  });
  $(".chat_people").swipe({
  swipeStatus:function(event, phase, direction, distance, duration,fingerCount) {
    if (distance > 10 && direction == "left") {
      $(this).find('.recent-close').css('display', 'inline-block');
    } else if (distance > 10 && direction == "right") {
      $(this).find('.recent-close').hide();
    }
  },
//   click:function(event){
//       //在手机上交替显示
// //      if (to_uid == $(this).attr('id')) {
// //        return ;
// //      };      console.log(8)
//       if ($(event.target).is('.recent-close')) { return;  };
//       if ($(window).width() < 500) {
//         $('.chat-container').show();
//         $('.details-list').hide();  
//       };
//       //end
//         to_uid = $(this).attr('id');
//         // groupId = $(this).attr('groupId');
//         ws.send('{"type":"mes_chat", "mes_para":"'+to_uid+'"}');
//         $('#mes_load').html(10);
//         mes_type = "message";
//        //会话id的改变
//         if (to_uid < uid) {
//           session_no = to_uid+"-"+uid;
//         } else {
//           session_no = uid+"-"+to_uid;
//         }
//         $('.mes_title_con').html($(this).attr('group-name'));
//         //消息向上滚动
//         $('.he-ov-box').unbind('scroll');
//         $('.he-ov-box').bind("scroll", function (){
//         if ($(".he-ov-box").scrollTop() <= 10 && $(".he-ov-box").scrollTop() >= 0) {
//           var mes_loadnum = $('#mes_load').html();
//           $('.loader').show()
//           mesHeight = $('.he-ov-box').height()
//           ws.send('{"type":"mes_load","mes_loadnum":"'+mes_loadnum+'", "message_type":"'+mes_type+'", "to_uid":"'+to_uid+'","session_no": "'+session_no+'"}');
//         };
//       })
//   }
  });
});
// 选择人聊天
$('.chat_people').live('click', function( e ){
      if ($(window).width() < 700) {
        $('.chat-container').show();
        $('.details-list').hide();  
      };
      //end
        to_uid = $(this).attr('id');
        //会话id的改变
        if (to_uid < uid) {
          session_no = to_uid+"-"+uid;
        } else {
          session_no = uid+"-"+to_uid;
        }
        // groupId = $(this).attr('groupId');
        ws.send('{"type":"mes_chat", "mes_para":"'+to_uid+'"}');
        $('#mes_load').html(10);
        mes_type = "message";
        $('.mes_title_con').html($(this).attr('group-name'));
        //消息向上滚动
        $('.he-ov-box').unbind('scroll');
        $('.he-ov-box').bind("scroll", function (){
        if ($(".he-ov-box").scrollTop() <= 10 && $(".he-ov-box").scrollTop() >= 0) {
          var mes_loadnum = $('#mes_load').html();
          $('.loader').show()
          mesHeight = $('.he_ov').height()
          ws.send('{"type":"mes_load","mes_loadnum":"'+mes_loadnum+'", "message_type":"'+mes_type+'", "to_uid":"'+to_uid+'","session_no": "'+session_no+'"}');
        };
      })
})

//选择群列表显示对话内容
$('.session_no').live('click', function (event){
    if ($(event.target).is('.session_no')) {
      if (session_no == $(this).attr('session_no')) {
        return ;
      };
      // console.log(4);return false;
        //获取群聊id
        var groupAll = $(this).attr('group-all');
        to_uid = groupAll;
        $('#b_is').attr('group-all', groupAll);
        //在手机上交替显示
        if ($(window).width() < 700) {
          $('.chat-container').show();
        $('.details-list').hide();  
        };
        //end
          var valName = $(this).attr('group-name');//会话名字
          session_no = $(this).attr('session_no')//会话id
          mes_type = "groupMessage";//消息类型
          groupId = $(this).attr('groupId');
         //消息向上滚动
        $('.he-ov-box').unbind('scroll');
        $('.he-ov-box').bind("scroll", function (){
          if ($(".he-ov-box").scrollTop() <= 10 && $(".he-ov-box").scrollTop() >= 0) {
              var mes_loadnum = $('#mes_load').html();
              $('.loader').show()
              mesHeight = $('.he_ov').height()
              ws.send('{"type":"mes_load","mes_loadnum":"'+mes_loadnum+'", "message_type":"'+mes_type+'", "to_uid":"'+to_uid+'","session_no": "'+session_no+'"}');
          };
        })
        $('.mes_title_con').html(valName);
        $('.mes_title_con').append('<i title="群聊添加人" class="add-groupMan"></i>');
        $('.add-groupMan').show();
        ws.send('{"type":"mes_groupChat", "session_no":"'+session_no+'", "groupvalue":"'+groupAll+'"}');
        $('#mes_load').html(10);
      };
})
//表情的添加
var emPath = "<?php echo DOCUMENT_URL?>/chat/emoticons/images/";//表情路径
var total = 134;//表情的个数
var newTotal = 14;//新增表情的个数
for(var i=0; i < newTotal ; i++) {
  $('.emoticons').append('<div class="em_gif"><img width="24px" class="cli_em" src="'+emPath+'f'+i+'.gif"></div>');
}
for(var i=0; i < total ; i++) {
  $('.emoticons').append('<div class="em_gif"><img class="cli_em" src="'+emPath+i+'.gif"></div>');
}
//表情的点击事件
$(".emoticons .cli_em").click(function (){
  $(this).clone().append().appendTo('.textarea');
  $('textarea').focus().val($('.textarea').html())
})
//表情的显示
$('.header_icon').click(function () {
  $(".emoticons").toggle();
  var inputHeight = $('.mes_footer').height();// 输入框的高度
  $(".he-ov-box").css("bottom", inputHeight);
  $(".he-ov-box").scrollTop($(".he-ov-box")[0].scrollHeight);
})

// 右边消息的个数
var mesnum = parseInt($('.mes_radio').html());//消息的个数
//消息的高度
$('.loader').hide();

//消息向上滚动
$('.he-ov-box').bind("scroll", function (){
  if ($(".he-ov-box").scrollTop() <= 10 && $(".he-ov-box").scrollTop() >= 0) {
    var mes_loadnum = $('#mes_load').html();
    $('.loader').show()
    mesHeight = $('.he-ov-box').height()
    ws.send('{"type":"mes_load","mes_loadnum":"'+mes_loadnum+'", "message_type":"'+mes_type+'", "to_uid":"'+to_uid+'","session_no": "'+session_no+'"}');
  };

})
//消息定位的底部
$(".he-ov-box").scrollTop($(".he-ov-box")[0].scrollHeight);
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
	if(event.shiftKey && (event.keyCode==13)){
		$('#textarea').append('<br/>')
	} else 	if (keycode==13) {
		$("#submit").trigger("click");
	}
});
//消息提交
$('#submit').click(function (){
  //接收人名字
  accept_name = $('.mes_title_con').text();
  if (to_uid == 0 && session_no == 0) {
      event.preventDefault();
      $('.mes_alert_con').html('请选择聊天对象！')
      $('.cd-popup').addClass('is-visible'); return false;
  };
  if ($('#textarea').html() =='') {
      event.preventDefault();
      $('.mes_alert_con').html('消息不能为空！')
      $('.cd-popup').addClass('is-visible'); return false;
  };
  // if ($('li[session_no="'+session_no+'"]')) {
  //   $('li[session_no="'+session_no+'"]').insertBefore($('.tab-content .list-group li').eq(0))
  // };
  onSubmit(to_uid, uid, groupId, accept_name, mes_type, session_no);
});

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
$("#textarea").autoTextarea({
   maxHeight:400,
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
$('.mes_ico_box').click(function (){
   var mes_abs = $('.mes_abs').css('right');
   var cata_box = $(this).attr('cata-box')
   if (cata_box == 'ren') {
    $('.online_man').show(100);
    $('.mes_con').hide(100);
    //0px 消息隐藏
    if (mes_abs == '0px' || mes_abs == '290px') {
      mes_abs = 140;
    } else {
       mes_abs = 0;
    }
    $('.mes_abs').animate({
      right: mes_abs
    },
    {
      queue: true,
      duration: 0
    })
   } else {
      $('.online_man').hide(100);
      $('.mes_con').show(100);
      //0px 消息隐藏
      if (mes_abs == '0px' || mes_abs == '140px') {
        mes_abs = 290;
      } else {
         mes_abs = 0;
      }
      $('.mes_abs').animate({
        right: mes_abs
      },
      {
        queue: true,
        duration: 0
      })
   }
})
// document点击事件
$(document).click(function (){
  if( !$(event.target).parents('.mes_fixed_big').is('.mes_fixed_big') && !$(event.target).is('.mes_close')) {
    // event.preventDefault();
    $('.mes_abs').animate({
        right: 0
      },
      {
        queue: true,
        duration: 0
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
  
  //单个消息关闭
  $('.mes_close, .mes_chakan_close').live('click', function (){
    var mestype = $(this).attr('mestype');
    var session_no = $(this).attr('session_no');
    var mesid = $(this).attr('mesid');//消息的id
    ws.send('{"type":"mes_close", "mesid":"'+mesid+'", "session_no":"'+session_no+'", "mestype":"'+mestype+'"}');
    mesnum--;
    $('.mes_radio').html(mesnum);
    $(this).parents('.mes_box').remove();
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
      document.getElementById( 'textarea' ).appendChild( img );
    };
    reader.readAsDataURL( blob );
  };
  document.getElementById( 'textarea' ).addEventListener( 'paste', function( e ){
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
      if( item && item.kind === 'file' && item.type.match(/^image\//i) ){
        imgReader( item );
      }
    }
  })
});
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
          if ($(".he-ov-box").scrollTop() <= 10 && $(".he-ov-box").scrollTop() >= 0) {
            var mes_loadnum = $('#mes_load').html();
            $('.loader').show()
            mesHeight = $('.he-ov-box').height()
            ws.send('{"type":"mes_load","mes_loadnum":"'+mes_loadnum+'", "message_type":"'+mes_type+'", "to_uid":"'+to_uid+'","session_no": "'+session_no+'"}');
          };
        })
        $.ajax({
          url:'getndp.php',
          data:'jsonText='+jsonText,
          type:'post',
          success:function(data){
            var d=eval('('+data+')')
            for (var i = 0; i < d.length; i++) {
              $('.mes_title_con').html(d[i].name);    
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
</script>
</body>
</html>
