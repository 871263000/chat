<?php 
require_once('config.inc.php');
require_once('header.php');

$tabName='oms_supplier';
$idName='supplier_id';
$oms_id = $_SESSION['oms_id'];
$d=new database();

if (isset($_GET['id']) && $_GET['id'] !='') {
	$sbid = $_GET['id'];

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

	$nation=$_GET['supplier_nation'];
	$psc=$_GET['supplier_province_state_city'];



	$nation2=$nation=="请选择国家"||$nation==null?null:" and `supplier_nation`='".$nation."'";
	$psc2=$psc=="请选择省州"||$psc==null?null:" and `supplier_province_state_city`='".$psc."'";

	$where=$nation2.$name2.$na2.$psc2;

	$row=$d->find("select * from  {$tabName} where sbid=$sbid and oms_id=".$_SESSION['oms_id']);

	$sbdata=$d->find("select * from  oms_equipment where id=$sbid and oms_id=".$_SESSION['oms_id']);

	$sid=$row['supplier_id'];
	$ne=$row['name'];
	$yt=$row['business_license_image'];
	$zt=$row['organizational_structure_code_image'];
	$st=$row['tax_registration_certificates_image'];
}else{
	$id=$_GET['supplier_id'];

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

	$nation=$_GET['supplier_nation'];
	$psc=$_GET['supplier_province_state_city'];



	$nation2=$nation=="请选择国家"||$nation==null?null:" and `supplier_nation`='".$nation."'";
	$psc2=$psc=="请选择省州"||$psc==null?null:" and `supplier_province_state_city`='".$psc."'";

	$where=$nation2.$name2.$na2.$psc2;

	$row=$d->find("select * from  {$tabName} where {$idName}='{$id}'");

	//上一篇下一篇
	$sql_pref="select * FROM  {$tabName} where `oms_id`=".$_SESSION['oms_id']." and `state`=0 and {$idName}<'{$id}' $where order by supplier_id desc limit 0,1";

	$sql_next="select * FROM  {$tabName} where `oms_id`=".$_SESSION['oms_id']." and `state`=0 and {$idName}>'{$id}' $where order by supplier_id asc limit 0,1";
	$res=$d->records($sql_pref);
	$row1=$d->findAll($sql_pref);
	foreach ($row1 as $key => $value) {
		$row1=$value;
	}
	if ($res) {
		//上一篇
		$pref="上一篇<a href='supplier_details.php?supplier_id={$row1['supplier_id']}&start={$start}&stop={$stop}&topic={$topic}'>{$row1['name']}</a>";
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
		$next="下一篇<a href='supplier_details.php?supplier_id={$row2['supplier_id']}&start={$start}&stop={$stop}&topic={$topic}'>{$row2['name']}</a>";
	}else{
		$next="下一篇 没有了";
	}
	$sid=$row['supplier_id'];
	$ne=$row['name'];
	$yt=$row['business_license_image'];
	$zt=$row['organizational_structure_code_image'];
	$st=$row['tax_registration_certificates_image'];

}

$sfgm = array(1=>'已购买',2=>'意向购买');

$gyslb = array("原材料","物流","外协","服务","礼品","劳防用品","办公用品","设备","备品备件","车辆","技术","咨询","顾问");


/********  聊天  ****************/

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
//客户聊天信息
$customer = [];
$customer['oms_id']  = !empty($arrChain_oms_id['oms_id']) ? $arrChain_oms_id['oms_id'] : 0;
if ( !empty($customer['oms_id']) ) {
	$sql = "SELECT a.*, b.`card_image`,b.`name` FROM `oms_friend_list` a LEFT JOIN `oms_hr` b ON a.staffid = b.staffid WHERE a.`oms_id`= '".$arrChain_oms_id['oms_id']."' AND a.`state` = 2 AND a.`pid`=".$uid;
	$arrFriendList = $d->findAll($sql);
}
/******** 聊天end  ***************/


?>

<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?php echo DOCUMENT_URL?>/css/bootstrap.min.css" rel="stylesheet">
    <script src='./js/jquery-1.8.2.min.js'></script>
    <style >
	    .chat_tab_list, .icon16{
	    	text-align: center;
	    	font-size: 18px;
	    }
	    .icon16 {
		    float: right;
		    height: 40px;
		    line-height: 40px;
		}
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
    	.lazy{cursor:pointer;}
		.left1{ position:fixed; top:50%; left:2%; cursor:pointer;z-index: 99999;}
		.right1{ position:fixed; top:50%; right:2.5%; cursor:pointer;}
		.popup{ position:fixed; background:rgba(0,0,0,0.4); bottom:0; top:0;left:0;right:0; display:none;z-index: 999999999}
		.imgshow{ position:absolute;display:none;border:5px solid #fff;top:0px;height: 100%;}
    </style>
	
	<title>供应商详情页</title>
</head>

<body >
<div class="alert alert-success" role="alert">
    <button class="close"  data-dismiss="alert" type="button" >&times;</button>
    <p class="alertMesCon">恭喜您操作成功！</p>
</div>

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
<div class="workpage">
	<div class="container">
		 <h1 class="text-center">供应商详情<a style="font-size:15px;color:#f90;margin-left:50px;text-decoration:none" href="admin/supplierlist_input.php" target='_blank'>供应商详情录入</a></h1>
		 <?php if ($sbdata): ?>
		 <table class="table table-bordered table-striped " width=100% border=1 cellspacing=0 cellpadding=5>
			<tr>
				<td width="20%">设备编号：</td>
				<td width="30%"><?php echo $sbdata['equipment_number'] ?></td>
				<td width="20%">设备型号：</td>
				<td><?php echo $sbdata['type'] ?></td>
			</tr>
			<tr>
				<td>设备名称：</td>
				<td><?php echo $sbdata['name'] ?></td>
				<td>设备规格：</td>
				<td><?php echo $sbdata['specification'] ?></td>
			</tr>
			<tr>
				<td>使用部门：</td>
				<td><?php echo $sbdata['use_department'].'-'.$sbdata['use_department_powder'] ?></td>
				<td>设备供应商：</td>
				<td><?php echo $sbdata['equipment_supplier'] ?></td>
			</tr>
			<tr>
				<td>设备状态：</td>
				<td><?php echo $sbdata['equipment_status'] ?></td>
				<td>设备设计寿命：</td>
				<td><?php echo $sbdata['equipment_design_life'] ?></td>
			</tr>
			<tr>
				<td>是否购买：</td>
				<td><?php echo $sfgm[$sbdata['purchase']];?></td>
				<td>购买时间：</td>
				<td><?php echo date("Y-m-d H:i:s",$sbdata['buy_date']); ?></td>
			</tr>
		</table>
		<?php endif ?>
		<?php
			if ($row) {
		?>
		 <table  class="table table-bordered table-striped">
		 	<tr>
		 		<td width="20%">供应商名称:</td>
		 		<td width="30%"><?php echo $row['name']?><span oms_id = "<?php echo $customer['oms_id'];?>" class="chat_chain External-staffid">&#xe603;</span></td>
		 		<td width="20%">供货商类别:</td>
		 		<td><?php echo $gyslb[$row['supply_goods_type']];?></td>
		 	</tr>
		 	<tr>
		 		<td>供应商级别:</td>
		 		<td><?php echo $row['supplier_type']?></td>
		 		<td>供应商状态:</td>
		 		<td><?php echo $row['supplier_status']?></td>
		 	</tr>
		 	<tr>
		 		<td>供应商行业:</td>
		 		<td><?php echo $row['supplier_industry'];?></td>
		 		<td >公司传真:</td>
		 		<td><?php echo $row['company_fax']?></td>
		 	</tr>
		 	<tr>
		 		<td>我方采购的产品:</td>
		 		<td><?php echo $row['products_we_purchase']?></td>
		 		<td>我方采购人员:</td>
		 		<td><?php echo $row['our_purchaser']?></td>
		 	</tr>
		 	<tr>
		 		<td>地址:</td>
		 		<td><?php echo $row['address']?></td>
		 		<td>国家:</td>
		 		<td><?php echo $row['supplier_nation']?></td>
		 	</tr>
		 	<tr>
		 		<td>省州:</td>
		 		<td><?php echo $row['supplier_province_state_city']?></td>
		 		<td>邮编:</td>
		 		<td><?php echo $row['post_code']?></td>
		 	</tr>
		 	<tr>
		 		<td>网址:</td>
		 		<td><?php echo $row['web_url']?></td>
		 		<td>网店:</td>
		 		<td><?php echo $row['web_shop']?></td>
		 	</tr>

		 	<tr>
		 		<td>公司邮箱:</td>
		 		<td><?php echo $row['company_email']?></td>
		 		<td>公司电话:</td>
		 		<td><?php echo $row['company_tel']?></td>
		 	</tr>

		 	<tr>
		 		<td>营业执照图片:</td>
		 		<td colspan="3"><?php 
		 			if($row['business_license_image']){
		 				echo "<img width='30%' class='yingye_wang' src='".$row['business_license_image']."'>";
		 			}else{
		 				echo "没有上传营业执照";
		 			}
		 		?>

		 		</td>
		 	</tr>
		 	<tr>
		 		<td>组织机构代码证图片:</td>
		 		<td colspan="3">
		 		<?php
		 			if($row['organizational_structure_code_image']){
		 				echo "<img width='30%' class='yingye_wang' src='".$row['organizational_structure_code_image']."'>";
		 			}else{
		 				echo "没有上传织机构代码证";
		 			}
		 		?>
		 		</td>
		 	</tr>
		 	<tr>
		 		<td>税务登记证图片:</td>
		 		<td colspan="3">
		 		<?php
		 			if($row['tax_registration_certificates_image']){
		 				echo "<img width='30%' class='yingye_wang' src='".$row['tax_registration_certificates_image']."'>";
		 			}else{
		 				echo "没有上传税务登记证";
		 			}
		 		?>
		 		</td>
		 	</tr>
		 	<tr>
		 		<td>开发票资料</td>
		 		<td colspan="3"><a class="btn btn-info" href="admin/oms_supplier_invoice_infomations_input.php?ac=<?php echo $ne; ?>&a=<?php echo $sid; ?>">录入</a>&nbsp<a class="btn btn-primary" href="supplier_invoice_infomations_list.php?ac=<?php echo $ne; ?>&a=<?php echo $sid; ?>">查看</a></td>
		 	</tr>
		 	<tr>
		 		<td>开户银行列表</td>
		 		<td colspan="3"><a class="btn btn-info" href="admin/oms_supplier_bank_of_deposit_input.php?ac=<?php echo $ne; ?>&a=<?php echo $sid; ?>">录入</a>&nbsp<a class="btn btn-primary" href="supplier_bank_of_deposit_list.php?ac=<?php echo $ne; ?>&a=<?php echo $sid; ?>">查看</a></td>
		 	</tr>
		 	<tr>
		 		<td>货物接受仓库列表</td>
		 		<td colspan="3"><a class="btn btn-info" href="admin/oms_supplier_warehouse_receipt_input.php?ac=<?php echo $ne; ?>&a=<?php echo $sid; ?>">录入</a>&nbsp<a class="btn btn-primary" href="oms_supplier_warehouse_receipt_list.php?ac=<?php echo $ne; ?>&a=<?php echo $sid; ?>">查看</a></td>
		 	</tr>

		 	<tr>
		 		<td>法人代表姓名:</td>
		 		<td><?php echo $row['legal_person_name']?></td>
		 		<td>性别:</td>
		 		<td><?php echo $row['legal_person_sex']?></td>
		 	</tr>

		 	<tr>
		 		<td>法人代表座机电话:</td>
		 		<td><?php echo $row['legal_person_tel']?></td>
		 		<td>法人代表移动电话:</td>
		 		<td><?php echo $row['legal_person_mobile_phone']?></td>
		 	</tr>

		 	<tr>
		 		<td>法人代表邮箱:</td>
		 		<td colspan="3"><?php echo $row['legal_person_email']?></td>
		 	</tr>

		 	<tr>
		 		<td>技术负责人姓名:</td>
		 		<td><?php echo $row['technology_person_name']?></td>
		 		<td>性别:</td>
		 		<td><?php echo $row['technology_person_sex']?></td>
		 	</tr>

		 	<tr>
		 		<td>技术负责人座机电话:</td>
		 		<td><?php echo $row['technology_person_tel']?></td>
		 		<td>技术负责人移动电话:</td>
		 		<td><?php echo $row['technology_person_mobile_phone']?></td>
		 	</tr>

		 	<tr>
		 		<td>技术负责人邮箱:</td>
		 		<td colspan="3"><?php echo $row['technology_person_email']?></td>
		 	</tr>

		 	<tr>
		 		<td>销售负责人姓名:</td>
		 		<td><?php echo $row['sale_person_name']?></td>
		 		<td>性别:</td>
		 		<td><?php echo $row['sale_person_sex']?></td>
		 	</tr>

		 	<tr>
		 		<td>销售负责人座机电话:</td>
		 		<td><?php echo $row['sale_person_tel']?></td>
		 		<td>销售负责人移动电话:</td>
		 		<td><?php echo $row['sale_person_mobile_phone']?></td>
		 	</tr>

		 	<tr>
		 		<td>销售负责人邮箱:</td>
		 		<td colspan="3"><?php echo $row['sale_person_email']?></td>
		 	</tr>

		 	<tr>
		 		<td>制造负责人姓名:</td>
		 		<td><?php echo $row['manufacture_person_name']?></td>
		 		<td>性别:</td>
		 		<td><?php echo $row['manufacture_person_sex']?></td>
		 	</tr>

		 	<tr>
		 		<td>制造负责人座机电话:</td>
		 		<td><?php echo $row['manufacture_person_tel']?></td>
		 		<td>制造负责人移动电话:</td>
		 		<td><?php echo $row['manufacture_person_mobile_phone']?></td>
		 	</tr>
		 	<tr>
		 		<td>制造负责人邮箱:</td>
		 		<td colspan="3"><?php echo $row['manufacture_person_email']?></td>
		 	</tr>

		 	<tr>
		 		<td>检验负责人姓名:</td>
		 		<td><?php echo $row['inspection_person_name']?></td>
		 		<td>性别:</td>
		 		<td><?php echo $row['inspection_person_sex']?></td>
		 	</tr>

		 	<tr>
		 		<td>检验负责人座机电话:</td>
		 		<td><?php echo $row['inspection_person_tel']?></td>
		 		<td>检验负责人移动电话:</td>
		 		<td><?php echo $row['inspection_person_mobile_phone']?></td>
		 	</tr>

		 	<tr>
		 		<td>检验负责人邮箱:</td>
		 		<td colspan="3"><?php echo $row['inspection_person_email']?></td>
		 	</tr>

		 	<tr>
		 		<td>财务负责人姓名:</td>
		 		<td><?php echo $row['finance_person_name']?></td>
		 		<td>性别:</td>
		 		<td><?php echo $row['finance_person_sex']?></td>
		 	</tr>

		 	<tr>
		 		<td>财务负责人座机电话:</td>
		 		<td><?php echo $row['finance_person_tel']?></td>
		 		<td>财务负责人移动电话:</td>
		 		<td><?php echo $row['finance_person_mobile_phone']?></td>
		 	</tr>

		 	<tr>
		 		<td>财务负责人邮箱:</td>
		 		<td colspan="3"><?php echo $row['finance_person_email']?></td>
		 	</tr>
		 	<tr>
		 		<td colspan=4 align="center"><a href="./supplier_details_edit.php?supplier_id=<?php echo $row['supplier_id'];?>&sbid=<?php echo $sbid;?>&p=<?php echo $_GET['p'];?>"><button class="btn btn-info">我要修改</button></a></td>
		 	</tr>
		 </table>
		 <?php
		 	}else{
		 ?>
			<table  class="table table-bordered table-striped">
				<tr>
					<td align="center">还没有录入供应商数据！</td>
				</tr>
			</table>
		<?php }?>
    <p style="float:left"><?php echo $pref;?></p><p style="float:right"><?php echo $next;?></p>
    <br/>
    </div>
    <?php require_once('footer.php');?>
</div>
	<div class="popup">
			
		<div class="imgshow">
			<img class="big" style='height:100%' src="images/big-1.gif"/>
		</div>
			
	</div>
</body>
	<script >
		$('.yingye_wang').click(function(){
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
		
		if($(event.target).is('.right1') || $(event.target).is('.left1')){
			return false;
		}
		$(".imgshow").hide();
		$(this).hide();
		$('body').css('overflow-y','scroll');
	});
	</script>

<script>
  /**************  联系客户   **********************/
	// 外部选择人聊天

	//好友集合
	var friendList = new Array();

	friendList = <?= !empty($arrFriendList) ? json_encode($arrFriendList) : json_encode([]);?>;
	//申请会话
	var $org_name = "<?= $org_name ?>";
	var $oms_id = "<?= $arrChain_oms_id['oms_id'] ?>";
</script>
<script src="/chat/js/conExternal.js"></script>
</html>