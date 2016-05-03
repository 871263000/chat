// 外部选择人聊天



$(document).on('click', '.chain_friend_all', function ( e ) {
	var chain_friend_all = $(this).attr('tagfriend');
	if ( chain_friend_all == "0") {
		alert('你们还没有关注不能对话！可以点右边的图标申请对话！');
	};
})
//拖动
var _move = false;

//联系外公司tab
var chainTab  =  $('.chat_tab_list');


$(document).on('click', '.apply_session' , function ( e ) {
    e.stopPropagation();
	var mes_id = $(this).attr('mes_id');
	var chainName = $(this).attr('name');
	var tagfriend = $(this).attr('tagfriend');
	console.log('{"type": "addFriends", "uid": "'+mes_id+'", "accept_name": "'+chainName+'", "companyName": "'+$org_name+'", "oms_id": '+$oms_id+'}');
	ws.send('{"type": "addFriends", "uid": "'+mes_id+'", "accept_name": "'+chainName+'", "companyName": "'+$org_name+'", "oms_id": '+$oms_id+'}');
	if ( tagfriend == 1 ) {
		alert('你们已经关注了！');
		return;
	};
	alertMes('已发送请求！');
	return;
})

chainTab.click(function () {
	var _this = $(this);
	var _index = $(this).index();
	$('.chat_tab_list').removeClass('current');
	_this.addClass('current');
	$('.chain_chat_con').removeClass('chat_friend_current');
	$('.chain_chat_con').eq(_index).addClass('chat_friend_current');

})
/****提示**************/

//提示消息

var alertMes = function ( con ) {
	$('.alertMesCon').text(con);
	$('.alert').show(500);
	setTimeout(function(){
		$('.alert').hide(500);
	},2000);
}

$('.close').click(function () {
	$('.alert').hide(500);
});

// $('.alert').show(500);
// setTimeout(function(){
// 	$('.alert').hide(500);
// },2000);


//拖动
jQuery(document).ready(function ($) {
	$(".chainEmployees").mousedown(function (e) {
	    _move = true;
	    __y = e.pageY;
	    __x = e.pageX;
	    _y = e.pageY - parseInt($(".chainEmployees").css("top"));
	    _x = e.pageX - parseInt($(".chainEmployees").css("left"));
	});
	$(".chainEmployees").mousemove(function (e) {
	    if (_move) {
	        var y = e.pageY - _y;
	        var x = e.pageX - _x;
	        // var wx = $(window).width() - $('#spig').width();
	            $(".chainEmployees").css({
	              top: y,
	              left: x,
	            }); //控件新位置
	          	ismove = true;
	    }
	}).mouseup(function () {
	      	_move = false;
	});
});
//滚动条滚动事件
var mesScroll = function (){
    if ($(".he-ov-box").scrollTop() <= 10 && $(".he-ov-box").scrollTop() >= 0) {
      var mes_loadnum = $('#mes_load').html();
      $('.loader').show()
      mesHeight = $('.he_ov').height()
      ws.send('{"type":"mes_load","mes_loadnum":"'+mes_loadnum+'", "message_type":"'+mes_type+'", "to_uid":"'+to_uid+'","session_no": "'+session_no+'"}');
    };
}
//员工的点击事件
$(document).on('click', '.external_chat_people', function( e ){
  	to_uid = $(this).attr('mes_id');
  	to_uid_header_img = $(this).find('img').attr('src');
  	//会话id的改变
  	session_no = to_uid < chat_uid ? to_uid+"-"+chat_uid : chat_uid+"-"+to_uid;
  	mes_type = "message";
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
    ws.send('{"type":"mes_chat", "to_uid":"'+to_uid+'"}');
    $('#mes_load').html(10);
    //消息向上滚动
    $('.he-ov-box').unbind('scroll');
    $('.he-ov-box').bind("scroll", function (){
      mesScroll();
    })
});
//最近联系人增加与更新
// var addContact = {};
//判断当前会话在最近联系人哪里有没有 
//储存外来员工的信息
var getExternal = {};

getExternalObj = $('.External-staffid, .staff-refresh');

//点击联系外来人员的事件；
getExternalObj.click(function () {
	// if ( customerStatu != 2 ) {
	// 	alert("你们没有相互关注还不能交谈~！");
	// 	return;
	// };
	getExternal.oms_id = $(this).attr('oms_id');
	if (getExternal.oms_id == 0) {
		alert('该公司还没有注册oms管理系统！');
		return;
	};
	//发送请求获得外公司的人员；
	$('.chainEmployees').css('display', 'block');
	ws.send('{"type":"getChainEmployees", "oms_id": '+getExternal.oms_id+'}');
});
//联系外来人员鼠标滑过的事件
$(document).on('mouseover', '.chainEmployees ul.list-group li', function() {
	$(this).css('border-bottom', '1px solid #E02020')

});
//联系外来人员鼠标离开的事件
$(document).on('mouseout', '.chainEmployees ul.list-group li', function () {
	$(this).css('border-bottom', '1px solid #ccc')
})
$('.staff-close').click(function () {
	$('.chainEmployees').css('display', 'none');
});
