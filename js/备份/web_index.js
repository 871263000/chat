
// 会话 添加

var AddSession = function () {
  this.sessionList = new Array();

}
AddSession.prototype.addSession = function ( headerImg, session_id, name ) {
  if ( $.inArray( session_id, sessionList ) ) {
    $('.session-tab ul').append('<li session_no ="'+session_id+'" class="chat-sessioning "><span class="session-tab-head"><img src="'+headerImg+'" alt="'+name+'" /></span><span class="session-tab-name">'+name+'</span><span class="session-tab-close">&times;</span></li>');
    var html = '<div class="chat-tab-content"><div class="mes_title">'+
               '<h2 class="mes_title_con">傻逼</h2><span aria-hidden="true" onclick="chatMin()" class="mes_dMinimize">-</span><span aria-hidden="true" class="mes_dclose">&times;</span>'+
           '</div>'+
           '<div class="mes_con_box">'+
               '<div class="">'+
                   '<div class="">'+
                       '<div class="he-ov-box mes-scroll pc_he-ov-box">'+
                           '<div class="loader">'+
                               '<div id="mes_load" style="display:none;">10</div>'+
                               '<div class="loading-3">'+
                                   '<i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i>'+
                               '</div>'+
                           '</div>'+
                           '<ul class="he_ov session-box"></ul>'+
                       '</div>'+
                   '</div>'+
                   '<div class="pc_mes_input_box">'+
                       '<div class="pc_emoji_box"></div>'+
                       '<div class="pc_mes_tool">'+
                           '<ul>'+
                               '<li class="pc_mes_tool_emoji pc_mes_tool_list"></li>'+
                               '<li class="pc_mes_tool_file pc_mes_tool_list"></li>'+
                           '</ul>'+
                       '</div>'+
                       '<div class="pc_mes_input" contenteditable="true" id="pc_mes_input"></div>'+
                       '<div class="pc_mes_send">'+
                           '<span style = "color: #aaa">按Esc 关闭，Shift + Enter 换行， Enter提交</span>'+
                           '<div class="chat_btn">发送</div>'+
                       '</div>'+
                   '</div>'+
                   '<div class="mes_footer mb_mes_footer mb_mes_footer">'+
                       '<div class="plus_menu_box">'+
                           '<div class="plus_menu">'+
                               '<span class="header_icon plus-list"><img src="/chat/images/header_icon.png" alt=""></span><input style="display:none" type="file" multiple id="send-upimg"><span id="cli-upFile"><img src="/chat/images/uploadfile.png" alt=""></span>'+
                           '</div>'+
                           '<i class="icon-caret-down"></i>'+
                       '</div>'+
                       '<div class="mes_input">'+
                           '<i class="plus_icon"></i>'+
                           '<div class="mes_inout textarea" id="mes_textarea" style="height:auto;" contenteditable="true"></div>'+
                           '<textarea style="display:none" class="mes_inout" ></textarea>'+
                           '<input type="submit" class="btn btn-primary btnSend" id="submit" value="发送" />'+
                           '<div style="clear:both"></div>'+
                       '</div>'+
                       '<div class="emoticons"></div>'+
                       '<div>'+
                       '</div>'+
                   '</div>'+
               '</div>'+
           '</div></div>';
           $('.chat-container').append(html);
  } else {

  }  
  this.sessionList.push(session_id);

};

// el dom 对象 ， session_id 会话ren id 
AddSession.prototype.delSession = function (session_id ) {
  $('.session-tab ul li[session_no = "'+session_id+'"]').remove();
  this.sessionList.splice( $.inArray(session_id,this.sessionList),1 );
};

// 实例化 增加  会话 
var addSession = new AddSession();
addSession.addSession('/chat/images/h1.jpg', 1, '傻逼');
// addSession.delSession(1, 1);
// 聊天框最小化
var chatMin = function (obj) {
  var chatName = $('.mes_title_con').text();
  $('.chat-container').hide();
  $('.chatMin').show().css('background-color', '#fff');
  $('.chatMin span').html(chatName);
}

// 聊天框最大话

var chatMax = function (obj) {
  $('.chat-container').show();
  $('.chatMin').hide().css('background-color', '#fff') ;
  $(".he-ov-box").scrollTop($(".he-ov-box")[0].scrollHeight);
}

//通知消息关闭
$('.chat_notice_close').click(function () {
  $('.chat_notice_container').hide(500);
})

// 当前的页面
var webUrl = '';
// 粘贴 插入
function insertHtmlAtCaret(html) {
    var sel, range;
    if (window.getSelection) {
        // IE9 and non-IE
        sel = document.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.deleteContents();
            // Range.createContextualFragment() would be useful here but is
            // non-standard and not supported in all browsers (IE9, for one)
            var el = document.createElement("div");
            el.innerHTML = html;
            var frag = document.createDocumentFragment(), node, lastNode;
            while ( (node = el.firstChild) ) {
                lastNode = frag.appendChild(node);
            }
            range.insertNode(frag);
            // Preserve the selection
            if (lastNode) {
                range = range.cloneRange();
                range.setStartAfter(lastNode);
                range.collapse(true);
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    } else if (document.selection && document.selection.type != "Control") {
        // IE < 9
        document.selection.createRange().pasteHTML(html);
    }
}

// 消息通知 里的系统通知 点击
$('.chat_customer_notice').on( 'click', function (){

  var htmlCon = $(this).find('.chat_mes_content').html();
  var mes_num = $(this).attr( 'chat_mes_num' );
  chatCustomerNotice( mes_num );
  $('.chat_message_notice .chat_message_notice_con').html('');
  $('.chat_message_notice .chat_message_notice_con').html(htmlCon);
  $('.chat_message_notice').show(500);

})

// 客户那里 消息关闭
var chatCustomerNotice = function ( mes_num ) {

  session_id = chat_uid+'sn';
  ws.send('{"type":"sys_mes_close", "message_type":"message"}');
  mesnum = mesnum - parseInt(mes_num);
  $('.mes_radio').html(mesnum);
  $('.mes_chakan_close[session_no="'+session_id+'"]').remove();

};
//系统 通知消息

$(document).on('click', '.chat_system_notice', function () {
    var json = '{"type":"sysNotice"}';
    chatController(json);
})
var chatController = function ( json ){
  ws.send(json);
}
//系统通知的关闭
$(document).on('click', '.chat_close',function () {
  $(this).parent().hide(500);
})
//输入框工具 滑过事件

$('.pc_mes_tool_list').hover(function () {
  $(this).css('opacity', 0.5 );
}, function () {
  $(this).css('opacity', 1 );
})

//声音提示
!function($) {
  var types = ['notice', 'warning', 'error', 'success'];
  var audio;
  
  var settings = {
    inEffect: {
      opacity: 'show'
    },
    inEffectDuration: 100,
    stay: 5000,
    sticky: false,
    type: 'notice',
    position: 'top-right',
    sound: '/chat/audio/notice.wav',
  };
  
  function dataSetting(el, options, name) {
    var data = el.data('notify-' + name);
    if (data) options[name] = data;
  };
  
  var Notify = function(el, options){
    var el = $(el);
    var $this = this;
    var dataSettings = {};
    
    $.each(['type','stay','position'], function(k, v){
      dataSetting(el, dataSettings, v);
    });
    
    if (el.data('notify-sticky')) {
      dataSettings['sticky'] = el.data('notify-sticky') == 'yes';
    }
    
    this.opts = $.extend({}, settings, dataSettings, typeof options == 'object' && options);
    
    // 检查 type 配置里面是否有 sticky 值
    if (this.opts.type.indexOf('sticky') > -1) {
      this.opts.sticky = true;
      this.opts.type = $.trim(this.opts.type.replace('sticky',''));
    }
    
    // 检查 type 类型是否支持
    if (types.indexOf(this.opts.type) == -1) {
      this.opts.type = settings.type;
    }
    
    var wrapAll =$('.mesNoticeContainer');
    var itemOuter = $('<div></div>').addClass('notify-item-wrapper');
    
    this.itemInner = $('<div></div>').show().addClass('mesNotice').appendTo(wrapAll).animate(this.opts.inEffect, this.opts.inEffectDuration).wrap(itemOuter);
    
    $('<div>&times;</div>').addClass('mesNoticeClose').prependTo(this.itemInner).click(function(){$this.close()});
    $('<div><img src = "'+options.imgUrl+'"></div>').addClass('mesNoticeImg').appendTo(this.itemInner);
    $('<div>'+options.tittle+'</div>').addClass('mesNoticeTittle').appendTo(this.itemInner);
    $('<div>'+options.content+'</div>').addClass('mesNoticeCon').appendTo(this.itemInner);
        navigator.userAgent.match(/MSIE 6/i) && wrapAll.css({top: document.documentElement.scrollTop});
    !this.opts.sticky && setTimeout(function(){$this.close()}, this.opts.stay);
    var VoiceState = localStorage.getItem('VoiceState');
    if ( VoiceState == '0' || VoiceState == null ) {
      if (window.HTMLAudioElement) {
        audio = new Audio();
        audio.src = options['soundUrl'];
        audio.play();
      }
    }
  };
  
  Notify.prototype.close = function () {
    var obj = this.itemInner;
    obj.animate({opacity: '0'}, 600, function() {
      obj.parent().animate({height: '0px'}, 300, function() {
        obj.parent().remove();
      });
    });
  };
  
  // $.notifySetup = function(options) {
  //   $.extend(settings, options);
  //   // console.log(options);
  //   if (options['sound']) {
  //     if (window.HTMLAudioElement) {
  //       audio = new Audio();
  //       audio.src = options['sound'];
  //     }
  //   }
  // };
  
  $.fn.notify = function(options) {
    return this.each(function () {
      if (typeof options == 'string') {
        return new Notify(this, {type: options});
      }
      return new Notify(this, options);
    });
  };

}(window.jQuery);

/***********  搜索人员   **************/
//人员的搜索
$('.search_staff').click(function () {
  $('#search_in').trigger('input');
})

//在线人员信息

var onlineManInfo  = new Array(); 
$('#search_in').on('input', function () {
  var inValue = $.trim($(this).val().toString());//去掉两头空格
  if ( inValue == '') {
      $('.search_result').hide();
      return;
  };
  var resSearch = search_in(inValue, onlineManInfo);
  $('.search_result').html('');
  
  for (var p in resSearch ) {
        $('.search_result').append('<li mes_id="'+p+'" data-placement="left" class="staff-info chat_people db_chat_people" group-name="'+resSearch[p].client_name+'"><span class="header-img"><img src="'+client_list[p].header_img_url+'" alt="'+resSearch[p].client_name+'"></span><span style = "color: red">'+resSearch[p].client_name+'</span></li>');
  }
  $('.search_result').show();
})
//搜索在线人数返回 搜索结果
var  search_in = function ( inValue, array) {
  //在线的人的名字
  var clientName = '';
  //返回的数组
  var resData = {};
  for (var i in array ) {
    clientName = array[i].client_name;
    if ( clientName.indexOf(inValue) != -1 ) {
      resData[i] = array[i];
    };
  }
  return resData;
}
/************** end   ****************/
/***************  pc输入框  ********************/
//文件发送
$('.pc_mes_tool_file').click( function () {
  trig($('#file_zdl'));
})
//图片发送
$('.pc_mes_tool_img').click( function () {
   trig($('#file'));
})
/****************  end  *******************/
//pc 提交
$('.chat_btn').click(function () {
  var inputValue = $('.pc_mes_input').html();
  $('#mes_textarea').html('');
  $('#mes_textarea').html(inputValue);
  $("#submit").trigger("click");
  $('.pc_mes_input').html('');
  $('.pc_mes_input').focus();
  
})
//pc 输入框聚焦
$('.pc_mes_input').focus( function () {
  $(".pc_emoji_box").hide();
  $(".he-ov-box").css("bottom", inputBottom);
  $(".he-ov-box").scrollTop($(".he-ov-box")[0].scrollHeight);
})
//pc inout 的高度
var inputBottom = $('.he-ov-box').css('bottom');
//pc 表情的显示
$('.pc_mes_tool_emoji').click(function () {
  $('.pc_emoji_box').show(500);
  addempath("pc_emoji_box");
})
//通知消息点击
//外来的oms_id 
var ChainOmsId = '' ;
$(document).on('click', '.chat_notice', function () {
  var session_id = $(this).attr('session_no');
  var sender_id = $(this).attr('mes_id');
  ChainOmsId = $(this).attr('oms_id');
  $('.chat_notice_container').show();
  // ws.send('{"type": "chat_notice", "sender_id":session_id+'}');
  var con_mes_num =  parseInt($(".mes_chakan_close[session_no='"+session_id+"']").attr('chat_mes_num'));
  mes_notice_close('message', sender_id, con_mes_num);
  nearestContact.push(session_id);
})
//通知消息同意不同意
$(document).on('click', '.chat_notice_sel', function () {
    var dataParm = $(this).attr('data-parm');
    var senderId = $(this).attr('sender-id');
    var noticeHtml = $(this).html();
    $('.chat_notice_sel').hide();
    ws.send('{"type": "chat_notice_sel", "dataParm": "'+dataParm+'", "senderId": "'+senderId+'", "oms_id": "'+ChainOmsId+'"}');
})
//通知消息关闭
var mes_notice_close = function (mestype, session_id, mes_num){
    ws.send('{"type":"mes_notice_close", "to_uid":"'+session_id+'", "mestype":"'+mestype+'"}');
    mesnum = mesnum - parseInt(mes_num);
    $('.mes_radio').html(mesnum);
    session_id = session_id + 't';
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
//拖动
// $(document).on('mouseover', ".online_man", function (){
//   if (proScroll < 1) {
//     $(".onlinesSroll-box").css('display', 'block');
//   };
// })
// $(document).on('mouseout', ".online_man", function (){
//   $(".onlinesSroll-box").css('display', 'none');
// })
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
    $('.staff-info-box').remove();
    $.ajax({
      url:"/getStaffTels.php",
      type:"post",
      data:"staffid="+staffid,
      success: function ( data ){
          var data = $.parseJSON(data);
          getStaffInfo(data);
          // var index = parseInt($(".infoCurrent").index());
          $(".infoCurrent").append('<div class= "staff-info-box"><div class="arrow"></div><ul><li>座机：'+data.tel+'</li><li>分机：'+data.tel_branch+'</li><li>手机：'+data.mobile_phone+'</li></ul></div>');
          $('.staff-info-box').css(css);
          $('.staff-info-box').css(directionChang, "100%");
          $('.staff-info-box').css(margin, "10");
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
$(function (){
  var winHeight = $(window).height();
  var onlineManHeight = winHeight - parseInt($('.online_man .list-group').css('top'));
  $('.online_man .list-group').css('height', onlineManHeight);
  $('.search_result').css('height', onlineManHeight);
})
$(document).on('mouseenter' , '.staff-info',function(){
    var obj = $(this);
    var _index = obj.index();
    var onlineManTop = $('.online_man .list-group').css('top');
    var staffid = obj.attr('mes_id');
    $('.online_man').addClass('infoCurrent');
    var direction = obj.attr('data-placement');
    var height = obj.outerHeight();
    var offtop = _index * height + height/2 + 38 - $('.online_man .list-group').scrollTop();
    // var offtop = height/2-85/2;
    ajaxGetStaffInfo(staffid, direction, { top: offtop});
});
$(document).on('mouseleave', '.staff-info, .online_man', function (){
    var obj = $(this);
    obj.removeClass('infoCurrent');
    $('.staff-info-box').remove();
});

// 人员消息的 消失
// $(document).on('mouseleave', '.online_man', function (){
//     var obj = $(this);
//     obj.removeClass('infoCurrent');
//     $('.staff-info-box').remove();
// });
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
// 七牛上传文件
var getQiniuToken = function () {
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
//触发文件点击
var trig = function (obj){
    obj.trigger('click');
}
$('#upclick').click( function(){
    trig($('#file_zdl'));
});
$('#cli-upFile img').click(function(){
  console.log(4);
  getQiniuToken();
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
$(".mes_footer, .pc_mes_input").keydown(function(e){
    var e = e || event,
        keycode = e.which || e.keyCode;
    if(e.shiftKey && (e.keyCode==13)){
    } else  if (keycode==13) {
        e.preventDefault();
        var inputValue = $('.pc_mes_input').html();
        $('#mes_textarea').html(inputValue);
        $('.pc_mes_input').html('');
        $("#submit").trigger("click");
    }
});
//对话框的高度
var mesHeight = 0;
//查看更多
$(document).on("click", ".onload", function(){
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
$(document).on('click', '.chat_people', function( e ){
    to_uid = $(this).attr('mes_id');
    to_uid_header_img = $(this).find('img').attr('src');
    //会话id的改变
    session_no = parseInt(to_uid) < parseInt(chat_uid) ? to_uid+"-"+chat_uid : chat_uid+"-"+to_uid;
    mes_type = "message";
    //增加一个会话
    addSession.addSession(to_uid_header_img, to_uid, $(this).attr('group-name'));
    // 提示消息消失
    $('.chatMin').hide();
      $('.chat-container').show();
    //end
    // groupId = $(this).attr('groupId');
    // if (!$(e.target).is('.mes_chakan_close')) {
    $('.mes_title_con').html($(this).attr('group-name'));
      if ($.inArray(session_no, arrMessageList) != -1) {
        var con_mes_num =  parseInt($(".mes_chakan_close[session_no='"+session_no+"']").attr('chat_mes_num'));
        mes_chakan_close('message', to_uid, con_mes_num);

      };
    // };
    ws.send('{"type":"mes_chat", "to_uid":"'+to_uid+'"}');
    $('#mes_load').html(10);
    //消息向上滚动
    $('.he-ov-box').unbind('scroll');
    $('.he-ov-box').bind("scroll", function (){
      mesScroll();
    })
})

//选择群列表显示对话内容
$(document).on('click', '.session_no', function ( event ){
    session_no = $(this).attr('session_no')//会话id
   mes_type = $(this).attr('mestype')//会话id
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
    if ( mes_type == 'groupMessage' ) {
      $('.mes_title_con').append('<i title="群聊添加人" class="add-groupMan"></i>');
      $('.add-groupMan').show();
      groupJson = '{"type":"mes_groupChat", "session_no":"'+session_no+'" }';
    } else if ( mes_type == 'adminMessage' ) {
      groupJson = '{"type":"chatAdmin", "session_no":"ca" }';
    };
    if ($(".mes_chakan_close[session_no='"+session_no+"']").length > 0) {
      var con_mes_num =  parseInt($(".mes_chakan_close[session_no='"+session_no+"']").find('.mes_num').html());
      mes_chakan_close(mes_type, session_no, con_mes_num);
    };
    // };
    ws.send(groupJson);
    $('#mes_load').html(10);
})

//表情的添加
function addempath(className) {
    var emPath = "/chat/emoticons/images/";//表情路径
    var total = 134;//表情的个数
    var newTotal = 14;//新增表情的个数
    $('.'+className).html('');
    for(var i=0; i < newTotal ; i++) {
      $('.'+className).append('<div class="em_gif"><img width="24px" class="cli_em" em_name="'+'f'+i+'" src="'+emPath+'f'+i+'.gif"></div>');
    }
    for(var i=0; i < total ; i++) {
      $('.'+className).append('<div class="em_gif"><img width="24px" class="cli_em" em_name="'+i+'" src="'+emPath+i+'.gif"></div>');
    }
}
//表情的点击事件
$(document).on('click', ".emoticons .cli_em, .pc_emoji_box .cli_em", function ( event ){
    event.stopPropagation();
    var em_name = $(this).attr('em_name');
    inputSave = inputSave + "{|"+em_name+"|}";
    var addThis = $(this).clone();
    addThis.append().appendTo('.textarea');
    var addThis = $(this).clone();
    addThis.append().appendTo('.pc_mes_input');
    // $('textarea').val($('.textarea').html())
})
//表情的显示
$('.header_icon').click(function () {
  addempath('emoticons');
  $(".emoticons").toggle();
  var inputHeight = $('.mes_footer').height();// 输入框的高度
  $(".he-ov-box").css("bottom", inputHeight);

  $(".he-ov-box").scrollTop($(".he-ov-box")[0].scrollHeight);
  $('.plus_menu_box').hide();
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
      if ($('#pc_mes_input').html()  != '') {
        return false;
      };
      //session 改变
      session_no = 0;
      $('.chat-container').hide();
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
    minHeight:inputBottom
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
  if (!$(event.target).is('.pc_mes_tool_emoji')) {
      $('.pc_emoji_box').hide();
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
  //单个消息的关闭
  var mes_chakan_close = function (mestype, session_id, mes_num){
      if ( mestype == 'message' ) {
        session_no = parseInt(chat_uid) < parseInt( session_id ) ? chat_uid+"-"+session_id : session_id+"-"+chat_uid;
      } else if ( mestype == 'groupMessage' ||  mestype == 'adminMessage' )  {
        session_no = session_id;
      }
      ws.send('{"type":"mes_close", "to_uid":"'+session_id+'",  "session_no": "'+session_no+'", "message_type":"'+mestype+'"}');
      mesnum = parseInt(mesnum) - parseInt(mes_num);
      $('.mes_radio').html(mesnum);
      $('.mes_chakan_close[session_no="'+session_no+'"]').remove();
      arrMessageList= _chat_remove(session_no, arrMessageList);
  }
  var mes_NoChakan_close = function (mestype, session_id, mes_num){
      if ( mestype == 'message' ) {
        session_close_no = parseInt(chat_uid) < parseInt( session_id ) ? chat_uid+"-"+session_id : session_id+"-"+chat_uid;
      } else {
        session_close_no = session_id;
      }
      ws.send('{"type":"mes_close", "to_uid":"'+session_id+'",  "session_no": "'+session_close_no+'", "message_type":"'+mestype+'"}');
      mesnum = parseInt(mesnum) - parseInt(mes_num);
      $('.mes_radio').html(mesnum);
      $('.mes_chakan_close[session_no="'+session_close_no+'"]').remove();
      arrMessageList= _chat_remove(session_close_no, arrMessageList);
  }
  // $('.mes_chakan_close').<liv></liv>e('click', function (){
  //   var mestype = $(this).attr('mestype');
  //   var mes_num = $(this).parent().next('.mes_num').html();
  //   session_no = $(this).attr('session_no');
  //   mes_chakan_close(mestype, session_no, mes_num);
  // })
//消息的关闭
$(document).on('click', '.mes_close', function ( e ){
    e = e || window.event;  
     e.stopPropagation();  //阻止冒泡
    var mestype = $(this).attr('mestype');
    var mes_num = $(this).prev('.mes_num').html();
    // session_no = $(this).attr('session_no');
    mes_id = $(this).attr('mes_id');
    mes_NoChakan_close(mestype, mes_id, mes_num);
});
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
          img.className = "send-img";
          $('.sending-img-box').html('');
          $('.sending-img-box').append( img );
          $('.img-box').show();
      };
      reader.readAsDataURL( blob );
    };
    // $('.pc_mes_input').bind('paste', function ( e ) {
    //   $('#mes_textarea').trigger('paste');
    // })
  var pasteEvnet = function ( e ) {
    e.preventDefault();
    var clipboardData = e.clipboardData,
        i = 0,
        items, item, types;
        if (!clipboardData.items) { //chrome  
            insertHtmlAtCaret(clipboardData.getData('text/plain'));
            return false;
        }  
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
            insertHtmlAtCaret(clipboardData.getData('text/plain'));
            inputSave += clipboardData.getData('text/plain');
          };
          if( item && item.kind === 'file' && item.type.match(/^image\//i) ){
            imgReader( item );
          }
          e.preventDefault();
      }
  } 
   document.getElementById( 'mes_textarea' ).addEventListener( 'paste', function( e ){
      pasteEvnet( e );return;
    }) 
    document.getElementById( 'pc_mes_input' ).addEventListener( 'paste', function( e ){
      pasteEvnet( e );return;
    }) 
});
//聊天对话框img放大
$(document).on( 'click', '.he_ov .send-img', function (){
  var curIndex = $(this).attr('index');
  $('.viewer-container').remove();
  $(".loadImg").viewer();
  $(".loadImg img").eq(parseInt(curIndex)).trigger('click');
});
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
//input 的 改变
$('#pc_mes_input, #mes_textarea').on('input', function () {
  var val = $(this).html();
  inputSave = val;
})
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
    onSubmit(to_uid, chat_uid, groupId, mes_type, 'text',session_no);
};
//消息提交
document.getElementsByClassName('send-clipboard-img')[0].onclick = function (){
    //接收人名字
    if (!mesParam.mes_obj()) {
        return false;
    };
    onSubmit(to_uid, chat_uid, groupId, mes_type, 'image',session_no);
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
//七牛发送 ajax

    //普通上传
var Qiniu_upload = function(f, token, key, sendType) {
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
            document.getElementById('key').value = blkRet.key;
            document.getElementById('filename').value = blkRet.fname;
            onSubmit(to_uid, chat_uid, groupId, mes_type, sendType, session_no);
            // console && console.log(blkRet);
            // $("#dialog").html(xhr.responseText).dialog();
        } else if (xhr.status != 200 && xhr.responseText) {
            var blkRet = JSON.parse(xhr.responseText);
            console.log(blkRet);
            alert('发送失败！');
            return false;
        }
    };
    startDate = new Date().getTime();
    $("#progressbar").show();
    xhr.send(formData);
};
 //发送文件
$('#file_zdl').on('change', function (){  
    var nowTime = new Date().getTime();
    var sendType =  'file';
    if (!mesParam.mes_obj()) {
      $(this).val('');
      return false;
    };
    if ($("#file_zdl")[0].files.length > 0 && token != "") {
      for (var i = 0; i < $("#file_zdl")[0].files.length; i++) {
        $key  = $("#file_zdl")[0].files[i].name;
        document.getElementById('filename').value = $key;
        $key ='file/'+chat_uid+'/'+to_uid+'/'+nowTime+'/'+$key;
        document.getElementById('key').value = $key;
        var token = $("#token").val();

        if(/image\/\w+/.test($(this)[0].files[i].type)){  
          sendType = 'images';
        } else {
          sendType = 'file';
        }
        Qiniu_upload($("#file_zdl")[0].files[i], token, $key, sendType);
      };
    }else {
      console && console.log("form input error");
    }
    $(".plus_menu_box").hide();
    return false;
});