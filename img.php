<?php
require_once('config.inc.php');
require_once('lib/mesages.class.php');

$chat_uid = $_SESSION['staffid'];
$oms_id = $_SESSION['oms_id'];

$chat_uid = 5;
$oms_id = 3;

$pageload = 10;//消息显示的条数
$session_no = 0;//会话id
$mesNum = 0;
$messageSessionList = [];
//消息类型
// $mes_type = 'message';
//实例化消息
$mes = new messageList($chat_uid, $oms_id);
//最近联系人
if (isset($chat_uid)) {

		$recentContact = $mes->recentContact();
}
//最近联系人session_no集合;
$ContactManSession = [];


foreach ($recentContact as $key => $value) {
	$ContactManSession[] = $value['session_no'];
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
//群聊列表
$arrGroup = $mes->groupChatList();

//管理员的所有信息 
// $chatAdminList = $mes->getAdmin();
?><?php
require_once('config.inc.php');
require_once('lib/mesages.class.php');

$chat_uid = $_SESSION['staffid'];
$oms_id = $_SESSION['oms_id'];

$chat_uid = 5;
$oms_id = 3;

$pageload = 10;//消息显示的条数
$session_no = 0;//会话id
$mesNum = 0;
$messageSessionList = [];
//消息类型
// $mes_type = 'message';
//实例化消息
$mes = new messageList($chat_uid, $oms_id);
//最近联系人
if (isset($chat_uid)) {

		$recentContact = $mes->recentContact();
}
//最近联系人session_no集合;
$ContactManSession = [];


foreach ($recentContact as $key => $value) {
	$ContactManSession[] = $value['session_no'];
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
//群聊列表
$arrGroup = $mes->groupChatList();

//管理员的所有信息 
// $chatAdminList = $mes->getAdmin();
?><?php
require_once('config.inc.php');
require_once('lib/mesages.class.php');

$chat_uid = $_SESSION['staffid'];
$oms_id = $_SESSION['oms_id'];

$chat_uid = 5;
$oms_id = 3;

$pageload = 10;//消息显示的条数
$session_no = 0;//会话id
$mesNum = 0;
$messageSessionList = [];
//消息类型
// $mes_type = 'message';
//实例化消息
$mes = new messageList($chat_uid, $oms_id);
//最近联系人
if (isset($chat_uid)) {

		$recentContact = $mes->recentContact();
}
//最近联系人session_no集合;
$ContactManSession = [];


foreach ($recentContact as $key => $value) {
	$ContactManSession[] = $value['session_no'];
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
//群聊列表
$arrGroup = $mes->groupChatList();

//管理员的所有信息 
// $chatAdminList = $mes->getAdmin();
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script type="text/javascript" src="js/swfobject.js"></script>
	<script type="text/javascript" src="js/web_socket.js"></script>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/touchSwipe.js?"></script>
	<script src="js/jquery-ui.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/photoswipe.min.js"></script>
	<script src="js/viewer.min.js"></script>
	<script src="js/photoswipe-ui-default.min.js"></script>
	<script type="text/javascript" src="js/web_message.js?v=1.0.0"></script>
	<link rel="stylesheet" href="css/jquery-ui.css">
	<link rel="stylesheet" href="css/default-skin.css">
	<link rel="stylesheet" href="css/photoswipe.css">
	<link rel="stylesheet" href="css/viewer.min.css">
	<link rel="stylesheet" href="css/webRight.css" type="text/css"/>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<link rel="stylesheet" href="css/font-awesome.min.css" type="text/css"/>
	<link rel="stylesheet" href="css/app.css" type="text/css"/>
<style type="text/css">
	*{font-family: 'Microsoft YaHei', SimSun, sans-serif !important;}
	.online_man{ text-align: center;margin: 0 auto;box-shadow: 0 0 5px #999;}
	.online_man ul{margin-left: auto;margin-right: auto;padding: 0;}
	#name_box{ position:fixed;background:#fff;display:none;width: 100%;margin-top:0px; z-index:9999;}
	.mes_chakan_close{ line-height: 20px; cursor: pointer; }
	.mes_dclose{ cursor: pointer; position: absolute;  top: 3px;  font-size: 33px;  color: #fff;  right: 0;}
	.tab-content{min-height: 300px;height: 80% !important; max-height: 600px; overflow: auto;position: relative;}
	#submit{height: 50px;}
</style>
</head>
<div class="">
	<ul class="loadImg">
	<li><img src="http://7xq4o9.com1.z0.glb.clouddn.com/file/5/4/1465310817490/group.jpg" alt=""></li>
	<li><img src="http://7xq4o9.com1.z0.glb.clouddn.com/file/5/4/1465310817490/group.jpg" alt=""></li>
	<li><img src="http://7xq4o9.com1.z0.glb.clouddn.com/file/5/4/1465801024900/2016-06-13_133824引导页.png" alt=""></li>
	</ul>
</div>
<div class="chat_voice_box" voice_url="">
	<audio src="" preload></audio>
</div>
<div class="chat_alert alert-success" role="alert">
		<button class="close"  data-dismiss="alert" type="button" >&times;</button>
		<p>恭喜您操作成功！</p>
</div>

<!-- 消息通知 -->
<div class="chat_message_notice">
	<i class= "chat_close">&times;</i>
	<div class="chat_message_notice_con"></div>
</div>
<!-- 消息提示 -->
<div class="mesNoticeContainer"></div>
<!--  end -->
<div class="chat_notice_container">
	<div class="chat_notice_close close">&times;</div>
	<div class="chat_notice_list_box">
		<div class="chat_notice_list">
			<div class="chat_notice_img">
				<img src="/chat/images/chatNotice.png" alt="">
			</div>
			<div class="chat_notice_con">
				<span></span>
				<span></span>
			</div>
			<div class="chat_notice_agree chat_notice_sel"></div>
			<div class="chat_notice_unagree chat_notice_sel"></div>
		</div>
	</div>
</div>
		<div id="name_box">
				<?php
					include('fenlei2/OS.php');
				?>
				<div style="width:100%;"><button style="width:157px;height:30px" id="b_no" class="btn btn-sm btn-info" >取&nbsp;消</button><button style="margin-top:17px;width:157px;height:30px;" id="b_is"  class="btn btn-sm btn-info">确&nbsp;定</button></div>
		</div>
		<div class="bagimg">
			<img src="images/2.jpg"  alt="">
		</div>
		<div class="container-box">
			<div class="details-list">
				<div class="container-title">
					<div class="container-header">
						<img  src="<?php echo $card_image;?>" width="50px" height="50px" alt="<?php echo $chat_name;?>">
						<h3><?php echo $chat_name;?></h3>
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
						<li title="选择人聊天"><img id="s_man" src="images/rens1.png" alt="选择人聊天"></li>
						<li class="tab-title" ind= '1' title="选择群聊"><img src="images/rens.png" alt="选择群聊"></li>
						<a href="new_groupChat.php" target="_blank" title="新建群聊"><li title="新建群聊"><img src="images/addMan.png" alt="新建群聊"></li></a>
						<i class="sanjiao"></i>
					</ul>
				</div>
				<!-- 最近联系人 -->
				<div class="tab-content con-tab-content">
					<!-- 最近联系人的搜索结果 -->
					<ul class="search-ren-res list-group"></ul>
					<ul class="list-group">
						<li class="search-ren"><i></i><input type="text" id="search-ren" placeholder="搜索联系人"></li>
						<li class="recent-contact chat_people recent-hover" style="display:none"  group-name="客服" session_no="customer"  mes_id="1" mestype ="message" session_no="<?php echo $oms_id.'c';?>"></li>
						<?php if ( $isAdmin == 1 ):?>
						<li class="chat_admin session_no" data-placement="right" group-name="管理员联系群"  session_no="ca" mes_id="" mestype ="adminMessage"  ><span class="header-img"><img src="/chat/images/chat_admin.png" alt=""></span><i>管理员联系群</i></li>
						<?php endif;?>
						<li class="chat_system_notice" data-placement="right" onclick="chatSystemNotice()" group-name=""><span class="header-img"><img src="/chat/images/chatNotice.png" alt=""></span><i>系统通知</i></li>
					<!-- <li class="companyGroup recent-hover" data-placement="right" group-name="公司群"></li> -->
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
				<div class="tab-content group-content" style="border-top: 1px solid #ccc;">
				<!-- 群列表 -->
						<div class="panel-group" id="accordion">
								<?php if (!empty($arrGroup)): foreach ($arrGroup as $key => $value): ?>
											<div class="panel panel-default">
													<div class="panel-heading session_no db_session_no" group-name="<?php echo $value['group_name']?>" groupId="<?php echo $value['id'];?>" group-all="<?php echo $value['all_staffid']?>" session_no='<?php echo $value['pid']?>'>
															<h4 class="panel-title"><a groupid = "<?php echo $value['pid']?>"  data-toggle="collapse" class="group-man-show" data-parent="#accordion" href="#collapse<?php echo $key;?>"><?php echo $value['group_name']?></a></h4>
													</div>
													<div id="collapse<?php echo $key;?>" class="panel-collapse collapse">
															<div class="panel-body">
																<ul class="list-group">
																<!-- 群聊参加人 -->
																</ul>
															</div>
													</div>
													<div class="dropdown">
														<i id="dLabel<?php echo $key;?>" data-target="#" href="http://example.com" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
															<i class="icon-list"></i>
																<span class="caret"></span>
															</i>

														<ul class="dropdown-menu" aria-labelledby="dLabel<?php echo $key;?>">
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
	<div class="chat-container">
				<div class="mes_title">
					<h2 class="mes_title_con">请选择人<i title="群聊添加人" class="add-groupMan"></i></h2><span aria-hidden="true" class="mes_dclose">&times;</span>
				</div>
				<div class="mes_con_box">
					<div class="">
								 <div class="he-ov-box mes-scroll pc_he-ov-box chat_initial">
										 <div class="">
										 <div class="loader">
												<div id="mes_load" style="display:none;"><?php echo $pageload;?></div>
												<div class="loading-3">
														<i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i>
												</div>
											</div>
											<ul class="he_ov session-box" >
											</ul> 
										 </div>
								 </div>
							<div class="pc_mes_input_box">
								<div class="pc_emoji_box">
									
								</div>
								<div class="pc_mes_tool">
									<ul>
										<li class="pc_mes_tool_emoji pc_mes_tool_list"></li>
										<!-- <li class="pc_mes_tool_img pc_mes_tool_list"></li> -->
										<li class="pc_mes_tool_file pc_mes_tool_list"></li>
										<!-- <li class="pc_mes_tool_audio pc_mes_tool_list keydown_voice"></li> -->
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
								</div>
								<i class="icon-caret-down"></i>
							</div>
									<!-- <form onsubmit="onSubmit(); return false;"> -->
											<div class="mes_input">
												<i class="plus_icon"></i>
													<div class="mes_inout textarea chat_text_voice" id="mes_textarea" style="height:auto;" contenteditable="true"></div>
													<textarea style="display:none" class="mes_inout" ></textarea>
													<input type="submit" class="btn btn-primary chat_text_voice" id="submit" value="发送" />
													<div class="keydown_voice chat_voice_input">按下开始说话</div>
													<div class="chat_input_key chat_voice_input"></div>
												<div style="clear:both"></div>
											</div>
											<div class="emoticons"></div>
									 <!-- </form> -->
								<div>
							</div>
						</div>
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
					<div class="kefu-icon">
						<div class="kefu" mes_id="customer" group-name ="客服">联系客服</div>
					</div>
					<!-- <div class="mes_move"><i class="icon-sort"></i></div> -->
				</div>
				<div class="online_man" >
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
					 <ul class="list-group oms_onlineNum"> 
						 
					 </ul>
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
											$par = ['/%6b/', '/%5C/', '/\{\|/U', '/\|\}/U'];
											$repStr = ['<br/>', '\\', '<img width="24px" class="cli_em" src="/chat/emoticons/images/', '.gif">'];
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
											<?php echo $content;?>
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
<!-- img放大 -->
			<div id="gallery" class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="pswp__bg"></div>
				<div class="pswp__scroll-wrap">
					<div class="pswp__container">
						<div class="pswp__item"></div>
						<div class="pswp__item"></div>
						<div class="pswp__item"></div>
					</div>
					<div class="pswp__ui pswp__ui--hidden">
						<div class="pswp__top-bar">
							<div class="pswp__counter"></div>
								<button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
								<button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
								<button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
								<div class="pswp__preloader">
									<div class="pswp__preloader__icn">
										<div class="pswp__preloader__cut">
											<div class="pswp__preloader__donut"></div>
										</div>
									</div>
								</div>
							</div>
							<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
								<div class="pswp__share-tooltip">
								</div>
							</div>
							<button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
							<button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
							<div class="pswp__caption">
								<div class="pswp__caption__center">
								</div>
							</div>
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
		<a href="#" class="cd-popup-close img-replace alertvalue">关闭</a>
	</div> <!-- cd-popup-chat-container -->
</div> <!-- cd-popup -->
<!-- Resource jQuery -->
<div class="img-box">
	<div class="img-box-title"><span>发送的图片</span><span style="color: #000;" class="com-close com-close-act">&times;</span></div>
	<div class="sending-img-box"></div>
	<div class="img-box-act"><span class="btn btn-success com-close-act">取消</span><span class="btn btn-info send-clipboard-img">发送</span></div>
</div>
<div class="file-main">
	<ul>
			<li>
					<input id="token" name="token" class="ipt" value="">
			</li>
			<li>
					<input id="key" name="key" class="ipt" value="">
			</li>
			<li>
					<input id="file" name="file" class="ipt" type="file" />
			</li>
			<li>
					<input id="filename" type="text" value="">
			</li>
	</ul>
</div>
<!-- 上传进度 -->
<div id="progressbar"><div class="progress-label"></div><div id="formatSpeed"></div></div>

<script type="text/javascript" src="js/webChataAudio.js"></script>
<script>
	$('.loadImg').viewer();

/**********************  业务处理  *************************/
// 管理员联系群的点击

// var chatAdmin = function (obj) {
//     $('.con-tab-content li').css('background', 'none');
//     $(obj).css('background', '#BEB7C7');
//     session_no = 'ca';
//     mes_type = "adminMessage";
//     $('.mes_title_con').html( $(obj).attr('group-name') );
//     $('#mes_load').html(10);
//     ws.send('{"type": "chatAdmin", "mes_type": "'+mes_type+'", "session_no": "ca"}');
//     //消息向上滚动
//     $('.he-ov-box').unbind('scroll');
//     $('.he-ov-box').bind("scroll", function (){
//         mesScroll();
//     })
// }
//文件的分享
var chatShare = function ( obj ) {

		window.event? window.event.cancelBubble = true : evt.stopPropagation();
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
			$('.con-tab-content .list-group').prepend('<li class="recent-contact staff-info chat_people recent-hover" data-placement="right" group-name="'+data.accept_name+'" session_no="'+data.session_no+'"  mes_id="'+data.mes_id+'" mestype ="'+data.mestype+'" ><span class="header-img"><img src="'+data.to_uid_header_img+'" alt=""></span><i>'+data.accept_name+'</i><span title = "删除聊天记录" mestype="'+data.mestype+'"  session="'+data.session_no+'" class="recent-close">&times;</span></div></li>')
	} else {
			$('.con-tab-content .list-group').prepend('<li class="session_no staff-info recent-hover" data-placement="right" group-name="'+data.accept_name+'" session_no="'+data.session_no+'" mes_id="'+data.mes_id+'" mestype ="'+data.mestype+'" ><div><span class="header-img"><img src="/chat/images/rens.png" alt=""></span><i>'+data.accept_name+'</i><span title = "删除聊天记录" mestype="'+data.mestype+'"  session="'+data.session_no+'" class="recent-close">&times;</span></div></li>')
	}
	nearestContact.push(session_no);
		ws.send(JSON.stringify(data));

};
//最近联系人更新
addContact.upd = function ( to_uid ) {

	if ( $('.recent-contact[session_no="'+session_no+'"]').index() != 1 ) {
			$('.con-tab-content .list-group').prepend($('.recent-contact[session_no="'+session_no+'"]'));
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

//群聊人显示
var gpManSh = function (groupid){

		ws.send('{"type": "groupManShow", "session_id":"'+groupid+'"}');
}

//群聊名字点击
$('.group-man-show').click(function (e){
		var obj = $(this);
		var showMansessionNo = obj.attr('groupid');
		if (!$(e.target).is('.groupSign')) {
			gpManSh(showMansessionNo);
		};
		$('.group-man-show').removeClass('groupAct');
		obj.addClass('groupAct');
		obj.addClass('groupSign');

})

//上传图片点击

//触发文件点击
var trig = function (obj){
		obj.trigger('click');
}
$('#upclick').click( function(){
		trig($('#file'));
});
$('#cli-upFile img').click(function(){
		trig($('#file'));
		return;
});
	// 选择图片的改变
$('#send-upimg').on('change', function(){
		//检验是否为图像文件
		var obj = $(this) ;
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
		$('.alertvalue').attr('groupid', groupid);
		$('.alertvalue').attr('atype', 'del');
		$('.alertvalue').attr('id', id);
		$(this).parent().addClass('mandel');
		$('.mes_alert_con').html('你确定要删除他吗？');
		$('.cd-popup').addClass('is-visible'); return false;

});
//退出群
$(document).on('click', '.esc-group', function (){
		alert('还没有开放！');
		return;
})
//群聊解散
$(document).on('click', '.dissolve-group', function (){
		var groupId = $(this).attr('groupId');
		ws.send('{"type": "dissolve_group", "groupId": "'+groupId+'"}');
		$(this).parents('.panel').remove();
})

$('.alertvalue').on('click', function (){
		var obj = $(this);
		if (obj.attr('atype') == "del") {
			$('.mandel').remove();
			var groupid = obj.attr("groupid");
			var id = obj.attr("id");
			ws.send('{"type":"delgroupman", "groupid": "'+groupid+'", "id":"'+id+'"}'); 
		};
})

//删除最近联系人
var delContactFun = function( id, mestype, session_id){
	nearestContact.remove(session_no);
		$('.recent-action').parent('li').remove()
		ws.send('{"type": "delContact", "session_no":"'+session_id+'", "mestype":"'+mestype+'", "id": "'+id+'"}');
}


// 选择人聊天
$(document).on('click', '.chat_people', function( e ){
	$('.pc_he-ov-box').removeClass('chat_initial');
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
			$('.con-tab-content li[mes_id='+to_uid+']').css('background', '#BEB7C7');
			//end
			// groupId = $(this).attr('groupId');
			// if (!$(e.target).is('.mes_chakan_close')) {
			$('.mes_title_con').html( $(this).attr('group-name') );
				if ($.inArray(session_no, arrMessageList) != -1) {
					var con_mes_num =  parseInt($(".mes_chakan_close[session_no='"+session_no+"']").attr('chat_mes_num'));
					mes_chakan_close('message', to_uid, con_mes_num);

				};
			// };
			ws.send('{"type":"mes_chat", "to_uid":"'+to_uid+'"}');
			$('#mes_load').html(10);
			//消息向上滚动
			$('.he-ov-box').unbind('scroll');
			$('.he-ov-box').bind("scroll", function (){
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
		$('.he-ov-box').unbind('scroll');
		$('.he-ov-box').bind("scroll", function (){
			mesScroll();
		})
		//背景颜色的改变
		$('.con-tab-content li').css('background', 'none');
		$('.con-tab-content li[session_no='+session_no+']').css('background', '#BEB7C7');
		$('.mes_title_con').html(valName);
		if ( mes_type == 'groupMessage' ) {
			$('.mes_title_con').append('<i title="群聊添加人" class="add-groupMan"></i>');
			$('.add-groupMan').show();
			groupJson = '{"type":"mes_groupChat", "session_no":"'+session_no+'" }';
		} else if ( mes_type == 'adminMessage' ) {
			groupJson = '{"type":"chatAdmin", "session_no":"ca" }';
		};
		// if (!$(event.target).is('.mes_chakan_close')) {
		if ($(".mes_chakan_close[session_no='"+session_no+"']").length > 0) {
			var con_mes_num =  parseInt($(".mes_chakan_close[session_no='"+session_no+"']").find('.mes_num').html());
			mes_chakan_close(mes_type, session_no, con_mes_num);
		};
		// };
		ws.send(groupJson);
		$('#mes_load').html(10);
})

//删除最近联系人

$(document).on('click', '.recent-action',function (e){
		e.stopPropagation();
		var delconid = ($(this).attr('delconid'));
		var session_id = ($(this).attr('session_no'));
		delContactFun( delconid, mes_type, session_id );
})

//enter 提交
$(".mes_footer, .pc_mes_input").keydown(function(e){
	var e = e || event,
	keycode = e.which || e.keyCode;
	if(e.shiftKey && (e.keyCode==13)){
	} else  if (keycode==13) {
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
		$key = $file.value.split(/(\\|\/)/g).pop();
		document.getElementById('filename').value = $key;
		$key ='file/'+chat_uid+'/'+to_uid+'/'+nowTime+'/'+$key;
		document.getElementById('key').value = $key;

		var token = $("#token").val();
		$.each( $(this)[0].files, function (i, file){
			if(/image\/\w+/.test(file.type)){  
					sendType = 'images';
			}
		})
		if ($("#file")[0].files.length > 0 && token != "") {
			Qiniu_upload($("#file")[0].files[0], token, $key, sendType);
		} else {
				console && console.log("form input error");
		}
		// $("#file").val('');
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
		} else {
			// to_uid_header_img = 
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
//打个消息的关闭
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
// 获取光标 粘贴
function insertHtmlAtCaret(html) {
		var sel, range;
		if (window.getSelection) {
				// IE9 and non-IE
				sel = document.getSelection();
				if (sel.getRangeAt && sel.rangeCount) {
						range = sel.getRangeAt(0);
						range.deleteContents();
						//             sel.getRangeAt(0).commonAncestorContainer;
						// console.log(sel.getRangeAt(0).commonAncestorContainer);
						// Range.createContextualFragment() would be useful here but is
						// non-standard and not supported in all browsers (IE9, for one)
						var el = document.createElement("div");
						el.innerHTML = html;
						var frag = document.createDocumentFragment(), node, lastNode;
						while ( (node = el.firstChild) ) {
								lastNode = frag.appendChild(node);
						}
						range.insertNode(frag);
						// Preserve the selection
						if (lastNode) {
								range = range.cloneRange();
								range.setStartAfter(lastNode);
								range.collapse(true);
								sel.removeAllRanges();
								sel.addRange(range);
						}
				}
		} else if (document.selection && document.selection.type != "Control") {
				// IE < 9
				document.selection.createRange().pasteHTML(html);
		}
}
//粘贴图片
$(function(){
		var imgReader = function( item ){
			var blob = item.getAsFile(),
				reader = new FileReader();
			reader.onload = function( e ){
					var img = new Image();
					img.src = e.target.result;
					img.className = "send-img";
					$('.sending-img-box').html('');
					$('.sending-img-box').append( img );
					$('.img-box').show();
			};
			reader.readAsDataURL( blob );
		};
		// $('.pc_mes_input').bind('paste', function ( e ) {
		//   $('#mes_textarea').trigger('paste');
		// })
	var pasteEvnet = function ( e ) {
		var clipboardData = e.clipboardData,
				i = 0,
				items, item, types;
			// console.log(window.clipboardData.getData("Text"));
			if( clipboardData ){
					items = clipboardData.items;
					if( !items ){
						return;
					}
					item = items[0];
					types = clipboardData.types || [];
					for( ; i < types.length; i++ ){
						if( types[i] === 'Files' ){
								item = items[i];
								break;
						}
					}
					if ( item && item.kind === 'string') {
						insertHtmlAtCaret(clipboardData.getData('text/plain'));
						// $( '#pc_mes_input' ).append( clipboardData.getData('text/plain') );
						inputSave += clipboardData.getData('text/plain');
						// $( '#pc_mes_input' ).on('input', function () {
						//   console.log($(this).html());
						// })
						e.preventDefault();
						return;
					};
					if( item && item.kind === 'file' && item.type.match(/^image\//i) ){
						imgReader( item );
					}
						e.preventDefault();
			}
	} 
	//粘贴事件
	 document.getElementById( 'mes_textarea' ).addEventListener( 'paste', function( e ){
			pasteEvnet( e );return;
		}) 
			//粘贴事件
		document.getElementById( 'pc_mes_input' ).addEventListener( 'paste', function( e ){
			pasteEvnet( e );return;
		}) 
});
//聊天对话框img放大
$(document).on( 'click', '.he_ov .send-img', function (){
	var curIndex = $(this).attr('index');
	// var viewer =new viewer($(".loadImg"));
	$(".loadImg").viewer('update');
	$(".loadImg img").eq(parseInt(curIndex)).trigger('click');
	console.log($(".loadImg img").eq(parseInt(curIndex)).attr('class'))
});
// $('.send-img-close, .send-img-box').click( function (event){

//     if ($(event.target).is('i')) {
//       return false;
//     };
//     $('.send-img-box').hide();
// })
//得到人员头像
var getStaffHead = function ( headImg ){
		to_uid_header_img = headImg;
};
//组织架构的引用
// console.log(sidList)
//button确定
$('#b_is').click(function (){
		var val = $(this).attr('val');

		if (val == "selman") {
			//在手机上交替显示
			if ( $(window).width() < 500 ) {
					$('.chat-container').show();
					$('.details-list').hide();  
			};
			//end
			var jsonText = JSON.stringify(sidList);
			if (sidList.length >1) {
					alert('只能选择一个人！');return false;
			};
			to_uid = sidList[0];
			ws.send('{"type":"mes_chat", "to_uid":"'+to_uid+'"}');
			$('#mes_load').html(10);
			mes_type = "message";
			//会话id的改变
			if ( parseInt(to_uid) < parseInt(chat_uid) ) {
				session_no = to_uid+"-"+chat_uid;
			} else {
				session_no = chat_uid+"-"+to_uid;
			}
			//消息向上滚动
			$('.he-ov-box').unbind('scroll');
			$('.he-ov-box').bind("scroll", function (){
				mesScroll();
			})
			$.ajax({
					url:'getndp.php',
					data:'jsonText='+jsonText,
					type:'post',
					success:function(data){
						var d=eval('('+data+')')
						for (var i = 0; i < d.length; i++) {
							$('.mes_title_con').html(d[i].name);  
								getStaffHead(d[i].card_image);  
						}
					}
			})
		} else if ( val == "addGroupMan" ) {
			// sidList = 1;
			$('.chat_alert').show(500);
				setTimeout(function(){
					$('.chat_alert').hide(500);
			},2000);
			ws.send('{"type":"addGroupMan", "session_no":"'+session_no+'", "sidList":['+sidList+']}');
		
		} else if ( val == "share" ) {
				to_uid = sidList[0];
				var session_id ;
				var content = $('.chat-shareing').attr('data-placement');
				if ( parseInt(to_uid) < parseInt(chat_uid) ) {
					session_id = to_uid+"-"+chat_uid;
				} else {
					session_id = chat_uid+"-"+to_uid;
				}
				$('.chat_alert p').html('已分享！');
				$('.chat_alert').show(500);
				setTimeout(function(){
					$('.chat_alert').hide(500, function (){
						$('.chat_alert p').html('恭喜您操作成功！');
					});
				},2000);
				ws.send('{"type":"sayUid","to_uid":"'+to_uid+'","groupId":"", "accept_name":"'+accept_name+'","message_type":"message", "mes_types":"file","session_no":"'+session_id+'","content":"'+content+'"}')
		}
		$('#name_box').hide();
		$('.selected').find('div').html('<ul class="unique_ul"></ul>');
		$('#No1').find('.ltclasscheckbox').attr('checked',false);
		$('.select_member_num').html($('.selected').find('sid').length+'/'+$('#No1').find('sid').length);
		sidList=[];
})
// 组织架构 button取消
$('#b_no').click(function(){
		$('#name_box').hide();
})

/*********************** end *******************************/

// 图片点击 放大
// (function() {
		// var initPhotoSwipeFromDOM = function(gallerySelector) {

		// 	var parseThumbnailElements = function(el) {
		// 			var thumbElements = el.childNodes,
		// 					numNodes = thumbElements.length,
		// 					items = [],
		// 					el,
		// 					Obj,
		// 					childElements,
		// 					thumbnailEl,
		// 					size,
		// 					item;
		// 			for(var i = 0; i < numNodes; i++) {
		// 				if ( thumbElements[i].className != 'Chat_ri he bigImg' && thumbElements[i].className != 'Chat_le bigImg') {
		// 					continue;
		// 				};
		// 					el = thumbElements[i];
		// 					// include only element nodes 
		// 					if(el.nodeType !== 1) {
		// 						continue;
		// 					}

		// 					childElements = el.children;
		// 					size = el.getAttribute('data-size').split('x');
		// 					// Obj = el.childNodes[0].childNodes;
							
		// 					// for (var y = Obj.length - 1; y >= 0; y--) {
		// 					//   // if ( Obj[i].tagName === 'DIV' ) {
		// 					//   //     console.log(Obj[i].childNodes[1].childNodes[0])
		// 					//   // };
								
		// 					// };
		// 					// create slide object
		// 					item = {
		// 						src: el.getAttribute('href'),
		// 						w: parseInt(size[0], 10),
		// 						h: parseInt(size[1], 10),
		// 						author: el.getAttribute('data-author')
		// 					};

		// 					item.el = el; // save link to element for getThumbBoundsFn

		// 					if(childElements.length > 0) {
		// 						item.msrc = childElements[0].getAttribute('src'); // thumbnail url
		// 						if(childElements.length > 1) {
		// 								item.title = childElements[1].innerHTML; // caption (contents of figure)
		// 						}
		// 					}


		// 			var mediumSrc = el.getAttribute('data-med');
		// 						if(mediumSrc) {
		// 							size = el.getAttribute('data-med-size').split('x');
		// 							// "medium-sized" image
		// 							item.m = {
		// 									src: mediumSrc,
		// 									w: parseInt(size[0], 10),
		// 									h: parseInt(size[1], 10)
		// 							};
		// 						}
		// 						// original image
		// 						item.o = {
		// 							src: item.src,
		// 							w: item.w,
		// 							h: item.h
		// 						};

		// 					items.push(item);
		// 			}
		// 			return items;
		// 	};
		// 	// find nearest parent element
		// 	var closest = function closest(el, fn) {
		// 			return el && ( fn(el) ? el : closest(el.parentNode, fn) );
		// 	};
		// 	var onThumbnailsClick = function(e) {
		// 			// e = e || window.event;
		// 			// e.preventDefault ? e.preventDefault() : e.returnValue = false;
		// 			var eTarget = e.target || e.srcElement;
		// 			if (eTarget.className == 'send-img') {
		// 				var imgHeight;
		// 				var imgWidth;
		// 				eTarget.className = ''
		// 				imgHeight = eTarget.height;
		// 				imgWidth = eTarget.width;
		// 				console.log();
		// 				var dataSize = imgWidth+'x'+imgHeight;
		// 				eTarget.className = 'send-img';
		// 			};
		// 			var clickedListItem = closest(eTarget, function(el) {
		// 					return el.tagName === 'LI';
		// 			});
		// 			if (eTarget.className == 'send-img') {
		// 				clickedListItem.setAttribute('data-size', dataSize)
		// 			};
		// 			if(!clickedListItem) {
		// 					return;
		// 			}

		// 			var clickedGallery = clickedListItem.parentNode;
		// 			var childNodes = clickedListItem.parentNode.childNodes,
		// 					numChildNodes = childNodes.length,
		// 					nodeIndex = 0,
		// 					index;
		// 			for (var i = 0; i < numChildNodes; i++) {
		// 				if ( childNodes[i].className != 'Chat_ri he bigImg' && childNodes[i].className != 'Chat_le bigImg') {
		// 					continue;
		// 				};
		// 					if(childNodes[i].nodeType !== 1) { 
		// 							continue; 
		// 					}
		// 					if(childNodes[i] === clickedListItem) {
		// 							index = nodeIndex;
		// 							break;
		// 					}
		// 					nodeIndex++;
		// 			}
		// 			if(index >= 0) {
		// 					openPhotoSwipe( index, clickedGallery );
		// 			}
		// 			// return false;
		// 	};

		// 	var photoswipeParseHash = function() {
		// 		var hash = window.location.hash.substring(1),
		// 			params = {};

		// 			if(hash.length < 5) { // pid=1
		// 					return params;
		// 			}

		// 			var vars = hash.split('&');
		// 			for (var i = 0; i < vars.length; i++) {
		// 					if(!vars[i]) {
		// 							continue;
		// 					}
		// 					var pair = vars[i].split('=');  
		// 					if(pair.length < 2) {
		// 							continue;
		// 					}           
		// 					params[pair[0]] = pair[1];
		// 			}

		// 			if(params.gid) {
		// 				params.gid = parseInt(params.gid, 10);
		// 			}

		// 			return params;
		// 	};

		// 	var openPhotoSwipe = function(index, galleryElement, disableAnimation, fromURL) {
		// 			var pswpElement = document.querySelectorAll('.pswp')[0],
		// 					gallery,
		// 					options,
		// 					items;

		// 		items = parseThumbnailElements(galleryElement);

		// 			// define options (if needed)
		// 			options = {

		// 					galleryUID: galleryElement.getAttribute('data-pswp-uid'),

		// 					getThumbBoundsFn: function(index) {
		// 							// See Options->getThumbBoundsFn section of docs for more info
		// 							var thumbnail = items[index].el.children[0],
		// 									pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
		// 									rect = thumbnail.getBoundingClientRect(); 

		// 							return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
		// 					},

		// 					addCaptionHTMLFn: function(item, captionEl, isFake) {
		// 				if(!item.title) {
		// 					captionEl.children[0].innerText = '';
		// 					return false;
		// 				}
		// 				captionEl.children[0].innerHTML = item.title +  '<br/><small>Photo: ' + item.author + '</small>';
		// 				return true;
		// 					}
					
		// 			};


		// 			if(fromURL) {
		// 				if(options.galleryPIDs) {
		// 					// parse real index when custom PIDs are used 
		// 					// http://photoswipe.com/documentation/faq.html#custom-pid-in-url
		// 					for(var j = 0; j < items.length; j++) {
		// 						if(items[j].pid == index) {
		// 							options.index = j;
		// 							break;
		// 						}
		// 					}
		// 				} else {
		// 					options.index = parseInt(index, 10) - 1;
		// 				}
		// 			} else {
		// 				options.index = parseInt(index, 10);
		// 			}

		// 			// exit if index not found
		// 			if( isNaN(options.index) ) {
		// 				return;
		// 			}



		// 		var radios = document.getElementsByName('gallery-style');
		// 		for (var i = 0, length = radios.length; i < length; i++) {
		// 				if (radios[i].checked) {
		// 						if(radios[i].id == 'radio-all-controls') {

		// 						} else if(radios[i].id == 'radio-minimal-black') {
		// 						 options.mainClass = 'pswp--minimal--dark';
		// 						 options.barsSize = {top:0,bottom:0};
		// 				 options.captionEl = false;
		// 				 options.fullscreenEl = false;
		// 				 options.shareEl = false;
		// 				 options.bgOpacity = 0.85;
		// 				 options.tapToClose = true;
		// 				 options.tapToToggleControls = false;
		// 						}
		// 						break;
		// 				}
		// 		}

		// 			if(disableAnimation) {
		// 					options.showAnimationDuration = 0;
		// 			}

		// 			// Pass data to PhotoSwipe and initialize it
		// 			// console.log(items);
		// 			gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);

		// 			// see: http://photoswipe.com/documentation/responsive-images.html
		// 		var realViewportWidth,
		// 				useLargeImages = false,
		// 				firstResize = true,
		// 				imageSrcWillChange;

		// 		gallery.listen('beforeResize', function() {

		// 		 var dpiRatio = window.devicePixelRatio ? window.devicePixelRatio : 1;
		// 		 dpiRatio = Math.min(dpiRatio, 2.5);
		// 				realViewportWidth = gallery.viewportSize.x * dpiRatio;


		// 				if(realViewportWidth >= 1200 || (!gallery.likelyTouchDevice && realViewportWidth > 800) || screen.width > 1200 ) {
		// 				 if(!useLargeImages) {
		// 					 useLargeImages = true;
		// 						 imageSrcWillChange = true;
		// 				 }
								
		// 				} else {
		// 				 if(useLargeImages) {
		// 					 useLargeImages = false;
		// 						 imageSrcWillChange = true;
		// 				 }
		// 				}

		// 				if(imageSrcWillChange && !firstResize) {
		// 						gallery.invalidateCurrItems();
		// 				}

		// 				if(firstResize) {
		// 						firstResize = false;
		// 				}

		// 				imageSrcWillChange = false;

		// 		});

		// 		gallery.listen('gettingData', function(index, item) {
		// 				if( useLargeImages ) {
		// 						item.src = item.o.src;
		// 						item.w = item.o.w;
		// 						item.h = item.o.h;
		// 				} else {
		// 						item.src = item.m.src;
		// 						item.w = item.m.w;
		// 						item.h = item.m.h;
		// 				}
		// 		});

		// 			gallery.init();
		// 	};

		// 	// select all gallery elements
		// 	var galleryElements = document.querySelectorAll( gallerySelector );
		// 	for(var i = 0, l = galleryElements.length; i < l; i++) {
		// 		galleryElements[i].setAttribute('data-pswp-uid', i+1);
		// 		galleryElements[i].onclick = onThumbnailsClick;
		// 	}

		// 	// Parse URL and open gallery if it contains #&pid=3&gid=1
		// 	var hashData = photoswipeParseHash();
		// 	if(hashData.pid && hashData.gid) {
		// 		openPhotoSwipe( hashData.pid,  galleryElements[ hashData.gid - 1 ], true, true );
		// 	}
		// };

	// });
/************************  非业务处理 ***********************/
// 获取光标的 位置
// (function ($) {
//     $.fn.extend({
//         insertAtCaret: function (myValue) {
//             var $t = $(this)[0];
//             if (document.selection) {
//                 this.focus();
//                 sel = document.selection.createRange();
//                 sel.text = myValue;
//                 this.focus();
//             } else
//                 if ($t.selectionStart || $t.selectionStart == '0') {
//                     var startPos = $t.selectionStart;
//                     var endPos = $t.selectionEnd;
//                     var scrollTop = $t.scrollTop;
//                     $t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
//                     this.focus();
//                     $t.selectionStart = startPos + myValue.length;
//                     $t.selectionEnd = startPos + myValue.length;
//                     $t.scrollTop = scrollTop;
//                 } else {
//                     this.value += myValue;
//                     this.focus();
//                 }
//         }
//     })
// })(jQuery);
// 客户那里 消息关闭
var chatCustomerNotice = function ( mes_num ) {

	session_id = chat_uid+'sn';
	ws.send('{"type":"sys_mes_close", "message_type":"message"}');
	mesnum = mesnum - parseInt(mes_num);
	$('.mes_radio').html(mesnum);
	$('.mes_chakan_close[session_no="'+session_id+'"]').remove();

};
//系统通知的关闭
$(document).on('click', '.chat_close',function () {
	$(this).parent().hide(500);
})
// 消息通知 里的系统通知 点击
$('.chat_customer_notice').on( 'click', function (){

	var htmlCon = $(this).find('.chat_mes_content').html();
	var mes_num = $(this).attr( 'chat_mes_num' );
	chatCustomerNotice( mes_num );
	$('.chat_message_notice .chat_message_notice_con').html('');
	$('.chat_message_notice .chat_message_notice_con').html(htmlCon);
	$('.chat_message_notice').show(500);

})
// 输入框的 列表 
$('.pc_mes_tool_list').hover(function () {
	$(this).css('opacity', 0.5 );
}, function () {
	$(this).css('opacity', 1 );
})

//用设置增加的class

var setuUserinfo = {
	VoiceState: {
		listClass: ['voiceOff','voiceOn'],
		listSwitch: 0,
		listText: ['关闭声音','开启声音']
	},
	desktopState: {
		listClass: ['deskOff','deskOn',],
		listSwitch: 1,
		listText: ['关闭桌面通知','开启桌面通知']
	}

};
//用户默认设置储存本地
// var setuUserinfo = {
//   VoiceState: 1,
// }
//页面加载
$(function () {
	$.each( setuUserinfo, function ( i, o) {

		if ( localStorage.getItem(i) == null ) {

			localStorage.setItem( i, o.listSwitch );

		} else {

			setuUserinfo[i].listSwitch = localStorage.getItem( i );
		}
		setUserAll( i, setuUserinfo[i].listSwitch );
	})
})

var setUserAll = function ( oc, state ) {
	state = parseInt(state);
	var obj = $(".chat-set-user li."+oc);

	var changeClass = setuUserinfo[oc].listClass[state];

	var changeHtml = setuUserinfo[oc].listText[state];

	obj.html('<i class ="'+changeClass+'"></i>'+changeHtml);
	obj.addClass(changeClass);
	state = state == 1 ? 0 : 1;
	obj.attr('set-switch', state);

}

$('.chat-set-user li').click(function () {

	var _this = $(this);
	var setClass = _this.attr('set-data');
	var setSwitch = _this.attr('set-switch');
	setUserAll(setClass, setSwitch);
	localStorage.setItem( setClass, setSwitch );
	setuUserinfo[setClass].listSwitch = setSwitch;

});
//



!function($) {
	var types = ['notice', 'warning', 'error', 'success'];
	var audio;
	
	var settings = {
		inEffect: {
			opacity: 'show'
		},
		inEffectDuration: 100,
		stay: 5000,
		sticky: false,
		type: 'notice',
		position: 'top-right',
		sound: '/chat/audio/notice.wav',
	};
	
	function dataSetting(el, options, name) {
		var data = el.data('notify-' + name);
		if (data) options[name] = data;
	};
	
	var Notify = function(el, options){
		var el = $(el);
		var $this = this;
		var dataSettings = {};
		
		$.each(['type','stay','position'], function(k, v){
			dataSetting(el, dataSettings, v);
		});
		
		if (el.data('notify-sticky')) {
			dataSettings['sticky'] = el.data('notify-sticky') == 'yes';
		}
		
		this.opts = $.extend({}, settings, dataSettings, typeof options == 'object' && options);
		
		// 检查 type 配置里面是否有 sticky 值
		if (this.opts.type.indexOf('sticky') > -1) {
			this.opts.sticky = true;
			this.opts.type = $.trim(this.opts.type.replace('sticky',''));
		}
		
		// 检查 type 类型是否支持
		if (types.indexOf(this.opts.type) == -1) {
			this.opts.type = settings.type;
		}
		
		var wrapAll =$('.mesNoticeContainer');
		var itemOuter = $('<div></div>').addClass('notify-item-wrapper');
		
		this.itemInner = $('<div></div>').show().addClass('mesNotice').appendTo(wrapAll).animate(this.opts.inEffect, this.opts.inEffectDuration).wrap(itemOuter);
		
		$('<div>&times;</div>').addClass('mesNoticeClose').prependTo(this.itemInner).click(function(){$this.close()});
		$('<div><img src = "'+options.imgUrl+'"></div>').addClass('mesNoticeImg').appendTo(this.itemInner);
		$('<div>'+options.tittle+'</div>').addClass('mesNoticeTittle').appendTo(this.itemInner);
		$('<div>'+options.content+'</div>').addClass('mesNoticeCon').appendTo(this.itemInner);
				navigator.userAgent.match(/MSIE 6/i) && wrapAll.css({top: document.documentElement.scrollTop});
		!this.opts.sticky && setTimeout(function(){$this.close()}, this.opts.stay);
		var VoiceState = localStorage.getItem('VoiceState');
		if ( VoiceState == '0' ) {
			if (window.HTMLAudioElement) {
				audio = new Audio();
				audio.src = options['soundUrl'];
				audio.play();
			}
		}
	};
	
	Notify.prototype.close = function () {
		var obj = this.itemInner;
		obj.animate({opacity: '0'}, 600, function() {
			obj.parent().animate({height: '0px'}, 300, function() {
				obj.parent().remove();
			});
		});
	};
	
	// $.notifySetup = function(options) {
	//   $.extend(settings, options);
	//   // console.log(options);
	//   if (options['sound']) {
	//     if (window.HTMLAudioElement) {
	//       audio = new Audio();
	//       audio.src = options['sound'];
	//     }
	//   }
	// };
	
	$.fn.notify = function(options) {
		return this.each(function () {
			if (typeof options == 'string') {
				return new Notify(this, {type: options});
			}
			return new Notify(this, options);
		});
	};

}(window.jQuery);

// 最近联系人 搜索

$('#search-ren').on('input', function () {
		var inValue = $.trim($(this).val().toString());//去掉两头空格
		var searchRenRes = $('.search-ren-res');
		if ( inValue == '') {
			searchRenRes.hide();
			return;
		};
		var resSearch = search_in('contacts_name', inValue, $recentContact);
		searchRenRes.html('');
		for (var i in resSearch ) {
			if (resSearch.mestype != 'message') {

				searchRenRes.append('<li class="session_no" data-placement="right" group-name="'+resSearch[i].accept_name+'" delConId = '+resSearch[i].id+'  session_no="'+resSearch[i].session_no+'" mes_id="'+resSearch[i].mes_id+'" mestype ="'+resSearch[i].mestype+'" ><span class="header-img"><img src="'+resSearch[i].card_image+'" alt=""></span><i>'+resSearch[i].accept_name+'</i><span title = "从列表中删除" mestype="'+resSearch[i].mestype+'" delConId = '+resSearch[i].id+'  session="'+resSearch[i].session_no+'" class="recent-close">&times;</span></li>');
			} else {
					searchRenRes.append('<li class="recent-contact content-staff-info chat_people" data-placement="right" group-name="'+resSearch[i].accept_name+'" delConId = '+resSearch[i].id+'  session_no="'+resSearch[i].session_no+'" mes_id="'+resSearch[i].mes_id+'" mestype ="'+resSearch[i].mestype+'" ><span class="header-img"><img src="/chat/images/rens.png" alt=""></span><i>'+resSearch[i].accept_name+'</i><span title = "从列表中删除" mestype="'+resSearch[i].mestype+'" delConId = '+resSearch[i].id+'  session="'+resSearch[i].session_no+'" class="recent-close">&times;</span></li>');
			}
		}
		searchRenRes.show();
})

//人员的搜索
$('.search_staff').click(function () {
	$('#search_in').trigger('input');
})

//在线人员信息

var onlineManInfo  = new Array(); 
$('#search_in').on('input', function () {
	var inValue = $.trim($(this).val().toString());//去掉两头空格
	if ( inValue == '') {
			$('.search_result').hide();
			return;
	};
	var resSearch = search_in('client_name', inValue, onlineManInfo);
	$('.search_result').html('');
	for (var p in resSearch ) {
				$('.search_result').append('<li mes_id="'+p+'" data-placement="left" class="staff-info chat_people db_chat_people" group-name="'+resSearch[p].client_name+'"><span class="header-img"><img src="'+client_list[p].header_img_url+'" alt="'+resSearch[p].client_name+'"></span><span style = "color: red">'+resSearch[p].client_name+'</span></li>');
	}
	$('.search_result').show();
})
//搜索在线人数返回 搜索结果
var  search_in = function (index, inValue, array) {
	//在线的人的名字
	var clientName = '';
	//返回的数组
	var resData = {};
	for (var i in array ) {
		clientName = array[i][index];
			console.log(typeof array[i]);
		if (typeof array[i] == 'object' ) {
			if ( clientName.indexOf(inValue) != -1 ) {
				resData[i] = array[i];
			};
		};
	}
	return resData;
}
//pc inout 的高度
var inputBottom = $('.he-ov-box').css('bottom');
//pc 表情的显示
$('.pc_mes_tool_emoji').click(function () {
	var obj = document.getElementById('pc_mes_input');
	$('.pc_emoji_box').show(500);
	addempath("pc_emoji_box");
})

//通知消息关闭
$('.chat_notice_close').click(function () {
	$('.alert').hide(500);
})

//提示消息

var alertMes = function ( con ) {
	$('.alertMesCon').text(con);
	$('.alert').show(500);
	setTimeout(function(){
		$('.alert').hide(500);
	},2000);
}

//客服滑过
$('.kefu-icon').hover(function (e){
	$('.kefu-icon .kefu').animate({
		top: 0,
	})
},function (e){
	$('.kefu-icon .kefu').animate({
		top: 40,
	})
});

//判断是不是移动端
function IsPC()  
{  
		var userAgentInfo = navigator.userAgent;  
		var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod");  
		var flag = true;  
		for (var v = 0; v < Agents.length; v++) {  
			 if (userAgentInfo.indexOf(Agents[v]) > 0) { flag = false; break; }  
		}  
		return flag;  
} 

//
//判断是不是移动端
if (IsPC()) {
		//在线人员滚动条滚动
	// var _move = false;
	// var ismove = false; //移动标记
	// var _x, _y, __y; //鼠标离控件左上角的相对位置
	// //在线人员滚动
	// var ScrollDistance = 47;//每次滑动距离；
	// var onlineTop = parseInt($(".online_man .list-group").css('top'));
	// var maxScroll = docuHeight - onlineTop; //滚动条的最大高度；
	// var onlineTopCh = onlineTop;
	// var onlinesSroll = onlineTopCh;//滚动条top;
	// var onlineScrollHeight = 0;//滚动条可以滚动的距离；
	// var onlineHeight = 0; // 在线人数的高度; 
	// var hideOnlineHeightPro = 0; // 在线人数隐藏的高度； 
	// var onlineSeeHeight = docuHeight - onlineTop;//在线人数可视化的高度；
	// var docuHeight = $(window).height();
	// var proScroll =  0; //在线人数的高度和文档高度的比例；
	// var mousewheelevt=(/Firefox/i.test(navigator.userAgent))?"DOMMouseScroll": "mousewheel"//FF doesn't recognize mousewheel as of FF3.x
	// var mousedir=(/Firefox/i.test(navigator.userAgent))?"detail": "deltaY"//FF doesn't recognize mousewheel as of FF3.x
		//拖动
	// $(document).on('mouseover', ".online_man", function (){
	//     if (proScroll < 1) {
	//       $(".onlinesSroll-box").css('display', 'block');
	//     };
	// })
	// $(document).on('mouseout', ".online_man", function (){
	//     $(".onlinesSroll-box").css('display', 'none');
	// })
};

//人员滑过
$(document).on('mouseover', '.list-group .recent-hover', function (){
		$('.recent-action').removeClass('recent-action');
		$(this).find('.recent-close').addClass('recent-action');
})

//人员离开
$(document).on('mouseout', '.list-group .recent-hover', function (){
		$('.recent-action').removeClass('recent-action');
})

//消息在线人数拖动
var mes_bottom = parseInt($('.mes_fixed').css('bottom'));
$('.mes_fixed').swipe( {
	swipeStatus:function(event, phase, direction, distance, duration, fingerCount) {
			if (direction == 'down' && mes_bottom > 0) {
				// console.log(distance)
				$('.mes_fixed').css('bottom', mes_bottom - distance);
					// console.log($('.mes_fixed').css('bottom'))
			} else if (direction == 'up') {
				// console.log(distance)
					$('.mes_fixed').css('bottom', distance + mes_bottom);
			};
			if (phase == 'cancel' || phase == 'end') {
				mes_bottom = parseInt($('.mes_fixed').css('bottom'));
			};
	},
});


//群聊参加人滑过事件
$(document).on('mouseover', '.group-people',function(){
		$(this).find('.delgroupman').show();
})
$(document).on('mouseout', '.group-people', function(){
		$(this).find('.delgroupman').hide();
})


//对话框的高度
var mesHeight = 0;
//查看更多
$(document).on("click", '.onload', function(){
		$('.he-ov-box').trigger("scroll");
})
//联系人tab
$('.tab-title').click(function(){
		var index = $(this).index();
		var ind = $(this).attr('ind');
		$('.tab-content').hide();
		$('.tab-content').eq(ind).show()
		//小三角移动
		$('.sanjiao').animate({left:((index+1)*2-1)*12.5+"%"}, 'linear');
})
//对话框关闭
$('.mes_dclose').on('click', function (){
		$('.chat-container').hide();
		$('.details-list').show();
})
//滚动条滚动事件
var mesScroll = function (){
		if ($(".he-ov-box").scrollTop() <= 10 && $(".he-ov-box").scrollTop() >= 0) {
			var mes_loadnum = $('#mes_load').html();
			$('.loader').show()
			mesHeight = $('.he_ov').height()
			ws.send('{"type":"mes_load","mes_loadnum":"'+mes_loadnum+'", "message_type":"'+mes_type+'", "to_uid":"'+to_uid+'","session_no": "'+session_no+'"}');
		};
}
//删除指定数组元素
Array.prototype.remove = function(val) {
	var index = this.indexOf(val);
	if (index > -1) {
		this.splice(index, 1);
	}
};

//表情的添加
function addempath(className) {
		var emPath = "<?php echo DOCUMENT_URL?>/chat/emoticons/images/";//表情路径
		var total = 134;//表情的个数
		var newTotal = 14;//新增表情的个数
		$('.'+className).html('');
		for(var i=0; i < newTotal ; i++) {
			$('.'+className).append('<div class="em_gif"><img width="24px" class="cli_em" em_name="'+'f'+i+'" src="'+emPath+'f'+i+'.gif"></div>');
		}
		for(var i=0; i < total ; i++) {
			$('.'+className).append('<div class="em_gif"><img width="24px" class="cli_em" em_name="'+i+'" src="'+emPath+i+'.gif"></div>');
		}
}
//加号的单击
$(".plus_icon").click( function (){
		$(".plus_menu_box").toggle();
});
//pc 输入框聚焦
$('.pc_mes_input').focus( function () {
	$(".pc_emoji_box").hide();
	$(".he-ov-box").css("bottom", inputBottom);
	$(".he-ov-box").scrollTop($(".he-ov-box")[0].scrollHeight);
})
//输入框聚焦
$('#mes_textarea').focus( function(){
		$(".emoticons").hide();
		var inputHeight = $('.mes_footer').height();// 输入框的高度
		$(".he-ov-box").css("bottom", inputHeight);
		$(".he-ov-box").scrollTop($(".he-ov-box")[0].scrollHeight);
})

//表情的点击事件
$(document).on('click', ".emoticons .cli_em, .pc_emoji_box .cli_em", function (event){
		event.stopPropagation();
		var em_name = $(this).attr('em_name');
		var obj = document.getElementById('pc_mes_input');
		var cursor;
		inputSave = inputSave + "{|"+em_name+"|}";
		var addThis = $(this).clone();
		addThis.appendTo('#mes_textarea');
		addThis = $(this).clone();
		// insertHtmlAtCaret(addThis[0].outerHTML);
		addThis.appendTo('#pc_mes_input');
		// $('textarea').val($('.textarea').html())
})
//表情的显示
$('.header_icon').click(function () {
		addempath('emoticons');
		$(".emoticons").toggle();
		$(".plus_menu_box").hide();
		var inputHeight = $('.mes_footer').height();// 输入框的高度
		$(".he-ov-box").css("bottom", inputHeight);
		$(".he-ov-box").scrollTop($(".he-ov-box")[0].scrollHeight);
		$('.plus_menu_box').hide();
})

// 右边消息的个数
var mesnum = parseInt($('.mes_radio').html());//消息的个数

$('.loader').hide();
//消息定位的底部
$(".he-ov-box").scrollTop($(".he_ov")[0].scrollHeight);
//close popup
$('.cd-popup').on('click', function(event){
	if( $(event.target).is('.cd-popup-close') || $(event.target).is('.cd-popup') || $(event.target).is('.clo_alert') ) {
			event.preventDefault();
			$(this).removeClass('is-visible');
	}
});
//close popup when clicking the esc keyboard button
$(document).keyup(function(event){
		if(event.which=='27'){
				$('.cd-popup').removeClass('is-visible');
		}
});
//textare 自适应高度
(function($){
		$.fn.autoTextarea = function(options) {
			var defaults={
					maxHeight:null,
					minHeight:$(this).height()
			};
			var opts = $.extend({},defaults,options);
			$(this).each(function() {
					$(this).bind("keyup",function(){
						var height,style=this.style;
						var inputHeight = $('.mes_footer').height();
						$(".mes_inout").val($(this).html());
						this.style.height =  opts.minHeight + 'px';
						if (this.scrollHeight > opts.minHeight) {
								if (opts.maxHeight && this.scrollHeight > opts.maxHeight) {
										height = opts.maxHeight;
										style.overflowY = 'scroll';
								} else {
										height = this.scrollHeight;
										style.overflowY = 'hidden';
								}
								style.height = height  + 'px';
								$(".he-ov-box").scrollTop($(".he-ov-box")[0].scrollHeight+45)
								$(".he-ov-box").css("bottom", inputHeight);
						}
					});
			});
		};
})(jQuery);
//输入框的最小高度；
$("#mes_textarea").autoTextarea({
		maxHeight:260,
		minHeight:inputBottom
});

//单聊选择人
$('#s_man').click(function(){
		$('#b_is').attr('val', 'selman');
		$('#name_box').show();
})
	//增加群聊人数选择人
$(document).on('click', '.add-groupMan', function(){
		$('#b_is').attr('val', 'addGroupMan');
		$('#name_box').show();
})
//右边图标点击事件
$('.mes_ico_box').swipe( {
		click:function(event, phase, direction, distance, duration,fingerCount) {
			
			var mes_abs = $('.mes_abs').css('right');
			var cata_box = $(this).attr('cata-box');
			if (cata_box == 'ren') {
					$('.online_man').show();
					$('.mes_con').hide();
					//0px 消息隐藏
					if (mes_abs == '0px' || mes_abs == '200px') {
							$('.mes_abs').animate({
										right: 140
							},
							{
									queue: true,
									duration: 500
							})
					} else {
						$('.mes_abs').animate({
								right: 0
						},
						{
								queue: true,
								duration: 500
						})
					}
			} else {
					$('.online_man').hide();
					$('.mes_con').show();
					if (mes_abs == '0px' || mes_abs == '140px') {
							$('.mes_abs').animate({
								right: 200
							},
							{
								queue: true,
								duration: 500
							})
					} else {
						$('.mes_abs').animate({
							right: 0
						},
						{
							 queue: true,
								duration: 500
						})
					}
			}
		}
})
// document点击事件
$(document).click(function (event){
		if( !$(event.target).parents('.mes_fixed_big').is('.mes_fixed_big') && !$(event.target).is('.mes_close')) {
			// event.preventDefault();
			$('.mes_abs').animate({
					right: 0
			},
			{
				queue: true,
				duration: 500
			})
		}
		if (!$(event.target).is('.pc_mes_tool_emoji')) {

			$('.pc_emoji_box').hide();
		};
		if (!$(event.target).parents('#name_box').is('#name_box')) {

			if (!$(event.target).is('#s_man') && !$(event.target).is('.add-groupMan')) {
					$('#name_box').hide();
			};

		};
})
//右边图标
$('.mes_ico_box').hover(function (){
		$(this).css('background-color', '#9dd2e7')
}, function (){
		$(this).css('background-color', '#444851')
})
//消息滑过
$(document).on('mouseenter' , '.mes_box',function(){
		$(this).css('background-color', '#9dd2e7')
		$(this).find('.mes_close').show()
	});
$(document).on('mouseleave', '.mes_box', function (){
		$(this).css('background-color', '#fff')
		$(this).find('.mes_close').hide()
});
//人员换过
//人员信息
var staffInfo = {
		tel:null,
		tel_branch:null,
		mobile_phone:null,
};

//获取人员信息
var getStaffInfo = function (data){
		staffInfo.tel = data.tel;
		staffInfo.tel_branch = data.tel_branch;
		staffInfo.mobile_phone = data.mobile_phone;
}
//请求人员信息
var ajaxGetStaffInfo = function (staffid, direction, css){

		var arrDirectionCg =new Object();
		arrDirectionCg = { 'left': 'right','right':'left', 'up': 'bottom', 'down': 'top' };
		var directionChang = arrDirectionCg[direction];
		var margin ="margin-"+directionChang;
		$('.staff-info-box').remove();
		$.ajax({
			url:"getStaffTels.php",
			type:"post",
			data:"staffid="+staffid,
			success: function ( data ){
					var data = $.parseJSON(data);
					getStaffInfo(data);
					// var index = parseInt($(".infoCurrent").index());
					$(".infoCurrent").append('<div class= "staff-info-box"><div class="arrow"></div><ul><li>座机：'+data.tel+'</li><li>分机：'+data.tel_branch+'</li><li>手机：'+data.mobile_phone+'</li></ul></div>');
					$('.staff-info-box').css(css);
					$('.staff-info-box').css(directionChang, "100%");
					$('.staff-info-box').css(margin, "10");
					$('.staff-info-box .arrow').css('border-'+direction,"8px solid #fff");
					$('.staff-info-box .arrow').css(direction,"100%");

					// $('.staff-info-box').css();
			}
		})
}

$(function (){
	var winHeight = $(window).height();
	var onlineManHeight = winHeight - parseInt($('.oms_onlineNum').css('top'));
	$('.oms_onlineNum').css('height', onlineManHeight);
	$('.search_result').css('height', onlineManHeight);

})
//消息滑过
$(document).on('mouseenter' , '.staff-info',function(){
		var obj = $(this);
		var _index = obj.index();
		var onlineManTop = $('.oms_onlineNum').css('top');
		var staffid = obj.attr('mes_id');
		$('.online_man').addClass('infoCurrent');
		var direction = obj.attr('data-placement');
		var height = obj.outerHeight();
		var offtop = _index * height + height/2 + 38 - $('.oms_onlineNum').scrollTop();
		// var offtop = height/2-85/2;

		ajaxGetStaffInfo(staffid, direction, { top: offtop});
});
// 人员消息的 消失
$(document).on('mouseleave', '.online_man', function (){
		var obj = $(this);
		obj.removeClass('infoCurrent');
		$('.staff-info-box').remove();
});
//消息滑过
$(document).on('mouseenter' , '.content-staff-info',function(){
		var obj = $(this);
		var staffid = obj.attr('mes_id');
		var _index = obj.index();
		var height = obj.outerHeight();
		var offtop = ( _index - 1 ) * height + 96 - $('.con-tab-content').scrollTop();
		$('.details-list').addClass('infoCurrent');
		var direction = obj.attr('data-placement');
		ajaxGetStaffInfo(staffid, direction, {top: offtop});
});
$(document).on('mouseleave', '.content-staff-info', function (){
		$('.details-list').removeClass('infoCurrent');
		$('.staff-info-box').remove();
});

//消息关闭
$('.close').click(function (){
		$('.mes_abs').animate({
			right: 0
		},
		{
			queue: true,
			duration: 0
		})
})
</script>
<body>
	
</body>
</html>