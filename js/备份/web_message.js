if (typeof console == "undefined") { this.console = { log: function(msg) {} }; }
WEB_SOCKET_SWF_LOCATION = "/swf/WebSocketMain.swf";
WEB_SOCKET_DEBUG = false;
var ws, client_list = {};
var message_type = '消息';
// 连接服务端
function connect() {
    // 创建websocket
    ws = new WebSocket("ws://" + document.domain + ":7272");
    ws.onopen = onopen;
    // 当有消息时根据消息类型显示不同信息
    ws.onmessage = onmessage;
    ws.onclose = function() {
        console.log("连接关闭，定时重连");
        connect();
    };
    ws.onerror = function() {
        console.log("出现错误");
    };
}
// 连接建立时发送登录信息
function onopen() {
    // 登录
    var login_data = '{"type":"login","oms_id":"' + oms_id + '", "uid": "' + chat_uid + '", "header_img_url":"' + header_img_url + '",  "client_name":"' + chat_name + '","room_id":"' + room_id + '"}';
    ws.send(login_data);
}
// 自己 发的 信息 
var ChatObj = {
    fromMes: function(data) {
        var addVoiceClass = "";
        var addClass = "chat_people";
        var addImgClass = "";
        var addImgAttr = "";
        var content = "";
        if ( data.session_no != session_no ) {
            return false;
        }
        switch (data.mes_types) {
            case 'image':
                var objE = document.createElement("div");　　objE.innerHTML = data.content;　　
                var obj = objE.childNodes;
                var ImgSrc = obj[0].getAttribute('src');
                content = "<img index='" + imgIndex + "' src='" + ImgSrc + "' class='send-img'>";
                addImgClass = 'bigImg';
                $('.loadImg-box .loadImging').append(content);
                imgIndex++;
                break;
            case 'images':
                addImgClass = 'bigImg';
                content = "<img index='" + imgIndex + "' src='http://7xq4o9.com1.z0.glb.clouddn.com/" + data.content + "' class='send-img'>";
                $('.loadImg-box .loadImging').append(content);
                imgIndex++;
                break;
            case 'text':
                content = data.content.replace(/\{\|/g, '<img width="24px" class="cli_em" src="/chat/emoticons/images/');
                content = content.replace(/\|\}/g, '.gif">');
                content = content.replace(/%5C/g, "\\").replace(/\&br\&/g, "<br>");
                break;
            case 'va':
                if ( chat_name == data.from_client_name ) {
                    var vName = '我';
                } else {
                    var vName = data.from_client_name;
                }
                content = "<div style='width:100%;height: 88px;background-color: #fff;color: #000;padding: 10px;'>"+
                    "<div style='width: 100%; height: 43px;border-bottom: 1px solid #ccc;'>"+vName+
                    "开启了群聊视频</div>"+
                    "<div style='width: 100%; height: 43px;text-align: center;line-height: 43px;'>"+
                    "<a href='https://www.omso2o.com/chat/va-chat/vaChat.php?session_id="+data.content+"&Invitation=1' target='_blank' >加入</a></div>"+
                "</div>";
                break;
            case 'file':
                var fileArray = new Array();
                fileArray = data.content.split('|');
                var addShare = '';
                if (webUrl == 'chat_index') {
                    addShare = "<span title='转发' data-placement = '" + data.content + "' onclick='chatShare(this)' class='chat-share'></span>";
                };
                content = "<div class='file-box'><div><i class='icon-folder-open icon-2x'> </i><span>" + fileArray[0] + "</span></div><div class='right'>" + addShare + "<a href='http://7xq4o9.com1.z0.glb.clouddn.com/" + fileArray[1] + "?attname='><i class='icon-cloud-download icon-2x'></i></a></div></div>";
                if ( data.to_uid != to_uid ) {
                    return false;
                };
                break;
        }
        $(".chating-content .he_ov").append('<li class="Chat_ri he ' + addImgClass + '"><div class="user_ri he"><span class="ri head_ri"><span class="header-img"><img src="' + data.header_img_url + '" alt=""></span></span> <span class="ri name_ri"><span style="padding: 0 20px 0 0">' + data.time + '</span>' + chat_name + '</span> <div class="ri content_ri chatMesCon"><span title ="撤销消息" uid="'+to_uid+'" data-man="self" mes_id= "'+data.insert_id+'" class="delChatMes delChatMes_left">&times;</span><span class="arrow ri"></span><span class="content_font_ri">' + content + '</span> </div></div></li>');
        $(".chating-content .he-ov-box").scrollTop($(".chating-content .he-ov-box")[0].scrollHeight);
        $(".chating-content .he_ov .delChatMes").unbind('click');
        $(".chating-content .he_ov .delChatMes").bind('click', function () {
            ChatObj.delChatMes($(this));
        });
    },
    delChatMes: function ( Obj ) {
        var dataMan = Obj.attr('data-man');
        if ( dataMan == "self" ) {
            var $tip = '撤销后不可恢复！你确定吗？';
        } else {
             var $tip = '阅后即焚！你确定吗？';
        }
        if (!confirm($tip)) {
            return false;
        };
        var mesId = Obj.attr('mes_id');
        var uid = Obj.attr('uid');
        Obj.parent().parent().parent().hide(500);
        
        ws.send('{"type": "delChatMes", "mes_id":"'+mesId+'","uid":"'+uid+'","dataMan": "'+dataMan+'"}');
    }
};
// 消息的 点击事件
// 服务端发来消息时
function onmessage(e) {
    // console.log(e.data);
    var data = eval("(" + e.data + ")");
    switch (data['type']) {
        // 服务端ping客户端
    case 'ping':
        ws.send('{"type":"pong"}');
        break;
        // 登录 更新用户列表
    case 'login':
        if (data['client_list']) {
            client_list = data['client_list'];
        } else {
            client_list[data['uid']] = data['client_name'];
        }
        onlineManInfo = data['client_list'];

        var online = 0;
        for (var i in data['client_list']) {
            //添加人员名字到在线人员名字
            online++;
        }
        $('.online_ren').html(online);
        setTimeout('flush_onlineman_list()', 1000);
        // flush_onlineman_list();
        // console.log(data['client_name']+"登录成功");
        break;
    case 'say_uid':
        sayUid(data['mes_types'], data['mestype'], data['header_img_url'], data['group_name'], data['insert_id'], data['session_no'], data['from_uid_id'], data['to_uid_id'], data['from_client_id'], data['from_client_name'], data['content'], data['time']);
        break;
    case 'va_say_uid':
        sayUid(data['mes_types'], data['mestype'], data['header_img_url'], data['group_name'], data['insert_id'], data['session_no'], data['from_uid_id'], data['to_uid_id'], data['from_client_id'], data['from_client_name'], data['content'], data['time']);
         if ( typeof audio != 'undefined' ) {
             audio.pause();
        };
        // 音频和视频的 view 取消；
        $('.vaChatRequest').remove();
        $('.vaChat').remove();
        break;
        //审核消息
    case 'showGroupMan':
        var Callback = data['Callback'];
        delete data['type'];
        showGroupMan.data = data;
        showGroupMan[Callback]();
        break;
    case 'audit':
        audit(data['session_no'], data['from_client_id'], data['from_client_name'], data['content'], data['message_url'], data['time']);
        // 用户退出 更新用户列表
        break;
    case 'mes_chat':
        mesShow.data = data;
        mesShow.mes_chat();
        // mes_chat(data);
        break;
        // 语音 请求
    case 'va':
        // console.log(data);
        vaChatRequest( data );
        break;
    // 语音 取消
    case 'vaCancel':
        ChatObj.fromMes(data);
        break;
    case 'vaAnswer':
        $('.vaChat').remove();
        if ( data.act == 'vM' ) {
            return false;
        };
        // window.location.href="https://www.omso2o.com/chat/va-chat/vaChat.php?session_id="+data.session_id+"&Invitation=1";
        window.location.href="https://www.omso2o.com/chat/va-chat/vaChat.php?session_id="+data.session_id+"&Invitation=1";
        //window.open("https://www.omso2o.com/chat/va-chat/vaChat.php?session_id="+data.session_id+"&Invitation=1");
        break;
        // 取消视频和语音；
    case 'cancelVa':
        if ( typeof audio != 'undefined' ) {
            audio.pause();
        };
    $('.vaChatRequest').remove();
    break;
        //消息的加载
    case 'onlode':
        mesShow.data = data;
        console.log(data);
        mesShow.onlode();
        // onlode(data);
        break;
    case 'chain_staff_list':
        var staff_list = data['staff_list'];
        var tagFriend = 0,
        addClass, addClassSession, iconcheage;
        $('.chainEmployees ul.chat_on_all').html('');
        for (var i in staff_list) {
            tagFriend = 0;
            addClass = '';
            iconcheage = '&#xe608;';
            addClassSession = 'apply_session';
            for (var y in friendList) {
                if (friendList[y].staffid == i) {
                    tagFriend = 1;
                    addClass = 'external_chat_people';
                    iconcheage = "&#xe603;";
                    addClassSession = addClass;
                }
            }
            $('.chainEmployees ul.chat_on_all').append('<li tagFriend = "' + tagFriend + '" class="chain_friend_all ' + addClass + '" mes_id = ' + i + ' org_name = "' + $org_name + '" group-name = "' + staff_list[i].client_name + '"><span class="externalStaffid-header-img"><img src="' + staff_list[i].header_img_url + '" alt="' + staff_list[i].client_name + '" /></span>' + staff_list[i].client_name + '<span class="icon16 ' + addClassSession + '" name = "' + staff_list[i].client_name + '" tagFriend = "' + tagFriend + '" mes_id = ' + i + ' group-name = "' + staff_list[i].client_name + '"  title= "会话">' + iconcheage + '</span></li>');
        }
        break;
    case 'mes_notice_close':
        $('.chat_notice_container').show(500);
        $('.chat_notice_list_box').html('');
        for (var i in data) {
            if (i == "type") {
                return;
            };
            $('.chat_notice_list_box').append('<div class="chat_notice_list"><div class="chat_notice_img"><img src="' + data[i].pid_header_url + '" alt=""></div><div class="chat_notice_con"><span>' + data[i].pid_name + '【请求对话】</span><span>公司：' + data[i].additional_Information + '</span></div></div><div data-parm = "agree" sender-id = "' + data[i].pid + '" class="chat_notice_agree chat_notice_sel">同意</div><div data-parm = "unagree" class="chat_notice_unagree chat_notice_sel" sender-id = "' + data[i].pid + '">不同意</div>');
        }
        break;
    case 'sysNotice':
        sysNotice(data);
        break;
    case 'resSayUid':
        ChatObj.fromMes(data);
        // resSayUid();
        break;
    // 显示搜索到的 好友
    case 'searchFriends':
        searchFriends(data);
        break;
    case 'logout':
        delete client_list[data['from_uid_id']];
        flush_onlineman_list();
        break;
    default:
        return;

    }
}
// 提交消息
/* 
    * content  消息内容
    * to_uid  消息接受人 要是为多个 to_uid 用逗号连接
    * senderid 发送人的id 
    * message_type 消息类型
    */
function onSubmit_messages(content, to_uid, senderid, message_type) {
    var to_client_name = '预留name';
    ws.send('{"type":"sayUid","to_uid":[' + to_uid + '],"senderid":"' + senderid + '", "message_type":"' + message_type + '",  "to_client_name":"' + to_client_name + '","content":"' + content + '"}');
}
// 对Date的扩展，将 Date 转化为指定格式的String
// 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符， 
// 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字) 
// 例子： 
// (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423 
// (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18 
Date.prototype.format = function(format) {
    var o = {
        "M+": this.getMonth() + 1,
        //month
        "d+": this.getDate(),
        //day
        "h+": this.getHours(),
        //hour
        "m+": this.getMinutes(),
        //minute
        "s+": this.getSeconds(),
        //second
        "q+": Math.floor((this.getMonth() + 3) / 3),
        //quarter
        "S": this.getMilliseconds() //millisecond
    }
    if (/(y+)/.test(format)) format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o) if (new RegExp("(" + k + ")").test(format)) format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));
    return format;
}

//  提交会话
function onSubmit(to_uid, chat_uid, groupId, message_type, mes_types, from_session_no) {
    var nowTime = new Date().format('yyyy-MM-dd hh:mm:ss');
    var accept_name = $('.chating-content .mes_title_con').text();
    // 要 显示 的内容
    var inputcur = "";
    //要发送的内容
    var inputValue = "";
    var input = "";
    var addImgClass = "";
    var addImgAttr = "";
    switch (mes_types) {
    case 'text':
        input = document.getElementById("mes_textarea");
        //里边 有 @
        if ( $(input).find('.given').length > 0 ) {
            $(input).find('.given').each(function (i, o) {
                var name = $(input).find('.given').attr('name');
                var staffid = $(input).find('.given').attr('staffid');
                var res = '{@'+name+'|'+staffid+'@}';
                $(this).after(res);
                $(this).remove();
            })
        };
        inputValue = input.innerHTML;

        inputValue = inputValue.replace(/<img([^>].*?)em_name=\"/ig, '{|').replace(/\"([^<].*?)[>?]/ig, '|}').replace(/<br>/g, '&br&').replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/\"/g, '&quot;').replace(/[\r\n]/g, "").replace(/[\\]/g, "%5C");
        inputcur = input.innerHTML;
        break;
    case 'image':
        addImgAttr = ' href = "' + $('.sending-img-box .send-img').attr('src') + '" data-size="1600x1068" data-med="' + $('.sending-img-box .send-img').attr('src') + '" data-med-size="1024x683" data-author=""';
        addImgClass = 'bigImg';

        inputcur = "<img index='" + imgIndex + "' src='" + $('.sending-img-box .send-img').attr('src') + "' class='send-img'>";
        $('.loadImg-box .loadImging').append(inputcur);
        inputValue = $('.sending-img-box .send-img').attr('src');
        $('.img-box').hide();
        imgIndex++;
        break;
    case 'images':
        var ImgSrc = document.getElementById('key').value;
        addImgAttr = ' href = "http://7xq4o9.com1.z0.glb.clouddn.com/' + ImgSrc + '" data-size="1600x1068" data-med="http://7xq4o9.com1.z0.glb.clouddn.com/' + ImgSrc + '" data-med-size="1024x683" data-author=""';
        addImgClass = 'bigImg';
        inputcur = "<img index='" + imgIndex + "' src='http://7xq4o9.com1.z0.glb.clouddn.com/" + ImgSrc + "' class='send-img'>";
        $('.loadImg-box .loadImging').append(inputcur);
        inputValue = ImgSrc;
        $('.img-box').hide();
        imgIndex++;
        break;
    case 'file':
        var fileName = document.getElementById('filename').value;
        var fileUrl = document.getElementById('key').value;
        inputcur = "<div class='file-box'><div><i class='icon-folder-open icon-2x'> </i><span>" + fileName + "</span></div><div class='right'><a href='http://7xq4o9.com1.z0.glb.clouddn.com/" + fileUrl + "?attname='><i class='icon-cloud-download icon-2x'></i></a></div></div>";
        inputValue = fileName + "|" + fileUrl;
        break;
    case 'voice':
        inputValue = $('.chat_voice_box').attr('voice_url'); 
        ws.send('{"type":"FUid","to_uid":"' + to_uid + '","senderid":"' + chat_uid + '", "groupId":"' + groupId + '", "accept_name":"' + accept_name + '","message_type":"' + message_type + '", "mes_types":"' + mes_types + '","session_no":"' + from_session_no + '","content":"' + inputValue + '"}');
        $(".he-ov-box").scrollTop($(".he-ov-box")[0].scrollHeight);
        return;

    }
    ws.send('{"type":"sayUid","to_uid":"' + to_uid + '","senderid":"' + chat_uid + '", "groupId":"' + groupId + '", "accept_name":"' + accept_name + '","message_type":"' + message_type + '", "mes_types":"' + mes_types + '","session_no":"' + from_session_no + '","content":"' + inputValue + '"}');
    document.getElementById("mes_textarea").innerHTML = "";
    inputSave = '';
    $("#mes_textarea").height(50);
    $(".chating-content .he-ov-box").css("bottom", inputBottom);
    $('.chating-content .emoticons').hide();
    document.getElementById("mes_textarea").focus();
}
//显示系统消息
function sysNotice(data) {
    $('.chat_notice_box').remove();
    var noticeOut = $('<div class="chat_notice_box"></div>');
    var noticeInner = $('<div class="chat_message_notice"></div>');
    noticeOut.append('<i class= "chat_close">&times;</i>').append(noticeInner);
    var a = 0;
    $.each(data,function(i, o) {
        if (typeof o == 'object') {
            noticeInner.append('<div class="chat_message_notice_con">' + o.sender_name + o.message_content + '</div>');
            a = 1;
        };
    })
    if (a == 0) {
        noticeInner.append('<div class="chat_message_notice_con">没有系统通知！</div>');
    };
    $('body').append(noticeOut);
    $('.chat_notice_box').show(500);
}
//显示群聊人
var showGroupMan = {
    data: {},
    showGroupMans_list: function () {

        var addClass = '';
        if ( this.data != '' ) {
            var data = this.data;
            if (data[0]['group_founder'] == chat_uid) {
                addClass = 'group-people';
            };
            delete data['Callback'];
            for (i in data) {
                $('.groupAct').parents('.panel').find('.list-group').append('<li class="db_chat_people chat_people ' + addClass + '" group-name="' + data[i]['name'] + '" groupId="' + data[i]['pid'] + '" mes_id="' + data[i]['staffid'] + '"><span class="header-img"><img src="' + data[i]['card_image'] + '" alt="' + data[i]['name'] + '"></span><i>' + data[i]['name'] + '</i><span class="delgroupman" groupId="' + data[i]['pid'] + '" id="' + data[i]['staffid'] + '">&times;</span></li>')

            };
            var RightClick = $('.groupRightClick li');
            RightClick.bind('contextmenu',function(e) { 
                return false; 
            }); 
            RightClick.mousedown(function (e) {
                e.preventDefault();
                e.stopPropagation();
                if ( e.which == 3 ) {
                    var name = $(this).attr('group-name');
                    var mesId = $(this).attr('mes_id');
                    $('.groupMenu').remove();
                    var _x = e.pageX;
                    var _y = e.pageY;
                    var menuBox = $('<div class ="groupMenu"></div>');
                    var menuBoxUl = $('<ul></ul>');
                    var menuBoxLi = $('<li onmousedown="return false" ontouchstart="return false" name="'+name+'" staffid="'+mesId+'">@Ta</li>');
                    menuBox.css({'position': 'absolute', 'width': '85px', 'top': _y+2, 'left': _x+3, 'background-color': '#fff','border': '1px solid #ccc', 'box-shadow': '0 0 12px #ccc', 'padding': '5px', 'cursor': 'pointer'});
                    menuBoxLi.click(function () {
                        var staffid = $(this).attr('staffid');
                        var name = $(this).attr('name');
                        var img = $(textToImg('@'+name));
                        img.addClass('given');
                        img.attr('staffid', staffid);
                        img.attr('name', name);
                        insertHtmlAtCaret(img[0].outerHTML, false);
                    })
                    menuBoxUl.append(menuBoxLi).appendTo(menuBox);
                    $('body').append(menuBox);
                };
                return false;
            })
            
        };
    },
    showGroupMan_dialogue: function () {

    },
}
// 文字 生成图片

function textToImg( text,fontSize, fontWeight ) {

    var txt = text;
    var len = txt.length;
    var i = 0;
    var fontSize = fontSize || 15;
    var fontWeight = fontWeight || 'normal';
    var fillStyle = '#1F5DEC';
    var canvas = document.createElement('canvas');
    canvas.width = fontSize * len;
    canvas.height = fontSize * (3 / 2)
            * (Math.ceil(txt.length / len) + txt.split('\n').length - 1);
    var context = canvas.getContext('2d');
    context.clearRect(0, 0, canvas.width, canvas.height);
    context.fillStyle = fillStyle;
    context.font = fontWeight + ' ' + fontSize + 'px sans-serif';
    context.textBaseline = 'top';
    canvas.style.display = 'none';
    function fillTxt(text) {
        while (text.length > len) {
            var txtLine = text.substring(0, len);
            text = text.substring(len);
            context.fillText(txtLine, 0, fontSize * (3 / 2) * i++,
                    canvas.width);
        }
        context.fillText(text, 0, fontSize * (3 / 2) * i, canvas.width);
    }
    var txtArray = txt.split('\n');
    for ( var j = 0; j < txtArray.length; j++ ) {
        fillTxt(txtArray[j]);
        context.fillText('\n', 0, fontSize * (3 / 2) * i++, canvas.width);
    }
    var imageData = context.getImageData(0, 0, canvas.width, canvas.height);
    var img = document.createElement('img');
    img.src = canvas.toDataURL("image/png");
    return img;
    // $('#pc_mes_input').append($(img));
}
// 刷新在线人数
function flush_onlineman_list() {
    var onlineman_ul = $(".online_man ul.list-group");
    var online_ren = 0;
    onlineman_ul.empty();
    onlineman_ul.append('<li class="chat_system_notice" onclick="chatSystemNotice()" data-placement="right" group-name=""><span class="header-img"><img src="/chat/images/chatNotice.png" alt=""></span>系统通知</li>');
    for (var p in client_list) {
        online_ren++;
        onlineman_ul.append('<li mes_id="' + p + '" data-placement="left" class="staff-info chat_people db_chat_people" group-name="' + client_list[p].client_name + '"><span class="header-img"><img src="' + client_list[p].header_img_url + '" alt="' + client_list[p].client_name + '"></span>' + client_list[p].client_name + '</li>');
    }
    $('.online_ren').html(online_ren);
}
//发言2
//通知消息
var ChatOptions = {
    tittle: '',
    content: '',
    imgUrl: '',
    sound: 'true',
    soundUrl: '/chat/audio/notice.wav',
};
// 接受消息
function sayUid(image, mestype, header_img_url, group_name, insert_id, from_session_no, from_uid_id, to_uid_id, from_client_id, from_client_name, content, time) {
    var content1;
    var content2 = "";
    var addVoiceClass = "";
    var addClass = "chat_people";
    var addImgClass = "";
    var addImgAttr = "";
    switch (image) {
    case 'image':
        var objE = document.createElement("div");　　objE.innerHTML = content;　　
        var obj = objE.childNodes;
        var ImgSrc = obj[0].getAttribute('src');
        content = "<img index='" + imgIndex + "' src='" + ImgSrc + "' class='send-img'>";
        addImgAttr = ' href = "' + ImgSrc + '" data-size="1600x1068" data-med="' + ImgSrc + '" data-med-size="1024x683" data-author=""';
        addImgClass = 'bigImg';
        content1 = '【图片】';
        $('.loadImg-box .loadImging').append(content);
        imgIndex++;
        break;
    case 'images':
        content1 = '【图片】';
        addImgAttr = ' href = "http://7xq4o9.com1.z0.glb.clouddn.com/' + content + '" data-size="1600x1068" data-med="http://7xq4o9.com1.z0.glb.clouddn.com/' + content + '" data-med-size="1024x683" data-author=""';
        addImgClass = 'bigImg';
        content = "<img index='" + imgIndex + "' src='http://7xq4o9.com1.z0.glb.clouddn.com/" + content + "' class='send-img'>";
        $('.loadImg-box .loadImging').append(content);
        imgIndex++;
        break;
    case 'text':
        content = content.replace(/\{\|/g, '<img width="24px" class="cli_em" src="/chat/emoticons/images/');
        content = content.replace(/\|\}/g, '.gif">');
        content1 = content.replace(/%5C/g, "\\").replace(/\&b\r&/g, " ");
        content = content.replace(/%5C/g, "\\").replace(/\&br\&/g, "<br>");
        break;
    case 'va':
        content1 = "开启了群聊视频";
        if ( chat_name == from_client_name ) {
            var vName = '我';
        } else {
            var vName = from_client_name;
        }
        content = "<div style='width:100%;height: 88px;background-color: #fff;color: #000;padding: 10px;'>"+
                    "<div style='width: 100%; height: 43px;border-bottom: 1px solid #ccc;'>"+vName+
                    "开启了群聊视频</div>"+
                    "<div style='width: 100%; height: 43px;text-align: center;line-height: 43px;'>"+
                    "<a href='https://www.omso2o.com/chat/va-chat/vaChat.php?session_id="+content+"&Invitation=1'  target='_blank' >加入</a></div>"+
                "</div>";
        break;
    case 'file':
        content1 = '【文 件 】';
        var fileArray = new Array();
        fileArray = content.split('|');
        var addShare = '';
        if (webUrl == 'chat_index') {
            addShare = "<span title='转发' data-placement = '" + content + "' onclick='chatShare(this)' class='chat-share'></span>";
        };
        content = "<div class='file-box'><div><i class='icon-folder-open icon-2x'> </i><span>" + fileArray[0] + "</span></div><div class='right'>" + addShare + "<a href='http://7xq4o9.com1.z0.glb.clouddn.com/" + fileArray[1] + "?attname='><i class='icon-cloud-download icon-2x'></i></a></div></div>";
        break;
    case 'voice':
        var voiceArray = new Array();
        voiceArray = content.split('|');
        content1 = "【 语音 】";
        content = '<div class="he_ov_mes_audio web_voice web_chat_voice_left_play"  web_voice_data = "left" web_voice = "' + voiceArray[0] + '"></div>';
        content2 = '<span class="chat_duration_left">' + voiceArray[1] + '\"</span';
        addVoiceClass = "web_chat_voice";
        break;
    case 'notice':
        content1 = content;
        header_img_url = "/chat/images/chatNotice.png";

        if ($.inArray(from_session_no, arrMessageList) != -1) {

            var curmesnum = parseInt($(".mes_chakan_close[session_no='" + from_session_no + "']").find('.mes_num').html()) + 1;
            $(".mes_chakan_close[session_no='" + from_session_no + "']").attr('chat_mes_num', curmesnum).find('.mex_con').html(from_client_name + '【请求会话】');
            $(".mes_chakan_close[session_no='" + from_session_no + "']").find('.mes_num').html(curmesnum);

        } else {

            $(".mes_con").append('<div class="mes_box mes_chakan_close chat_notice" chat_mes_num="1" oms_id= "' + insert_id + '"  mestype="' + mestype + '"  group-name="' + from_client_name + '" mes_id="' + from_uid_id + '" session_no="' + from_session_no + '"><div class= "mes_header"><img src="' + header_img_url + '" alt="' + from_client_name + '" /></div><span class="mex_con">' + from_client_name + '【 请求会话 】</span><div class="mes_content_list" style=""><span class="chat_mes_content">' + content1 + '</span></div><span class="mes_num">1</span><span session_no="' + from_session_no + '" mes_id="' + from_uid_id + '"  mestype="' + mestype + '" group-name="' + from_client_name + '" class="mes_close">X</span></div>');

        }

        mesnum++;
        // console.log(mesnum);
        $('.mes_radio').html(mesnum);
        arrMessageList.push(from_session_no);
        return;
    case 'notice_respond':
        header_img_url = "/chat/images/chatNotice.png";
        content1 = "说点什么吧";
        header_img_url = "/chat/images/chatNotice.png";

        if ($.inArray(from_session_no, arrMessageList) != -1) {

            var curmesnum = parseInt($(".mes_chakan_close[session_no='" + from_session_no + "']").find('.mes_num').html()) + 1;
            $(".mes_chakan_close[session_no='" + from_session_no + "']").attr('chat_mes_num', curmesnum).find('.mex_con').html('你与' + from_client_name + '可以会话了');
            $(".mes_chakan_close[session_no='" + from_session_no + "']").find('.mes_num').html(curmesnum);

        } else {

            $(".mes_con").append('<div class="mes_box mes_chakan_close ' + addClass + '" chat_mes_num="1"  mestype="' + mestype + '"  group-name="' + from_client_name + '" mes_id="' + from_uid_id + '" session_no="' + from_session_no + '"><div class= "mes_header"><img src="' + header_img_url + '" alt="' + from_client_name + '" /></div><span class="mex_con">你与' + from_client_name + '可以会话了</span><div class="mes_content_list" style=""><span class="chat_mes_content">' + content1 + '</span></div><span class="mes_num">1</span><span session_no="' + from_session_no + '" mes_id="' + from_uid_id + '"  mestype="' + mestype + '" group-name="' + from_client_name + '" class="mes_close">X</span></div>');

        }
        mesnum++;
        // console.log(mesnum);
        $('.mes_radio').html(mesnum);
        arrMessageList.push(from_session_no);
        return;
    }

    // 发消息者和正在聊天不同
    if (mestype != "message") {
        from_uid_id = from_session_no;
    }
    if ($.inArray(from_uid_id, addSession.sessionList) == -1) {
        ChatOptions.tittle = from_client_name;
        ChatOptions.content = content1;
        ChatOptions.imgUrl = header_img_url;
        // $('#mesNotice').chatNotice( options );
        if (localStorage.getItem('desktopState') == '0') {
            showMsgNotification(from_client_name, content1, header_img_url);
        };
        $('.mesNoticeContainer').notify(ChatOptions);
        //end
        if (mestype != "message") {
            from_client_name = group_name;
            addClass = "session_no";
            header_img_url = '/chat/images/rens.png';
        }
        if ($.inArray(from_session_no, arrMessageList) != -1) {
            var curmesnum = parseInt($(".mes_chakan_close[session_no='" + from_session_no + "']").find('.mes_num').html()) + 1;
            $(".mes_chakan_close[session_no='" + from_session_no + "']").attr('chat_mes_num', curmesnum).find('.mex_con').html(from_client_name);
            $(".mes_chakan_close[session_no='" + from_session_no + "']").find('.mes_num').html(curmesnum);
            $(".mes_chakan_close[session_no='" + from_session_no + "']").find('.chat_mes_content').html(content1);
        } else {
            $(".mes_con").append('<div class="mes_box mes_chakan_close ' + addClass + '" chat_mes_num="1"  mestype="' + mestype + '"  group-name="' + from_client_name + '" mes_id="' + from_uid_id + '" session_no="' + from_session_no + '"><div class= "mes_header"><img src="' + header_img_url + '" alt="' + from_client_name + '" /></div><span class="mex_con">' + from_client_name + '</span><div class="mes_content_list" style=""><span class="chat_mes_content">' + content1 + '</span></div><span class="mes_num">1</span><span session_no="' + from_session_no + '" mes_id="' + from_uid_id + '"  mestype="' + mestype + '" group-name="' + from_client_name + '" class="mes_close">X</span></div>');
        }
        mesnum++;
        // console.log(mesnum);
        $('.mes_radio').html(mesnum);
        arrMessageList.push(from_session_no);
    };
    // 发消息者和正在聊天
    if ($.inArray(from_uid_id, addSession.sessionList) != -1) {
        // 找出 和谁在聊天
        if (IsPC() == true) {
            var _index = addSession.mesnum(from_uid_id);
            var tabObj = $('.chat-tab-content').eq(_index + 1).find('.he_ov');
        } else {
            var tabObj = $('.mb-chat-tab-content .he_ov');
        }
        // 消息提示
        $('.chatMin').css('background-color', '#BD8246');

        tabObj.append('<li ' + addImgAttr + ' class="Chat_le ' + addImgClass + '"><div class="user"><span class="head le"><span class="header-img"><img src="' + header_img_url + '" alt=""></span></span> <span class="name le">' + from_client_name + '<span style="padding: 0 0 0 20px">' + time + '</span></span><div class="mes_content le"><span class="jian le"></span> <span class="content-font ' + addVoiceClass + ' le">' + content + '</span>' + content2 + '</div></div></li>');
        // 发来的人 和正在聊天的人 相同 
        if (session_no == from_session_no) {
            $(".chating-content .he-ov-box").scrollTop($(".chating-content .he-ov-box")[0].scrollHeight);
        }
        if ($(".mes_chakan_close[session_no='" + session_no + "']").length > 0) {
            mes_num = parseInt($(".mes_chakan_close[session_no='" + session_no + "']").parent().next('.mes_num').html());
            sendMes_num = mes_num;

        } else {
            sendMes_num = 0;
        }
        if (mestype != "message") {
            mes_chakan_close(mestype, session_no, 0);
        } else {
            mes_chakan_close(mestype, from_uid_id, 0);
        }
    };
}

// 语音请求
 function vaChatRequest( data ) {
    if ( $('.vaChatRequest').length > 0  ) {
        return false;
    };
    if (window.HTMLAudioElement) {
        audio = new Audio();
        audio.src = '/chat/audio/vaWait.WAV';
        audio.loop="loop";
        audio.play();
    }
    $anSwerStyle = '';
    $session_id = '';
    if  ( data.act == 'a' ) {
        var text = '邀请你语音通话';
    } else if ( data.act == 'v' ) { 
        var text = '邀请你视频通话';
    } else if ( data.act == 'vM' ) {
        if ( data.from_uid == chat_uid ) {
            $session_id = data.session_id;
            data.client_name = '';
            var text = '加入多人视频通话';
            $anSwerStyle = 'style="width: 80px;margin:auto;padding: 0"';
            audio.pause();
        } else {
            var text = '发起多人视频通话';
        }
    };
    var vaQuest = $('<div class="vaChatRequest"><div class="vaRequestName">'+data.client_name+text+'</div></div>');
    var handleBox = $('<div class="handleBox" '+$anSwerStyle+'></div>');
    var Answer = $('<span from-uid = "'+data.from_uid+'" session_id="'+data.session_id+'" act="'+data.act+'" client_id="'+data.client_id+'" class="vaAnswer">接听</span>').click(function () {
        var client_id = $(this).attr('client_id');
        var act = $(this).attr('act');
        if ( typeof audio != 'undefined' ) {
            audio.pause();
        };
        if ( act == 'vM' ) {
            var session_id = $(this).attr('session_id');
            ws.send('{"type": "vaAnswer", "client_id": "'+client_id+'", "act": "'+act+'", "session_id": "'+session_id+'"}');
            window.location.href="https://www.omso2o.com/chat/va-chat/vaChat.php?session_id="+session_id+"&Invitation=1";
            //window.open("https://www.omso2o.com/chat/va-chat/vaChat.php?session_id="+session_id+"&Invitation=1");
            return false;
        };
        $.ajax({
            url: '/chat/va-chat/ajax_getToken.php',
            data: 'createSession=1',
            type: 'post',
            success: function (data) {

                ws.send('{"type": "vaAnswer", "client_id": "'+client_id+'", "act": "'+act+'", "session_id": "'+data+'"}');
                // window.location.href="https://www.omso2o.com/chat/va-chat/vaChat.php?session_id="+data+"&Invitation=1";
                window.location.href="https://www.omso2o.com/chat/va-chat/vaChat.php?session_id="+data+"&Invitation=1";

                //window.open("https://www.omso2o.com/chat/va-chat/vaChat.php?session_id="+data[1].session_id+"&Invitation=1");
            },

        })
    });
    if ( data.from_uid == chat_uid ) {
        var cancel = "";
    } else {
        var cancel = $('<span from-uid = "'+data.from_uid+'" act="'+data.act+'" class="vaCancel">取消</span>').click(function () {
            if ( typeof audio != 'undefined' ) {
                audio.pause();
            };
            var t_to_uid = $(this).attr('from-uid');
            var act = $(this).attr('act');
            var content = "视频取消";
            if ( act == 'a' ) {
                content = "语音取消";
            };
            ws.send('{"type":"vaChat","to_uid":"'+t_to_uid+'","senderid":"'+chat_uid+'", "groupId":"0", "accept_name":"'+ $('.chating-content .mes_title_con').text()+'","message_type":"message", "mes_types":"text","session_no":"'+session_no+'","content":"'+content+'"}');
            $('.vaChatRequest').remove();
        });
    }
    handleBox.append(Answer).append(cancel);
    vaQuest.append(handleBox);
    $('body').append(vaQuest);
 }
//  聊天消息显示
var messageShow = function(data) { 
    var mes_time;
    var content2 = "";
    var addVoiceClass = "";
    var addImgClass = "";
    var addImgAttr = "";
    var content = "";
    for (var i in data) {
        if (i == 'type') {
            break;
        };
        // console.log(data);
        mes_time = data[i].create_time;
        switch (data[i].mesages_types) {
        case 'text':
            content = data[i].message_content.replace(/\{\|/g, '<img width="24px" class="cli_em" src="/chat/emoticons/images/');
            content = content.replace(/\|\}/g, '.gif">');
            content = content.replace(/%5C/g, "\\").replace(/\&br\&/g, "<br/>");
            break;
        case 'va':

            if ( chat_name == data[i].sender_name ) {
                var vName = '我';
            } else {
                var vName = data[i].sender_name;
            }
            content = "<div style='width:100%;height: 88px;background-color: #fff;color: #000;padding: 10px;'>"+
                    "<div style='width: 100%; height: 43px;border-bottom: 1px solid #ccc;'>"+vName+
                    "开启了群聊视频</div>"+
                    "<div style='width: 100%; height: 43px;text-align: center;line-height: 43px;'>"+
                    "<a href='https://www.omso2o.com/chat/va-chat/vaChat.php?session_id="+data[i].message_content+"&Invitation=1'  target='_blank' >加入</a></div>"+
                "</div>";
            break;
        case 'file':
            var fileArray = new Array();
            var addShare = '';
            fileArray = data[i].message_content.split('|');
            if (webUrl == 'chat_index') {

                addShare = "<span title='分享给别人' data-placement = '" + data[i].message_content + "' onclick='chatShare(this)' class='chat-share'></span>";
            };
            content = "<div class='file-box'><div><i class='icon-folder-open icon-2x'> </i><span>" + fileArray[0] + "</span></div><div class='right'>  " + addShare + "<a href='http://7xq4o9.com1.z0.glb.clouddn.com/" + fileArray[1] + "?attname='><i class='icon-cloud-download icon-2x'></i></a></div></div>";
            break;
        case 'image':
            content = data[i].message_content;
            var objE = document.createElement("div");　　objE.innerHTML = content;　　
            var obj = objE.childNodes;
            var ImgSrc = obj[0].getAttribute('src');
            content = "<img index='" + imgIndex + "' src='" + ImgSrc + "' class='send-img'>";
            addImgAttr = ' href = "' + ImgSrc + '" data-size="1600x1068" data-med="' + ImgSrc + '" data-med-size="1024x683" data-author=""';
            addImgClass = 'bigImg';
            $('.loadImg-box .loadImging').append(content);
            imgIndex++;
            break;
        case 'images':
            addImgAttr = ' href = "http://7xq4o9.com1.z0.glb.clouddn.com/' + data[i].message_content + '" data-size="1600x1068" data-med="http://7xq4o9.com1.z0.glb.clouddn.com/' + data[i].message_content + '" data-med-size="1024x683" data-author=""';
            addImgClass = 'bigImg';
            content = "<img index='" + imgIndex + "' src='http://7xq4o9.com1.z0.glb.clouddn.com/" + data[i].message_content + "' class='send-img'>";
            $('#chat-session-img').append("<li><img src='http://7xq4o9.com1.z0.glb.clouddn.com/" + data[i].message_content + "' ></li>");
            $('.loadImg-box .loadImging').append(content);
            imgIndex++;
            break;
        case 'voice':
            var voiceArray = new Array();
            voiceArray = data[i].message_content.split('|');
            if (data[i].sender_id == chat_uid) {
                content = '<div class="he_ov_mes_audio web_voice web_chat_voice_right_play" web_voice_data = "right" web_voice = "' + voiceArray[0] + '"></div>';
                content2 = '<span class="chat_duration_right">' + voiceArray[1] + '\"</span';
            } else {
                content = '<div class="he_ov_mes_audio web_voice web_chat_voice_left_play" web_voice_data = "left" web_voice = "' + voiceArray[0] + '"></div>';
                content2 = '<span class="chat_duration_left">' + voiceArray[1] + '\"</span';
            }
            voiceArray = data[i].message_content.split('|');
            addVoiceClass = "web_chat_voice";
            break;
        case 'revoke':
            $(".chating-content .he_ov").prepend('<div style="text-align:center;margin:10px 0;color:#ccc;">'+data[i].sender_name+'撤销了一条信息</div>');
            // if ( data[i].delState == data[i].sender_id ) {
            // } else {
            //     $(".chating-content .he_ov").prepend('<div style="text-align:center;margin:10px 0;color:#ccc;">'+data[i].accept_name+'撤销了一条信息</div>');
            // }
            continue;
            // return;
        default:
            break;
        }
        if (data[i].sender_id == chat_uid) {
            $(".chating-content .he_ov").prepend('<li ' + addImgAttr + ' class="Chat_ri he ' + addImgClass + '"><div class="user_ri he"><span class="ri head_ri"><span class="header-img"><img src="' + header_img_url + '" alt=""></span></span> <span class="ri name_ri"><span style="padding: 0 20px 0 0">' + mes_time + '</span>' + chat_name + '</span> <div class="ri content_ri chatMesCon"><span mes_id= "'+data[i].id+'" title ="撤销消息" uid = "'+to_uid+'" data-man="self" class="delChatMes delChatMes_left">&times;</span><span class="arrow ri"></span><span class="content_font_ri ' + addVoiceClass + '">' + content + '</span> ' + content2 + ' </div></div></li>');
        } else {
            $(".chating-content .he_ov").prepend('<li ' + addImgAttr + ' class="Chat_le ' + addImgClass + '"><div class="user"><span class="head le"><span class="header-img"><img src="' + data[i].card_image + '" alt=""></span></span> <span class="name le">' + data[i].sender_name + '<span style="padding: 0 0 0 20px">' + mes_time + '</span></span><div class="mes_content le chatMesCon"><span mes_id= "'+data[i].id+'" title ="撤销消息" uid = "'+to_uid+'" data-man="other" class="delChatMes delChatMes_right">&times;</span><span class="jian le"></span> <span class="content-font ' + addVoiceClass + ' le">' + content + '</span>' + content2 + '</div></div></li>');
        }
        addVoiceClass = "";
        addImgClass = "";
        addImgAttr = "";
        content2 = "";
        content = "";
    }
    $(".chating-content .he_ov .delChatMes").unbind('click');
    $(".chating-content .he_ov .delChatMes").bind('click', function () {
        ChatObj.delChatMes($(this));
    });
    // initPhotoSwipeFromDOM('.session-box');
}
// 查找好友 显示
var searchFriends = function (data) {
    var html = '';
    $('.friendItem-box').html('');
    $.each(data, function (i,o) {
        html =  '<div class="friendItem">'+
                '<div class="friendHeader"><img src="'+o['card_image']+'" alt="" /></div><div class="friendInfo"><span>'+o['org_name']+'</span><span>'+o['name']+'</span><span class="friendAdd" style="background-color: #337ab7;text-align:center;color: #fff;" staffid = "'+o.staffid+'" onclick="searchFriends.friendAdd(this)">加为好友</span></div></div>';
        if (typeof o == 'object' ) {
            $('.friendItem-box').append(html);
        };
    })
}
searchFriends.friendAdd = function ( obj ) {
    var staffid = obj.getAttribute('staffid');
    ws.send('{"type":"friendAdd","actType":"add","staffid":"'+staffid+'"}');
    alert('发送成功！');

}
var messageShow = function () {
    this.data = ''; // 消息的 数据
    this.type = ''; // 消息的 类型
    this.showHtmlBox = $(".chating-content .he_ov"); // 显示消息 容器
    this.showHtmlboxStr = ''; // 需要添加的 到 显示消息的容器 所有东西
    this.messageData = '';  // 单条消息的 数据
    this.sender_id = '';
    this.options = {
        addVoiceClass: "",
        addImgClass: "",
        addImgAttr: "",
        content2: "",
        content: "",
    },
    this.createStrL = function (data) {
        this.showHtmlboxStr = '<li ' + this.options.addImgAttr + ' class="Chat_le ' + this.options.addImgClass + '"><div class="user"><span class="head le"><span class="header-img"><img src="' + data.card_image + '" alt=""></span></span> <span class="name le">' + data.sender_name + '<span style="padding: 0 0 0 20px">' + data.create_time + '</span></span><div class="mes_content le chatMesCon"><span mes_id= "'+data.id+'" title ="撤销消息" uid = "'+to_uid+'" data-man="other" class="delChatMes delChatMes_right">&times;</span><span class="jian le"></span> <span class="content-font ' + this.options.addVoiceClass + ' le">' + this.options.content + '</span>' + this.options.content2 + '</div></div></li>';

    },
    this.createStrR = function (data) {
        this.showHtmlboxStr = '<li ' + this.options.addImgAttr + ' class="Chat_ri he ' + this.options.addImgClass + '"><div class="user_ri he"><span class="ri head_ri"><span class="header-img"><img src="' + header_img_url + '" alt=""></span></span> <span class="ri name_ri"><span style="padding: 0 20px 0 0">' + data.create_time + '</span>' + chat_name + '</span> <div class="ri content_ri chatMesCon"><span mes_id= "'+data.id+'" title ="撤销消息" uid = "'+to_uid+'" data-man="self" class="delChatMes delChatMes_left">&times;</span><span class="arrow ri"></span><span class="content_font_ri ' + this.options.addVoiceClass + '">' + this.options.content + '</span> ' + this.options.content2 + ' </div></div></li>';
    },
    this.text = function () {
        var content = this.messageData.message_content.replace(/\{\|/g, '<img width="24px" class="cli_em" src="/chat/emoticons/images/');
        content = content.replace(/\|\}/g, '.gif">');
        content = content.replace(/%5C/g, "\\").replace(/\&br\&/g, "<br/>");
        this.options.content = content;

    },
    this.va =  function () {
        if ( chat_name == this.messageData.sender_name ) {
            var vName = '我';
        } else {
            var vName = this.messageData.sender_name;
        }
        var content = "<div style='width:100%;height: 88px;background-color: #fff;color: #000;padding: 10px;'>"+
                    "<div style='width: 100%; height: 43px;border-bottom: 1px solid #ccc;'>"+vName+
                    "开启了群聊视频</div>"+
                    "<div style='width: 100%; height: 43px;text-align: center;line-height: 43px;'>"+
                    "<a href='https://www.omso2o.com/chat/va-chat/vaChat.php?session_id="+this.messageData.message_content+"&Invitation=1'  target='_blank' >加入</a></div>"+
                "</div>";
        this.options.content = content;

    },
    this.file = function () {
        var fileArray = new Array();
        var addShare = '';
        fileArray = this.messageData.message_content.split('|');
        if (webUrl == 'chat_index') {

            addShare = "<span title='分享给别人' data-placement = '" + this.messageData.message_content + "' onclick='chatShare(this)' class='chat-share'></span>";
        };
        content = "<div class='file-box'><div><i class='icon-folder-open icon-2x'> </i><span>" + fileArray[0] + "</span></div><div class='right'>  " + addShare + "<a href='http://7xq4o9.com1.z0.glb.clouddn.com/" + fileArray[1] + "?attname='><i class='icon-cloud-download icon-2x'></i></a></div></div>";
        this.options.content = content;

    },
    this.image =  function () {
        var content = data[i].message_content;
        var objE = document.createElement("div");　　objE.innerHTML = content;　　
        var obj = objE.childNodes;
        var ImgSrc = obj[0].getAttribute('src');
        content = "<img index='" + imgIndex + "' src='" + ImgSrc + "' class='send-img'>";
        this.options.addImgAttr = ' href = "' + ImgSrc + '" data-size="1600x1068" data-med="' + ImgSrc + '" data-med-size="1024x683" data-author=""';
        this.options.addImgClass = 'bigImg';
        this.options.content = content;
        $('.loadImg-box .loadImging').append(content);
        imgIndex++;

    },
    this.images = function () {
        var content = '';
        this.options.addImgAttr = ' href = "http://7xq4o9.com1.z0.glb.clouddn.com/' + this.messageData.message_content + '" data-size="1600x1068" data-med="http://7xq4o9.com1.z0.glb.clouddn.com/' + this.messageData.message_content + '" data-med-size="1024x683" data-author=""';
        this.options.addImgClass = 'bigImg';
        this.options.content = "<img index='" + imgIndex + "' src='http://7xq4o9.com1.z0.glb.clouddn.com/" + this.messageData.message_content + "' class='send-img'>";
        $('#chat-session-img').append("<li><img src='http://7xq4o9.com1.z0.glb.clouddn.com/" + this.messageData.message_content + "' ></li>");
        $('.loadImg-box .loadImging').append(this.options.content);
        imgIndex++;

    }
    this.voice = function () {
        var voiceArray = new Array();
        voiceArray = this.messageData.message_content.split('|');
        if ( this.messageData.sender_id == chat_uid ) {
            this.options.content = '<div class="he_ov_mes_audio web_voice web_chat_voice_right_play" web_voice_data = "right" web_voice = "' + voiceArray[0] + '"></div>';
            this.options.content2 = '<span class="chat_duration_right">' + voiceArray[1] + '\"</span';
        } else {
            this.options.content = '<div class="he_ov_mes_audio web_voice web_chat_voice_left_play" web_voice_data = "left" web_voice = "' + voiceArray[0] + '"></div>';
            this.options.content2 = '<span class="chat_duration_left">' + voiceArray[1] + '\"</span';
        }
        this.options.addVoiceClass = "web_chat_voice";

    },
    this.revoke = function () {
        this.showHtmlboxStr = '<div style="text-align:center;margin:10px 0;color:#ccc;">'+this.messageData.sender_name+'撤销了一条信息</div>';
    }
}
// 显示消息 操作
messageShow.prototype = {
    // init: function ( data, type ) {
    //     this.data = data; // 消息的 数据
    //     this.type = type; // 消息的 类型
    // }
    //选择人后的消息列表
    mes_chat : function () {
        this.showHtmlBox.html('');
        this.show();
        this.showHtmlBox.prepend(" <div class='onload'  style='text-align: center;'><span style='color: #000;padding: 5px 0;'>---查看更多---</span></div>");
        // 回到底部
        $(".chating-content .he_ov img").load(function() {
        $(".chating-content .he-ov-box").scrollTop($(".chating-content .he_ov")[0].scrollHeight);
        });
        return false;
    },
     // 加载更多消息
    onlode: function () {
        $(".chating-content .onload").remove();
        if (this.data.save == 0) {
            $(".chating-content .he_ov").prepend("<div style='text-align: center;' class= 'seeMore' ><span style='padding: 5px 0;'>没有了！</span></div>");
            $('.chating-content .loader').hide();
            $('.chating-content .onload').remove();
            $('.chating-content .he-ov-box').unbind('scroll');
            return;
        };
        delete this.data.save;
        this.show();
        $('.loader').hide();
        var mes_load = $('.chating-content .mes_load').html();
        $('.chating-content .mes_load').html(parseInt(mes_load) + 10);
        $(".chating-content .he_ov img").load(function() {
            $(".chating-content .he-ov-box").scrollTop($('.chating-content .he_ov').height() - mesHeight);
        });
        return false;
    },
    // 显示消息
    show: function (data) {
        var type = this.data.type;
        delete this.data.type;
        for (var i in this.data) {
            this.type = this.data[i].mesages_types; // 消息的类型
            this.messageData = this.data[i]; // 每条 的消息的 数据
            console.log(this.type);
            this[this.type](); // 根据类型 调取 相应的方法
            if ( type != 'revoke' ) {
                if ( this.data[i].sender_id == chat_uid ) {
                    this.createStrR(this.data[i]);
                } else {
                    this.createStrL( this.data[i] );
                }
            }
            this.addHtml();
        }
        $(".chating-content .he_ov .delChatMes").unbind('click');
        $(".chating-content .he_ov .delChatMes").bind('click', function () {
            ChatObj.delChatMes($(this));
        });
    },
    // 添加 消息到 消息容器
    addHtml: function () {
        this.showHtmlBox.prepend( this.showHtmlboxStr );
    },
    typeFun: function () {
        
    }
}

//选择人后的消息列表
function mes_chat(data) {
    $(".chating-content .he_ov").html('');
    messageShow(data);
    $(".chating-content .he_ov").prepend(" <div class='onload'  style='text-align: center;'><span style='color: #000;padding: 5px 0;'>---查看更多---</span></div>");
    // 回到底部
    $(".chating-content .he_ov img").load(function() {
        $(".chating-content .he-ov-box").scrollTop($(".chating-content .he_ov")[0].scrollHeight);
    });
    return false;
}
//加载消息
function onlode(data) {
    $(".chating-content .onload").remove();
    if (data.save == 0) {
        $(".chating-content .he_ov").prepend("<div style='text-align: center;' class= 'seeMore' ><span style='padding: 5px 0;'>没有了！</span></div>");
        $('.chating-content .loader').hide();
        $('.chating-content .onload').remove();
        $('.chating-content .he-ov-box').unbind('scroll');
        return;
    };
    messageShow(data);
    $('.loader').hide();
    var mes_load = $('.chating-content .mes_load').html();
    $('.chating-content .mes_load').html(parseInt(mes_load) + 10);
    $(".chating-content .he_ov img").load(function() {
        $(".chating-content .he-ov-box").scrollTop($('.chating-content .he_ov').height() - mesHeight);
    });
    return false;
}
/*window消息提醒*/
function showMsgNotification(title, msg, document_url) {
    var Notification = window.Notification || window.mozNotification || window.webkitNotification;
    if (Notification && Notification.permission === "granted") {
        var instance = new Notification(title, {
            icon: document_url,
            body: msg,
        });
        instance.onclick = function() {
            // Something to do
        };
        instance.onerror = function() {
            // Something to do
        };
        instance.onshow = function() {
            // Something to do
            // console.log(instance.close);
            setTimeout(instance.close.bind(instance), 3000);
        };
        instance.onclose = function() {
            // Something to do
        };
    } else if (Notification && Notification.permission !== "denied") {
        Notification.requestPermission(function(status) {
            if (Notification.permission !== status) {
                Notification.permission = status;
            }
            // If the user said okay
            if (status === "granted") {
                var instance = new Notification(title, {
                    icon: "http://www.omso2o.com/chat/images/header.jpg",
                    body: msg,

                });

                instance.onclick = function() {
                    // Something to do
                };
                instance.onerror = function() {
                    // Something to do
                };
                instance.onshow = function() {
                    // Something to do
                };
                instance.onclose = function() {
                    // Something to do
                };
            } else {
                return false
            }
        });
    } else {
        return false;
    }
}