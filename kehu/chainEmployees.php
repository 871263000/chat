<?php
require_once('header.php');
//最近联系人
if (isset($uid)) {
  	$recentContact = $mes->recentContact();
}
//最近联系人session_no集合;
$ContactManSession = [];
foreach ($recentContact as $key => $value) {
	$ContactManSession[] = $value['session_no'];
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script type="text/javascript" src = "/chat/js/jquery.js"></script>
	<script type="text/javascript" src = "/chat/js/web_message.js"></script>
	<link rel="stylesheet" href="/chat/css/bootstrap.min.css">
	<link rel="stylesheet" href="/chat/css/style.css">
</head>
<style>
	@font-face {
	  font-family: 'iconfont';
	  src: url('//at.alicdn.com/t/font_1457932407_5732756.eot'); /* IE9*/
	  src: url('//at.alicdn.com/t/font_1457932407_5732756.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
	  url('//at.alicdn.com/t/font_1457932407_5732756.woff') format('woff'), /* chrome、firefox */
	  url('//at.alicdn.com/t/font_1457932407_5732756.ttf') format('truetype'), /* chrome、firefox、opera、Safari, Android, iOS 4.2+*/
	  url('//at.alicdn.com/t/font_1457932407_5732756.svg#iconfont') format('svg'); /* iOS 4.1- */
	}
                    
                    
                    
                    
	.staff-refresh, .staff-close{
		font-family: 'iconfont';
	}
</style>
<script>
	    var uid;
    $(function (){
        connect();
    })
    var custom = 1;//客服id
    var mes_online = 1;
    var oms_id = "<?php echo $oms_id;?>";
    uid = "<?php echo $uid;?>"; // 发送人id
    var name = "<?php echo $name;?>";// 发送人name
    var accept_name ="<?php echo $accname?>";//接收人名字
    var room_id = "<?php echo isset($oms_id) ? $oms_id : 1?>";//房间id
    var to_uid = <?php echo isset($_GET['staffid']) ? $_GET['staffid'] : 0;?>;// 接收人id
    // var db_id;//indexeddb
    var to_uid_header_img ='';// 接收人id
    var mes_type = "<?php echo $mes_type?>";
    var session_no = "<?php echo $session_no?>";//会话id
    var document_url = "<?php echo DOCUMENT_URL?>";
    var groupId = 0;
    var header_img_url = "<?php echo $card_image?>";



    nearestContact = new Object();
    nearestContact = <?php echo !empty($ContactManSession) ? json_encode( $ContactManSession ): json_encode([]);?>;
</script>
<body>
	<div class="chainEmployees">
		<div class="externalStaffTitle"><span class="staff-refresh">&#xe600;</span><span class="staff-close">&#xe601;</span></div>
		<ul class="list-group"></ul>
	</div>
	<div class="External-staffid" oms_id="2" style="width:100px; height: 100px;">
	<span class="mes_title_con"></span>
		点击
		<input type="submit" id="submit">
	</div>
</body>
<script type="text/javascript">
// 外部选择人聊天
$('.external_chat_people').live('click', function( e ){
  	to_uid = $(this).attr('mes_id');
  	to_uid_header_img = $(this).find('img').attr('src');
  	//会话id的改变
  	session_no = to_uid < uid ? to_uid+"-"+uid : uid+"-"+to_uid;
  	mes_type = "message";
  	if (!$(e.target).is('.recent-action')) {
    	if ($(window).width() < 700) {
      		$('.chat-container').show();
      		$('.details-list').hide();  
    	};
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
	//最近联系人增加与更新
	var addContact = {};
	//判断当前会话在最近联系人哪里有没有 
	addContact.is =  function (session_id) {
		for (var i in nearestContact) {
			if ( nearestContact[i] == session_id) {
				return true;
			};
		}
		return false;
	}
	//增加最近联系人
	addContact.Dom = function () {
		var data = {
		    "type": "addContact",
		   	"mestype": 'message',
		   	"session_no" : session_no,
		   	"sender_name": name,
		   	"accept_name": $('.mes_title_con').text(),
		   	"mes_id": to_uid,
		  	"to_uid_header_img": to_uid_header_img,
		   	"timeStamp": new Date().getTime(),
	  	};
		nearestContact.push(session_no);
	  	ws.send(JSON.stringify(data));

	};
	document.getElementById('submit').addEventListener('click', function () {
		addContact.Dom();
	})
	//最近联系人更新
	// addContact.upd = function (session_id) {
	// 	if ($('.recent-hover[session_no="'+session_id+'"]').parent('li').index() !=0 ) {
	//   		$('.con-tab-content .list-group').prepend($('span[session_no="'+session_id+'"]').parent('li'));
	//   		ws.send('{"type": "updContact", "session_no": "'+session_id+'"}')
	// 	};
	// }
	// $(function (){
	// 	connect()
	// })
	//储存外来员工的信息
	var getExternal = {};

	getExternalObj = $('.External-staffid, .staff-refresh');
	//点击联系外来人员的事件；
	getExternalObj.click(function () {
		getExternal.oms_id = $(this).attr('oms_id');
		//发送请求获得外公司的人员；
		$('.chainEmployees').css('display', 'block');
		ws.send('{"type":"getChainEmployees", "oms_id": '+getExternal.oms_id+'}');
	});
	//联系外来人员鼠标滑过的事件
	$(document).on('mouseover', '.chainEmployees ul li', function() {
		$(this).css('border-bottom', '1px solid #E02020')

	});
	//联系外来人员鼠标离开的事件
	$(document).on('mouseout', '.chainEmployees ul li', function () {
		$(this).css('border-bottom', '1px solid #ccc')
	})
	$('.staff-close').click(function () {
		$('.chainEmployees').css('display', 'none');
	});
	// getExternalObj.////
</script>
</html>