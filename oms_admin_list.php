<?php
require_once("config.inc.php");
require_once("header_admin.php");
require_once("./lib/bootstrap_page.class.php");
error_reporting(0);
$where="";
if(!empty($_GET['start_time']))
{
	$start_time=strtotime($_GET['start_time']);
	$where.=" and `create_time`>='{$start_time}'";
}
if(!empty($_GET['stop_time']))
{
	$stop_time=strtotime($_GET['stop_time']);
	$where.=" and `create_time`<='{$stop_time}'";
}
if(!empty($_GET['org_name']))
{
	$org_name=$_GET['org_name'];
	$where.=" and `org_name` like '%{$org_name}%'";
}
$d=new database();
$pageSize = 38;
$page = empty($_GET['p']) ? 1:intval($_GET['p']);
$limit = (($page-1) * $pageSize) .','. $pageSize;
$total_result = mysql_query("SELECT COUNT(*) AS `totalRows` FROM `oms_general_admin_user` where `state`=0 $where ");
$total_row =@ mysql_fetch_assoc($total_result);
$sql="SELECT * FROM `oms_general_admin_user` where `state`=0 $where  order by oms_id desc LIMIT $limit";
$data=$d->findAll($sql);
function formHash()//防御csrf
{
	return substr(md5(substr(time(), 0, -5).'8b0c949517feb7046f255614613d9ffc'), 16);
}
?>
<!doctype html>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<script language="javascript" type="text/javascript" src="js/My97DatePicker/WdatePicker.js"></script>
	<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="js/lhgdialog/lhgdialog.min.js?self=false"></script>
	<style type="text/css">
		th{text-align:center;}
		.ic_odd{float:left;display:block;}
		.ic_even{float:left;display:block;}
		.pagination li{width:100%; overflow:hidden; margin-top:20px; list-style:none;}
	    .pagination a{display:block; height:30px; min-width:30px; text-align:center; font-size:14px;  float:left; margin-left:10px; padding:3px 5px; line-height:30px; text-decoration:none; color:#666;}
	    .pagination a:hover,.pagination a.here{background:#337AB7; border-color:#337AB7; color:#FFF;}
	    ul{margin: 0 !important;}
	</style>
	<title>管理员列表</title>
</head>
<body>
	<div>
	<center>
		<h1>管理员列表</h1><br/>
        <h3>实时在线人数：<span id="allOnlineNum">0</span>人</h3><br/>
		<form method="get" action="" class="form-inline text-center">
			 <div class="input-group">
                <span class="input-group-addon" >起</span>
                <input id='start_time' name='start_time' placeholder="请输入起始日期" value="<?php echo empty($_GET['start_time'])?'':$_GET['start_time'];?>" class='form-control Wdate' onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"style='width:170px'>
            </div>
			<div class="input-group">
                <span class="input-group-addon" >止</span>
                <input id='stop_time' name='stop_time' placeholder="请输入结束日期" value="<?php echo empty($_GET['stop_time'])?'':$_GET['stop_time'];?>" class='form-control Wdate' onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"style='width:170px'>
            </div>
			<div class="input-group">
                <span class="input-group-addon" >组织名称</span>
                <input id='org_name' name='org_name' placeholder='请输入组织名称' value="<?php echo empty($_GET['org_name'])?'':$_GET['org_name'];?>" class='form-control' style='width:170px'>
            </div>
			<input type="submit" class="btn btn-primary" value="搜索">
		</form>
		<br>
		<div class="table-responsive">
		<table class="table table-bordered table-striped ">
		 	<tr>
		 		<th>编号</th>
                <th>申请日期</th>
                <th>IP地址</th>
                <th>城市</th>
		 		<th>管理员姓名</th>
		 		<th>组织类型</th>
		 		<th>组织名称</th>
		 		<th>第一次审核状态</th>
		 		<th>第二次审核状态</th>
		 		<th>第三次审核状态</th>
		 		<th>最终审核状态</th>
		 		<th>操作</th>
		 	</tr>
			<?php
			$y = $total_row['totalRows'] - (($page-1) * $pageSize);
				//遍历
				if(!empty($data)){
					foreach ( $data as $key => $value) {
						$url = '<button onclick="ThisPage.operate(this,'."'verify'".');" type="button" class="btn btn-primary btn-xs"  href="javascript:void(0);">审核</button> ';
						$arrnum = array('1'=>'--', '2'=>'--', '3'=>'--');
						$arr = array('1'=>'isverify_1', '2'=>'isverify_2', '3'=>'isverify_3');
						$arrIsverify_end = array('0'=>'<button type="button" class="btn btn-info btn-sm">审核中</button>', '2'=>'<button type="button" class="btn btn-danger btn-sm">未通过</button>', '3'=>'<button type="button" class="btn btn-success btn-sm">通过</button>');
						$isverify_number = $value['isverify_number'];
						for ($i=1; $i <=$isverify_number ; $i++) { 
							if ($value['isverify_'.$i] == 0) {
								$arrnum[$i] = '<button type="button" class="btn btn-primary btn-sm">待审核</button>';
							} elseif ( $value['isverify_'.$i] == 1 ) {
								$arrnum[$i] = '<button type="button" class="btn btn-info btn-sm">审核中</button>';
							} elseif ( $value['isverify_'.$i] == 2 ) {
								$arrnum[$i] ='<button type="button" class="btn btn-danger btn-sm">未通过</button>';
							} elseif ( $value['isverify_'.$i] == 3 ) {
								$arrnum[$i] = '<button type="button" class="btn btn-success btn-sm">通过</button>';
							}
						}
						if ( $isverify_number == 3) {
							if ($value['isverify_1'] >1 && $value['isverify_2'] >1 ) {
								if ($value['isverify_1'] == $value['isverify_2'] ) {
									$arrnum[3] = '--';
									$url = '';
								} else {
									$url = '<button onclick="ThisPage.operate(this,'."'verify'".');" type="button" class="btn btn-primary btn-xs"  href="javascript:void(0);">审核</button> ';
								}
							}
						}
						if ( $isverify_number == 3 && $value['isverify_3'] >1 ) {
							$url = '';
						}
						//最终审核状态
						$verifier_end = $value['verifier_end'];
						$verifier_end = $arrIsverify_end[$verifier_end];
						$create_time=date('Y-m-d H:i:s',$value['create_time']);
						$value['org_type']=array_search($value['org_type'],array('制造业'=>1, '家我群'=>2, '政府机构'=>3, '学校'=>4, '医院'=>5, '商业机构'=>6, 'HMG'=>7,'朗弗睿（上海）_管理公司'=>8,'非商业机构'=>9));
						$hash=formHash();
						echo <<<EOT
						<tr style='text-align:center;' data-id={$value['oms_id']} data-hash={$hash}>
						<td>{$y}</td>
                        <td><a onclick="ThisPage.operate(this,'info');" href="javascript:void(0);">$create_time</td>
						<td>{$value['ip_address']}</td>
						<td>{$value['ip_address_city']}</td>
						<td><a onclick="ThisPage.operate(this,'info');" href="javascript:void(0);">{$value['admin_username']}</td>
						<td><a onclick="ThisPage.operate(this,'info');" href="javascript:void(0);">{$value['org_type']}</td>
						<td><a onclick="ThisPage.operate(this,'info');" href="javascript:void(0);">{$value['org_name']}</td>
						<td><a onclick="ThisPage.operate(this,'info');" href="javascript:void(0);">{$arrnum[1]}</td>
						<td><a onclick="ThisPage.operate(this,'info');" href="javascript:void(0);">{$arrnum[2]}</td>
						<td><a onclick="ThisPage.operate(this,'info');" href="javascript:void(0);">{$arrnum[3]}</td>
						<td><a onclick="ThisPage.operate(this,'info');" href="javascript:void(0);">{$verifier_end}</td><td>$url<button type='button' onclick="ThisPage.operate(this,'info');" class='btn btn-info btn-xs'>查看</button>&nbsp;<button type='button' onclick="ThisPage.operate(this,'del');"; class='btn btn-danger btn-xs'>删除</button>&nbsp;<!--button type='button' onclick="ThisPage.operate(this,'edit');"; class='btn btn-warning btn-xs'>修改</button--></td></tr>
EOT;
						$y--;
					}
					$param = array('totalRows'=>$total_row['totalRows'],'pageSize'=>$pageSize,'currentPage'=>$_GET['p'],'baseUrl'=>'oms_admin_list.php?start_time='.$_GET['start_time'].'&stop_time='.$_GET['stop_time'].'&org_name='.$_GET['org_name']);
                    $page1 = new Bootstrap_Page($param);

                   echo '<tr><td colspan=22><span style="line-height:100%;font-size:14px">当前页码：<span style="color:red">'.$page1->getCurrentPage().'</span>&nbsp;&nbsp;&nbsp;&nbsp;共计&nbsp;'.$page1->pageAmount().'&nbsp;页</span><div style="float:right"><ul class="pagination">'.$page1->pagination().'</ul></div></td></tr>';
				}else{
					echo "<tr><td colspan='22' style='text-align:center'>没有数据!!!</td></tr>";
				}
			 ?>
		 </table>
		 </div>
	</center>
    <div style="width:100%;height:200px">

    </div>
	<script>
	$(function(){
		$('.pagination a').each(function(){
			$(this).wrapAll("<li></li>");
		})
	})
		var ThisPage={
			operate:function(btn,opr){
				var start_time=$('#start_time').val(),stop_time=$('#stop_time').val(),org_name=$('#org_name').val(),tr_dom=$(btn).closest('tr'),trid=tr_dom.data('id'),form_hash=tr_dom.data('hash');
				switch(opr)
				{
					case 'del':
						if($(btn).hasClass('disabled'))
							return false;
						$(btn).addClass('disabled');
						$.dialog.confirm('删除后将无法恢复，确定删除？', function(){
							$.ajax({
								url: "./oms_admin_del_action.php?rand="+Math.round(Math.random()*100),
								type: "post",
								dataType: "json", 
								data:{"hash":form_hash,"id":trid},
								success: function (data) {
									$(btn).removeClass('disabled');
									if(data.status==200)
									{
										$.dialog.tips('删除成功',0.5,'success.gif',function(){
											window.location.reload();
										});
									}
									else
									{
										$.dialog.tips(data.msg,1,'error.gif');
									}
								},
								error:function(a,b,c){
									//alert(b);
								}
							});
						}, function(){
							$(btn).removeClass('disabled');
						});
						break;
					case 'info':
						$.dialog({
							width: '700px',
							title:'查看管理员申请',
							height: '500px',
							lock:true,
							content: 'url:./oms_admin_details.php',
							data:{"hash":form_hash,"id":trid,"start_time":start_time,"stop_time":stop_time,"org_name":org_name},
							focus: true,
							button: [
								{
									name: '关闭'
								}
							]
						});
						break;
					case 'verify':
						var vry=$.dialog({
							width: '700px',
							title:'审核管理员申请',
							height: '500px',
							lock:true,
							content: 'url:./oms_admin_details.php',
							data:{"action":"verify","hash":form_hash,"id":trid,"start_time":start_time,"stop_time":stop_time,"org_name":org_name},
							focus: true,
							button: [
								{
									name: '通过',
									callback: function(){
										var wt=$.dialog({
											content:'操作中，请稍候...',
											icon:'loading.gif',
											lock:true,
											time:1e3,
											resize: false,
											cancel: false,
											fixed: true,
											id: 'Tips',
											title: false,
										});
										$.ajax({
											url:'oms_admin_isverify_upd.php',
											type:'post',
											dataType:'json',
											data:{hash:form_hash,id:trid,verify:"pass"},
											success:function(e){
												wt.close();
												if(e.status==200)
												{
													$.dialog.tips('操作成功',0.8,'success.gif',function(){
														vry.close();
														window.location.reload();
													});
												}
												else
												{
													$.dialog.tips(e.msg,1,'error.gif');
												}
											}
										});
										return false;
									}
								},
								{
									name: '不通过',
									callback: function(){
										var wt=$.dialog({
											content:'操作中，请稍候...',
											icon:'loading.gif',
											lock:true,
											time:1e3,
											resize: false,
											cancel: false,
											fixed: true,
											id: 'Tips',
											title: false,
										});
										$.ajax({
											url:'oms_admin_isverify_upd.php',
											type:'post',
											dataType:'json',
											data:{hash:form_hash,id:trid,verify:"fail"},
											success:function(e){
												wt.close();
												if(e.status==200)
												{
													$.dialog.tips('操作成功',0.8,'success.gif',function(){
														vry.close();
														window.location.reload();
													});
												}
												else
												{
													$.dialog.tips(e.msg,1,'error.gif');
												}
											}
										});
										return false;
									},
									focus: true
								},
								{
									name: '关闭',
									callback:function(){
										window.location.reload();
									}
								}
							]
						});
						break;
					case 'edit':
						$.dialog({
							width: '700px',
							title:'修改管理员申请',
							height: '500px',
							lock:true,
							content: 'url:./oms_admin_details.php',
							data:{"action":"edit","hash":form_hash,"id":trid,"start_time":start_time,"stop_time":stop_time,"org_name":org_name},
							focus: true
						});
						break;
				}
			}
		};
	</script>
	<?php require_once('footer.php'); ?>
</body>
</html>