<?php
require_once('config.inc.php');
$uid = $_SESSION['staffid'];//发送人id
$oms_id = $_SESSION['oms_id'];//发送人oms_id
$uid = 4;
$oms_id = 1;
if (isset($uid)) {
  $d = new database();
  //根据$_SESSION获取名字
  $sql = 'SELECT `name` FROM `oms_hr` WHERE staffid='.$uid;
  $arrName = $d->find($sql);
  $name = $arrName['name'];
  if (empty($name)) { $name = '匿名'; }
} else {
  $uid = 'n'; $name = '匿名';
}
$sql = 'SELECT * FROM `oms_string_message` WHERE `state`= 0 AND `accept_id`='.$uid;
$arrMes = $d->findALL($sql);
$mesNum = count($arrMes);//消息的个数
?>
<html><head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>workerman-chat PHP聊天室 Websocket(HTLM5/Flash)+PHP多进程socket实时推送技术</title>
  <script type="text/javascript">
  //WebSocket = null;
  </script>
  <!-- <link href="css/bootstrap.min.css" rel="stylesheet"> -->
  <!-- <link href="css/style.css" rel="stylesheet"> -->
  <!-- Include these three JS files: -->

  <script type="text/javascript" src="js/swfobject.js"></script>
  <script type="text/javascript" src="js/web_socket.js"></script>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/web_message.js"></script>
  <link rel="stylesheet" href="css/spigPet.css" type="text/css"/>
  <link rel="stylesheet" href="css/web_chat.css" type="text/css">
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
    var oms_id = "<?php echo $oms_id;?>";
    var uid = "<?php echo $uid;?>"; // 发送人id
    var name = "<?php echo $name;?>";// 发送人name
    var room_id = "<?php echo isset($_GET['room_id']) ? $_GET['room_id'] : 1?>";//房间id
    var to_uid =new Array();// 接收人id
    
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
            $.ajax({
              url:'../getndp.php',
              data:'jsonText='+jsonText,
              type:'post',
              success:function(data){
                var d=eval('('+data+')')
                // console.log(d);
                for (var i = 0; i < d.length; i++) {
                  $('#man').append('<div style="float:left;" class="c_task"><ul><li class="man_s">"'+d[i].new_department+':'+d[i].new_department_two+':'+d[i].new_position+'"'+d[i].pt+":"+d[i].name+'";'+'</li></ul></div>');    
                }
                $('#man').append('<br>')
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
<div class="mes_containner">
  <div class="mes_header">
    <h1 class="mes_title">人名</h1>
  </div>
  <!-- <div style="text-align: center;font-family: 'Microsoft YaHei', SimSun, sans-serif;">当前在线人数<span id='online_ren'></span>人</div> -->
  <span id='s_man'>请选择</span>
</div>

<!-- 消息 -->
<div class="mes_fixed_big">
      <div class="mes_abs">
        <div class="mes_fixed">
          <div class="mes_ico_box" cata-box='ren'>
            <div class="mes_ico" style="background-position:0 -50px"></div>
            <div class="mes_ico" style="background-position:-100px -100px;text-align: center;color:#fff;">
              <span style='line-height: 50px;'><span class='online_ren'>0</span>人</span>
            </div>
          </div>
          <div class="mes_ico_box" cata-box='mes'>
            <div class="mes_ico" style="background-position:0 0"></div>
            <div class="mes_ico" style="background-position:-100px -100px">
              <div class="mes_radio"><?php echo $mesNum;?></div>
            </div>
          </div>
        </div>
        <div class="online_man" >
          <div class="man_tittle">
            <span>人员列表</span>
            <span class ="close" style='cursor: pointer; margin-right: 5px;background: #000;color: #fff; position:absolute;width:20px;height:20px;border-radius: 50%;right: 0; top: 7px; '>X</span>
          </div>
          <ul>
          </ul>
        </div>
        <div class="mes_con" style="display: none;">
            <div class="mes_tittle">
              <span>消息列表</span>
              <span class ="close" style='cursor: pointer; margin-right: 5px;background: #000;color: #fff; position:absolute;width:20px;height:20px;border-radius: 50%;right: 0; top: 7px; '>X</span>
            </div>
            <!-- 消息列表 -->
            <?php foreach ($arrMes as $key => $value) :?>
            <div class="mes_box">
                <span class='mex_con'><?php echo $value['message_content'];?></span>
                <div style='height:30px'>
                  <a href="<?php echo $value['message_url'];?>">
                    <span class='mes_chakan'>
                      查看
                    </span>
                  </a>
                </div>
                <span class='mes_close' mesid='<?php echo $value['session_no'];?>'>X</span>
            </div>
          <?php endforeach;?>
            <!-- end -->
          </div>
        </div>
    </div>

<script type="text/javascript">
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
//body点击事件
// $('body').click(function (){
//       $('.mes_abs').animate({
//         right: 0
//       },
//       {
//         queue: true,
//         duration: 0
//       })
// })

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
  $('.mes_close').click(function (){
    var mesid = $(this).attr('mesid');//消息的id
    var mesnum = parseInt($('.mes_radio').html());//消息的个数
    ws.send('{"type":"mes_close", "mesid":"'+mesid+'"}');
    mesnum--;
    $('.mes_radio').html(mesnum);
    $(this).parent().remove();
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
  //消息点击显示消息列表
  // $('.mes_fixed').click(function (){
  //   var mes_con = $('.mes_abs').css('right');
  //   //0px 消息隐藏
  //   if (mes_con == '0px') {
  //     mes_con = 290;
  //   } else {
  //      mes_con = 0;
  //   }
  //   $('.mes_abs').animate({
  //     right: mes_con
  //   },
  //   {
  //     queue: true,
  //     duration: 0
  //   })
  // })
</script>
</body>
</html>
