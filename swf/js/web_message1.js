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
        var login_data = '{"type":"login","oms_id":"'+oms_id+'", "uid": "'+uid+'", "client_name":"'+name+'","room_id":"'+room_id+'"}';
        console.log("websocket握手成功，发送登录数据:"+login_data);
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
                break;;
            // 登录 更新用户列表
            case 'login':
                //{"type":"login","client_id":xxx,"client_name":"xxx","client_list":"[...]","time":"xxx"}
                // say(data['client_id'], data['client_name'],  data['client_name']+' 加入了聊天室', data['time']);
                if(data['client_list'])
                {
                    client_list = data['client_list'];
                }
                else
                {
                    client_list[data['client_id']] = data['client_name']; 
                }
                flush_onlineman_list();
                // console.log(data['client_name']+"登录成功");
                break;
            case 'say_uid':
                console.log(data);
                say_uid(data['mestype'], data['group_name'], data['session_no'], data['insert_id'], data['from_uid_id'],data['from_client_id'], data['from_client_name'], data['content'], data['time']);
                // showMessage(data['content']);
                break;
            //审核消息
            case 'audit':
                audit(data['session_no'], data['from_client_id'], data['from_client_name'], data['content'], data['message_url'], data['time']); 
            // 用户退出 更新用户列表
              break;
            case 'logout':
                // {"type":"logout","client_id":xxx,"time":"xxx"}
                // say(data['from_client_id'], data['from_client_name'], data['from_client_name']+' 退出了', data['time']);
                delete client_list[data['from_client_id']];
                flush_onlineman_list();
                break;

        }
    }
    /*window消息提醒*/
    function showMsgNotification(title, msg, document_url){
      var Notification = window.Notification || window.mozNotification || window.webkitNotification;
      if (Notification && Notification.permission === "granted") {
        var instance = new Notification(
          title, {
          icon: "http://www.omso2o.com/images/header.jpg",
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
            icon: "http://www.omso2o.com/images/header.jpg",
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
    // 刷新在线人数
    function flush_onlineman_list(){
        var onlineman_ul = $(".online_man ul");
        var online_ren = 0;
        var arrUid = new Array();
        onlineman_ul.empty();
        for(var p in client_list){
        if ($.inArray(client_list[p]['uid'], arrUid) == -1) {
            online_ren++;
            onlineman_ul.append('<a href="/chat/index.php?staffid='+client_list[p]['uid']+'" target="_blank"><li id="'+client_list[p]['uid']+'">'+client_list[p]['client_name']+'</li></a>');
        };
        arrUid.push(client_list[p]['uid']);
        }
        $('.online_ren').html(online_ren);
    }
    //发言2
    //发言2
    function say_uid(mestype, group_name, from_session_no, insert_id, from_uid_id,from_client_id, from_client_name, content, time){
      if (mestype == "message") {
        showMsgNotification(from_client_name, content, document_url);
        $(".mes_con").append('<div class="mes_box"><span class="mex_con">'+from_client_name+':'+content.replace(/%5C/g,"\\").replace(/%6b/g," ")+'</span><div style="height:30px"><a href = "chat/index.php?staffid='+from_uid_id+'&session_no='+from_session_no+'" mesid= "'+insert_id+'" mestype="'+mestype+'" class="mes_chakan_close" target="_blank"><span mesid = "'+insert_id+'" class="mes_chakan">查看</a></span></div><span session_no="'+from_session_no+'" mesid= "'+insert_id+'" mestype="'+mestype+'" class="mes_close">X</span></div>');
      } else {
        showMsgNotification(group_name, content, document_url);
        $(".mes_con").append('<div class="mes_box"><span class="mex_con">'+group_name+':'+content.replace(/%5C/g,"\\").replace(/%6b/g," ")+'</span><div style="height:30px"><a href = "chat/index.php?staffid='+from_uid_id+'&session_no='+from_session_no+'" session_no="'+from_session_no+'" mesid= "'+insert_id+'" mestype="'+mestype+'" class="mes_chakan_close" target="_blank"><span mesid = "'+insert_id+'" class="mes_chakan">查看</a></span></div><span session_no="'+from_session_no+'" mesid= "'+insert_id+'" mestype="'+mestype+'" class="mes_close">X</span></div>');
      };
       mesnum++;
       $('.mes_radio').html(mesnum);
    }
    //审核消息
    function audit(from_session_no, from_client_id, from_client_name, content, message_url,time){
      $(".mes_con").append('<div class="mes_box"><span class="mex_con">'+content+'</span><div style="height:30px"><a href="'+message_url+'"><span mesid = "'+from_session_no+'" class="mes_chakan">查看</span></a></div><span mesid= "'+from_session_no+'" class="mes_close">X</span></div>');
       //消息加一
       mesnum++;
       $('.mes_radio').html(mesnum);
    }