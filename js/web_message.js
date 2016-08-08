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
// 
var ChatObj = {
    data: '',
    resSayUid: function(data) {

    },
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
            case 'file':
                var fileArray = new Array();
                fileArray = data.content.split('|');
                if (webUrl == 'chat_index') {
                    addShare = "<span title='转发' data-placement = '" + data.content + "' onclick='chatShare(this)' class='chat-share'></span>";
                };
                content = "<div class='file-box'><div><i class='icon-folder-open icon-2x'> </i><span>" + fileArray[0] + "</span></div><div class='right'>" + addShare + "<a href='http://7xq4o9.com1.z0.glb.clouddn.com/" + fileArray[1] + "?attname='><i class='icon-cloud-download icon-2x'></i></a></div></div>";
                if ( data.to_uid != to_uid ) {
                    return false;
                };
                break;
        }
        $(".chating-content .he_ov").append('<li class="Chat_ri he ' + addImgClass + '"><div class="user_ri he"><span class="ri head_ri"><span class="header-img"><img src="' + data.header_img_url + '" alt=""></span></span> <span class="ri name_ri"><span style="padding: 0 20px 0 0">' + data.time + '</span>' + chat_name + '</span> <div class="ri content_ri chatMesCon"><span title ="撤销消息" mes_id= "'+data.insert_id+'" class="delChatMes">&times;</span><span class="arrow ri"></span><span class="content_font_ri">' + content + '</span> </div></div></li>');
        $(".chating-content .he-ov-box").scrollTop($(".chating-content .he-ov-box")[0].scrollHeight);
        $(".chating-content .he_ov .delChatMes").unbind('click');
        $(".chating-content .he_ov .delChatMes").bind('click', function () {
            ChatObj.delChatMes($(this));
        });
    },
    delChatMes: function ( Obj ) {
        if (!confirm('删除后不能恢复！你确定吗？')) {
            return false;
        };
        var mesId = Obj.attr('mes_id');
        Obj.parent().parent().parent().hide(500);
        
        ws.send('{"type": "delChatMes", "mes_id":"'+mesId+'"}');
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
         audio.pause();
        $('.vaChatRequest').remove();
        $('.vaChat').remove();
        break;
        //审核消息
    case 'showGroupMan':
        showGroupMan(data);
        break;
    case 'audit':
        audit(data['session_no'], data['from_client_id'], data['from_client_name'], data['content'], data['message_url'], data['time']);
        // 用户退出 更新用户列表
        break;
    case 'mes_chat':
        mes_chat(data);
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
        // window.location.href="https://www.omso2o.com/chat/va-chat/vaChat.php?uuid="+data.uuid+"&act="+data.act;
        window.location.href="/chat/va-chat/vaChat.php?uuid="+data.uuid+"&act="+data.act+"&session_id="+data.session_id;
        // window.open("/chat/va-chat/vaChat.php?uuid="+data.uuid+"&act="+data.act);
        break;
    case 'cancelVa':
    $('.vaChatRequest').remove();
    break;
        //消息的加载
    case 'onlode':
        onlode(data);
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

/*
    * to_uid to_uid 如果是多个to_uid  用逗号连接
    * message_type 消息类型
    * content 消息内容
    * auditUrl 审核的连接
    */
//提交审核
function onAudit(to_uid, message_type, chat_uid, oms_id, content, auditUrl) {
    ws.send('{"type":"audit","oms_id":' + oms_id + ', "to_uid_id":[' + to_uid + '],"senderid":' + chat_uid + ', "message_type":"' + message_type + '","content":"' + content + '", "message_url":"' + auditUrl + '"}');
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
        inputValue = input.innerHTML;
        inputValue = inputValue.replace(/<img[(\s.*)]width="24px"[(\s.*)]class=\"cli_em\"[\s(.*)]em_name=\"/ig, '{|').replace(/\"[\s(.*)]src=\"(.*?)\"[>?]/ig, '|}').replace(/<br>/g, '&br&').replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/\"/g, '&quot;').replace(/[\r\n]/g, "").replace(/[\\]/g, "%5C");
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
    // $(".chating-content .he_ov").append('<li ' + addImgAttr + ' class="Chat_ri he ' + addImgClass + '"><div class="user_ri he"><span class="ri head_ri"><span class="header-img"><img src="' + header_img_url + '" alt=""></span></span> <span class="ri name_ri"><span style="padding: 0 20px 0 0">' + nowTime + '</span>' + chat_name + '</span> <div class="ri content_ri"><span class="arrow ri"></span><span class="content_font_ri">' + inputcur + '</span> </div></div></li>');
    // $(".chating-content .he-ov-box").scrollTop($(".chating-content .he-ov-box")[0].scrollHeight);
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
function showGroupMan(data) {
    var addClass = ''
    if (data[0]['group_founder'] == chat_uid) {
        addClass = 'group-people';
    };
    for (i in data) {
        if (i == "type") {
            break;
        };
        $('.groupAct').parents('.panel').find('.list-group').append('<li class="db_chat_people chat_people ' + addClass + '" group-name="' + data[i]['name'] + '" groupId="' + data[i]['pid'] + '" mes_id="' + data[i]['staffid'] + '"><span class="header-img"><img src="' + data[i]['card_image'] + '" alt="' + data[i]['name'] + '"></span><i>' + data[i]['name'] + '</i><span class="delgroupman" groupId="' + data[i]['pid'] + '" id="' + data[i]['staffid'] + '">&times;</span></li>')

    };
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
    case 'file':
        content1 = '【文 件 】';
        var fileArray = new Array();
        fileArray = content.split('|');
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
    if  ( data.act == 'a' ) {
        var text = '邀请你语音通话';
    } else { 
        var text = '邀请你视频通话';
    }
    var vaQuest = $('<div class="vaChatRequest"><div class="vaRequestName">'+data.client_name+text+'</div></div>');
    var handleBox = $('<div class="handleBox"></div>');
    var Answer = $('<span from-uid = "'+data.from_uid+'" act="'+data.act+'" client_id="'+data.client_id+'" class="vaAnswer">接听</span>').click(function () {
        var client_id = $(this).attr('client_id');
        var act = $(this).attr('act');
        audio.pause();
        $.ajax({
            url: '/chat/va-chat/ajax_getToken.php',
            data: '',
            type: 'post',
            dataType: 'json',
            success: function (data) {
                // window.location.href="https://www.omso2o.com/chat/va-chat/vaChat.php?uuid="+data[0].uuid+"&act="+act;
                ws.send('{"type": "vaAnswer", "client_id": "'+client_id+'", "uuid": "'+data[1].uuid+'", "act": "'+act+'", "session_id": "'+data[1].session_id+'"}');
                window.location.href="/chat/va-chat/vaChat.php?uuid="+data[0].uuid+"&act="+act+"&session_id="+data[0].session_id;

                // window.open("/chat/va-chat/vaChat.php?uuid="+data[0].uuid+"&act="+act);
            },

        })
    });
    var cancel = $('<span from-uid = "'+data.from_uid+'" act="'+data.act+'" class="vaCancel">取消</span>').click(function () {
        audio.pause();
        var t_to_uid = $(this).attr('from-uid');
        var act = $(this).attr('act');
        var content = "视频取消";
        if ( act == 'a' ) {
            content = "语音取消";
        };
        ws.send('{"type":"vaChat","to_uid":"'+t_to_uid+'","senderid":"'+chat_uid+'", "groupId":"0", "accept_name":"'+ $('.chating-content .mes_title_con').text()+'","message_type":"message", "mes_types":"text","session_no":"'+session_no+'","content":"'+content+'"}');
        $('.vaChatRequest').remove();
    });
    handleBox.append(Answer).append(cancel);
    vaQuest.append(handleBox);
    $('body').append(vaQuest);
 }
//  聊天消息显示
var messageShow = function(data) { 
    var mes_time;
    var content;
    var content2 = "";
    for (var i in data) {
        var addVoiceClass = "";
        var addImgClass = "";
        var addImgAttr = "";
        var content2 = "";
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
        default:
            break;
        }
        if (data[i].sender_id == chat_uid) {
            $(".chating-content .he_ov").prepend('<li ' + addImgAttr + ' class="Chat_ri he ' + addImgClass + '"><div class="user_ri he"><span class="ri head_ri"><span class="header-img"><img src="' + header_img_url + '" alt=""></span></span> <span class="ri name_ri"><span style="padding: 0 20px 0 0">' + mes_time + '</span>' + chat_name + '</span> <div class="ri content_ri chatMesCon"><span mes_id= "'+data[i].id+'" title ="撤销消息" class="delChatMes">&times;</span><span class="arrow ri"></span><span class="content_font_ri ' + addVoiceClass + '">' + content + '</span> ' + content2 + ' </div></div></li>');
        } else {
            $(".chating-content .he_ov").prepend('<li ' + addImgAttr + ' class="Chat_le ' + addImgClass + '"><div class="user"><span class="head le"><span class="header-img"><img src="' + data[i].card_image + '" alt=""></span></span> <span class="name le">' + data[i].sender_name + '<span style="padding: 0 0 0 20px">' + mes_time + '</span></span><div class="mes_content le"><span class="jian le"></span> <span class="content-font ' + addVoiceClass + ' le">' + content + '</span>' + content2 + '</div></div></li>');
        }
    }
    $(".chating-content .he_ov .delChatMes").unbind('click');
    $(".chating-content .he_ov .delChatMes").bind('click', function () {
        ChatObj.delChatMes($(this));
    });
    // initPhotoSwipeFromDOM('.session-box');
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