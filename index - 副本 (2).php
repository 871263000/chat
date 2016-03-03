<?php
require_once('config.inc.php');
$uid = $_SESSION['staffid'];//发送人id
$oms_id = $_SESSION['oms_id'];//发送人oms_id
$uid = 4;
$oms_id = 1;
$accname = '还没有选择人';// 选择人的名字
$d = new database();
$pageload = 10;// 每页显示的消息个数
if (isset($_GET['session_no'])) {
  if (isset($_GET['staffid'])) {
      //对话消息列表
    $session_no = $uid <= $_GET['staffid'] ? $uid."-".$_GET['staffid'] : $_GET['staffid']."-".$uid;
    $sql = "SELECT * FROM `oms_string_message` WHERE session_no="."'{$session_no}' ORDER BY create_time desc limit 0,".$pageload;
    $arrRes = $d->findAll($sql);//消息的data
    //接收人名字
    $sql = 'SELECT `name` FROM `oms_hr` WHERE staffid='.$_GET['staffid'];
    $arrAccname = $d->find($sql);
    $accname = $arrAccname['name'];//接收人名字
  } else {

  }
} else {
  if (isset($_GET['staffid'])) {
      //对话消息列表
    $session_no = $uid <= $_GET['staffid'] ? $uid."-".$_GET['staffid'] : $_GET['staffid']."-".$uid;
    $sql = "SELECT * FROM `oms_string_message` WHERE session_no="."'{$session_no}' ORDER BY create_time desc limit 0,".$pageload;
    $arrRes = $d->findAll($sql);//消息的data
    //接收人名字
    $sql = 'SELECT `name` FROM `oms_hr` WHERE staffid='.$_GET['staffid'];
    $arrAccname = $d->find($sql);
    $accname = $arrAccname['name'];//接收人名字
  }
}

if (isset($uid)) {
  //右边的消息根据$_SESSION获取名字
  $sql = 'SELECT `name` FROM `oms_hr` WHERE staffid='.$uid;
  $arrName = $d->find($sql);
  $name = $arrName['name'];
  if (empty($name)) { $name = '匿名'; }
  $sql = 'SELECT * FROM `oms_string_message` WHERE `state`= 0 AND  `accept_id`='.$uid;
  $arrMes = $d->findALL($sql);
  $mesNum = count($arrMes);//消息的个数
} else {
  $uid = 'n'; $name = '匿名';
}
?>
<html><head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>workerman-chat PHP聊天室 Websocket(HTLM5/Flash)+PHP多进程socket实时推送技术</title>
  <script type="text/javascript">
  //WebSocket = null;
  </script>
  <!-- <link href="css/bootstrap.min.css" rel="stylesheet"> -->
  <link href="css/style.css" rel="stylesheet">
  <!-- Include these three JS files: -->
  <script type="text/javascript" src="js/swfobject.js"></script>
  <script type="text/javascript" src="js/web_socket.js"></script>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/web_message.js"></script>
  <link rel="stylesheet" href="css/webRight.css" type="text/css"/>
  <link rel="stylesheet" href="css/app.css" type="text/css"/>
  <link rel="stylesheet" href="css/phone.css" type="text/css"/>
<style type="text/css">
  *{font-family: 'Microsoft YaHei', SimSun, sans-serif !important;}
  .mes_radio{ width: 21px; height: 21px; display: block; border-radius: 50%; background: #ed145b; text-align: center; line-height: 21px; margin: 9px 0 0 10px; cursor: pointer; color: #fff; text-align: center; line-height: 21px; margin: 10px 0 0 14px; }
  .online_man{ text-align: center;margin: 0 auto;box-shadow: 0 0 5px #999;}
  .online_man ul li{list-style: none;padding: 5px;border-bottom: 1px solid #dedede;}
  .online_man ul{margin-left: auto;margin-right: auto;padding: 0;}
  #name_box{
    position:absolute;background:#fff;display:none;width: 100%;margin-top:200px;
    z-index: 111;
  }
</style>
  <script type="text/javascript">
    $(function (){
        connect();
    })
    var mes_online = 1;
    var oms_id = "<?php echo $oms_id;?>";
    var uid = "<?php echo $uid;?>"; // 发送人id
    var name = "<?php echo $name;?>";// 发送人name
    var room_id = "<?php echo isset($_GET['room_id']) ? $_GET['room_id'] : 1?>";//房间id
    var to_uid = <?php echo isset($_GET['staffid']) ? $_GET['staffid'] : 0;?>;// 接收人id
  </script>
</head>
<body>
    <div id="name_box">
        <?php
            include('fenlei2/OS.php');
        ?>
        <div style="clear:both;width:100%;margin:50px 10px 0px 30%"><button style="width:60px;height:50px;font-size:18px" id="b_no" >取&nbsp;消</button><button style="margin-left:50px;width:60px;height:50px;font-size:18px" id="b_is">确&nbsp;定</button></div>
        <script type="text/javascript">
          // console.log(sidList)
          //button确定
          $('#b_is').click(function (){
            var jsonText = JSON.stringify(sidList);
            if (sidList.length >1) {
              alert('只能选择一个人！');return false;
            };
            to_uid = sidList.join(',');
            ws.send('{"type":"mes_chat", "mes_para":"'+to_uid+'"}')
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
            $('#name_box').hide();
            $('.selected').find('div').html('<ul></ul>');
            $('#No1').find('.ltclasscheckbox').attr('checked',false);
            $('.select_member_num').html($('.selected').find('sid').length+'/'+$('#No1').find('sid').length);
            sidList=[];
          })
          //button取消
          $('#b_no').click(function(){
            $('#name_box').hide();
          })
        </script>
    </div>
    <div class="bagimg">
      <img src="images/2.jpg"  alt="">
    </div>
    <div class="container">
    <div class="mes_title">
    <span id='s_man' alt='选择人聊天' class='mes_ren'></span>
    <span id='mes_ren' alt='选择群聊' class='mes_rens'></span>
    <a href="new_groupChat.php" target="_blank"><span id='groupMan' alt='新建群聊' class='groupMan'></span></a>
      <h2 class="mes_title_con"><?php echo $accname;?></h2>
    </div>
    <div class="mes_con_box">
  	    <div class="row clearfix">
  	           <div class="thumbnail">
  	               <div class="caption" id="dialog">
                   <div class="loader">
                      <div id="mes_load" style="display:none;"><?php echo $pageload;?></div>
                      <div class="loading-3">
                          <i></i>
                          <i></i>
                          <i></i>
                          <i></i>
                          <i></i>
                          <i></i>
                          <i></i>
                          <i></i>
                      </div>
                    </div>
                    <ul class="he_ov">
                    
                    <?php if (!empty($arrRes)) :foreach (array_reverse($arrRes) as $key => $value) :?>
                      <?php if ($value['sender_id'] == $uid):?>
                        <li class="Chat_ri he"><div class="user_ri he"><span class="ri head_ri"><img src="images/header.jpg" alt=""></span> <span class="ri name_ri"><span style="padding: 0 20px 0 0"><?php echo date('Y-m-d H:i:s', $value['create_time']);?></span><?php echo $name;?></span> <div class="ri content_ri"><span class="arrow ri"></span><span class="content_font_ri"><?php $rest =preg_replace('/%6b/', '<br/>',$value['message_content']); echo preg_replace('/%5C/', '\\',$rest);?></span> </div></div></li>
                      <?php else:?>
                        <li class="Chat_le"><div class="user"><span class="head le"><img src="images/header.jpg" alt=""></span> <span class="name le"><?php echo $value['accept_name'];?><span style="padding: 0 0 0 20px"><?php echo date('Y-m-d H:i:s', $value['create_time']);?></span></span></span><div class="mes_content le"><span class="jian le"></span> <span class="content-font le"><?php $rest =preg_replace('/%6b/', '<br/>',$value['message_content']); echo preg_replace('/%5C/', '\\',$rest);?></span></div></div></li>
                      <?php endif;?>
                      <?php endforeach;?>
                    <?php endif;?>
                    </ul> 
                   </div>
  	           </div>
  <!--           <div class="col-md-3 column">
               <div class="thumbnail">
                     <div class="caption" id="userlist"></div>
                 </div>
            </div> -->
            <div class="mes_footer">
                <!-- <form onsubmit="onSubmit(); return false;"> -->
  <!--                   <select style="margin-bottom:8px" id="client_list">
                          <option value="all">所有人</option>
                    </select> -->
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
            <span class ="close" style='cursor: pointer; margin-right: 5px;background: #000;color: #fff; position:absolute;width:20px;height:20px;border-radius: 50%;right: 0; top: 7px; '>X</span>
          </div>
          <!-- 人员列表 -->
           <ul> </ul>
        </div>
        <div class="mes_con" style="display: none;">
            <div class="mes_tittle">
              <span>消息列表</span>
              <span class ="close" style='cursor: pointer; margin-right: 5px;background: #000;color: #fff; position:absolute;width:20px;height:20px;border-radius: 50%;right: 0; top: 7px; '>X</span>
            </div>
            <!-- 消息列表 -->
            <?php foreach ($arrMes as $key => $value) :?>
            <?php
              $rest =preg_replace('/%6b/', '<br/>',$value['message_content']);
              $rest = preg_replace('/%5C/', '\\',$rest);
            ?>
            <div class="mes_box">
                <span class='mex_con'><?php echo $value['accept_name'].":".$rest;?></span>
                <div style='height:30px'>
                  <a href="<?php
                      if ($value['message_type'] == 'message') {
                         echo 'index.php?staffid='.$value['sender_id'];
                      } else {
                        echo $value['message_url'];
                      }
                     ?>" class="mes_chakan_close" mestype="<?php echo $value['message_type'];?>" mesid="<?php echo $value['id'];?>" target="_blank">
                    <span class='mes_chakan' >
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
    <!-- <div style="text-align: center;font-family: 'Microsoft YaHei', SimSun, sans-serif;">当前在线人数<span id='online_ren'></span>人</div> -->
<!-- 弹出框 -->
<div class="cd-popup" role="alert">
  <div class="cd-popup-container">
    <p class="mes_alert_con">还没有选择人！</p>
    <ul class="cd-buttons">
      <li><a href="#" class="clo_alert">确定</a></li>
      <!-- <li><a href="#">否</a></li> -->
    </ul>
    <a href="#" class="cd-popup-close img-replace">关闭</a>
  </div> <!-- cd-popup-container -->
</div> <!-- cd-popup -->

<!-- <script src="js/jquery.1.11.1.js"></script> -->
<!-- <script src="js/main.js"></script>  -->
<!-- Resource jQuery -->
<script type="text/javascript">

//弹出框
// $('.cd-popup-trigger').on('click', function(event){
//   event.preventDefault();
//   $('.cd-popup').addClass('is-visible');
// });

//表情的添加
var emPath = "<?php echo DOCUMENT_URL?>/emoticons/images/";//表情路径
var total = 134;//表情的个数
var newTotal = 14;//新增表情的个数
for(var i=0; i < newTotal ; i++) {
  $('.emoticons').append('<div class="em_gif"><img width="24px" class="cli_em" src="'+emPath+'f'+i+'.gif"></div>');
}
for(var i=0; i < total ; i++) {
  $('.emoticons').append('<div class="em_gif"><img class="cli_em" src="'+emPath+i+'.gif"></div>');
}
//表情的点击事件
$(".cli_em").click(function (){
  $(this).clone().append().appendTo('.textarea');
  $('textarea').focus().val($('.textarea').html())
})
//表情的显示
$('.header_icon').click(function () {
  $(".emoticons").toggle();
  var inputHeight = $('.mes_footer').height();// 输入框的高度
  $(".he_ov").css("bottom", inputHeight);
  $(".he_ov").scrollTop($(".he_ov")[0].scrollHeight);
})

// 右边消息的个数
var mesnum = parseInt($('.mes_radio').html());//消息的个数
//消息的高度
var mesHeight;
$('.loader').hide();

//消息向上滚动
$('.he_ov').bind("scroll", function (){
  if ($(".he_ov").scrollTop() <= 20) {
    var mes_loadnum = $('#mes_load').html();
    $('.loader').show()
    mesHeight = $('.he_ov').height()
    ws.send('{"type":"mes_load","mes_loadnum":"'+mes_loadnum+'", "to_uid": "'+to_uid+'"}');
  };

})
//消息定位的底部
// console.log($(".he_ov").scrollTop())
$(".he_ov").scrollTop($(".he_ov")[0].scrollHeight);
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
//消息提交
$('#submit').click(function (){
  if (to_uid == 0) {
      event.preventDefault();
      $('.mes_alert_con').html('还没有选择人！')
      $('.cd-popup').addClass('is-visible'); return false;
  };
  if ($('#textarea').html() =='') {
      event.preventDefault();
      $('.mes_alert_con').html('消息不能为空！')
      $('.cd-popup').addClass('is-visible'); return false;
  };
  onSubmit(to_uid, uid, "message");
});


// var scrHeight =$('.container').height();
// $('.mes_con_box').height(scrHeight - 100);
// message keyup 事件
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
                    $(".he_ov").scrollTop($(".he_ov")[0].scrollHeight+45)

                    $(".he_ov").css("bottom", inputHeight);
                }
            });
        });
    };
})(jQuery);
$("#textarea").autoTextarea({
   maxHeight:400,
   minHeight:32
});
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
})

//右边图标
$('.mes_ico_box').hover(function (){
  $(this).css('background-color', '#9dd2e7')
}, function (){
  $(this).css('background-color', '#444851')
})
  //选择人
  $('#s_man').click(function(){
    $('#name_box').show();
  })
  //消息滑过
  $('.mes_box').hover(
    function(){
      $(this).css('background-color', '#9dd2e7')
      $(this).find('.mes_close').show()
    },function (){
      $(this).css('background-color', '#fff')
      $(this).find('.mes_close').hide()
    });
  //单个消息关闭
  $('.mes_close, .mes_chakan_close').click(function (){
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
</script>
</body>
</html>
