<?php
require_once(__DIR__.'/../config.inc.php');
require_once(DOCUMENT_ROOT.'/lib/mesages.class.php');


$chat_uid = $_SESSION['staffid'];
$oms_id = $_SESSION['oms_id'];

$pageload = 10;//消息显示的条数
$session_no = 0;//会话id

$mesNum = 0;

$messageSessionList = [];
//消息类型
// $mes_type = 'message';
//实例化消息
$mes = new messageList( $chat_uid, $oms_id );
//最近联系人
if (isset($chat_uid)) {
	$recentContact = $mes->recentContact();
}
//最近联系人session_no集合;
$ContactManSession = [];

//群聊列表
$arrGroup = $mes->groupChatList();

// 好友列表
$arrfrientList = $mes->friendsList();

$friendSearch = $recentContact;
// 最近联系人  集合
if (is_array($friendSearch)) {
	foreach ($friendSearch as $key => $value) {
		$ContactManSession[] = $value['session_no'];
	}
}
//搜索

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
// 加入管理员 群 的 session
$ContactManSession[] = 'ca';

//自己的信息
$userinfo = $mes->userinfo();
$chat_name = $userinfo['name'];//自己名字
$card_image = $userinfo['card_image'];//头像的url
$isAdmin = $userinfo['general_admin'];
//提示消息列表
if (isset($chat_uid)) {
	$arrMes = $mes->mesAlertList($isAdmin);
	if (!empty($arrMes)) {
		foreach ($arrMes as $key => $value) {
			$mesNum  += $value['mes_num'];
			$messageSessionList[] = $value['session_no'];
		}
	}
}
// print_r($arrMes);
//管理员的所有信息 
// $chatAdminList = $mes->getAdmin();
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<!-- Include these three JS files: -->
	<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" src="js/touchSwipe.js?"></script>
	<script src="js/jquery-ui.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/chatViewer.min.js"></script>
	<script type="text/javascript" src="js/web_message.js?v=1.0.0"></script>
	<link rel="stylesheet" href="css/jquery-ui.css">
	<link rel="stylesheet" href="css/viewer.min.css">
	<link rel="stylesheet" href="css/webRight.css" type="text/css"/>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<link rel="stylesheet" href="css/font-awesome.min.css" type="text/css"/>
	<link rel="stylesheet" href="css/app.css" type="text/css"/>
	<script src="/js/selectPerson.js"></script>
	<script type="text/javascript">
		var  chatConfig = {
			chat_uid: "", // 自己的id
			chat_name: "", // 自己的 名字
			room_id: "",//  房间id
 			to_uid_header_img: "", // 接受这者 的头像url
			session_no: "", // 会话id
			mes_type: "", // 会话 类型 message 单聊 ，  groupMessage
		};
		var freeChat = function () {
			
		}
		var chat_uid = "<?php echo $chat_uid;?>";
		$(function (){
			//连接 服务器
			connect();
		})
		var custom = 1;//客服id
		var mes_online = 1;
		var oms_id = "<?php echo $oms_id;?>";//自己的组织id
		chat_uid = "<?php echo $chat_uid;?>"; // 自己的用户id
		var chat_name = "<?php echo $chat_name;?>";// 自己的名字
		var accept_name ="<?php echo $accname?>";//接收人名字
		var room_id = "<?php echo isset($oms_id) ? $oms_id : 1?>";//房间id
		var to_uid = <?php echo isset($_GET['staffid']) ? $_GET['staffid'] : 0;?>;// 接收人id
		// var db_id;//indexeddb
		var to_uid_header_img ='';// 接收人id
		var mes_type = "message";
		var session_no = 0;//会话id
		var document_url = "<?php echo DOCUMENT_URL?>";
		var groupId = 0;
		var header_img_url = "<?php echo $card_image?>";
		//保存输入的数据
		var inputSave = "";
		// 当前的页面
		var webUrl = 'chat_index';
		// 加载图片的 index
		var imgIndex = 0;
		//光标的位置
		// var cursorPos; 
		//最近联系人
		nearestContact = new Object();
		nearestContact = <?php echo !empty($ContactManSession) ? json_encode( $ContactManSession ): json_encode([]);?>;
		$recentContact = <?php echo !empty($friendSearch) ? json_encode( $friendSearch ): json_encode([]);?>;
		// 会话 对象

	</script>
</head>
<body>
<div class="bagimg">
	<img src="images/2.jpg"  alt="">
</div>
<div class="container-box">
<div class="details-list">
		<div class="container-title">
			<div class="container-header">
				<img  src="<?php echo $card_image;?>" width="50px" height="50px" alt="<?php echo $chat_name;?>">
				<h3 style="padding: 5px 0 5px 70px;text-align:left;"><?php echo $chat_name;?></h3>
				<h3 style="padding: 5px 10px 5px 70px;text-align:left;color: #A9A9A9;
    font-size: 18px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;" title="<?php echo $_SESSION['org_name'];?>"><?php echo $_SESSION['org_name'];?></h3>
			</div>
			<div class="dropdown">
				<i id="chatNoticeList" data-target="#" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
					<i class="icon-list"></i>
					<span class="caret"></span>
				</i>
				<ul  class="dropdown-menu chat-set-user" aria-labelledby="chatNoticeList">
					<li set-switch = '1' set-data = "VoiceState" class="dropdown-menu-man dissolve-group VoiceState close-voice">关闭声音</li>
					<li set-switch = '1' set-data = "desktopState" class="dropdown-menu-man dissolve-group desktopState close-voice">关闭桌面通知</li>
				</ul>
			</div>
			<!-- <span class="chat_mes_notice"><img src="/chat/images/chat_notice.png" alt=""></span> -->
		</div>
		<div class="container-tab">
			<ul>
			<li class="tab-title" ind= '0' title="最近联系人"><img src="images/mesage.png" alt="最近联系人"></li>
				<!-- <li title="选择好友聊天"><img id="f_man" src="images/friend.png" alt="选择好友聊天"></li> -->
				<li title="选择人聊天"><img id="s_man" src="images/rens1.png" alt="选择人聊天"></li>
				<li class="tab-title" ind= '1' title="选择群聊"><img src="images/rens.png" alt="选择群聊"></li>
				<a href="new_groupChat.php" target="_blank" title="新建群聊"><li title="新建群聊"><img src="images/addMan.png" alt="新建群聊"></li></a>
				<i class="sanjiao"></i>
			</ul>
		</div>
		<!-- 最近联系人 -->
		<div class="recent-container tab-chat">
			<div class="search-ren-pos">
				<ul class="list-group recent-box">
					<li class="search-ren"><i class="search-icon"></i><input type="text" id="search-ren" placeholder="搜索联系人"></li>
						<?php $style = 'style= "margin-top:83px;"'?>
					<?php if ( $isAdmin == 1 ):?>
						<?php $style = 'style= "margin-top:126px;"'?>
					<li class="chat_admin session_no" data-placement="right" group-name="管理员联系群"  session_no="ca" mes_id="" mestype ="adminMessage"  ><span class="header-img"><img src="/chat/images/chat_admin.png" alt=""></span><i>管理员联系群</i></li>
					<?php endif;?>
					<li class="chat_system_notice" data-placement="right" onclick="chatSystemNotice()" group-name=""><span class="header-img"><img src="/chat/images/chatNotice.png" alt=""></span><i>系统通知</i></li>
				</ul>
			</div>
			<div class="friends-list-box" <?php echo $style;?>>
				<!-- 好友列表 -->
				<div class="panel-group" id="accordion">
					<div class="panel friend-group">
						<div class="panel-heading" mestype= "groupMessage" data-toggle="collapse" class="group-man-show" data-parent="#accordion" href="#friends-list-d">
							<h4 class="panel-title"><span title="我的好友" >我的好友</span></h4>
						</div>
						<div id="friends-list-d" class="panel-collapse collapse">
							<div class="panel-body groupMan-list">
								<ul class="list-group">
								<?php if (!empty($arrfrientList)) :?>
									<?php foreach ($arrfrientList as $k => $v) : ?>
										<li mes_id="<?php echo $v['staffid'];?>" group-name="<?php echo $v['name'];?>" class="chat_people group-people"><img class="header-img" src="<?php echo $v['card_image']; ?>" alt=""><?php echo $v['name'];?><span class="delgroupman" data-type="fri"  id="<?php echo $v['staffid'];?>" style="display: none;">×</span></li>
									<?php endforeach; ?>
								<?php endif ;?>
								</ul>
							</div>
						</div>
						<span class="caret drop-down"></span>
					</div>
				</div>
				<!-- end群 -->
			</div>
			<!-- 最近联系人的搜索结果 -->
			<ul class="search-ren-res list-group"></ul>
			<div class="tab-content con-tab-content">
				<ul class="list-group com-recent-box" <?php echo $style ;?>>
				<?php if (!empty($recentContact)): foreach ($recentContact as $key => $value) :?>
					<?php
						$herder_img = $value['card_image'];
						$contacts_name = $value['contacts_name'];

						if ($value['mestype'] !== "message") {
							$contacts_name = $value['accept_name'];
							$herder_img = '/chat/images/rens.png';
						}

						$cont_id = $value['mes_id'];
					?>
					<?php if ($value['mestype'] !== "message") :?>
					 <li class="session_no recent-hover" data-placement="right" group-name="<?php echo $value['accept_name']; ?>" delConId = <?php echo $value['id'];?>  session_no="<?php echo $value['session_no'];?>" mes_id="<?php echo $cont_id;?>" mestype ="<?php echo $value['mestype'];?>" ><span class="header-img"><img src="<?php echo $herder_img?>" alt=""></span><i><?php echo $contacts_name;?></i><span title = "从列表中删除" mestype="<?php echo $value['mestype'];?>" delConId = <?php echo $value['id'];?>  session="<?php echo $value['session_no'];?>" class="recent-close">&times;</span></li>
					<?php else: ?>
					<li class="recent-contact content-staff-info chat_people recent-hover" data-placement="right" group-name="<?php echo $contacts_name;?>" delConId = <?php echo $value['id'];?>  session_no="<?php echo $value['session_no'];?>"  mes_id="<?php echo $cont_id;?>" mestype ="<?php echo $value['mestype'];?>" ><span class="header-img"><img src="<?php echo $herder_img;?>" alt=""></span><i><?php echo $contacts_name;?></i><span title = "删除聊天记录" mestype="<?php echo $value['mestype'];?>" delConId = <?php echo $value['id'];?>  session="<?php echo $value['session_no'];?>" class="recent-close">&times;</span></li>
					<?php endif; ?>
					<?php endforeach; ?>
					<?php endif; ?>
				</ul>
				
			</div>
			<div class="chat-main-footer">
				<ul>
					<li class="footer-friend"><i class="fri-icon" title="好友"></i></li>
					<li><i class="all-search search-icon" id = "allSearch"></i></li>
					<li class=""><a href="#" title="多人视频" data-para= "vM" onclick="vMChat(this)" class="pc_mes_tool_videoM pc_mes_tool_list"></a></li>
					<li class=""><a href="/welcomepage.php" target="_blank" class="chat-home" title="主页" ></a></li>
				</ul>
			</div>
		</div>
		<div class="tab-content group-content tab-chat" style="border-top: 1px solid #ccc;">
			<!-- 群列表 -->
			<div class="panel-group" id="accordion">
			<?php if (!empty($arrGroup)): foreach ($arrGroup as $key => $value): ?>
				<div class="panel group-chat">
					<div class="panel-heading session_no db_session_no" mestype= "groupMessage" group-name="<?php echo $value['group_name']?>" groupId="<?php echo $value['id'];?>" group-all="<?php echo $value['all_staffid']?>" session_no='<?php echo $value['pid']?>'>
						<h4 class="panel-title"><a title="<?php echo $value['group_name']?>" groupid = "<?php echo $value['pid']?>"  data-toggle="collapse" class="group-man-show" data-parent="#accordion" href="#collapse<?php echo $key;?>"><?php echo $value['group_name']?></a></h4>
					</div>
					<div id="collapse<?php echo $key;?>" class="panel-collapse collapse">
						<div class="panel-body groupMan-list">
							<ul class="list-group groupRightClick">
							<!-- 群聊参加人 -->
							</ul>
						</div>
					</div>
					<div class="dropdown">
						<i id="dLabel<?php echo $key;?>" data-target="#" href="http://example.com" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<i class="icon-list"></i>
							<span class="caret"></span>
						</i>
						<ul class="dropdown-menu group-operation" aria-labelledby="dLabel<?php echo $key;?>">
						<?php if ($chat_uid == $value['group_founder']):?>
							<li groupId="<?php echo $value['pid'];?>" class="dropdown-menu-man dissolve-group">解散该群</li>
						<?php else:?>
							<li groupId="<?php echo $value['pid'];?>" class="dropdown-menu-man esc-group">退出该群</li>
						<?php endif;?>
						</ul>
					</div>
				</div>
			<?php endforeach;?>
			<?php endif;?>
			</div>
			<!-- end群 -->
		</div>
	</div>
	<div class="chat-container ">
		<div class=" chating-content chat-tab-content">
			<div class="mes_title">
				<h2 class="mes_title_con" onmousedown="return false" ontouchstart="return false" unselectable="on">请选择人<i title="群聊添加人" class="add-groupMan"></i></h2><span aria-hidden="true" class="mes_dclose">&times;</span>
			</div>
			<div class="mes_con_box">
				<div class="he-ov-box mes-scroll pc_he-ov-box chat_initial">

					<div class="loader">
						<div class="mes_load" style="display:none;"><?php echo $pageload;?></div>
						<div class="loading-3">
								<i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i>
						</div>
					</div>
						<ul class="he_ov session-box" ></ul> 
				</div>
				<div class="pc_mes_input_box">
					<div class="pc_emoji_box"  onmousedown="return false" ontouchstart="return false"></div>
					<div class="pc_mes_tool">
						<ul>
							<li class="pc_mes_tool_emoji pc_mes_tool_list"  onmousedown="return false" ontouchstart="return false" unselectable="on"></li>
							<!-- <li class="pc_mes_tool_img pc_mes_tool_list"></li> -->
							<li class="pc_mes_tool_file pc_mes_tool_list"></li>
							<li class=""><a href="javascript:void(0)" title="语音" data-para= "a" onclick="vaChat(this)" class="pc_mes_tool_audio pc_mes_tool_list keydown_voice"></a></li>
							<li class=""><a href="#" title="视频" data-para= "v" onclick="vaChat(this)" class="pc_mes_tool_video pc_mes_tool_list"></a></li>
						</ul>
					</div>

					<div class="pc_mes_input" id="pc_mes_input" contenteditable="true"></div>
					<div class="pc_mes_send">
						<span style = "color: #aaa">按Shift + Enter 换行， Enter提交</span>
						<div class="chat_btn">发送</div>
					</div>
				</div>
				<div class="mes_footer mb_mes_footer mb_mes_footer">
					<div class="plus_menu_box">
						<div class="plus_menu">
							<span class="header_icon plus-list"><img src="/chat/images/header_icon.png" alt=""></span>
							<input style="display:none" type="file" multiple id="send-upimg"><span id="cli-upFile"><img src="images/uploadfile.png" alt=""></span>
							<span title="视频" data-para= "v" onclick="vaChat(this)" class="pc_mes_tool_video pc_mes_tool_list" style="display: in"></span>
						</div>
						<i class="icon-caret-down"></i>
					</div>
						<!-- <form onsubmit="onSubmit(); return false;"> -->
					<div class="mes_input">
						<i class="plus_icon"></i>
							<div class="mes_inout textarea chat_text_voice" id="mes_textarea" style="height:auto;" contenteditable="true"></div>
							<textarea style="display:none" class="mes_inout" ></textarea>
							<input type="submit" class="btn btn-primary chat_text_voice chat_submit" id="submit" value="发送" />
							<!-- <div class="keydown_voice chat_voice_input">按下开始说话</div> -->
							<div class="chat_input_key chat_voice_input"></div>
						<div style="clear:both"></div>
					</div>
					<div class="emoticons"></div>
						 <!-- </form> -->
				</div>
			</div>
		</div>
	 <!-- 右边人数 和 消息数 -->
		<div class="mes_fixed_big">
			<div class="mes_abs">
				<div class="mes_fixed">
					<div class="mes_ico_box" cata-box='ren'>
						<div class="mes_ico mes_hide" style="background-position:-6px -50px"></div>
						<div class="mes_ico mes_hide" style="background-position:-100px -100px;text-align: center;color:#fff;">
							<span class="mes_hide" style='line-height: 50px;'><span class='online_ren'>0</span>人</span>
						</div>
					</div>
					<div class="mes_ico_box" cata-box='mes'>
						<div class="mes_ico mes_hide" style="background-position:-6px 0"></div>
						<div class="mes_ico mes_hide" style="background-position:-100px -100px">
							<div class="mes_radio mes_hide"><?= $mesNum;?></div>
						</div>
					</div>
<!-- 					<div class="kefu-icon">
						<div class="kefu" mes_id="customer" group-name ="客服">联系客服</div>
					</div> -->
					<!-- <div class="mes_move"><i class="icon-sort"></i></div> -->
				</div>
				<div class="online_man">
					<div class="man_tittle">
						<span>人员列表</span>
						<span class ="close" style='cursor: pointer; margin-right: 5px;background: #000;color: #fff; position:absolute;width:20px;height:20px;border-radius: 50%;right: 0; top: 7px; '>&times;</span>
					</div>
					<div class="onlinesSroll-box">
						<div class="onlinesSroll"></div>
					</div>
				<!-- 人员列表 -->
				<!-- 搜索框 -->
					<div class="search_box">
						<input type="text" class= "search_in" id="search_in" placeholder= "搜索">
						<span class="search_staff"></span>
						<ul class="staff-list-group search_result">
							
						</ul>
					</div>
					 <ul class="list-group oms_onlineNum"></ul>
				</div>
				<div class="mes_con" style="display: none;">
					<div class="mes_tittle">
						<span>消息列表</span>
						<span class ="close" style='cursor: pointer; margin-right: 5px;background: #000;color: #fff; position:absolute;width:20px;height:20px;border-radius: 50%;right: 0; top: 7px; '>&times;</span>
					</div>
					<!-- 消息列表 -->
					<?php if (!empty($arrMes)):?>
					<?php foreach ($arrMes as $key => $value) :?>
					<?php 
						$messageTypes = $value['mesages_types'];
						if ( $value['message_type'] == 'message') {
									$sender_name = $value['sender_name'];
									$addClass = "chat_people";
									$chat_header_img = $value['chat_header_img'];
							} else if ( $value['message_type'] == 'groupMessage' ){
								$sender_name = $value['accept_name'];
								$addClass = "session_no";
								$chat_header_img = '/chat/images/rens.png';
							} elseif ( $value['message_type'] == 'adminMessage' ) {
								$sender_name = $value['accept_name'];
								$addClass = "session_no";
								$chat_header_img = '/chat/images/chat_admin.png';
							}
						 switch ( $messageTypes ) {
								case 'text':
										$par = ['/%6b/', '/%5C/', '/{@(.+)@}/U', '/\{\|/U', '/\|\}/U'];
										$repStr = ['<br/>', '\\', '', '<img width="24px" class="cli_em" src="/chat/emoticons/images/', '.gif">'];
										$content =preg_replace($par, $repStr,$value['message_content']);
										break;
								case 'image':
								case 'images':
										$content = '【图片】';
										break;
								case 'file':
										$content = '【文件】';
										break;
								case 'voice':
										$content = '【语音】';
										break;
								case 'revoke':
									$content = '撤销一条信息';
									break;
								case 'va':
						            $content = "开启了群聊视频";
					            	break;
								case 'notice':
									$sender_name = $sender_name.'【申请对话】';
									$content = $value['message_content'];
									$chat_header_img = "/chat/images/chatNotice.png";
									$addClass = 'chat_notice';
									break;
								case 'notice_respond':
									$sender_name = $sender_name;
									$content = '已同意可以会话了';
									$chat_header_img = "/chat/images/chatNotice.png";
									$addClass = 'chat_people';
								break;
								case 'sysNotice':
									$content = $sender_name.$value['message_content'];
									$sender_name = '系统通知';
									$chat_header_img = "/chat/images/chatNotice.png";
									$addClass = 'chat_customer_notice';
									break;
								default:
										$content = '出错了';
										break;
						}
							
					?>
					<div class="mes_box mes_chakan_close <?php echo $addClass;?>" chat_mes_num= "<?php echo $value['mes_num'];?>"  mes_id="<?php echo $value['sender_id'];?>" session_no='<?php echo $value['session_no'];?>' mesid="<?php echo $value['id'];?>" mestype="<?php echo $value['message_type'];?>" group-name="<?php echo $sender_name;?>" group-all = "<?php echo $value['accept_id'];?>" session_no='<?php echo $value['session_no'];?>'>
						<div class="mes_header">
							<img src="<?php echo $chat_header_img;?>" alt="">
						</div>
							<span class='mex_con'><?php echo $sender_name;?></span>
							<div class="mes_content_list" style=''>
									<span class="chat_mes_content">
									<?php if ( !empty( $value['mention'] )  ):?>
										<span class ="mention" data-name ="<?php echo rtrim(ltrim($value['mention'], '0'), ',');?>" style="color: red">
											有人@我
										</span>
									<?php endif; ?>
										<span class="chat_mes_content_s">
											<?php echo $content;?>
										</span>
									</span>
							</div>
							<span class="mes_num"><?php echo $value['mes_num'];?></span>
							<span class='mes_close' mestype='<?php echo $value['message_type'];?>' mes_id='<?php echo $value['sender_id'];?>'  session_no='<?php echo $value['session_no'];?>'>X</span>
						</div>
						<?php $mesNum += $value['mes_num'];?>
					<?php endforeach;?>
				<?php endif;?>
						<!-- end -->
					</div>
				</div>
		</div>
</div>
<div class="send-img-box">
	<span class="send-img-close com-close">&times;</span>
	<!-- <canvas id="canvas"></canvas> -->
</div>
<!-- 弹出框 -->
<div class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p class="mes_alert_con">请选择聊天对象</p>
		<ul class="cd-buttons">
			<li><a href="#" class="clo_alert alertvalue">确定</a></li>
			<!-- <li><a href="#">否</a></li> -->
		</ul>
		<a href="#" class="cd-popup-close img-replace">关闭</a>
	</div> <!-- cd-popup-chat-container -->
</div> <!-- cd-popup -->
<!-- Resource jQuery -->
<div class="img-box">
	<div class="img-box-title"><span>发送的图片</span><span style="color: #000;" class="com-close com-close-act">&times;</span></div>
	<div class="sending-img-box"></div>
	<div class="img-box-act"><span class="btn btn-success com-close-act">取消</span><span class="btn btn-info send-clipboard-img">发送</span></div>
</div>
<div class="file-main">
<form action=""  enctype ="multipart/form-data">
	<ul>
		<li>
			<input id="token" name="token" class="ipt" value="">
		</li>
		<li>
			<input id="key" name="key" class="ipt" value="">
		</li>
		<li>
			<input id="file" name="file" multiple = 'true' class="ipt" type="file" />
		</li>
		<li>
			<input id="filename"  type="text" value="">
		</li>
	</ul>
</form>
</div>
<!-- 上传进度 -->
<div id="progressbar"><div class="progress-label"></div><div id="formatSpeed"></div></div>
<!-- <script type="text/javascript" src="js/webChataAudio.js"></script> -->

<!--    查找好友  模态框    -->

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"  id = "search-all"  >
  <div class="modal-dialog modal-lg modal-css">
    <div class="modal-content">
	    <div class="modal-header">
	     	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">添加本组织以外的好友</h4>
	    </div>
      <div class="modal-body">
      <div class="search-box">
      	<input type="text" id="searchIn" class="form-control" placeholder="请输入你想找的名字"><i id= "actSearchFriend" class="searchAll-icon"></i>
      </div>
        <!-- <p>我好友</p> -->
        <div class="friendItem-box clearfix"></div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<input type="text" id="searchIn" class="form-control" placeholder="请输入你想找的名字"><i id= "actSearchFriend" class="searchAll-icon"></i> 
<i class="all-search search-icon" id = "allSearch"></i>
<script type="text/javascript">
$(function () {
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
			this.friendMap.remove(this.mes_id);
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
			// console.log( $thisIn.prop('checked'));
			// if ( $thisIn.prop('checked') ) {
			// 	$thisIn.prop('checked', false);
			// 	$('.myFriend-list li.myFriend-items-sel[mes_id="'+mes_id+'"]').remove();
			// 	myFriend.delMan();
			// } else {
			// 	$thisIn.prop('checked', true);
			// 	myFriend.addMan($this);
			// }
			myFriend.addMan($(this));
		});

		// 手机上的 input  点击
		$('.myFriend-items-Mask').click(function ( e ){
			e.stopPropagation();
			var mes_id = $(this).attr('mes_id');
			myFriend.mes_id = mes_id;
			if ( $(this).next().prop('checked') ) {
				$(this).next().prop('checked', false);
				myFriend.delMan();
			} else {
				myFriend.addMan('');
				$(this).next().prop('checked', true);
				// $('.myFriend-list li.myFriend-items-sel[mes_id="'+mes_id+'"]').remove();
			}
		})
		// myFriendItemsIn.click(function (e){
		// 	// 阻止冒泡
		// 	e.stopPropagation();
		// 	var mes_id = $(this).attr('mes_id');
 	// 		$(this).prop('checked');
 	// 		myFriend.mes_id = mes_id;
		// 	if ( $(this).prop('checked') ) {
		// 		myFriend.addMan($(this).parent());
		// 	} else {
		// 		$('.myFriend-list li.myFriend-items-sel[mes_id="'+mes_id+'"]').remove();
		// 		myFriend.delMan();
		// 	}
		// })
	})
</script>
<script>
	(function () {               
	// 输入的名字
	var searchName = '';
	// 调用模态框
	var allSearch = document.getElementById('allSearch');
	var searchIn = document.getElementById('searchIn');
	// enter 搜索
	searchIn.onkeydown = function (e) {
		var e = e || event,
		keycode = e.which || e.keyCode;
		if ( keycode == 13 ) {
			searchName = searchIn.value;
			searchFriend.searchAct(searchName);
		};
	}
	// 好友的查找
	var actSearchFriend = document.getElementById('actSearchFriend');
	actSearchFriend.onclick = function () {
		searchName = searchIn.value;
		searchFriend.searchAct(searchName);
	}
	searchIn.oninput  = function () {
		if ( $(this).val() == '' ) {
			$('.friendItem-box').html('');
		};
	}
	allSearch.onclick = function () {
		$('#search-all').modal('show');
	}
	var searchFriend = {};
	searchFriend.searchAct = function ( name ) {
		ws.send('{"type": "friendAdd","actType":"lookup","name":"'+name+'"}');
	}
	// foot li  点击事件 增加 高亮 显示
	$('.footer-friend').click(function (e) {
		$('.sanjiao').hide();
		$(this).addClass('foot-curColor');
	})

}())
//文件的分享
var chatShare = function ( obj ) {
	window.event ? window.event.cancelBubble = true : evt.stopPropagation();
	$('.chat-shareing').removeClass('chat-shareing');
	obj.className += ' chat-shareing';
	$('#b_is').attr( 'val', 'share' );
	$('#name_box').show();

}
//系统 通知消息
var chatSystemNotice = function () {
	var json = '{"type":"sysNotice"}';
	chatController(json);
}

var chatController = function ( json ){
	ws.send(json);
}

/***************  pc输入框  ********************/
//文件发送
$('.pc_mes_tool_file').click( function () {
	getQiniuToken();
	trig($('#file'));
})
//图片发送
// $('.pc_mes_tool_img').click( function () {
//   trig($('#file'));
// })
/****************  end  *******************/

// pc 输入框的bing 事件；

$(function () {
	var mesInput = $('#pc_mes_input');
	mesInput.on('drop', function ( e ) {
		e.stopPropagation();
		e.preventDefault();
	})
})

//pc 提交
$('#pc_mes_input, #mes_textarea').on('input', function () {
	var val = $(this).html();
	inputSave = val;
})
$('.chat_btn').click(function () {
	var inputValue = $('.pc_mes_input').html();
	$('#mes_textarea').html('');
	$('#mes_textarea').html(inputValue);
	$("#submit").trigger("click");
	$('.pc_mes_input').html('');
	$('.pc_mes_input').focus();
})

//最近联系人增加与更新
var addContact = {};

addContact.is =  function (session_id) {
	for (var i in nearestContact) {
		if ( nearestContact[i] == session_id) {
			return true;
		};
	}
	return false;
}

//通知消息同意不同意
$(document).on('click', '.chat_notice_sel', function () {
	var dataParm = $(this).attr('data-parm');
	var senderId = $(this).attr('sender-id');
	var noticeHtml = $(this).html();
	$('.chat_notice_container').hide();
	ws.send('{"type": "chat_notice_sel", "dataParm": "'+dataParm+'", "senderId": "'+senderId+'", "oms_id": "'+ChainOmsId+'"}');
})
//增加最近联系人
addContact.Dom = function () {
	var data = {
			"type": "addContact",
			"mestype": mes_type,
			"session_no" : session_no,
			"sender_name": chat_name,
			"accept_name": $('.mes_title_con').text(),
			"mes_id": to_uid,
			"to_uid_header_img": to_uid_header_img,
			"timeStamp": new Date().getTime(),
		};
	if (mes_type == "message") {
		$('.con-tab-content .com-recent-box').prepend('<li class="recent-contact staff-info chat_people recent-hover" data-placement="right" group-name="'+data.accept_name+'" session_no="'+data.session_no+'"  mes_id="'+data.mes_id+'" mestype ="'+data.mestype+'" ><span class="header-img"><img src="'+data.to_uid_header_img+'" alt=""></span><i>'+data.accept_name+'</i><span title = "删除聊天记录" mestype="'+data.mestype+'"  session="'+data.session_no+'" class="recent-close">&times;</span></div></li>')
	} else {
		$('.con-tab-content .com-recent-box').prepend('<li class="session_no staff-info recent-hover" data-placement="right" group-name="'+data.accept_name+'" session_no="'+data.session_no+'" mes_id="'+data.mes_id+'" mestype ="'+data.mestype+'" ><div><span class="header-img"><img src="/chat/images/rens.png" alt=""></span><i>'+data.accept_name+'</i><span title = "删除聊天记录" mestype="'+data.mestype+'"  session="'+data.session_no+'" class="recent-close">&times;</span></div></li>')
	}
	nearestContact.push(session_no);
	ws.send(JSON.stringify(data));

};
//最近联系人更新
addContact.upd = function ( to_uid ) {
	if ( $('.recent-contact[session_no="'+session_no+'"]').index() != 0 ) {
		$('.con-tab-content .com-recent-box').prepend($('.recent-contact[session_no="'+session_no+'"]'));
			ws.send('{"type": "updContact", "to_uid": "'+to_uid+'", "session_no": "'+session_no+'", "message_type": "'+mes_type+'"}');
	};

}


/*******************   消息列表session集合   *********************/

var arrMessageList = new Array();
arrMessageList = <?php echo !empty($messageSessionList) ? json_encode($messageSessionList) : json_encode([]);?>;



//通知消息点击
//外来的oms_id 
var ChainOmsId = '' ;
$(document).on('click', '.chat_notice', function () {
	var session_id = $(this).attr('session_no');
	var sender_id = $(this).attr('mes_id');
	ChainOmsId = $(this).attr('oms_id');
	$('.chat_notice_container').show();
	// ws.send('{"type": "chat_notice", "sender_id": '+session_id+'}');
	var con_mes_num =  parseInt($(".mes_chakan_close[session_no='"+session_id+"']").attr('chat_mes_num'));
	mes_notice_close('message', sender_id, con_mes_num);
	nearestContact.push(session_id);
})


//群聊名字点击
// $('.group-man-show').click(function (e){
// 	// e.stopPropagation();
// 	var obj = $(this);
// 	var showMansessionNo = obj.attr('groupid');
// 	if (!$(e.target).is('.groupSign')) {
// 		gpManSh( showMansessionNo, 'showGroupMans_list' );
// 	};
// 	$('.group-man-show').removeClass('groupAct');
// 	obj.addClass('groupAct');
// 	obj.addClass('groupSign');

// })

//上传图片点击

//触发文件点击
var trig = function (obj){
		obj.trigger('click');
}
$('#upclick').click( function(){
		trig($('#file'));
});
$('#cli-upFile img').click(function(){
		getQiniuToken();
		trig($('#file'));
		return;
});
	// 选择图片的改变
$('#send-upimg').on('change', function(){
	//检验是否为图像文件
	var obj = $(this);
	var file = $(this)[0].files[0];
	$.each( $(this)[0].files, function (i, file){
		if(!/image\/\w+/.test(file.type)){  
				alert("只能发送图片");  
				return false;  
		}  
		var reader = new FileReader();  
		//将文件以Data URL形式读入页面  
		reader.readAsDataURL(file);  
		reader.onload=function(e){
			var img = new Image();
			img.src = this.result;
			img.className = "send-img";
			$('.sending-img-box').html('');
			$('.sending-img-box').append( img );
			$('.send-clipboard-img').trigger('click');
			$('.plus_menu_box').hide();
		//显示文件  
		}
	});
});
//粘贴发送的图片取消
$('.com-close-act').click(function(){
		$('.img-box').hide();
})
//群聊删除人
$(document).on('click', '.delgroupman', function(){
	var obj = $(this);
	var groupid = obj.attr("groupid");
	var id = obj.attr("id");
	var dataType = obj.attr("data-type");
	$('.alertvalue').attr('id', id);
	$(this).parent().addClass('mandel');
	$('.mes_alert_con').html('你确定要删除他吗？');
	$('.cd-popup').addClass('is-visible'); 
	if ( dataType == "fri" ) {
		$('.alertvalue').attr('type', 'friDel');
	} else {
		$('.alertvalue').attr('groupid', groupid);
		$('.alertvalue').attr('type', 'del');
	}
	return false;

});
//退出群
$('.esc-group').on('click', function (){
	var groupId = $(this).attr('groupId');
	ws.send('{"type": "esc_group", "groupId": "'+groupId+'"}');
	$(this).parents('.panel').remove();
	alert('退出群成功！');
	return;
})

//群聊解散
$('.dissolve-group').on('click', function (){
	var groupId = $(this).attr('groupId');
	ws.send('{"type": "dissolve_group", "groupId": "'+groupId+'"}');
	$(this).parents('.panel').remove();
})

$('.alertvalue').on('click', function (){
	var obj = $(this);
	var id = obj.attr("id");
	$('.mandel').remove();
	if ( obj.attr('type') == "del") {
		var groupid = obj.attr("groupid");
		ws.send('{"type":"delgroupman", "groupid": "'+groupid+'", "id":"'+id+'"}'); 
	} else if (  obj.attr('type') == "friDel" ) {
		ws.send('{"type":"delFriend", "uid":"'+id+'"}'); 
	}
})

//删除最近联系人
var delContactFun = function( id, mestype, session_id){
	nearestContact.remove(session_no);
		$('.recent-action').parent('li').remove()
		ws.send('{"type": "delContact", "session_no":"'+session_id+'", "mestype":"'+mestype+'", "id": "'+id+'"}');
}


// 选择人聊天
$(document).on('click', '.chat_people', function( e ){
	$('.chating-content .pc_he-ov-box').removeClass('chat_initial');
	to_uid = $(this).attr('mes_id');
	to_uid_header_img = $(this).find('img').attr('src');
	//会话id的改变
	session_no = parseInt( to_uid ) < parseInt( chat_uid ) ? to_uid+"-"+chat_uid : chat_uid+"-"+to_uid;

	mes_type = "message";

	if ($(window).width() < 700) {
			$('.chat-container').show();
			$('.details-list').hide();  
	};
	// 最近联系人的背景改变
	$('.con-tab-content li').css('background', 'none');
	$('.con-tab-content li[session_no='+session_no+']').css('background', '#BEB7C7');
	//end
	// groupId = $(this).attr('groupId');
	// if (!$(e.target).is('.mes_chakan_close')) {
	$('.chating-content .mes_title_con').html( $(this).attr('group-name') );
	if ($.inArray(session_no, arrMessageList) != -1) {
		var con_mes_num =  parseInt($(".mes_chakan_close[session_no='"+session_no+"']").attr('chat_mes_num'));
		mes_chakan_close('message', to_uid, con_mes_num);

	};
	// };
	 var res = addSession.addSession(to_uid);
	ws.send('{"type":"mes_chat", "to_uid":"'+to_uid+'"}');
	$('.chating-content .mes_load').html(10);
	// 图片的清空
	$('.loadImg').html('');
	imgIndex = 0;
	//消息向上滚动
	// 群聊里的人 消失
	$('.chat-show-groupMan-box').remove();
	$('.add-groupMan').remove();
	$('.groupManShowIng').removeClass('groupManShowIng');

	$('.chating-content .he-ov-box').unbind('scroll');
	$('.chating-content .he-ov-box').bind("scroll", function (){
		mesScroll();
	})
})

//选择群列表显示对话内容
$(document).on('click', '.session_no', function ( event ){

		var groupJson = ''; 
		$('.pc_he-ov-box').removeClass('chat_initial');
		session_no = $(this).attr('session_no')//会话id
		mes_type = $(this).attr('mestype')//会话id
			// if (session_no == $(this).attr('session_no')) { return ; };
			//在手机上交替显示
		if ($(window).width() < 700) {
				$('.chat-container').show();
				$('.details-list').hide();  
		};
		//end
		var valName = $(this).attr('group-name');//会话名字
		// groupId = $(this).attr('groupId');
		//消息向上滚动
		$('.chating-content .he-ov-box').unbind('scroll');
		$('.chating-content .he-ov-box').bind("scroll", function (){
			mesScroll();
		})
		//背景颜色的改变
		$('.con-tab-content li').css('background', 'none');
		$('.con-tab-content li[session_no='+session_no+']').css('background', '#BEB7C7');
		$('.mes_title_con').html(valName);
		// 加入聊天
		addSession.addSession(session_no);
		$('.mes_title').append('<i title="群聊添加人" class="add-groupMan"></i>');
		if ( mes_type == 'groupMessage' ) {
			$('.mes_title_con').append('<i class = "dropDown-showMan-n"></i>');
			$('.add-groupMan').show();
			groupJson = '{"type":"mes_groupChat", "session_no":"'+session_no+'" }';
		} else if ( mes_type == 'adminMessage' ) {
			groupJson = '{"type":"chatAdmin", "session_no":"ca" }';
		};
		// if (!$(event.target).is('.mes_chakan_close')) {
		var mesCC = $(".mes_chakan_close[session_no='"+session_no+"']");
		// 是否 有人 @ 
		if ( mesCC.length > 0 ) {
			if ( mesCC.find('.mention').length > 0 ) {

				var mentionName = mesCC.find('.mention').attr('data-name');
				mentionNotice ( mentionName );
			};
			var con_mes_num =  parseInt($(".mes_chakan_close[session_no='"+session_no+"']").find('.mes_num').html());
			mes_chakan_close( mes_type, session_no, con_mes_num );
		};
		// };
		ws.send(groupJson);
		$('.chating-content .mes_load').html(10);
		// 图片的清空
		$('.loadImg').html('');
		imgIndex = 0;
		// 群聊的人 重置
		$('.chat-show-groupMan-box').remove();
		$('.groupManShowIng').removeClass('groupManShowIng');
});


//群聊 操作 事件
$(function(){
	var GroupOperation = function ()　{
		this.groupName = $('.group-man-show');
		this.groupid = '';
		var slef = this;
		this.sg = $('.mes_con_box');
		
		this.groupName.click(function ( e ) {
			var obj = $(this);
			var showMansessionNo = obj.attr('groupid');
			if (!$(e.target).is('.groupSign')) {
				slef.gpManSh( showMansessionNo, 'showGroupMans_list' );
			};
			$('.group-man-show').removeClass('groupAct');
			obj.addClass('groupAct');
			obj.addClass('groupSign');
		});
	}
	GroupOperation.prototype = {
	    init: function ( obj ) {
	    	var slef = this;
			obj.click(function (e) {
				if ( message_type == 'groupMessage' ) {
					return false;
				};
				e.stopPropagation();
				// var groupid = $(this).attr('groupid');
				if ( $(this).hasClass('groupManShowIng') ) {
					$('.chat-show-groupMan-box').slideToggle("slow", function () {
						if ( $('.chat-show-groupMan-box').css('display') == 'none' ) {
							$('.dropDown-showMan-n').css('background-image', "url(/chat/images/xialaIng.png)");
						} else {
							$('.dropDown-showMan-n').css('background-image', "url(/chat/images/xiala.png)");
						}
					});
					return false;
				};
				$(this).addClass('groupManShowIng');
				slef.gpManSh( session_no, 'TshowGroupMans_list' );
				// ws.send('{"": "", "": "", "": ""}');
			})
	    },
		gpManSh: function (groupid, Callback) {
			ws.send('{"type": "groupManShow", "Callback":"'+Callback+'", "session_id":"'+groupid+'"}');
		}
	}
	var GroupOperationObj = new GroupOperation();
	GroupOperationObj.init( $('.mes_title_con') );
	window.GroupOperationObj = GroupOperationObj;
})

// 有人@
var mentionNotice = function ( name ) {
	var mentionNoticeObj = $('<div class = "mentionNotice" style="position: relative"><div  style ="position: absolute; top: 0; width: 100%;height: 30px;line-height: 30px;text-align: center;background-color: #A27373;color: #fff;z-index: 999">'+name+'@你</div></div>');
	$('.mes_con_box').prepend(mentionNoticeObj);
	setTimeout('$(".mentionNotice").fadeOut(1000)', 4000);
}
//删除最近联系人

$(document).on('click', '.recent-action', function (e) {
		e.stopPropagation();
		var r=confirm("要从联系人中删除他吗？");
		if ( r == false ) {
			return false;
		};
		var delconid = ($(this).attr('delconid'));
		var session_id = ($(this).attr('session_no'));
		delContactFun( delconid, mes_type, session_id );
})

//enter 提交
$(".mes_footer, .pc_mes_input").keydown(function(e){
	var e = e || event,
	keycode = e.which || e.keyCode;
	if(e.shiftKey && (e.keyCode==13)){
	} else if (keycode==13) {
			e.preventDefault();
			var inputValue = $('.pc_mes_input').html();
			$('#mes_textarea').html(inputValue);
			$('.pc_mes_input').html('');
			$("#submit").trigger("click");
	}
});
 //消息提交
var mesParam = {
	mes_obj: function (){
		if (to_uid == 0 && session_no == 0) {
				$('.mes_alert_con').html('请选择聊天对象！')
				$('.cd-popup').addClass('is-visible'); return false;
		} else { return true; }
	},
	mes_empty: function (){
		if ($('#mes_textarea').html() =='') {
				$('.mes_alert_con').html('消息不能为空！')
				$('.cd-popup').addClass('is-visible'); return false;
		} else { return true; }
	},
};

/*********** 文件发送   ******************/

//发送文件

//请求 token
function xmlhttp() {
		var $xmlhttp;

		if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				$xmlhttp = new XMLHttpRequest();
		} else {
				// code for IE6, IE5
				$xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		return $xmlhttp;
}

window.onload = function() {
};

//  请求token 
var getQiniuToken = function () {
		$xmlhttp = xmlhttp();
		$xmlhttp.onreadystatechange = function() {
				if ($xmlhttp.readyState == 4) {
						if($xmlhttp.status == 200){
								document.getElementById('token').value = $xmlhttp.responseText;
						} else {
								alert('get uptoken other than 200 code was returned')
						}
				}
		}
		$upTokenUrl = '/chat/uptoken.php';
		$xmlhttp.open('GET', $upTokenUrl, true);
		$xmlhttp.send(); 

		$file = document.getElementById('file');
};
//消息提交
var Qiniu_UploadUrl = "http://up.qiniu.com";
var progressbar = $("#progressbar"),
progressLabel = $(".progress-label");
progressbar.progressbar({
		value: false,
		change: function() {
				$('#progressbar').show();
				// progressLabel.text(progressbar.progressbar("value") + "%");
		},
		complete: function() {
				$('#progressbar').hide();
				// progressLabel.hide();
				// progressLabel.text("Complete!");
		}
});
//普通文件上传
var Qiniu_upload = function(f, token, key, sendType) {
	var xhr = new XMLHttpRequest();
	xhr.open('POST', Qiniu_UploadUrl, true);
	var formData, startDate;
	formData = new FormData();
	if (key !== null && key !== undefined) formData.append('key', key);
	formData.append('token', token);
	formData.append('file', f);
	var taking;
	xhr.upload.addEventListener("progress", function(evt) {
		if (evt.lengthComputable) {
			var nowDate = new Date().getTime();
			taking = nowDate - startDate;
			var x = (evt.loaded) / 1024;
			var y = taking / 1000;
			// var uploadSpeed = (x / y);
			// var formatSpeed;
			// if (uploadSpeed > 1024) {
			//     formatSpeed = (uploadSpeed / 1024).toFixed(2) + "Mb\/s";
			//     // $('#formatSpeed').html(formatSpeed);
			// } else {
			//     formatSpeed = uploadSpeed.toFixed(2) + "Kb\/s";
			//     // $('#formatSpeed').html(formatSpeed);
			// }
			var percentComplete = Math.round(evt.loaded * 100 / evt.total);
			progressbar.progressbar("value", percentComplete);
			// console && console.log(percentComplete, ",", formatSpeed);
		}
	}, false);
	xhr.onreadystatechange = function(response) {
		if (xhr.readyState == 4 && xhr.status == 200 && xhr.responseText != "") {
				var blkRet = JSON.parse(xhr.responseText);
				document.getElementById('key').value = blkRet.key;
				document.getElementById('filename').value = blkRet.fname;
				onSubmit(to_uid, chat_uid, groupId, mes_type, sendType, session_no);
				// console && console.log(blkRet);
		} else if (xhr.status != 200 && xhr.responseText) {
				var blkRet = JSON.parse(xhr.responseText);
				// onSubmit(to_uid, chat_uid, groupId, mes_type, 'file',session_no);
				console.log(blkRet);
				alert('发送失败！');
				return false;
				// console.log(blkRet);
		}
	};
	startDate = new Date().getTime();
	$("#progressbar").show();
	xhr.send(formData);
};

 //发送文件
$('#file').on('change', function (){
	var nowTime = new Date().getTime();
	var sendType =  'file';
	if (!mesParam.mes_obj()) {
		$(this).val('');
		return false;
	};
	if ($("#file")[0].files.length > 0 && token != "") {
		for (var i = 0; i < $("#file")[0].files.length; i++) {
			$key  = $("#file")[0].files[i].name;
			document.getElementById('filename').value = $key;
			$key ='file/'+chat_uid+'/'+to_uid+'/'+nowTime+'/'+$key;
			document.getElementById('key').value = $key;
			var token = $("#token").val();

			if(/image\/\w+/.test($(this)[0].files[i].type)){  
				sendType = 'images';
			} else {
				sendType = 'file';
			}
			Qiniu_upload($("#file")[0].files[i], token, $key, sendType);
			// $.each( $(this)[0].files, function (i, file){
			// 	if(/image\/\w+/.test(file.type)){  
			// 			sendType = 'images';
			// 	}
			// })
		};
	}else {
			console && console.log("form input error");
	}
	$(".plus_menu_box").hide();
	
});

//会话提交
document.getElementById('submit').onclick = function (){
	//接收人名字
		if (!mesParam.mes_obj() || !mesParam.mes_empty()) {
			return false;
		};
		if (mes_type == 'groupMessage') {
			to_uid_header_img = '/chat/images/rens.png';
		} 
		// if ($('.recent-hover[session_no="'+session_no+'"]').length == 0) {
		if ( $.inArray(session_no, nearestContact) == -1 ) {
			addContact.Dom();
		} else {
			addContact.upd( to_uid );
		}
		// H5AppDB.indexedDB.selectData(data)
		onSubmit(to_uid, chat_uid, groupId, mes_type, 'text',session_no);
};
//消息提交
document.getElementsByClassName('send-clipboard-img')[0].onclick = function (){
		//接收人名字
		if (!mesParam.mes_obj()) {
				return false;
		};
		onSubmit(to_uid, chat_uid, groupId, mes_type, 'image',session_no);
};

//通知消息关闭
var mes_notice_close = function (mestype, session_id, mes_num){
		ws.send('{"type":"mes_notice_close", "to_uid":"'+session_id+'", "mestype":"'+mestype+'"}');
		mesnum = mesnum - parseInt(mes_num);
		$('.mes_radio').html(mesnum);
		session_id = session_id + 't';
		$('.mes_chakan_close[session_no="'+session_id+'"]').remove();
		arrMessageList.remove(session_id);
}
//消息的关闭
var mes_chakan_close = function (mestype, session_id, mes_num){
		if ( $.inArray(session_no, nearestContact ) == -1 ) {
			addContact.Dom();
		} else {
			addContact.upd( session_id );
		}
		if ( mestype == 'message' ) {
			session_no = parseInt(chat_uid) < parseInt( session_id ) ? chat_uid+"-"+session_id : session_id+"-"+chat_uid;
		} else if ( mestype == 'groupMessage' ||  mestype == 'adminMessage' )  {
			session_no = session_id;
		}
		ws.send('{"type":"mes_close", "to_uid":"'+session_id+'",  "session_no": "'+session_no+'", "message_type":"'+mestype+'"}');
		mesnum = mesnum - parseInt(mes_num);
		$('.mes_radio').html(mesnum);
		$('.mes_chakan_close[session_no="'+session_no+'"]').remove();
		arrMessageList.remove(session_no);
}
var mes_NoChakan_close = function (mestype, session_id, mes_num){
		// if ( $.inArray(session_no, nearestContact ) == -1 ) {
		//   addContact.Dom();
		// } else {
		//   addContact.upd( session_id );
		// }
		if ( mestype == 'message' ) {
			session_close_no = parseInt(chat_uid) < parseInt( session_id ) ? chat_uid+"-"+session_id : session_id+"-"+chat_uid;
		} else {
			session_close_no = session_id;
		}
		ws.send('{"type":"mes_close", "to_uid":"'+session_id+'",  "session_no": "'+session_close_no+'", "message_type":"'+mestype+'"}');
		mesnum = mesnum - parseInt(mes_num);
		$('.mes_radio').html(mesnum);
		$('.mes_chakan_close[session_no="'+session_close_no+'"]').remove();
		arrMessageList.remove(session_close_no);
}

//消息的关闭
$(document).on('click', '.mes_close', function ( e ){
		e = e || window.event;  
		 e.stopPropagation();  //阻止冒泡
		var mestype = $(this).attr('mestype');
		var mes_num = $(this).prev('.mes_num').html();
		// session_no = $(this).attr('session_no');
		mes_id = $(this).attr('mes_id');
		mes_NoChakan_close(mestype, mes_id, mes_num);
});

// function getCursortPosition (ctrl) {//获取光标位置函数
//      var CaretPos = 0; 
//      // IE Support 
//     if (document.selection) { 
//         ctrl.focus ();
//         var Sel = document.selection.createRange ();
//             Sel.moveStart ('character', -ctrl.value.length); 
//             CaretPos = Sel.text.length; 
//     } 
//      // Firefox support 
//     else if (ctrl.selectionStart || ctrl.selectionStart == '0') {
//         CaretPos = ctrl.selectionStart;
//         return (CaretPos); 
//     }
// }
// //2.设置光标位置
// function setCaretPosition(ctrl, pos){//设置光标位置函数 
//     if(ctrl.setSelectionRange)
//     { 
//         ctrl.focus();
//         ctrl.setSelectionRange(pos,pos);
//     } else if (ctrl.createTextRange) {
//         var range = ctrl.createTextRange(); 
//         range.collapse(true); 
//         range.moveEnd('character', pos);
//         range.moveStart('character', pos); 
//         range.select();
//     } 
// }
function doGetCaretPosition (oField) {

  // Initialize
  var iCaretPos = 0;
	document.getSelection();
  // IE Support
  if (document.selection) {
    // Set focus on the element
    oField.focus();

    // To get cursor position, get empty selection range
    var oSel = document.selection.createRange();

    // Move selection start to 0 position
    oSel.moveStart('character', -oField.value.length);

    // The caret position is selection length
    iCaretPos = oSel.text.length;
  }

  // Firefox support
  else if (oField.selectionStart || oField.selectionStart == '0'){
    iCaretPos = oField.selectionStart;
    }
  // Return results
  return iCaretPos;
}
// function getCursortPosition (ctrl) {
// 	//获取光标位置函数 
// 	var CaretPos = 0; 
// 	// IE Support 
// 	if (document.selection) { 
// 		ctrl.focus ();
// 		var Sel = document.selection.createRange ();
// 		Sel.moveStart ('character', -ctrl.value.length);
// 		CaretPos = Sel.text.length; 
// 	} 
// 	 // Firefox support 
// 	else if (ctrl.selectionStart || ctrl.selectionStart == '0') {
// 	 	CaretPos = ctrl.selectionStart; return (CaretPos);
// 	}
//  }
// 获取光标 粘贴
/*
向光标 后插入 内容
@parm html 要插入的内容
@parm bool 是否 把内容转换 html 实体
*/

</script>

<span id="s_man">dd</span>

</body>
<script>
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
	            val:'#s_man',   //字符串值存放位置
	            // sidstorage:'#aaaa',
            	// deposidstorage:'#bbbb',
	            callback:function(ids,names){
	                console.log(ids)
	                console.log(names)
	            }
	        }
	    ],
	})
</script>	
</html>