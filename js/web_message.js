if (typeof console == "undefined") {    this.console = { log: function (msg) {  } };}
    WEB_SOCKET_SWF_LOCATION = "/swf/WebSocketMain.swf";
    WEB_SOCKET_DEBUG = true;
    var ws, client_list={};
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
      var login_data = '{"type":"login","oms_id":"'+oms_id+'", "uid": "'+chat_uid+'", "header_img_url":"'+header_img_url+'",  "client_name":"'+name+'","room_id":"'+room_id+'"}';
      ws.send(login_data);
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
                sayUid(data['mes_types'], data['mestype'], data['header_img_url'],data['group_name'], data['insert_id'],  data['session_no'], data['from_uid_id'], data['to_uid_id'], data['from_client_id'], data['from_client_name'], data['content'], data['time']);
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
            case 'chain_staff_list':
              var staff_list = data['staff_list'];
              var tagFriend = 0, addClass, addClassSession, iconcheage;
              $('.chainEmployees ul.chat_on_all').html('');
              for ( var i in staff_list) {
                tagFriend = 0;
                addClass = '';
                iconcheage = '&#xe608;';
                addClassSession= 'apply_session';
                for ( var y in friendList) {
                  if ( friendList[y].staffid == i ) {
                    tagFriend = 1;
                    addClass = 'external_chat_people';
                    iconcheage = "&#xe603;";
                    addClassSession = addClass;
                  }
                }
                $('.chainEmployees ul.chat_on_all').append('<li tagFriend = "'+tagFriend+'" class="chain_friend_all '+addClass+'" mes_id = '+i+' org_name = "'+$org_name+'" group-name = "'+staff_list[i].client_name+'"><span class="externalStaffid-header-img"><img src="'+staff_list[i].header_img_url+'" alt="'+staff_list[i].client_name+'" /></span>'+staff_list[i].client_name+'<span class="icon16 '+addClassSession+'" name = "'+staff_list[i].client_name+'" tagFriend = "'+tagFriend+'" mes_id = '+i+' group-name = "'+staff_list[i].client_name+'"  title= "会话">'+iconcheage+'</span></li>');
              }
              break;
            case 'mes_notice_close':
              $('.chat_notice_container').show(500);
              $('.chat_notice_list_box').html('');
              for ( var i in data) {
                if ( i == "type") {
                  return;
                };
                $('.chat_notice_list_box').append('<div class="chat_notice_list"><div class="chat_notice_img"><img src="'+data[i].pid_header_url+'" alt=""></div><div class="chat_notice_con"><span>'+data[i].pid_name+'【请求对话】</span><span>公司：'+data[i].additional_Information+'</span></div><div data-parm = "agree" sender-id = "'+data[i].pid+'" class="chat_notice_agree chat_notice_sel">同意</div><div data-parm = "unagree" class="chat_notice_unagree chat_notice_sel" sender-id = "'+data[i].pid+'">不同意</div></div>');
              }
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
      ws.send('{"type":"sayUid","to_uid_id":['+to_uid+'],"senderid":"'+senderid+'", "message_type":"'+message_type+'",  "to_client_name":"'+to_client_name+'","content":"'+content+'"}');
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
          inputcur  = "<div class='file-box'><div><i class='icon-folder-open icon-2x'> </i><span>"+fileName+"</span></div><div class='right'><a href='http://7xq4o9.com1.z0.glb.clouddn.com/"+fileUrl+"'><i class='icon-cloud-download icon-2x'></i></a></div></div>";
          inputValue = fileName+"|"+fileUrl;
          break;
        case 'voice':
        inputValue = $('.chat_voice_box').attr('voice_url');
        ws.send('{"type":"sayUid","to_uid_id":"'+to_uid+'","senderid":"'+chat_uid+'", "groupId":"'+groupId+'", "accept_name":"'+accept_name+'","message_type":"'+message_type+'", "mes_types":"'+mes_types+'","session_no":"'+from_session_no+'","content":"'+inputValue+'"}');
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
      $(".he_ov").append('<li class="Chat_ri he"><div class="user_ri he"><span class="ri head_ri"><span class="header-img"><img src="'+header_img_url+'" alt=""></span></span> <span class="ri name_ri"><span style="padding: 0 20px 0 0">'+nowTime+'</span>'+name+'</span> <div class="ri content_ri"><span class="arrow ri"></span><span class="content_font_ri">'+inputcur+'</span> </div></div></li>');
      $(".he-ov-box").scrollTop($(".he-ov-box")[0].scrollHeight);
      ws.send('{"type":"sayUid","to_uid_id":"'+to_uid+'","senderid":"'+chat_uid+'", "groupId":"'+groupId+'", "accept_name":"'+accept_name+'","message_type":"'+message_type+'", "mes_types":"'+mes_types+'","session_no":"'+from_session_no+'","content":"'+inputValue+'"}');
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
            content = "<div class='file-box'><div><i class='icon-folder-open icon-2x'> </i><span>"+fileArray[0]+"</span></div><div class='right'><a href='http://7xq4o9.com1.z0.glb.clouddn.com/"+fileArray[1]+"'><i class='icon-cloud-download icon-2x'></i></a></div></div>";
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

                $(".mes_con").append('<div class="mes_box mes_chakan_close chat_notice" chat_mes_num="1"  mestype="'+mestype+'"  group-name="'+from_client_name+'" mes_id="'+from_uid_id+'" session_no="'+from_session_no+'"><div class= "mes_header"><img src="'+header_img_url+'" alt="'+from_client_name+'" /></div><span class="mex_con">'+from_client_name+'【 请求会话 】</span><div class="mes_content_list" style=""><span class="chat_mes_content">'+content1+'</span></div><span class="mes_num">1</span><span session_no="'+from_session_no+'" mes_id="'+from_uid_id+'"  mestype="'+mestype+'" group-name="'+from_client_name+'" class="mes_close">X</span></div>');

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
      var addVoiceClass = "";
      $(".he_ov").html('');
      console.log()
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
          $(".he_ov").prepend('<li class="Chat_ri he"><div class="user_ri he"><span class="ri head_ri"><span class="header-img"><img src="'+header_img_url+'" alt=""></span></span> <span class="ri name_ri"><span style="padding: 0 20px 0 0">'+mes_time+'</span>'+name+'</span> <div class="ri content_ri"><span class="arrow ri"></span><span class="content_font_ri '+addVoiceClass+'">'+content+'</span> '+content2+' </div></div></li>');
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
      var addVoiceClass = "";
      if (data.save == 0) {
        $(".he_ov").prepend("<div style='text-align: center;' class= 'seeMore' ><span style='padding: 5px 0;'>没有了！</span></div>");
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
          $(".he_ov").prepend('<li class="Chat_ri he"><div class="user_ri he"><span class="ri head_ri"><span class="herder-img"><img src="'+header_img_url+'" alt=""></span></span> <span class="ri name_ri"><span style="padding: 0 20px 0 0">'+mes_time+'</span>'+name+'</span> <div class="ri content_ri"><span class="arrow ri"></span><span class="content_font_ri '+addVoiceClass+'">'+content+'</span>'+content2+' </div></div></li>');
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
    // select 人
    $(function(){
    });