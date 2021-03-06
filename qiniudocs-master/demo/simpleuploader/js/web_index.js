// JavaScript Document
//shift+enter 换行
//enter 提交
$(".mes_footer").keydown(function(e){
    var e = e || event,
        keycode = e.which || e.keyCode;
    if(event.shiftKey && (event.keyCode==13)){
        $('#textarea').append('<br/>')
    } else  if (keycode==13) {
        $("#submit").trigger("click");
    }
});
//对话框的高度
var mesHeight = 0;
//查看更多
$('.onload').live("click", function(){
    $('.he-ov-box').trigger("scroll");
})
//对话框关闭
$('.mes_dclose').on('click', function (){
  $('.chat-container').hide();
})
// 选择人聊天
$('.chat_people').live('click' , function(e){
        $('.chat-container').show();
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
});
$('.session_no').live('click', function (){
    if ($(event.target).is('.session_no')) {
      if (session_no == $(this).attr('session_no')) {
        return ;
      };
        var groupAll = $(this).attr('group-all');
        to_uid = groupAll;
        $('#b_is').attr('group-all', groupAll);
        //在手机上交替显示
          $('.chat-container').show();
        //end
          var groupvalue = $(this).attr('group-all')
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
        ws.send('{"type":"mes_groupChat", "session_no":"'+session_no+'", "groupvalue":"'+groupvalue+'"}');
        $('#mes_load').html(10);
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
//表情的点击事件
$(".emoticons .cli_em").click(function (){
  $(this).clone().append().appendTo('.textarea');
  $('textarea').focus().val($('.textarea').html())
})
//表情的显示
$('.header_icon').click(function () {
  addempath();
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
//$(".he-ov-box").scrollTop($(".he-ov-box")[0].scrollHeight);
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
                        // style.overflowY = 'hidden';
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
   maxHeight:260,
   minHeight:50
});
//右边图标点击事件
$('.mes_ico_box').click(function (){
   var mes_abs = $('.mes_abs').css('right');
   var cata_box = $(this).attr('cata-box')
   if (cata_box == 'ren') {
    $('.online_man').show();
    $('.mes_con').hide();
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
      $('.online_man').hide();
      $('.mes_con').show();
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
  $(document).on('click', '.mes_close, .mes_chakan_close',function (){
    var mestype = $(this).attr('mestype');
    var session_no = $(this).attr('session_no');
    var mesid = $(this).attr('mesid');//消息的id
    ws.send('{"type":"mes_close", "mesid":"'+mesid+'", "session_no":"'+session_no+'", "mestype":"'+mestype+'"}');
    mesnum = mesnum - $('.mes_close[session_no="'+session_no+'"]').length;
    $('.mes_radio').html(mesnum);
    $('.mes_close[session_no="'+session_no+'"]').parents('.mes_box').remove();
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
      console.log(img);
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