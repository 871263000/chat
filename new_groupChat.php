<?php 
	require_once('../config.inc.php');
	require_once('../lib/mesages.class.php');

	$chat_uid = $_SESSION['staffid'];
	$oms_id = $_SESSION['oms_id'];

	$mes = new messageList($chat_uid, $oms_id);
	$arrfrientList = $mes->friendsList();

	if (!empty($arrfrientList)) {
		foreach ($arrfrientList as $key => $value) {
			$friend['id'] =  $value['id'];
			$friend['id'] =  $value['pid'];
			$friend['mestype'] =  'message';
			$friend['contacts_name'] =  $value['name'];
			$friend['mes_id'] =  $value['staffid'];
			$friend['card_image'] =  $value['card_image'];
			$friendSearch[] = $friend;
		}

	}
?>
<!doctype html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0" />
<title>新建群聊</title>
<script src='js/jquery.min.js'></script>
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<style type="text/css">
/**{font-family: '微软雅黑' !important;}*/
html,body {
	height: 100%;
	/*background: #6699FF;*/
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#6699FF', endColorstr='#6699FF'); /*  IE */
	background-image:linear-gradient(bottom, #6699FF 0%, #6699FF 100%);
	background-image:-o-linear-gradient(bottom, #6699FF 0%, #6699FF 100%);
	background-image:-moz-linear-gradient(bottom, #6699FF 0%, #6699FF 100%);
	background-image:-webkit-linear-gradient(bottom, #6699FF 0%, #6699FF 100%);
	background-image:-ms-linear-gradient(bottom, #6699FF 0%, #6699FF 100%);
	
	/*margin: 0 auto;*/
	/*position: relative;*/
	width: 100%;
}
#name_box{ position:fixed;background:#fff;display:none;width: 100%;margin-top:0px; z-index:9999;height: 100%;}
#group_name{ z-index: 0; } 
.myFriend { display: none; width: 80%;height: 300px;margin: auto;border: 1px solid #ccc; }
.myFriend > div { float: left; }
.myFriend .myFriend-icon{ width: 8%;height: 100%;line-height: 300px; text-align: center;vertical-align: middle;}
.myFriend .myFriend-icon span{ width: 32px; height: 32px;margin: auto;display: inline-block; }
.myFriend .chat-orgFriend{ width: 46%;height: 100%;overflow: auto;background: rgb(250,238,241); }
.myFriend .myFriend-list li { height: 36px; line-height: 36px; font-size: 14px; position: relative;}
.myFriend .myFriend-list li:hover {background-color: #ccc;}
.myFriend .myFriend-list .myFriend-items-del { cursor: pointer; width: 32px;height: 32px;padding: 5px;display: inline-block;margin-left: 10px;line-height: 22px;text-align: center;background: #F1A8A8;}

.myFriend .myFriend-body{padding: 2px;}
.myFriend .myFriend-list li img { height: 32px; width: 32px;line-height: 32px; }
.myFriend .myFriend-list li input { display: none; height: 23px; line-height: 23px;width: 15px; float: left;}
.orgFriend { width: 100%;text-align: center; }
.orgFriend > p { display: inline-block; border: 1px solid #ccc; }
.orgFriend .orgFriend-list-cur { background-color: #ccc; }
#chat-orgFriend-list{ overflow: auto; }
#chat-orgFriend .panel{ background: transparent; }
.chat-orgName-select { width: 46%;height: 100%;background: rgb(250,238,241);}
/*.orgFriend .orgFriend-list { border: 1px solid #ccc; }*/
/*.orgFriend  > span { width: 120px; height: 40px; line-height: 40px;display: inline-block; }*/
.mes_chakan_close{ line-height: 20px; cursor: pointer; }
.mes_dclose{ cursor: pointer; position: absolute;  top: 3px;  font-size: 33px;  color: #fff;  right: 0;}
.tab-content{ overflow: auto;position: absolute; width: 100%;top: 105px;bottom: 37px;}
/*#submit{height: 35px;}*/
@media screen and (max-width:500px){
	.myFriend .chat-orgFriend{ width: 100%;height: 100%;overflow: auto;background: rgb(250,238,241); }
	.myFriend { display: none; width: 100%;height: 300px;margin: auto;border: 1px solid #ccc; }
	.chat-orgName-select ,.myFriend-icon{display: none;}
	.myFriend .myFriend-list li input { display: block; }
	.myFriend .myFriend-list li > span { display: inline-block;width: 100%;position: absolute;height: 100%;background-color: transparent;opacity: 0;left: 0;z-index: 111;}
}
.friend-group{ cursor: pointer; position: relative;}
.drop-down{position: absolute;top: 16px;right: 23px;border-left: 6px solid transparent; border-top: 6px solid #000;border-right: 6px solid transparent;  }
h2{margin:0;}
.box_con {
	/*filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#6699FF', endColorstr='#6699FF');  /* IE */*/
/*	background-image:linear-gradient(bottom, #6699FF 0%, #6699FF 100%);
	background-image:-o-linear-gradient(bottom, #6699FF 0%, #6699FF 100%);
	background-image:-moz-linear-gradient(bottom, #6699FF 0%, #6699FF 100%);
	background-image:-webkit-linear-gradient(bottom, #6699FF 0%, #6699FF 100%);
	background-image:-ms-linear-gradient(bottom, #6699FF 0%, #6699FF 100%);*/
	
/*	margin: 0 auto;
	position: relative;*/
	width: 100%;
	height: 100%;
}
.login-box {
	width: 100%;
	max-width:500px;
	position: absolute;
	top: 40%;

	margin-top: -100px;
	/*设置负值，为要定位子盒子的一半高度*/
	
}
@media screen and (min-width:500px){
	.login-box {
		left: 50%;
		/*设置负值，为要定位子盒子的一半宽度*/
		margin-left: -250px;
	}
}	

.form {
	width: 100%;
	max-width:500px;
	margin: 25px auto 0px auto;
	padding-top: 25px;
}	
.login-content {
	width: 100%;
	max-width:500px;
	background-color: rgba(255, 250, 2550, .6);
	float: left;
}		
	
	
.input-group {
	margin: 0px 0px 20px 0px !important;
}
.event{
	padding: 0 15px;
	float: left;
	width: 100%;
}
.event input{
	margin: 10px 0;
	height: 40px;
	line-height: 40px;
	font-size: 16px;
	font-family: '微软雅黑';
	width: 100%;
}
#content{
	text-align: top;
	padding: 0;
	/*line-height: 200px;*/
}
.form-control,
.input-group {
	width: 100%;
	height: 50px;
}

.form-group {
	margin-bottom: 0px !important;
}
.login-title {
	padding: 10px 10px;
	background-color: rgba(0, 0, 0, .6);
}
.login-title h1 {
	margin-top: 10px !important;
}
.login-title small {
	color: #fff;
}
.link p {
	line-height: 20px;
	margin-top: 30px;
}
.btn-sm {
	padding: 8px 24px !important;
	font-size: 16px !important;
}
/*滚动条*/
.group_pep::-webkit-scrollbar{width:100px ;display: block;}
.group_pep::-webkit-scrollbar{width:10px;height:10px}
.group_pep::-webkit-scrollbar-track{background:#fff}
.group_pep::-webkit-scrollbar-thumb{background:#ccc;border-radius:10px}
.group_pep::-webkit-scrollbar-corner{background:#82afff}
.group_pep::-webkit-scrollbar-resizer{background:#ff0bee}
.group_pep{text-align: center;max-height: 300px; overflow: auto;}
.group_pep.ul li{padding: 5px 0; border-bottom: 1px solid #dedede;}
.latt-container{    height: auto !important;overflow: auto !important;position: inherit !important; }	
.latt-wrapper{height: auto !important;  }
</style>

</head>

<body>

<div id="name_box">
	<div class="orgFriend">
		<p>
			<span class="orgFriend-list orgFriend-list-cur btn" data-type= "c">本组织内部好友</span>
			<span class="orgFriend-list btn" data-type="m">我的好友</span>
		</p>
	</div>
	<div class="myFriend">
		<!-- 我的好友 -->
		<div class="chat-orgFriend"> 
			<div class="panel-group" id="chat-orgFriend">
				<div class="panel friend-group">
					<div class="panel-heading" mestype= "groupMessage" data-toggle="collapse" class="group-man-show" data-parent="#chat-orgFriend" href="#chat-orgFriend-list">
						<h4 class="panel-title"><span title="我的好友" >我的好友</span></h4>
					</div>

					<div id="chat-orgFriend-list" class="panel-collapse collapse">
						<div class="panel-body myFriend-body">
							<ul class="list-group myFriend-list">
							<?php if (!empty($arrfrientList)) :?>
								<?php foreach ($arrfrientList as $k => $v) : ?>
									<li mes_id="<?php echo $v['staffid'];?>" group-name="<?php echo $v['name'];?>" class="myFriend-items"><span class="myFriend-items-Mask" mes_id="<?php echo $v['staffid'];?>"></span><input type="checkbox" mes_id="<?php echo $v['staffid'];?>" class="myFriend-items-in"><img class="header-img" src="<?php echo empty($v['card_image'])?'/chat/images/niming.png':$v['card_image']; ?>" alt=""><?php echo $v['name'];?></li>
								<?php endforeach; ?>
							<?php else:?>
								还没有好友！
							<?php endif ;?>
							</ul>
						</div>
					</div>
					<span class="caret drop-down"></span>
				</div>
			</div>
		</div>
		<div class="myFriend-icon"><span><img src="/chat/images/jiantou.png" alt=""></span></div>
		<!-- 选择人后的容器 -->
		<div class="chat-orgName-select">
		<h5>已选好友</h5>
			<ul class="list-group myFriend-list">
				
			</ul>
		</div>
	</div>

	<!-- 公司组织 -->
	<div class="orgJiagou" style="height: 400px; overflow: auto;"></div>
	<div style="clear:both;"></div>
	<div id="orgAct" style="width:100%;text-align: center;"></div>
	<div style="width:100%;text-align: center;">
		<button style="" id="b_no" class="btn btn-sm btn-info" >取&nbsp;消</button><button style="" id="b_is"  class="btn btn-sm btn-info">确&nbsp;定</button>
	</div>
</div>
<script src="/lattice/Publics/Js/selectPerson.js"></script>
<script type="text/javascript">
lattSetting({
	    glob:{ //不设置这个属性或者属性为空就不强制应用全局属性
	        // val:'#chooseTec',    //字符串值存放位置
	        // ignore:[true,false],   //是否忽略（如果设置了一致性，下面也还设置val的话讲优先采取下面的）
	        // type:[1,2,3],
	        // on:['click','dblclick','.....'],
	        isfill:false,  //是否把结果值填充的val选择器中 [true,false];
	    },
	    items:[
	        {
	            type:1,  //组织架构类型
	            val:'#s_man',   // 选择人
	            // sidstorage: '#group_participants',
	            callback:function(sidList,names){
	            	//在手机上交替显示
	            	var arrStaffid = [], strStaffid;
					for (var i = 0; i < sidList.length; i++ ) {
						arrStaffid.push(sidList[i][1]);
					}
					if ( typeof friendMap != 'undefined' ) {
						Array.prototype.push.apply(arrStaffid, friendMap);
					};
					$('.group_pep').html('');
					var jsonText = JSON.stringify(arrStaffid);
					strStaffid = join(',',arrStaffid);
					$('#group_participants').val(strStaffid);
		            $.ajax({
		              url:'getndp.php',
		              data:'jsonText='+jsonText,
		              type:'post',
		              success:function(data){
		                var d=eval('('+data+')');
		                for (var i = 0; i < d.length; i++) {
		                  $('.group_pep').append("<li class='list-group-item'>"+d[i]['name']+"</li>");    
		                }
		              }
		            });
	            }
	        },

	    ],
	})
	$(function () {
		$(document).on('click', '#s_man', function (e) {
			// e.stopPropagation();
			$('#name_box').show();
			$('.latt-container').css('height', '400px !important').appendTo($('.orgJiagou'));
			$('#latt-ok').hide();
			$('.latt-container .btn').hide();
		});
		var nIs= $('#b_is');
		var nNo= $('#b_no');
		nIs.click(function  () {
			$('#latt-ok').trigger('click');
			$('#name_box').hide();
		});
		nNo.click(function () {
			$('#name_box').hide();
		})

	})
	$(function () {
		//删除指定数组元素
		var _chat_remove = function (val, array) {
		    var index = array.indexOf(val);
		    if (index > -1) {
		        array.splice(index, 1);
		    }
		    return array;
		}
		// 手机 上的点击
		var myFriendItemsIn = $('.myFriend-items-in');

		var friendItem = $('.myFriend-items');
		var orgNameSelected = $('.chat-orgName-select ul');
		var orgFriendList = $('.orgFriend-list');
		orgFriendList.click(function () {
			var dataType = $(this).attr('data-type');
			orgFriendList.removeClass('orgFriend-list-cur');
			$(this).addClass('orgFriend-list-cur');
			if ( dataType == 'c' ) {
				$('.myFriend').hide();
				$('.orgJiagou').show();
			} else {
				$('.myFriend').show();
				$('.orgJiagou').hide();
			}
		})

		// window.friendMap = '';
		var friendSelect = function  () {
			this.mes_id ='';
			this.group_name ='';
			this.friendMap = [];
		}
		friendSelect.prototype.addMan = function (obj){

			if ( $.inArray(this.mes_id, this.friendMap)  == -1 ) {
				if ( typeof obj == 'object' ) {
					var selected = obj.clone();
					var manDel = $('<span mes_id="'+this.mes_id+'">&times;</span>');
					selected.addClass('myFriend-items-sel');
					manDel.addClass('myFriend-items-del');
					manDel.css('display', 'none');
					// 删除 已选好友
					manDel.click(function (e) {
						e.stopPropagation();
						var mes_id = $(this).attr('mes_id');
						myFriend.mes_id = mes_id;
						selected.remove();
						myFriend.delMan();
					})
					selected.hover(function () {
						manDel.css('display', 'inline-block');
					}, function () {
						manDel.css('display', 'none');
					});
					selected.append(manDel);
					orgNameSelected.append(selected);
				}
				this.friendMap.push(this.mes_id);
				return window.friendMap = this.friendMap;
			};
		}
		friendSelect.prototype.delMan = function (obj) {
			this.friendMap = _chat_remove(this.mes_id, this.friendMap);
			// this.friendMap.remove(this.mes_id);
			return window.friendMap = this.friendMap;
		}
		var myFriend = new friendSelect();
		
		friendItem.click(function (e) {
			var $this = $(this);
			var $thisIn = $this.find('input');
			var mes_id = $this .attr('mes_id');
			var group_name = $this .attr('group-name');
			myFriend.mes_id = mes_id;
			myFriend.group_name = group_name;
			myFriend.addMan($(this));
		});

		// 手机上的 input  点击
		$('.myFriend-items-Mask').click(function (e){
			e.stopPropagation();
			var mes_id = $(this).attr('mes_id');
			myFriend.mes_id = mes_id;
			if ( $(this).next().prop('checked') ) {
				$(this).next().prop('checked', false);
				myFriend.delMan();
			} else {
				myFriend.addMan('');
				$(this).next().prop('checked', true);
			}
		})
	})
</script>
<div class="box">
		<div class="login-box">
			<div class="login-title text-center">
				<h1><small>新建群聊</small></h1>
			</div>
			<div class="box_con">
				<div class="login-content ">
				<div class="form">
				<form action="oms_group_chat_add_action.php" method="post">
					<div class="form-group">
						<div class="col-xs-12  ">
							<div class="input-group">
								<input type="text" id="group_name" name="group_name" class="form-control" placeholder="新群聊名称">
							</div>
						</div>
					</div>
					<div>
						<h2 style="margin-left: 16px;" id="s_man" class="btn btn-sm btn-info">选择参加人</h2>
						<!--<small>(不需要选择自己)</small>-->
						<input type="hidden" id="group_participants" name="group_participants" />
					</div>
					<div>
						<ul class="group_pep list-group">
						</ul>
					</div>
					<div class="form-group form-actions">
						<div style="text-align: center;" class="">
							<a href="javascript:void(0)"  onclick="window.open('about:blank','_self'); window.close();"  class="btn btn-sm btn-info"> 取消</a>
                            <button type="submit" class="btn btn-sm btn-info createGroup"> 提交</button>
						</div>
					</div>
				</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
          // console.log(sidList)
          //button确定
          // var staffUid = '';
   //       $('#b_is').click(function (){
			// if ( typeof friendMap != 'undefined' ) {
			// 	Array.prototype.push.apply(sidList, friendMap);
			// };
   //          var jsonText = JSON.stringify(sidList);
   //          $('.group_pep').html('')
   //          to_uid = sidList.join(',');
   //          $('#group_participants').val(to_uid);
   //          $.ajax({
   //            url:'getndp.php',
   //            data:'jsonText='+jsonText,
   //            type:'post',
   //            success:function(data){
   //              var d=eval('('+data+')')
   //              for (var i = 0; i < d.length; i++) {
   //                $('.group_pep').append("<li class='list-group-item'>"+d[i]['name']+"</li>");    
   //              }
   //            }
   //          })
   //          $('#name_box').hide();
   //          $('.selected').find('div').html('<ul></ul>');
   //          $('#No1').find('.ltclasscheckbox').attr('checked',false);
   //          $('.select_member_num').html($('.selected').find('sid').length+'/'+$('#No1').find('sid').length);
   //           staffUid = sidList;
   //           sidList=[];

   //        })
   //        //button取消
   //        $('#b_no').click(function(){
   //          $('#name_box').hide();
   //        })
        </script>
<script type="text/javascript">


//选择人
// $('#s_man').click(function(){
//   $('#name_box').show();
// })
$('.createGroup').on('click',function(){
	if ( $('#group_name').val() == '' ){
		alert('群聊名字不能为空！');
		return false ;
	}
	if (staffUid.length == 0) {
		alert('还没有选择人!')
		return false;
	};
})
</script>
</body>
<?php require_once('header.php');?>
</html>