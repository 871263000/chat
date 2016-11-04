$(function () {
 // websocket 连接 

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
};
connect();

//  websocket 后台发来的消息
function onmessage(e) {
	var data = JSON.parse(e.data);
	var type = data['type'];
	if ( type == 'ping' ) {
		return false;
	};
	mesPUsh.data = data;
	if ( typeof omsChat.type[type] == 'function' ) {
		omsChat.type[type]();
	};
};


// 自己的信息
var selfInfo = {
	chat_uid : '',
	chat_name: '',
	header_img_url: '',
	oms_id: ''
};
// 连接建立时发送登录信息
function onopen() {
    // 登录
    // console.log(selfInfo.header_img_url);return false;
    $.ajax({
    	url: '../lib/userInfo.php',
    	dataType: 'json',
    	success: function (data) {
    		var login_data = '{"type":"login","oms_id":"' + data.oms_id + '", "uid": "' + data.staffid + '", "header_img_url":"' + data.card_image + '",  "client_name":"' + data.name + '","room_id":"' + data.oms_id + '"}';
    		ws.send(login_data);
    	}
    })
};
// 消息推送 
var mesPUsh = function (option) {
	this.data = '';
	//this.mesNumObj = $('.chat-num');
	// this.mesNum = 0;
	// this.option = option;

};
mesPUsh.prototype.type = {
	'login': function () {

	},
	'ping': function () {
		// body...
	},
	'say_uid': function () {
        var mesNumObj = $('.chat-num');
		if ( mesNumObj.html() != 0 ) {
			var mesNum = parseInt( mesNumObj.html() );
            mesNum += 1;
			mesNumObj.html(mesNum);
		} else {
            mesNumObj.show();
			mesNumObj.html(1);
		}
	},
	'mesClose': function () {
        var mesNumObj = $('.chat-num');
		mesPUsh.data.mesNum = parseInt(mesPUsh.data.mesNum);
		var mesNum = parseInt( mesNumObj.html() );
		mesNum -= mesNum;
		mesNumObj.html(mesNum);
        if ( mesNum == 0 ) {
            mesNumObj.hide();
        }
	}
}
 // 实例化 聊天 
var omsChat = new mesPUsh();

})