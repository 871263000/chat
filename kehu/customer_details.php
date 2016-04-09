<?php 
require_once('config.inc.php');
require_once('header.php');

$tabName='oms_customer';
$idName='customer_id';
$id=$_GET['customer_id'];
$uid = $_SESSION['staffid'];
$oms_id = $_SESSION['oms_id'];
$d=new database();

/*   查看营业执照代码证税务证    */

//在字符串中用特定字符分割查找指定字符是否存在 默认|， 存在返回true
function strSearchNum($str,$num,$delim='|'){
    $tmp=explode($delim,$str);
    for($i=0;$i<count($tmp);$i++){
        if($tmp[$i]==$num){
            return true;
        }
    } return false;
}
/*******************************/
//接受时间搜索
$where="";
$name=$_GET['name'];
$name2=$name==null?null:' and `name`like"%'.$name.'%"';

$na=$_GET['na'];
$na2=$na=="请选择"||$nation==null?null:' and `our_sales_team_VP`like"%'.$na.'%" or `our_sales` like "%'.$na.'%"';

$nation=$_GET['customer_nation'];
$psc=$_GET['customer_province_state_city'];
$category=$_GET['category'];


$nation2=$nation=="请选择国家"||$nation==null?null:" and `customer_nation`='".$nation."'";
$psc2=$psc=="请选择省州"||$psc==null?null:" and `customer_province_state_city`='".$psc."'";
$category2=$category=="请选择产品大类"||$category==null?null:" and `category`='".$category."'";
$where=$nation2.$name2.$na2.$psc2.$category2;

$row=$d->find("select * from  {$tabName} where `oms_id`= $oms_id AND {$idName}='{$id}'");
//上一篇下一篇
$sql_pref="select * FROM  {$tabName} where `oms_id`=".$_SESSION['oms_id']." and `state`=0 and {$idName}<'{$id}' $where order by customer_id desc limit 0,1";

$sql_next="select * FROM  {$tabName} where `oms_id`=".$_SESSION['oms_id']." and `state`=0 and {$idName}>'{$id}' $where order by customer_id asc limit 0,1";
$res=$d->records($sql_pref);
$row1=$d->findAll($sql_pref);
foreach ($row1 as $key => $value) {
	$row1=$value;
}
if ($res) {
	//上一篇
	$pref="上一篇<a href='customer_details.php?customer_id={$row1['customer_id']}&start={$start}&stop={$stop}&topic={$topic}'>{$row1['name']}</a>";
}else{
	$pref="上一篇 没有了";
}
$res=$d->records($sql_next);
$row2=$d->findAll($sql_next);
foreach ($row2 as $key => $value) {
	$row2=$value;
}
if ($res) {
	//下一篇
	$next="下一篇<a href='customer_details.php?customer_id={$row2['customer_id']}&start={$start}&stop={$stop}&topic={$topic}'>{$row2['name']}</a>";
}else{
	$next="下一篇 没有了";
}
$sid=$row['customer_id'];
$ne=$row['name'];
$yt=$row['business_license_image'];
$zt=$row['organizational_structure_code_image'];
$st=$row['tax_registration_certificates_image'];
//自己公司的名字
if ( !empty($oms_id )) {
	$sql = "SELECT org_name FROM `oms_general_admin_user` WHERE `oms_id` = ".$oms_id;
	$arrOrg_name = $d->find($sql);
}
$org_name = !empty($arrOrg_name['org_name']) ? $arrOrg_name['org_name'] : '公司名字去哪了';//公司名字
//获取客户的oms_id
if (!empty($row['name'])) {

	$sql = 'SELECT `oms_id` FROM `oms_general_admin_user` WHERE `org_name` = "'.$row['name'].'"';
	$arrChain_oms_id = $d->find($sql);

}
//客户信息
$customer = [];
$customer['oms_id']  = !empty($arrChain_oms_id['oms_id']) ? $arrChain_oms_id['oms_id'] : 0;
if ( !empty($customer['oms_id']) ) {
	$sql = "SELECT a.*, b.`card_image`,b.`name` FROM `oms_friend_list` a LEFT JOIN `oms_hr` b ON a.staffid = b.staffid WHERE a.`state` = 2 AND a.`pid`=".$uid;
	$arrFriendList = $d->findAll($sql);
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css" />
	<script language="javascript" type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
	<script src='./js/jquery-1.8.2.min.js'></script>
	<link rel="stylesheet" type="text/css" href="css/OMS.css">
	<style>
		@font-face {
		  font-family: 'iconfont';
		  src: url('//at.alicdn.com/t/font_1458536667_7204554.eot'); /* IE9*/
		  src: url('//at.alicdn.com/t/font_1458536667_7204554.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
		  url('//at.alicdn.com/t/font_1458536667_7204554.woff') format('woff'), /* chrome、firefox */
		  url('//at.alicdn.com/t/font_1458536667_7204554.ttf') format('truetype'), /* chrome、firefox、opera、Safari, Android, iOS 4.2+*/
		  url('//at.alicdn.com/t/font_1458536667_7204554.svg#iconfont') format('svg'); /* iOS 4.1- */
		}
	    .staff-refresh, .staff-close, .chat_chain, .chat_online, .chat_haoyou, .icon16{
	        font-family: 'iconfont' !important;
	    }
	    .icon16{ float: right; height: 40px; line-height: 40px; }
	    .chat_tab_list, .icon16{ font-size: 18px; text-align: center;cursor: pointer;}
	    .chat_chain{ padding: 0 10px; cursor: pointer;}               
		.popup{ position:fixed; background:rgba(0,0,0,0.4); bottom:0; top:0;left:0;right:0; display:none;z-index: 999999999}
		.imgshow{ position:absolute;display:none;border:5px solid #fff;top:0px;height: 100%;}
		img{
			cursor:pointer;
		}
	</style>
	<title>客户详情页</title>
</head>

<body>
<div class="alert alert-success" role="alert">
    <button class="close"  data-dismiss="alert" type="button" >&times;</button>
    <p class="alertMesCon">恭喜您操作成功！</p>
</div>
<!--  -->

	<div class="chainEmployees Loss_of_coke">
		<div class="externalStaffTitle">
			<!-- <span oms_id = "<?php echo $arrChain_oms_id['oms_id'] = !empty($arrChain_oms_id['oms_id']) ? $arrChain_oms_id['oms_id'] : 0;?>" class="staff-refresh">&#xe600;</span> -->
			<span class="staff-close">&#xe601;</span>
		</div>
		<div class = "chain_chat_tab">
			<ul>
				<li class="chat_online chat_tab_list current" title="人员列表">&#xe603;</li>
				<li class="chat_haoyou chat_tab_list" title="联系过的好友">&#xe607;</li>
				<div style="clear:both;"></div>
			</ul>
		</div>
		<div class="chain_chat_con chat_friend_current">
			<ul class="list-group chat_on_all"></ul>
		</div>
		<div class="chain_chat_con">
			<ul class="list-group">
				<?php foreach ($arrFriendList as $key => $value) : ?>
					<?= '<li mes_id= "'.$value['staffid'].'" group-name="'.$value['name'].'" class="externalStaffid-header-img external_chat_people"><img src="'.$value['card_image'].'" alt="">'.$value['name'].'</li>' ?> 
				<?php endforeach; ?>
			</ul>
		</div>
	</div>

<input type="submit" id="submit">
<div class="container">
	<h1 style="text-align: center;">客户详情</h1>
	<a href="admin/customerlist_input.php" target='_blank' class='btn btn-info'>跳转到 "客户详情录入"</a>
	<table class="table table-bordered table-striped">
		<tr>
			<td width="20%">客户名称</td>
			<td width="30%" style="">
			<div></div>
				<?php echo $row['name']?><span oms_id = "<?php echo $customer['oms_id'];?>" class="chat_chain External-staffid">&#xe603;</span>
				

			</td>
			<td width="20%">
				购买产品大类
			</td>
			<td>
				<?php echo $row['category']?>
			</td>
		</tr>
		<tr>
			<td>客户类型</td>
			<td>
				<?php echo $row['client_type']?>
			</td>
			<td>客户状态</td>
			<td>
				<?php echo $row['client_status']?>
			</td>
		</tr>
		<tr>
			<td>我方销售主管副总</td>
			<td colspan="3">
				<?php echo $row['our_sales_team_VP']?>
			</td>
		</tr>
		<tr>
			<td>我方销售负责团队</td>
			<td colspan="3">
				<?php echo $row['our_sales_team']?>
			</td>
		</tr>
		<tr>
			<td>我方销售负责人员</td>
			<td colspan="3">
				<?php echo $row['our_sales']?>
			</td>
		</tr>
		<tr>
			<td>国家</td>
			<td>
				<?php echo $row['customer_nation']?>
			</td>
			<td>省州</td>
			<td>
				<?php echo $row['customer_province_state_city']?>
			</td>
		</tr>
		<tr>
			<td>地址</td>
			<td>
				<?php echo $row['address']?>
			</td>
			<td>邮编</td>
			<td>
				<?php echo $row['post_code']?>
			</td>
		</tr>
		<tr>
			<td>网址</td>
			<td>
				<?php echo $row['web_url']?>
			</td>
			<td>网店</td>
			<td>
				<?php echo $row['web_shop']?>
			</td>
		</tr>
		<tr>
			<td>公司邮箱</td>
			<td>
				<?php echo $row['company_email']?>
			</td>
			<td>公司电话</td>
			<td>
				<?php echo $row['company_tel']?>
			</td>
		</tr>
		<tr>
			<td>公司传真</td>
			<td colspan="3">
				<?php echo $row['company_fax']?>
			</td>
		</tr>
		<tr>
			<td>开发票资料</td>
			<td colspan="3">
				<a href="admin/oms_invoice_infomations_input.php?ac=<?php echo $ne; ?>&a=<?php echo $sid; ?>" class='btn btn-info'>录入</a>
				<a href="invoice_infomations_list.php?ac=<?php echo $ne; ?>&a=<?php echo $sid; ?>" class='btn btn-info'>查看</a>
			</td>
		</tr>
		<tr>
			<td>开户银行列表</td>
			<td colspan="3">
				<a href="admin/oms_bank_of_deposit_input.php?ac=<?php echo $ne; ?>&a=<?php echo $sid; ?>" class='btn btn-info'>录入</a>
				<a href="bank_of_deposit_list.php?ac=<?php echo $ne; ?>&a=<?php echo $sid; ?>" class='btn btn-info'>查看</a>
			</td>
		</tr>
		<tr>
			<td>货物接收仓库列表</td>
			<td colspan="3">
				<a href="admin/oms_warehouse_receipt_input.php?ac=<?php echo $ne; ?>&a=<?php echo $sid; ?>" class='btn btn-info'>录入</a>
				<a href="oms_warehouse_receipt_list.php?ac=<?php echo $ne; ?>&a=<?php echo $sid; ?>" class='btn btn-info'>查看</a>
			</td>
		</tr>
		<tr>
			<td>营业执照图片</td>
			<td colspan="3">
				<?php if($yt) { 
				?>
				<img class="xsximg" src="<?php echo $row['business_license_image']?>" alt="" style="width:30%;">
				<?php } else { ?>
					<span>没有上传营业执照！</span>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td>组织机构代码证图片</td>
			<td colspan="3">
				<?php 
				if($zt) { 
				?>
				<img class="xsximg" src="<?php echo $row['organizational_structure_code_image']?>" alt="" style="width:30%; cursor:pointer;">
				<?php } else { ?>
					<span>没有上传组织机构代码证！</span>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td>税务登记证图片</td>
			<td colspan="3">
				<?php if($st) {
				?>
				<img class="xsximg" src="<?php echo $row['tax_registration_certificates_image']?>" alt="" style="width:30%;">
				<?php } else { ?>
					<span>没有上传税务登记证！</span>
				<?php } ?>
			</td>
		</tr>

		<tr>
			<td>采购负责人姓名</td>
			<td>
				<?php echo $row['purchase_person_name']?>
			</td>
			<td>性别</td>
			<td>
				<?php echo $row['purchase_person_sex']?>
			</td>
		</tr>
		<tr>
			<td>采购负责人座机电话</td>
			<td>
				<?php echo $row['purchase_person_tel']?>
			</td>
			<td>采购负责人移动电话</td>
			<td>
				<?php echo $row['purchase_person_mobile_phone']?>
			</td>
		</tr>
		<tr>
			<td>采购负责人邮箱</td>
			<td colspan="3">
				<?php echo $row['purchase_person_email']?>
			</td>
		</tr>
		<tr>
			<td>法人代表姓名</td>
			<td>
				<?php echo $row['legal_person_name']?>
			</td>
			<td>性别</td>
			<td>
				<?php echo $row['legal_person_sex']?>
			</td>
		</tr>
		<tr>
			<td>法人代表座机电话</td>
			<td>
				<?php echo $row['legal_person_tel']?>
			</td>
			<td>法人代表移动电话</td>
			<td>
				<?php echo $row['legal_person_mobile_phone']?>
			</td>
		</tr>
		<tr>
			<td>法人代表邮箱</td>
			<td colspan="3">
				<?php echo $row['legal_person_email']?>
			</td>
		</tr>
		<tr>
			<td>技术负责人姓名</td>
			<td>
				<?php echo $row['technology_person_name']?>
			</td>
			<td>性别</td>
			<td>
				<?php echo $row['technology_person_sex']?>
			</td>
		</tr>
		<tr>
			<td>技术负责人座机电话</td>
			<td>
				<?php echo $row['technology_person_tel']?>
			</td>
			<td>技术负责人移动电话</td>
			<td>
				<?php echo $row['technology_person_mobile_phone']?>
			</td>
		</tr>
		<tr>
			<td>技术负责人邮箱</td>
			<td colspan="3">
				<?php echo $row['technology_person_email']?>
			</td>
		</tr>
		<tr>
			<td>生产负责人姓名</td>
			<td>
				<?php echo $row['manufacture_person_name']?>
			</td>
			<td>性别</td>
			<td>
				<?php echo $row['manufacture_person_sex']?>
			</td>
		</tr>
		<tr>
			<td>生产负责人座机电话</td>
			<td>
				<?php echo $row['manufacture_person_tel']?>
			</td>
			<td>生产负责人移动电话</td>
			<td>
				<?php echo $row['manufacture_person_mobile_phone']?>
			</td>
		</tr>
		<tr>
			<td>生产负责人邮箱</td>
			<td colspan="3">
				<?php echo $row['manufacture_person_email']?>
			</td>
		</tr>
		<tr>
			<td>检验负责人姓名</td>
			<td>
				<?php echo $row['inspection_person_name']?>
			</td>
			<td>性别</td>
			<td>
				<?php echo $row['inspection_person_sex']?>
			</td>
		</tr>
		<tr>
			<td>检验负责人座机电话</td>
			<td>
				<?php echo $row['inspection_person_tel']?>
			</td>
			<td>
				检验负责人移动电话
			</td>
			<td>
				<?php echo $row['inspection_person_mobile_phone']?>
			</td>
		</tr>
		<tr>
			<td>检验负责人邮箱</td>
			<td colspan="3">
				<?php echo $row['inspection_person_email']?>
			</td>
		</tr>
		<tr>
			<td>财务负责人姓名</td>
			<td>
				<?php echo $row['finance_person_name']?>
			</td>
			<td>性别</td>
			<td>
				<?php echo $row['finance_person_sex']?>
			</td>
		</tr>
		<tr>
			<td>财务负责人座机电话</td>
			<td>
				<?php echo $row['finance_person_tel']?>
			</td>
			<td>财务负责人移动电话</td>
			<td>
				<?php echo $row['finance_person_mobile_phone']?>
			</td>
		</tr>
		<tr>
			<td>财务负责人邮箱</td>
			<td colspan="3">
				<?php echo $row['finance_person_email']?>
			</td>
		</tr>
		<tr>
			<td>客户发货特殊要求</td>
			<td colspan="3">
				<?php echo $row['custom_delivery_special_requirement']?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<a href="customer_details_edit.php?customer_id=<?php echo $_GET['customer_id']; ?>">我要修改</a>
			</td>
			<td colspan="2" style="text-align:right;">
				<a href="customerlist.php">返回客户名录</a>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<p style="float:left"><?php echo $pref;?></p>
			</td>
			<td colspan="2">
				<p style="float:right"><?php echo $next;?></p>
			</td>
		</tr>
	</table>
	<div class="othertables">
	    <a href="oms_customer_order_list.php?id=<?php echo $id ?>" class='btn btn-info'>合同订单汇总</a>
	    <a href="oms_customer_quotation_list.php?name=<?php echo $row['name'] ?>" class='btn btn-info'>报价单汇总</a>
	    <a href="oms_customer_confirmation_requests_list.php?name=<?php echo $row['name'] ?>" class='btn btn-info'>询证函汇总</a>
	    <a href="oms_customer_technical_specification_list.php?name=<?php echo $row['name'] ?>" class='btn btn-info'>技术规范汇总</a>
	    <a href="oms_statement_of_account_list.php?money_source_unit_person=<?php echo $row['name'] ?>" class='btn btn-info'>对账单汇总</a>
	    <a href="oms_customer_borrowing_list.php?name=<?php echo $row['name'] ?>" class='btn btn-info'>试用/借用单汇总</a>
	    <a href="oms_customer_deliver_the_goods_list.php?id=<?php echo $id ?>" class='btn btn-info'>交货数量汇总</a>
	    <a href="oms_should_take_back_money_list.php?money_source_unit_person=<?php echo $row['name'] ?>" class='btn btn-info'>应收账款汇总</a><br/><br/>
	    <a href="oms_customer_payment_for_goods_list.php?name=<?php echo $row['name'] ?>" class='btn btn-info'>货款回笼汇总</a>
	    <a href="oms_customer_product_quality_issues_list.php?name=<?php echo $row['name'] ?>" class='btn btn-info'>质量投诉汇总</a>
	    <a href="oms_customer_production_equipment_list.php?name=<?php echo $row['name'] ?>&hz=1" class='btn btn-info'>客户生产设备概况</a>
	    <a href="oms_visit_customer_plan_list.php?name=<?php echo $row['name'] ?>&hz=1" class='btn btn-info'>走访计划汇总</a>
	    <a href="oms_customer_intention_goods_list.php?name=<?php echo $row['name'] ?>&hz=1" class='btn btn-info'>意向购买我方产品汇总</a>
	    <a href="oms_business_travel_report.php?id=<?php echo $id ?>" class='btn btn-info'>出差报告汇总</a>
    </div>
</div>
<div class="popup">
	<div class="imgshow">
	<img class="big" style='height:100%' src="images/big-1.gif"/>
	</div>
</div>
<?php require_once('footer.php');?>
</body>
</html>
<script>
/**************  联系客户   **********************/
// 外部选择人聊天

//好友集合
var friendList = new Array();

friendList = <?= !empty($arrFriendList) ? json_encode($arrFriendList) : json_encode([]);?>;

$(document).on('click', '.chain_friend_all', function ( e ) {
	if ($(e.target).is('.apply_session')) {
		return;
	};
	var chain_friend_all = $(this).attr('tagfriend');
	if ( chain_friend_all == "0") {
		alert('你们还没有关注不能对话！可以点右边的图标申请对话！');
	};
})
//拖动
var _move = false;

//联系外公司tab
var chainTab  =  $('.chat_tab_list');

//申请会话
var $org_name = "<?= $org_name ?>";
$(document).on('click', '.apply_session' , function () {
	var mes_id = $(this).attr('mes_id');
	var chainName = $(this).attr('name');
	var tagfriend = $(this).attr('tagfriend');
	ws.send('{"type": "addFriends", "uid": "'+mes_id+'", "accept_name": "'+chainName+'", "companyName": "'+$org_name+'"}');
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

/********************************/
// $('.alert').show(500);
// setTimeout(function(){
// 	$('.alert').hide(500);
// },2000);

/************* 关注 **************/

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
//员工的点击事件
$(document).on('click', '.external_chat_people', function( e ){
  	to_uid = $(this).attr('mes_id');
  	to_uid_header_img = $(this).find('img').attr('src');
  	//会话id的改变
  	session_no = to_uid < uid ? to_uid+"-"+uid : uid+"-"+to_uid;
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
    ws.send('{"type":"mes_chat", "mes_para":"'+to_uid+'"}');
    $('#mes_load').html(10);
    //消息向上滚动
    $('.he-ov-box').unbind('scroll');
    $('.he-ov-box').bind("scroll", function (){
      mesScroll();
    })
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
	if ( addContact.is( session_no ) == false ) {
		addContact.Dom();
	};
})
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
/***************end****************/
// getExternalObj.////
$('.xsximg').click(function(){
	pl = [],pli=[];
	$(this).parent().find('img').each(function(i){
		pl[$(this).attr('src')]=i; pli[i] = $(this).attr('src');
	})
	_index = pl[$(this).attr('src')];
	$(".imgshow img.big").attr("src",pli[_index]);
	$(".popup").show(); $(".imgshow").show();


	var imgWidth = $('.imgshow img.big').width();//图片的宽度
    var mar = $(window).width()/2 - imgWidth/2;

    $('.imgshow').css('margin-left', mar);
	$('body').css('overflow-y','hidden');
	$('.popup').css('overflow-y','scroll');
	$('.popup').css('overflow-x','hidden');
})
$(".popup").click(function(event){
	$(".imgshow").hide();
	$(this).hide();
	$('body').css('overflow-y','scroll');
});
</script>