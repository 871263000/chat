if (typeof console == "undefined") {    this.console = { log: function (msg) {  } };}
    WEB_SOCKET_SWF_LOCATION = "/swf/WebSocketMain.swf";
    WEB_SOCKET_DEBUG = true;
    var ws, client_list={};
    var message_type = '消息';
    // 连接服务端
    function connect() {
       // 创建websocket
       ws = new WebSocket("ws://"+document.domain+":7272");
       // 当socket连接打开时，输入用户名
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
      // var login_data = '{"type":"login","oms_id":"'+oms_id+'", "uid": "'+uid+'", "header_img_url":"'+header_img_url+'",  "client_name":"'+name+'","room_id":"'+room_id+'"}';
      // ws.send(login_data);
    }

    // 服务端发来消息时
    function onmessage(e)
    {
        // console.log(e.data);
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
              $('#allOnlineNum').html(data['allOnlineNum']);
            break;
            case 'loggoutTwo':
              var allOnlineNum = $('#allOnlineNum').html();
              $('#allOnlineNum').html(parseInt(allOnlineNum)-1);
            break;

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
      ws.send('{"type":"say_uid","to_uid_id":['+to_uid+'],"senderid":"'+senderid+'", "message_type":"'+message_type+'",  "to_client_name":"'+to_client_name+'","content":"'+content+'"}');
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
    /*window消息提醒*/
    // function showMsgNotification(title, msg, document_url){
    //   var Notification = window.Notification || window.mozNotification || window.webkitNotification;
    //   if (Notification && Notification.permission === "granted") {
    //     var instance = new Notification(
    //       title, {
    //       icon: "http://www.omso2o.com/chat/images/header.jpg",
    //       body: msg,
    //       }
    //     );
    //     instance.onclick = function () {
    //     // Something to do
    //     };
    //     instance.onerror = function () {
    //     // Something to do
    //     };
    //     instance.onshow = function () {
    //     // Something to do
    //     // console.log(instance.close);
    //     setTimeout(instance.close.bind(instance), 3000); 
    //     };
    //     instance.onclose = function () {
    //     // Something to do
    //     };
    //   }else if (Notification && Notification.permission !== "denied") {
    //     Notification.requestPermission(function (status) {
    //     if (Notification.permission !== status) {
    //       Notification.permission = status;
    //     }
    //     // If the user said okay
    //     if (status === "granted") {
    //       var instance = new Notification(
    //       title, {
    //         icon: "http://www.omso2o.com/chat/images/header.jpg",
    //         body: msg,
          
    //       }
    //       );

    //       instance.onclick = function () {
    //       // Something to do
    //       };
    //       instance.onerror = function () {
    //       // Something to do
    //       };
    //       instance.onshow = function () {
    //       // Something to do
    //       };
    //       instance.onclose = function () {
    //       // Something to do
    //       };
    //     }else {
    //     return false
    //     }
    //     });
    //   }else{
    //   return false;
    //   }
    // }
    
    /*
    * to_uid to_uid 如果是多个to_uid  用逗号连接
    * message_type 消息类型
    * content 消息内容
    * auditUrl 审核的连接
    */
    //提交审核
    function onAudit(to_uid,message_type, uid, oms_id, content, auditUrl) {
      ws.send('{"type":"audit","oms_id":'+oms_id+', "to_uid_id":['+to_uid+'],"senderid":'+uid+', "message_type":"'+message_type+'","content":"'+content+'", "message_url":"'+auditUrl+'"}');
    }    
    //  提交会话
    function onSubmit(to_uid, uid, groupId, message_type, mes_types,from_session_no) {
      var nowTime = new Date().format('yyyy-MM-dd hh:mm:ss');
      var accept_name = $('.mes_title_con').text();
      switch(mes_types){
        case 'text':
          var input = document.getElementById("mes_textarea");
          var inputValue = input.innerHTML.replace(/[\\]/g,"%5C").replace(/[\r\n]/g,"%6b").replace(/["]/g,"'");
          var inputcur = input.innerHTML.replace(/[\r\n]/g,"<br/>");
        break;
        case 'image':
          var inputcur = "<img src='"+$('.sending-img-box .send-img').attr('src')+"' class='send-img'>";
          var inputValue = $('.sending-img-box .send-img').attr('src');
          $('.img-box').hide();
        break;
        case 'file':
          var fileName = document.getElementById('filename').value;
          var fileUrl = document.getElementById('key').value;
          var inputcur  = "<div class='file-box'><div><i class='icon-folder-open icon-2x'> </i><span>"+fileName+"</span></div><div class='right'><a href='http://7xq4o9.com1.z0.glb.clouddn.com/"+fileUrl+"'><i class='icon-cloud-download icon-2x'></i></a></div></div>";
          var inputValue = fileName+"|"+fileUrl;
        break;

      }
      // if (mes_types == 'text') {
      //   var input = document.getElementById("textarea");
      //   var inputValue = input.innerHTML.replace(/[\\]/g,"%5C").replace(/[\r\n]/g,"%6b").replace(/["]/g,"'");
      //   var inputcur = input.innerHTML.replace(/[\r\n]/g,"<br/>");
      // } else if (mes_types == 'image') {
      //   var inputcur = "<img src='"+$('.sending-img-box .send-img').attr('src')+"' class='send-img'>";
      //   var inputValue = $('.sending-img-box .send-img').attr('src');
      //   $('.img-box').hide();
      // };
      $(".he_ov").append('<li class="Chat_ri he"><div class="user_ri he"><span class="ri head_ri"><span class="header-img"><img src="'+header_img_url+'" alt=""></span></span> <span class="ri name_ri"><span style="padding: 0 20px 0 0">'+nowTime+'</span>'+name+'</span> <div class="ri content_ri"><span class="arrow ri"></span><span class="content_font_ri">'+inputcur+'</span> </div></div></li>');
      $(".he-ov-box").scrollTop($(".he-ov-box")[0].scrollHeight);
      
      ws.send('{"type":"say_uid","to_uid_id":"'+to_uid+'","senderid":"'+uid+'", "groupId":"'+groupId+'", "accept_name":"'+accept_name+'","message_type":"'+message_type+'", "mes_types":"'+mes_types+'","session_no":"'+from_session_no+'","content":"'+inputValue+'"}');
      document.getElementById("textarea").innerHTML = "";
      $("#textarea").height(50);
      $(".he-ov-box").css("bottom", "50px");
      $('.emoticons').hide();
      document.getElementById("textarea").focus();
    }
    //显示群聊人
    function showGroupMan(data){
      var addClass = ''
      if (data[0]['group_founder'] == uid) {
        addClass = 'group-people';
      };
      for(i in data) {
        if ( i == "type") {
          break; 
        };
        $('.groupSign').parents('.panel').find('.list-group').append('<li class="db_chat_people chat_people '+addClass+'" group-name="'+data[i]['name']+'" groupId="'+data[i]['pid']+'" mes_id="'+data[i]['staffid']+'"><span class="header-img"><img src="'+data[i]['card_image']+'" alt="'+data[i]['name']+'"></span><i>'+data[i]['name']+'</i><span class="delgroupman" groupId="'+data[i]['pid']+'" id="'+data[i]['staffid']+'">&times;</span></li>')

      };
    }
    // 刷新在线人数
    function flush_onlineman_list(){
      var onlineman_ul = $(".online_man ul");
      var online_ren = 0;
      onlineman_ul.empty();
      for(var p in client_list){
          online_ren ++;
          onlineman_ul.append('<li mes_id="'+p+'" data-placement="left" class="staff-info chat_people db_chat_people" group-name="'+client_list[p].client_name+'"><span class="header-img"><img src="'+client_list[p].header_img_url+'" alt="'+client_list[p].client_name+'"></span>'+client_list[p].client_name+'</li>');
      }
      $('.online_ren').html(online_ren);
      onlineHeight = $(".online_man .list-group").height();
      proScroll = ( docuHeight - onlineTop )/onlineHeight;
      hideOnlineHeightPro = onlineHeight - (docuHeight - onlineTop);
      onlineScrollHeight = (docuHeight - onlineTop) * ( 1- proScroll );
      $('.onlinesSroll-box').css('height', (docuHeight - onlineTop) * proScroll);
    }
    //发言2
    function say_uid(image, mestype, header_img_url,group_name,insert_id, from_session_no, from_uid_id, to_uid_id, from_client_id, from_client_name, content, time){
        var content1;
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
            content = "<div class='file-box'><div><i class='icon-folder-open icon-2x'> </i><span>"+fileArray[0]+"</span></div><div class='right'><a href='http://7xq4o9.com1.z0.glb.clouddn.com/"+fileArray[1]+"'><i class='icon-cloud-download icon-2x'></i></a></div></div>";
          break;
        }
        if (session_no != from_session_no) {
          if (mestype != "message") {
              from_client_name = group_name;
          }
          if ($(".mes_chakan_close[session_no='"+from_session_no+"']").length > 0) {
            var curmesnum = parseInt($(".mes_chakan_close[session_no='"+from_session_no+"']").parent().next('.mes_num').html()) + 1;
            $(".mes_chakan_close[session_no='"+from_session_no+"']").parent().prev('.mex_con').html(from_client_name+':'+content1)
            $(".mes_chakan_close[session_no='"+from_session_no+"']").parent().next('.mes_num').html(curmesnum);
          } else {
            $(".mes_con").append('<div class="mes_box"><span class="mex_con">'+from_client_name+':'+content1+'</span><div style="height:30px"><span mestype="'+mestype+'"  group-name="'+from_client_name+'" mes_id="'+from_uid_id+'" session_no="'+from_session_no+'" class="mes_chakan_close chat_people">查看</span></div><span class="mes_num">1</span><span session_no="'+from_session_no+'" mes_id="'+from_uid_id+'"  mestype="'+mestype+'" group-name="'+from_client_name+'" class="mes_close">X</span></div>');
            // } else {
            //   $(".mes_con").append('<div class="mes_box"><span class="mex_con">'+group_name+':'+content1+'</span><div style="height:30px"><span mestype="'+mestype+'" mesid = "'+session_no+'" group-name="'+group_name+'" group-all="'+to_uid_id+'" id="'+from_uid_id+'" class="mes_chakan_close session_no" session_no="'+from_session_no+'">查看</span></div><span session_no="'+from_session_no+'" mesid= "'+session_no+'" mestype="'+mestype+'" class="mes_close">X</span></div>');
            // };
          }
          mesnum++;
          $('.mes_radio').html(mesnum);
        };
      if (session_no == from_session_no) {
        $(".he_ov").append('<li class="Chat_le"><div class="user"><span class="head le"><span class="header-img"><img src="'+header_img_url+'" alt=""></span></span> <span class="name le">'+from_client_name+'<span style="padding: 0 0 0 20px">'+time+'</span></span><div class="mes_content le"><span class="jian le"></span> <span class="content-font le">'+content+'</span></div></div></li>');
          $(".he-ov-box").scrollTop($(".he-ov-box")[0].scrollHeight);
          if ($(".mes_chakan_close[session_no='"+session_no+"']").length > 0) {
            mes_num = parseInt($(".mes_chakan_close[session_no='"+session_no+"']").parent().next('.mes_num').html());
            mes_chakan_close(mestype, session_no, mes_num);
            // mes_close(mestype, session_no, session_no);
          } else {
            mes_chakan_close(mestype, session_no, 0);
          }
      };
    }
    //选择人后的消息列表
    function mes_chat(data){
      // var chatNum = data.length;
      var mes_time;
      var content;
      $(".he_ov").html('');
      for (var i in data) {
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
              content = "<div class='file-box'><div><i class='icon-folder-open icon-2x'> </i><span>"+fileArray[0]+"</span></div><div class='right'><a href='http://7xq4o9.com1.z0.glb.clouddn.com/"+fileArray[1]+"'><i class='icon-cloud-download icon-2x'></i></a></div></div>";
                break;
            case 'image':
                content = data[i].message_content;
                break;
            default:
                break;
        }
        if (data[i].sender_id == uid) {
          $(".he_ov").prepend('<li class="Chat_ri he"><div class="user_ri he"><span class="ri head_ri"><span class="header-img"><img src="'+header_img_url+'" alt=""></span></span> <span class="ri name_ri"><span style="padding: 0 20px 0 0">'+mes_time+'</span>'+name+'</span> <div class="ri content_ri"><span class="arrow ri"></span><span class="content_font_ri">'+content+'</span> </div></div></li>');
        } else {
          $(".he_ov").prepend('<li class="Chat_le"><div class="user"><span class="head le"><span class="header-img"><img src="'+data[i].card_image+'" alt=""></span></span> <span class="name le">'+data[i].sender_name+'<span style="padding: 0 0 0 20px">'+mes_time+'</span></span><div class="mes_content le"><span class="jian le"></span> <span class="content-font le">'+content+'</span></div></div></li>');
        }
      }
    }
    //加载消息
    function onlode(data){
      var mes_time;
      var content;
      if (data.save == 0) {
        $(".he_ov").prepend("<div style='text-align: center;'><span style='color: #fff;padding: 5px 0;'>没有了！</span></div>");
          $('.loader').hide();
          $('.onload').remove();
          $('.he-ov-box').unbind('scroll');
        return;
      };
      $(".he_ov .onload").hide();
      for (var i in data) {
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
              content = "<div class='file-box'><div><i class='icon-folder-open icon-2x'> </i><span>"+fileArray[0]+"</span></div><div class='right'><a href='http://7xq4o9.com1.z0.glb.clouddn.com/"+fileArray[1]+"'><i class='icon-cloud-download icon-2x'></i></a></div></div>";
                break;
            case 'image':
                content = data[i].message_content;
                break;
            default:
                break;
        }
        if (data[i].sender_id == uid) {
          $(".he_ov").prepend('<li class="Chat_ri he"><div class="user_ri he"><span class="ri head_ri"><span class="herder-img"><img src="'+header_img_url+'" alt=""></span></span> <span class="ri name_ri"><span style="padding: 0 20px 0 0">'+mes_time+'</span>'+name+'</span> <div class="ri content_ri"><span class="arrow ri"></span><span class="content_font_ri">'+content+'</span> </div></div></li>');
        } else {
          $(".he_ov").prepend('<li class="Chat_le"><div class="user"><span class="head le"><span class="header-img"> <img src="'+data[i].card_image+'" alt=""></span></span> <span class="name le">'+data[i].sender_name+'<span style="padding: 0 0 0 20px">'+mes_time+'</span></span><div class="mes_content le"><span class="jian le"></span> <span class="content-font le">'+content+'</span></div></div></li>');
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
    // select 人
    $(function(){
    });