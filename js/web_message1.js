if (typeof console == "undefined") {    this.console = { log: function (msg) {  } };}
    WEB_SOCKET_SWF_LOCATION = "/swf/WebSocketMain.swf";
    WEB_SOCKET_DEBUG = true;
    var ws, allclient_list = client_list={};
    var message_type = '消息';
    // 连接服务端
    function connect() {
       // 创建websocket
       ws = new WebSocket("ws://"+document.domain+":7272");
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
    function onopen()
    {
        // 登录
      var login_data = '{"type":"adminLogin","oms_id":"'+oms_id+'","token":"'+chat_token+'", "uid": "'+chat_uid+'", "header_img_url":"'+header_img_url+'",  "client_name":"'+chat_name+'","room_id":"'+room_id+'"}';
        console.log(login_data);
        ws.send(login_data);
    }

    // 服务端发来消息时
    function onmessage(e)
    {
        console.log(e.data);
        var data = eval("("+e.data+")");
        switch(data['type']){
            // 服务端ping客户端
            case 'ping':
                ws.send('{"type":"pong"}');
                break;
            // 登录 更新用户列表
            case 'login':
              if(data['client_list'])
              {
                  client_list = data['client_list'];
              }
              else
              {
                  client_list[data['uid']] = data['client_name']; 
              }
              var online = 0;
              for (var i in data['client_list']) {
                online++;
              }
              $('.online_ren').html(online);
              setTimeout('flush_onlineman_list()', 1000);
              // flush_onlineman_list();
              // console.log(data['client_name']+"登录成功");
                break;
              // 员工登录 发送管理员 
            case 'adminLoginNum':
              if ( data['client_num'].uid != 554 ) {
                allclient_list['arrALlonlineInfo'][data['client_num'].uid] = data['client_num'].room_id;
              };
              flush_room_list();
            break;
            //管理员登录
            case 'adminLogin':
              ws.send('{"type": "allOnlineNum"}');
              // addLoginNum();
            break;
            case 'say_uid':
                say_uid(data['image'], data['mestype'], data['header_img_url'],data['group_name'], data['insert_id'],  data['session_no'], data['from_uid_id'], data['to_uid_id'], data['from_client_id'], data['from_client_name'], data['content'], data['time']);
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
            //消息的加载
            case 'onlode':
              onlode(data);
              break;
            case 'logout':
                delete client_list[data['from_uid_id']];
                flush_onlineman_list();
                break;
            //所有的在线人数
            case 'allOnlineNum':
                allclient_list = data;
              console.log(data);
              var allOnlineNum = 0;
              for( var i in  data['arrALlonlineInfo'] ) {
                allOnlineNum ++;
              }
               var hr_online_num = (allOnlineNum/$hr_total)*100;
                $('.hr_percent').html(hr_online_num.toFixed(6)+'%');
              // var allOnlineNum = $('#allOnlineNum').html();
              $('#allOnlineNum').html(allOnlineNum);
              var num = 0;
              var chatAllRoom = $('.chatAllRoom');
              chatAllRoom.html('');
              $.each( allclient_list['arrAllRoomId'], function (i, v) {
                  num ++;
                  var manNum = allclient_list.roomInfo[i].length
                  // chatAllRoom.append('<tr><td>'+num+'</td><td>'+allclient_list['comInfo'][i]+'</td><td>'+v+'</td><td chat-roomId = "'+i+'" class="getAllChatName"><i class="AllChatName"></i></td></tr>')
                  chatAllRoom.append('<div class="panel panel-default chat-drop-down" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'+num+'" chat-roomId="'+i+'" aria-expanded="false" aria-controls="collapse'+num+'"><div class="panel-heading" role="tab" id="headingOne"><div class="row chat-drop"><div class="col-xs-2 col-md-2">'+num+'</div><div class="col-xs-8 col-md-8">'+allclient_list['comInfo'][i]+'</div><div class="col-xs-2 col-md-2">'+manNum+'</div></div></div><div id="collapse'+num+'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne"><div class="panel-body"><ul></ul></div></div></div>');
              })
              $('#myModal').modal('show');
            break;
            case 'loggoutTwo':
            // _chat_remove(data['from_uid'], allclient_list);
            // allOnlineNum = allclient_list.length
            delete allclient_list['arrALlonlineInfo'][data['from_uid']];
            flush_room_list();
            break;
            default:
            return;

        }
    }
    //删除指定数组元素
    // var _chat_remove = function (val, array) {
    //     var index = array.indexOf(val);
    //     if (index > -1) {
    //         array.splice(index, 1);
    //     }
    //     return array;
    // }
    // 提交消息
    /* 
    * content  消息内容
    * to_uid  消息接受人 要是为多个 to_uid 用逗号连接
    * senderid 发送人的id 
    * message_type 消息类型
    */  
    function onSubmit_messages(content, to_uid, senderid, message_type) {
      var to_client_name = '预留name';
      ws.send('{"type":"sayUid","to_uid":['+to_uid+'],"senderid":"'+senderid+'", "message_type":"'+message_type+'",  "to_client_name":"'+to_client_name+'","content":"'+content+'"}');
    }
    // 对Date的扩展，将 Date 转化为指定格式的String
    // 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符， 
    // 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字) 
    // 例子： 
    // (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423 
    // (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18 
    Date.prototype.format =function(format){
      var o = {
      "M+" : this.getMonth()+1, //month
      "d+" : this.getDate(), //day
      "h+" : this.getHours(), //hour
      "m+" : this.getMinutes(), //minute
      "s+" : this.getSeconds(), //second
      "q+" : Math.floor((this.getMonth()+3)/3), //quarter
      "S" : this.getMilliseconds() //millisecond
      }
      if(/(y+)/.test(format)) format=format.replace(RegExp.$1,
      (this.getFullYear()+"").substr(4- RegExp.$1.length));
      for(var k in o)if(new RegExp("("+ k +")").test(format))
      format = format.replace(RegExp.$1,
      RegExp.$1.length==1? o[k] :
      ("00"+ o[k]).substr((""+ o[k]).length));
      return format;
    }
    
    /*
    * to_uid to_uid 如果是多个to_uid  用逗号连接
    * message_type 消息类型
    * content 消息内容
    * auditUrl 审核的连接
    */
    //提交审核
    function onAudit(to_uid,message_type, chat_uid, oms_id, content, auditUrl) {
      ws.send('{"type":"audit","oms_id":'+oms_id+', "to_uid_id":['+to_uid+'],"senderid":'+chat_uid+', "message_type":"'+message_type+'","content":"'+content+'", "message_url":"'+auditUrl+'"}');
    }    
    //  提交会话
    function onSubmit(to_uid, chat_uid, groupId, message_type, mes_types,from_session_no) {
      var nowTime = new Date().format('yyyy-MM-dd hh:mm:ss');
      var accept_name = $('.mes_title_con').text();
      var inputcur = "";
      var inputValue = "";
      var input = "";
      switch(mes_types){
        case 'text':
          input = document.getElementById("mes_textarea");
          inputValue = input.innerHTML.replace(/[\\]/g,"%5C").replace(/[\r\n]/g,"%6b").replace(/["]/g,"'");
          inputcur = input.innerHTML.replace(/[\r\n]/g,"<br/>");
        break;
        case 'image':
          inputcur = "<img src='"+$('.sending-img-box .send-img').attr('src')+"' class='send-img'>";
          inputValue = $('.sending-img-box .send-img').attr('src');
          $('.img-box').hide();
        break;
        case 'file':
          var fileName = document.getElementById('filename').value;
          var fileUrl = document.getElementById('key').value;
          inputcur  = "<div class='file-box'><div><i class='icon-folder-open icon-2x'> </i><span>"+fileName+"</span></div><div class='right'><a href='http://7xq4o9.com1.z0.glb.clouddn.com/"+fileUrl+"?attname='><i class='icon-cloud-download icon-2x'></i></a></div></div>";
          inputValue = fileName+"|"+fileUrl;
          break;
        case 'voice':
        inputValue = $('.chat_voice_box').attr('voice_url');
        ws.send('{"type":"sayUid","to_uid":"'+to_uid+'","senderid":"'+chat_uid+'", "groupId":"'+groupId+'", "accept_name":"'+accept_name+'","message_type":"'+message_type+'", "mes_types":"'+mes_types+'","session_no":"'+from_session_no+'","content":"'+inputValue+'"}');
        $(".he-ov-box").scrollTop($(".he-ov-box")[0].scrollHeight);
          return;

      }
      // if (mes_types == 'text') {
      //   var input = document.getElementById("mes_textarea");
      //   var inputValue = input.innerHTML.replace(/[\\]/g,"%5C").replace(/[\r\n]/g,"%6b").replace(/["]/g,"'");
      //   var inputcur = input.innerHTML.replace(/[\r\n]/g,"<br/>");
      // } else if (mes_types == 'image') {
      //   var inputcur = "<img src='"+$('.sending-img-box .send-img').attr('src')+"' class='send-img'>";
      //   var inputValue = $('.sending-img-box .send-img').attr('src');
      //   $('.img-box').hide();
      // };
      $(".he_ov").append('<li class="Chat_ri he"><div class="user_ri he"><span class="ri head_ri"><span class="header-img"><img src="'+header_img_url+'" alt=""></span></span> <span class="ri name_ri"><span style="padding: 0 20px 0 0">'+nowTime+'</span>'+chat_name+'</span> <div class="ri content_ri"><span class="arrow ri"></span><span class="content_font_ri">'+inputcur+'</span> </div></div></li>');
      $(".he-ov-box").scrollTop($(".he-ov-box")[0].scrollHeight);
        ws.send('{"type":"sayUid","to_uid":"'+to_uid+'","senderid":"'+chat_uid+'", "groupId":"'+groupId+'", "accept_name":"'+accept_name+'","message_type":"'+message_type+'", "mes_types":"'+mes_types+'","session_no":"'+from_session_no+'","content":"'+inputValue+'"}');
      document.getElementById("mes_textarea").innerHTML = "";
      $("#mes_textarea").height(50);
      $(".he-ov-box").css("bottom", inputBottom);
      $('.emoticons').hide();
      document.getElementById("mes_textarea").focus();
    }
    //显示群聊人
    function showGroupMan(data){
      var addClass = ''
      if (data[0]['group_founder'] == chat_uid) {
        addClass = 'group-people';
      };
      for(i in data) {
        if ( i == "type") {
          break; 
        };
        $('.groupAct').parents('.panel').find('.list-group').append('<li class="db_chat_people chat_people '+addClass+'" group-name="'+data[i]['name']+'" groupId="'+data[i]['pid']+'" mes_id="'+data[i]['staffid']+'"><span class="header-img"><img src="'+data[i]['card_image']+'" alt="'+data[i]['name']+'"></span><i>'+data[i]['name']+'</i><span class="delgroupman" groupId="'+data[i]['pid']+'" id="'+data[i]['staffid']+'">&times;</span></li>')

      };
    }
    // 刷新所有房间的信息
    function flush_room_list(){
      var allOnlineNum = 0;
      for( var i in  allclient_list['arrALlonlineInfo'] ) {
        allOnlineNum ++;
      }
      $('#allOnlineNum').html(allOnlineNum);
    }
    // 刷新在线人数
    function flush_onlineman_list(){
      var onlineman_ul = $(".online_man ul.list-group");
      var online_ren = 0;
      onlineman_ul.empty();
      for(var p in client_list){
          online_ren ++;
          onlineman_ul.append('<li mes_id="'+p+'" data-placement="left" class="staff-info chat_people db_chat_people" group-name="'+client_list[p].client_name+'"><span class="header-img"><img src="'+client_list[p].header_img_url+'" alt="'+client_list[p].client_name+'"></span>'+client_list[p].client_name+'</li>');
      }
      $('.online_ren').html(online_ren);
      // onlineHeight = $(".online_man .list-group").height();
      // proScroll = ( docuHeight - onlineTop )/onlineHeight;
      // hideOnlineHeightPro = onlineHeight - (docuHeight - onlineTop);
      // onlineScrollHeight = (docuHeight - onlineTop) * ( 1- proScroll );
      // $('.onlinesSroll-box').css('height', (docuHeight - onlineTop) * proScroll);
    }
    //发言2
var messageShow = function () {
    this.data = ''; // 消息的 数据
    this.type = ''; // 消息的 类型
    this.showHtmlBox = ''; // 显示消息 容器
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
            var content = '';
            var res = this.messageData.message_content.match(/\{\@(.*?)\@\}/g);
            var imgStr = '';
            if ( res ) {
                var Appoint = {staffid: [], name: [], sn: {} };
                for (var i = 0; i <= res.length - 1; i++) {
                    var ress = res[i].match(/^{@(.*)@}$/);
                    var ressArr = ress[1].split('|');
                    // Appoint.name.push(ressArr[0]);
                    // Appoint.sn[from_uid_id] = from_client_name;
                    imgStr += $(textToImg('@'+ressArr[0]))[0].outerHTML;
                    // Appoint.staffid.push(ressArr[1]);
                };
                this.messageData.message_content = this.messageData.message_content.replace(/\{\@(.*?)\@\}/g, '');
            };
            content = this.messageData.message_content.replace(/\{\|/g, '<img width="24px" class="cli_em" src="/chat/emoticons/images/');
            content = content.replace(/\|\}/g, '.gif">');
            content = content.replace(/%5C/g, "\\").replace(/\&br\&/g, "<br/>");
            content = content.replace(/face\[([^\s\[\]]+?)\]/g, function(face){  //转义表情
                var alt = face.replace(/^face/g, '');
                return '<img alt="'+ alt +'" title="'+ alt +'" src="' + faces[alt] + '">';
            })
                .replace(/file\([\s\S]+?\)\[[\s\S]*?\]/g, function(str){ //转义文件
                    var href = (str.match(/file\(([\s\S]+?)\)\[/)||[])[1];
                    var text = (str.match(/\)\[([\s\S]*?)\]/)||[])[1];
                    if(!href) return str;
                    content = "<div class='file-box'><div><i class='icon-folder-open icon-2x'> </i><span>" + (text||href) + "</span></div><div class='right'><a download target='_blank' href='" + href + "?attname='><i class='icon-cloud-download icon-2x'></i></a></div></div>";
                    return content;
                })
                .replace(/img\[([^\s]+?)\]/g, function(img){  //转义图片
                    $('.loadImg-box .loadImging').append('<img index="' + imgIndex + '" class="layui-layim-photos send-img" src="' + img.replace(/(^img\[)|(\]$)/g, '') + '">');
                    imgIndex++;
                    var newImgIndex = imgIndex - 1;
                    return '<img index="' + newImgIndex + '" class="layui-layim-photos send-img" src="' + img.replace(/(^img\[)|(\]$)/g, '') + '">';
                })
            if ( imgStr ) {
                content = imgStr+content;
            };
            this.options.content = content;

        },
        this.kefu = function () {
            this.text();
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
            // var fileArray = new Array();
            // var addShare = '';
            // fileArray = this.messageData.message_content.split('|');
            // console.log(567);
            var content = this.messageData.message_content.replace(/file\([\s\S]+?\)\[[\s\S]*?\]/g, function(str){ //转义文件
                var href = (str.match(/file\(([\s\S]+?)\)\[/)||[])[1];
                var text = (str.match(/\)\[([\s\S]*?)\]/)||[])[1];
                if(!href) return str;
                content = "<div class='file-box'><div><i class='icon-folder-open icon-2x'> </i><span>" + (text||href) + "</span></div><div class='right'><a download target='_blank' href='" + href + "?attname='><i class='icon-cloud-download icon-2x'></i></a></div></div>";
                return content;
            })
            // if (webUrl == 'chat_index') {

            //     addShare = "<span title='分享给别人' data-placement = '" + this.messageData.message_content + "' onclick='chatShare(this)' class='chat-share'></span>";
            // };
            // content = "<div class='file-box'><div><i class='icon-folder-open icon-2x'> </i><span>" + fileArray[0] + "</span></div><div class='right'>  " + addShare + "<a href='http://7xq4o9.com1.z0.glb.clouddn.com/" + fileArray[1] + "?attname='><i class='icon-cloud-download icon-2x'></i></a></div></div>";
            this.options.content = content;

        },
        this.image =  function () {
            var content = this.messageData.message_content;
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
    init: function (data) {
        this.data = data;
        this.showHtmlBox = $(".chating-content .he_ov");
    },
    // 自己 说的消息
    resSayUid: function () {

    },
    // sayUid: function () {

    // },
    //选择人后的消息列表
    mes_chat : function () {
        this.showHtmlBox.html('');
        this.showOldMore();
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
        this.showOldMore();
        $('.loader').hide();
        var mes_load = $('.chating-content .mes_load').html();
        $('.chating-content .mes_load').html(parseInt(mes_load) + 10);
        $(".chating-content .he_ov img").load(function() {
            $(".chating-content .he-ov-box").scrollTop($('.chating-content .he_ov').height() - mesHeight);
        });
        return false;
    },
    // 显示消息
    showOldMore: function (data) {
        var type = this.data.type;
        delete this.data.type;
        for (var i in this.data) {
            this.showSay( this.data[i], 'n' );
        }
        $(".chating-content .he_ov .delChatMes").unbind('click');
        $(".chating-content .he_ov .delChatMes").bind('click', function () {
            ChatObj.delChatMes($(this));
        });
    },
    // 显示 对话内容 @parm data 对话数据 @parm direction 显示在上面 还是下面
    showSay: function ( data, direction ) {
        this.type = data.mesages_types; // 消息的类型
        this.messageData = data; // 每条 的消息的 数据
        typeof this[this.type] == 'function' && this[this.type]() ; // 根据类型 调取 相应的方法
        if ( this.type != 'revoke' ) {
            if ( this.messageData.sender_id == chat_uid ) {
                this.createStrR( data );
            } else {
                this.createStrL( data );
            }
        }
        if ( direction == 'n' ) {
            this.addHtmlN();

        } else {
            this.addHtmlP();
        }
        this.options = {
            addVoiceClass: "",
            addImgClass: "",
            addImgAttr: "",
            content2: "",
            content: "",
        }
    },
    // 添加 消息到 消息容器 下
    addHtmlN: function () {
        this.showHtmlBox.prepend( this.showHtmlboxStr );
    },
    // 添加 消息到 消息容器 上
    addHtmlP: function () {
        this.showHtmlBox.append( this.showHtmlboxStr );
    },
}
    //通知消息
    var ChatOptions = {
      tittle: '',
      content: '',
      imgUrl: '',
      sound: 'true',
      soundUrl: '/chat/audio/notice.wav',
    };
    function sayUid(image, mestype, header_img_url,group_name,insert_id, from_session_no, from_uid_id, to_uid_id, from_client_id, from_client_name, content, time){
        var content1;
        var content2 = "";
        var addVoiceClass = "";
        var addClass = "chat_people";
        switch (image){
          case 'image':
            content1 = '【图片】';
          break;
          case 'text':
            content1 = content.replace(/%5C/g,"\\").replace(/%6b/g," ");
            content = content.replace(/%5C/g,"\\").replace(/%6b/g,"<br/>");
          break;
          case 'file':
            content1 = '【文 件 】';
            var fileArray = new Array();
            fileArray = content.split('|');
            content = "<div class='file-box'><div><i class='icon-folder-open icon-2x'> </i><span>"+fileArray[0]+"</span></div><div class='right'><a href='http://7xq4o9.com1.z0.glb.clouddn.com/"+fileArray[1]+"?attname='><i class='icon-cloud-download icon-2x'></i></a></div></div>";
          break;
          case 'voice':
            var voiceArray = new Array();
            voiceArray = content.split('|');
            content1 = "【 语音 】";
            content = '<div class="he_ov_mes_audio web_voice web_chat_voice_left_play"  web_voice_data = "left" web_voice = "'+voiceArray[0]+'"></div>';
            content2 = '<span class="chat_duration_left">'+voiceArray[1]+'\"</span';
            addVoiceClass = "web_chat_voice";
          break;
          case 'notice':
              content1 = content;
              header_img_url = "/chat/images/chatNotice.png";

              if ($.inArray(from_session_no, arrMessageList) != -1) {

                var curmesnum = parseInt($(".mes_chakan_close[session_no='"+from_session_no+"']").find('.mes_num').html()) + 1;
                $(".mes_chakan_close[session_no='"+from_session_no+"']").attr('chat_mes_num', curmesnum).find('.mex_con').html(from_client_name+'【请求会话】')
                $(".mes_chakan_close[session_no='"+from_session_no+"']").find('.mes_num').html(curmesnum);

              } else {

                $(".mes_con").append('<div class="mes_box mes_chakan_close chat_notice" chat_mes_num="1" oms_id= "'+insert_id+'"  mestype="'+mestype+'"  group-name="'+from_client_name+'" mes_id="'+from_uid_id+'" session_no="'+from_session_no+'"><div class= "mes_header"><img src="'+header_img_url+'" alt="'+from_client_name+'" /></div><span class="mex_con">'+from_client_name+'【 请求会话 】</span><div class="mes_content_list" style=""><span class="chat_mes_content">'+content1+'</span></div><span class="mes_num">1</span><span session_no="'+from_session_no+'" mes_id="'+from_uid_id+'"  mestype="'+mestype+'" group-name="'+from_client_name+'" class="mes_close">X</span></div>');

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

                var curmesnum = parseInt($(".mes_chakan_close[session_no='"+from_session_no+"']").find('.mes_num').html()) + 1;
                $(".mes_chakan_close[session_no='"+from_session_no+"']").attr('chat_mes_num', curmesnum).find('.mex_con').html('你与'+from_client_name+'可以会话了')
                $(".mes_chakan_close[session_no='"+from_session_no+"']").find('.mes_num').html(curmesnum);

              } else {

                $(".mes_con").append('<div class="mes_box mes_chakan_close '+addClass+'" chat_mes_num="1"  mestype="'+mestype+'"  group-name="'+from_client_name+'" mes_id="'+from_uid_id+'" session_no="'+from_session_no+'"><div class= "mes_header"><img src="'+header_img_url+'" alt="'+from_client_name+'" /></div><span class="mex_con">你与'+from_client_name+'可以会话了</span><div class="mes_content_list" style=""><span class="chat_mes_content">'+content1+'</span></div><span class="mes_num">1</span><span session_no="'+from_session_no+'" mes_id="'+from_uid_id+'"  mestype="'+mestype+'" group-name="'+from_client_name+'" class="mes_close">X</span></div>');

              }
              mesnum++;
              // console.log(mesnum);
              $('.mes_radio').html(mesnum);
              arrMessageList.push(from_session_no);
          return;
        }

        
        if (session_no != from_session_no) {
            //通知消息的显示
          // var options = {
          //   tittle: from_client_name,
          //   content: content1,
          //   imgUrl: header_img_url,
          //   sound: 'true',
          //   soundUrl: '/chat/audio/notice.wav',
          // };
          ChatOptions.tittle = from_client_name;
          ChatOptions.content = content1;
          ChatOptions.imgUrl = header_img_url;
            // $('#mesNotice').chatNotice( options );
            if ( localStorage.getItem('desktopState') == '0' ) {
              showMsgNotification( from_client_name, content1, header_img_url );
            };
          $('.mesNoticeContainer').notify(ChatOptions);
            //end
          if (mestype != "message") {
              from_client_name = group_name;
              addClass = "session_no";
              header_img_url = '/chat/images/rens.png';
          }
          // console.log(arrMessageList);
          // console.log(from_session_no);
          // console.log($.inArray(from_session_no, arrMessageList));
          if ($.inArray(from_session_no, arrMessageList) != -1) {
            var curmesnum = parseInt($(".mes_chakan_close[session_no='"+from_session_no+"']").find('.mes_num').html()) + 1;
            $(".mes_chakan_close[session_no='"+from_session_no+"']").attr('chat_mes_num', curmesnum).find('.mex_con').html(from_client_name)
            $(".mes_chakan_close[session_no='"+from_session_no+"']").find('.mes_num').html(curmesnum);
            $(".mes_chakan_close[session_no='"+from_session_no+"']").find('.chat_mes_content').html(content1);
          } else {
            $(".mes_con").append('<div class="mes_box mes_chakan_close '+addClass+'" chat_mes_num="1"  mestype="'+mestype+'"  group-name="'+from_client_name+'" mes_id="'+from_uid_id+'" session_no="'+from_session_no+'"><div class= "mes_header"><img src="'+header_img_url+'" alt="'+from_client_name+'" /></div><span class="mex_con">'+from_client_name+'</span><div class="mes_content_list" style=""><span class="chat_mes_content">'+content1+'</span></div><span class="mes_num">1</span><span session_no="'+from_session_no+'" mes_id="'+from_uid_id+'"  mestype="'+mestype+'" group-name="'+from_client_name+'" class="mes_close">X</span></div>');
          }
          mesnum++;
          // console.log(mesnum);
          $('.mes_radio').html(mesnum);
          arrMessageList.push(from_session_no);
        };

      if (session_no == from_session_no) {

        $(".he_ov").append('<li class="Chat_le"><div class="user"><span class="head le"><span class="header-img"><img src="'+header_img_url+'" alt=""></span></span> <span class="name le">'+from_client_name+'<span style="padding: 0 0 0 20px">'+time+'</span></span><div class="mes_content le"><span class="jian le"></span> <span class="content-font '+addVoiceClass+' le">'+content+'</span>'+content2+'</div></div></li>');

          $(".he-ov-box").scrollTop($(".he-ov-box")[0].scrollHeight);

          if ($(".mes_chakan_close[session_no='"+session_no+"']").length > 0) {                         
            mes_num = parseInt($(".mes_chakan_close[session_no='"+session_no+"']").parent().next('.mes_num').html());
            // console.log($.inArray(session_no, arrMessageList));
            if (mestype != "message") {
              mes_chakan_close(mestype, session_no, mes_num);
            } else {
              mes_chakan_close(mestype, from_uid_id, mes_num);
            }
            // mes_close(mestype, session_no, session_no);
          } else {
            if (mestype != "message") {
              mes_chakan_close(mestype, session_no, 0);
            } else {
              mes_chakan_close(mestype, from_uid_id, 0);
            }
          }
      };
    }
    //选择人后的消息列表
    function mes_chat(data){
      // var chatNum = data.length;
      var mes_time;
      var content;
      var content2 = "";
      $(".he_ov").html('');
      for (var i in data) {
        var addVoiceClass = "";
        if (i == 'type') {
          $(".he_ov").prepend(" <div class='onload'  style='text-align: center;'><span style='color: #fff;padding: 5px 0;'>---查看更多---</span></div>");
          $(".he-ov-box").scrollTop($(".he_ov")[0].scrollHeight);
            return ;
        };
        // console.log(data);
        mes_time = data[i].create_time;
        switch (data[i].mesages_types) {
            case 'text':
              content = data[i].message_content.replace(/%5C/g,"\\").replace(/%6b/g,"<br/>")
                break;
            case 'file':
              var fileArray = new Array();
              fileArray = data[i].message_content.split('|');
              content = "<div class='file-box'><div><i class='icon-folder-open icon-2x'> </i><span>"+fileArray[0]+"</span></div><div class='right'><a href='http://7xq4o9.com1.z0.glb.clouddn.com/"+fileArray[1]+"?attname='><i class='icon-cloud-download icon-2x'></i></a></div></div>";
                break;
            case 'image':
                content = data[i].message_content;
                break;
            case 'voice':
            var voiceArray = new Array();
            voiceArray = data[i].message_content.split('|');
            if (data[i].sender_id == chat_uid) {
              content = '<div class="he_ov_mes_audio web_voice web_chat_voice_right_play" web_voice_data = "right" web_voice = "'+voiceArray[0]+'"></div>';
              content2 = '<span class="chat_duration_right">'+voiceArray[1]+'\"</span';
            } else {
              content = '<div class="he_ov_mes_audio web_voice web_chat_voice_left_play" web_voice_data = "left" web_voice = "'+voiceArray[0]+'"></div>';
              content2 = '<span class="chat_duration_left">'+voiceArray[1]+'\"</span';
            }
            voiceArray = data[i].message_content.split('|');
            addVoiceClass = "web_chat_voice";
            break;
            default:
                break;
        }
        if (data[i].sender_id == chat_uid) {
          $(".he_ov").prepend('<li class="Chat_ri he"><div class="user_ri he"><span class="ri head_ri"><span class="header-img"><img src="'+header_img_url+'" alt=""></span></span> <span class="ri name_ri"><span style="padding: 0 20px 0 0">'+mes_time+'</span>'+chat_name+'</span> <div class="ri content_ri"><span class="arrow ri"></span><span class="content_font_ri '+addVoiceClass+'">'+content+'</span> '+content2+' </div></div></li>');
        } else {
          $(".he_ov").prepend('<li class="Chat_le"><div class="user"><span class="head le"><span class="header-img"><img src="'+data[i].card_image+'" alt=""></span></span> <span class="name le">'+data[i].sender_name+'<span style="padding: 0 0 0 20px">'+mes_time+'</span></span><div class="mes_content le"><span class="jian le"></span> <span class="content-font '+addVoiceClass+' le">'+content+'</span>'+content2+'</div></div></li>');
        }
      }
    }
    //加载消息
    function onlode(data){
      var mes_time;
      var content;
      var content2 = "";
      if (data.save == 0) {
        $(".he_ov").prepend("<div style='text-align: center;' class= 'seeMore' ><span style='padding: 5px 0;'>没有了！</span></div>");
          $('.loader').hide();
          $('.onload').remove();
          $('.he-ov-box').unbind('scroll');
        return;
      };
      $(".he_ov .onload").hide();
      for (var i in data) {
        var addVoiceClass = "";
        if (i == 'type') {
          $('.loader').hide();
          var mes_load = $('#mes_load').html();
          $('#mes_load').html(parseInt(mes_load)+10);
          $(".he-ov-box").scrollTop($('.he_ov').height() - mesHeight);
          return ;
        };
        mes_time = data[i].create_time;
        switch (data[i].mesages_types) {
            case 'text':
              content = data[i].message_content.replace(/%5C/g,"\\").replace(/%6b/g,"<br/>")
                break;
            case 'file':
              var fileArray = new Array();
              fileArray = data[i].message_content.split('|');
              content = "<div class='file-box'><div><i class='icon-folder-open icon-2x'> </i><span>"+fileArray[0]+"</span></div><div class='right'><a href='http://7xq4o9.com1.z0.glb.clouddn.com/"+fileArray[1]+"?attname='><i class='icon-cloud-download icon-2x'></i></a></div></div>";
                break;
            case 'image':
                content = data[i].message_content;
                break;
            case 'voice':
            var voiceArray = new Array();
            voiceArray = data[i].message_content.split('|');
            if (data[i].sender_id == chat_uid) {
              content = '<div class="he_ov_mes_audio web_voice web_chat_voice_right_play" web_voice_data = "right" web_voice = "'+voiceArray[0]+'"></div>';
              content2 = '<span class="chat_duration_right">'+voiceArray[1]+'\"</span';
            } else {
              content = '<div class="he_ov_mes_audio web_voice web_chat_voice_left_play" web_voice_data = "left" web_voice = "'+voiceArray[0]+'"></div>';
              content2 = '<span class="chat_duration_left">'+voiceArray[1]+'\"</span';
            }
              addVoiceClass = "web_chat_voice";
              break;
            default:
                break;
        }
        if (data[i].sender_id == chat_uid) {
          $(".he_ov").prepend('<li class="Chat_ri he"><div class="user_ri he"><span class="ri head_ri"><span class="header-img"><img src="'+header_img_url+'" alt=""></span></span> <span class="ri name_ri"><span style="padding: 0 20px 0 0">'+mes_time+'</span>'+chat_name+'</span> <div class="ri content_ri"><span class="arrow ri"></span><span class="content_font_ri '+addVoiceClass+'">'+content+'</span>'+content2+' </div></div></li>');
        } else {
          $(".he_ov").prepend('<li class="Chat_le"><div class="user"><span class="head le"><span class="header-img"> <img src="'+data[i].card_image+'" alt=""></span></span> <span class="name le">'+data[i].sender_name+'<span style="padding: 0 0 0 20px">'+mes_time+'</span></span><div class="mes_content le"><span class="jian le"></span> <span class="content-font '+addVoiceClass+' le">'+content+'</span>'+content2+'</div></div></li>');
        }
      }

    }
    //审核消息
    function audit(from_session_no, from_client_id, from_client_name, content, message_url,time){
      $(".mes_con").append('<div class="mes_box"><span class="mex_con">'+content+'</span><div style="height:30px"><a href="'+message_url+'"><span mesid = "'+from_session_no+'" class="mes_chakan">查看</span></a></div><span mesid= "'+from_session_no+'" class="mes_close">X</span></div>');
       //消息加一
       mesnum++;
       $('.mes_radio').html(mesnum);
    }
    /*window消息提醒*/
    function showMsgNotification(title, msg, document_url){
      var Notification = window.Notification || window.mozNotification || window.webkitNotification;
      if (Notification && Notification.permission === "granted") {
        var instance = new Notification(
          title, {
          icon: document_url,
          body: msg,
          }
        );
        instance.onclick = function () {
        // Something to do
        };
        instance.onerror = function () {
        // Something to do
        };
        instance.onshow = function () {
        // Something to do
        // console.log(instance.close);
        setTimeout(instance.close.bind(instance), 3000); 
        };
        instance.onclose = function () {
        // Something to do
        };
      }else if (Notification && Notification.permission !== "denied") {
        Notification.requestPermission(function (status) {
        if (Notification.permission !== status) {
          Notification.permission = status;
        }
        // If the user said okay
        if (status === "granted") {
          var instance = new Notification(
          title, {
            icon: "http://www.omso2o.com/chat/images/header.jpg",
            body: msg,
          
          }
          );

          instance.onclick = function () {
          // Something to do
          };
          instance.onerror = function () {
          // Something to do
          };
          instance.onshow = function () {
          // Something to do
          };
          instance.onclose = function () {
          // Something to do
          };
        }else {
        return false
        }
        });
      }else{
      return false;
      }
    }
    // select 人
    $(function(){
    });