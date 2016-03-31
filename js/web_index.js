//通知消息点击
$(document).on('click', '.chat_notice', function () {
    var session_id = $(this).attr('session_no');
    var sender_id = $(this).attr('mes_id');
    $('.chat_notice_container').show();
    ws.send('{"type": "chat_notice", "sender_id": '+session_id+'}');
    var con_mes_num =  parseInt($(".mes_chakan_close[session_no='"+session_id+"']").attr('chat_mes_num'));
    mes_notice_close('message', session_id, con_mes_num);
    nearestContact.push(session_id);
})
//通知消息同意不同意
$(document).on('click', '.chat_notice_sel', function () {
    var dataParm = $(this).attr('data-parm');
    var senderId = $(this).attr('sender-id');
    var noticeHtml = $(this).html();
    $('.alert').hide();
    ws.send('{"type": "chat_notice_sel", "dataParm": "'+dataParm+'", "senderId": "'+senderId+'"}');
})
//通知消息关闭
var mes_notice_close = function (mestype, session_id, mes_num){
    ws.send('{"type":"mes_notice_close", "session_no":"'+session_id+'", "mestype":"'+mestype+'"}');
    mesnum = mesnum - parseInt(mes_num);
    $('.mes_radio').html(mesnum);
    $('.mes_chakan_close[session_no="'+session_id+'"]').remove();
    arrMessageList= _chat_remove(session_id, arrMessageList);
    //arrMessageList.remove(session_id);
}
//删除指定数组元素
var _chat_remove = function (val, array) {
    var index = array.indexOf(val);
    if (index > -1) {
        array.splice(index, 1);
    }
    return array;
}
/*
Array.prototype.remove = function(val) {
    var index = this.indexOf(val);
    if (index > -1) {
        this.splice(index, 1);
    }
};*/
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
      console.log(onlineTop);
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
var ajaxGetStaffInfo = function (staffid, direction, height){
    var arrDirectionCg =new Object();
    arrDirectionCg = { 'left': 'right','right':'left', 'up': 'bottom', 'down': 'top' };
    var directionChang = arrDirectionCg[direction];
    var margin ="margin-"+directionChang;
    $.ajax({
        url:"/getStaffTels.php",
        type:"post",
        data:"staffid="+staffid,
        success: function ( data ){
            var data = $.parseJSON(data);
            getStaffInfo(data);
            // var index = parseInt($(".infoCurrent").index());
            $(".infoCurrent").append('<div class= "staff-info-box"><div class="arrow"></div><ul><li>手机：'+data.mobile_phone+'</li><li>座机：'+data.tel+'</li><li>分机：'+data.tel_branch+'</li></ul></div>');
            var offtop = height/2-85/2;
            $('.staff-info-box').css('top', "-50%");
            $('.staff-info-box').css(directionChang, "100%");
            $('.staff-info-box').css(margin, "6");
            $('.staff-info-box .arrow').css('border-'+direction,"8px solid #fff");
            $('.staff-info-box .arrow').css(direction,"100%");

            // $('.staff-info-box').css();
        }
    })
}
//人员信息弹框
//
// var staffPopover = function ( data ){

// }
//消息滑过
$(document).on('mouseenter' , '.staff-info',function(){
    var obj = $(this);
    var staffid = obj.attr('mes_id');
    obj.addClass('infoCurrent');
    var direction = obj.attr('data-placement');
    var height = obj.height();
    ajaxGetStaffInfo(staffid, direction, height);
});
$(document).on('mouseleave', '.staff-info', function (){
    var obj = $(this);
    obj.removeClass('infoCurrent');
    $('.staff-info-box').remove();
});
//拖动
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
//触发文件点击
var trig = function (obj){
    obj.trigger('click');
}
$('#upclick').click( function(){
    trig($('#send-upimg'));
});
$('#cli-upFile img').click(function(){
    trig($('#file_zdl'));
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
//加号的单击
$(".plus_icon").click( function (){
  $(".plus_menu_box").toggle();
});
//shift+enter 换行
//enter 提交
$(".mes_footer").keydown(function(e){
    var e = e || event,
        keycode = e.which || e.keyCode;
    if(event.shiftKey && (event.keyCode==13)){
        $('#mes_textarea').append('<br/>')
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
  session_no = 0;
  $('.chat-container').hide();
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
// 选择人聊天
$('.chat_people').live('click', function( e ){
    to_uid = $(this).attr('mes_id');
    to_uid_header_img = $(this).find('img').attr('src');
    //会话id的改变
    session_no = to_uid < uid ? to_uid+"-"+uid : uid+"-"+to_uid;
    mes_type = "message";
    if (!$(e.target).is('.recent-action')) {
        $('.chat-container').show();
      //end
      // groupId = $(this).attr('groupId');
      // if (!$(e.target).is('.mes_chakan_close')) {
      $('.mes_title_con').html($(this).attr('group-name'));
        if ($(".mes_chakan_close[session_no='"+session_no+"']").length > 0) {
          var con_mes_num =  parseInt($(".mes_chakan_close[session_no='"+session_no+"']").attr('chat_mes_num'));
          mes_chakan_close('message', session_no, con_mes_num);
        };
      // };
      ws.send('{"type":"mes_chat", "mes_para":"'+to_uid+'"}');
      $('#mes_load').html(10);
      //消息向上滚动
      $('.he-ov-box').unbind('scroll');
      $('.he-ov-box').bind("scroll", function (){
        mesScroll();
      })
    } 
})

//选择群列表显示对话内容
$('.session_no').live('click', function ( event ){
    session_no = $(this).attr('session_no')//会话id
    mes_type = "groupMessage";//消息类型
    if (!$(event.target).is('.recent-action')) {
    $('.chat-container').show();
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
    // if (!$(event.target).is('.mes_chakan_close')) {
    if ($(".mes_chakan_close[session_no='"+session_no+"']").length > 0) {
      var con_mes_num =  parseInt($(".mes_chakan_close[session_no='"+session_no+"']").find('.mes_num').html());
      mes_chakan_close('groupMessage', session_no, con_mes_num);
    };
    // };
    ws.send('{"type":"mes_groupChat", "session_no":"'+session_no+'" }');
    $('#mes_load').html(10);
  } 
})
//表情的添加
function addempath() {
  var emPath = "/chat/emoticons/images/";//表情路径
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
$(".emoticons .cli_em").live('click',function (){
    $(this).clone().append().appendTo('.textarea');
    $('textarea').val($('.textarea').html())
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
  mesScroll();

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
$("#mes_textarea").autoTextarea({
   maxHeight:260,
   minHeight:50
});
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
                mes_abs = 140;
            } else {
                mes_abs = 0;
            }
            $('.mes_abs').animate({
                    right: mes_abs
                },
                {
                    queue: true,
                    duration: 500
                })
        } else {
            $('.online_man').hide();
            $('.mes_con').show();
            //0px 消息隐藏
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
  //打个消息的关闭
  var mes_chakan_close = function (mestype, session_no, mes_num){
    if ( mestype == 'message' ) {
      session_no = parseInt(uid) < parseInt( session_id ) ? uid+"-"+session_id : session_id+"-"+uid;
    } else {
      session_no = session_id;
    }
    ws.send('{"type":"mes_close", "session_no":"'+session_id+'", "mestype":"'+mestype+'"}');
    mesnum = parseInt(mesnum) - parseInt(mes_num);
    $('.mes_radio').html(mesnum);
    $('.mes_chakan_close[session_no="'+session_no+'"]').remove();
    arrMessageList= _chat_remove(session_id, arrMessageList);
  }
  //单个消息关闭
  // $('.mes_chakan_close').live('click', function (){
  //   var mestype = $(this).attr('mestype');
  //   var mes_num = $(this).parent().next('.mes_num').html();
  //   session_no = $(this).attr('session_no');
  //   mes_chakan_close(mestype, session_no, mes_num);
  // })
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
      duration: 500
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
      document.getElementById( 'mes_textarea' ).appendChild( img );
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
      if (item && item.kind === 'string') {
        $('#mes_textarea').append(clipboardData.getData('text/plain'));
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

    $file = document.getElementById('file_zdl');
};
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
document.getElementById('submit').onclick = function (){
    //接收人名字
    if (!mesParam.mes_obj() || !mesParam.mes_empty()) {
        return false;
    };
    onSubmit(to_uid, uid, groupId, mes_type, 'text',session_no);
};
//消息提交
document.getElementsByClassName('send-clipboard-img')[0].onclick = function (){
    //接收人名字
    if (!mesParam.mes_obj()) {
        return false;
    };
    onSubmit(to_uid, uid, groupId, mes_type, 'image',session_no);
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
$('#file_zdl').on('change', function (){
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
                onSubmit(to_uid, uid, groupId, mes_type, 'file',session_no);
            }
        };
        startDate = new Date().getTime();
        $("#progressbar").show();
        xhr.send(formData);
    };
    var token = $("#token").val();
    if ($("#file_zdl")[0].files.length > 0 && token != "") {
        Qiniu_upload($("#file_zdl")[0].files[0], token, $key);
    } else {
        console && console.log("form input error");
    }

});