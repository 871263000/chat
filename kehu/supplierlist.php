<?php require_once('config.inc.php');?>
<?php require_once('header.php');?>
<?php

error_reporting(E_ALL ^ E_NOTICE);
require_once'fenlei/lt.php';
    //加载时 查询所有部门
$o=$r->getNode('getone');
$one=json_encode($o);
require_once("array_country_city.php");
function county($arr){
	foreach ($arr as $key => $value) {
		$Countylist[]=$value['name'];
	}
	return $Countylist;
}
$county=county($countryCityArray);
$d=new database();

//按供货类别查找
$supply_goods_type = $_GET['supply_goods_type'];

if(isset($supply_goods_type) && $supply_goods_type !=''){
    $where1 = "and (supply_goods_type='".$supply_goods_type."') ";
}else{
    $where1='';
}

//echo $supply_goods_type;
$omsid = $_SESSION['oms_id'];
//搜索
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

//page 分页
$pageSize = 38;
$page = $_GET['p'] ? intval($_GET['p']) : 1;
$limit = (($page-1) * $pageSize) .','. $pageSize;

$total_result = mysql_query("SELECT COUNT(*) AS `totalRows` FROM `oms_supplier` where `state`=0 and `oms_id`=$omsid $where1 $where");

$total_row = @mysql_fetch_assoc($total_result);
$sql="SELECT * FROM `oms_supplier` where `state`=0 and `oms_id`=$omsid $where1 $where order by supplier_id asc  LIMIT $limit ";
$data=$d->findAll($sql);


?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?php echo DOCUMENT_URL?>/css/bootstrap.min.css" rel="stylesheet">
		  <script language="javascript" type="text/javascript" src="js/My97DatePicker/WdatePicker.js"></script>
		  <script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
  		<style>
  		  *{margin: 0;padding: 0;font-family: inherit;}
  		      .pa li{width:100%; overflow:hidden; margin-top:20px; list-style:none;}
  		      .pa a{display:block; height:30px; min-width:30px; text-align:center; font-size:14px;  float:left; margin-left:10px; padding:3px 5px; line-height:30px; text-decoration:none; color:#666;}
  		      .pa a:hover,.pa a.here{background:#FF4500; border-color:#FF4500; color:#FFF;}
  		  li{
  		    list-style-type:none;

  		  }
  		  table th{
          text-align: center;
  		    }
  		  ul{
  		  	float: left;
  		  }
        .option, .option1{ border: 1px solid #2e6da4; position: absolute;top: 100%;z-index: 2; background: #2e6da4;color:#fff;}
        .option li, .option1 li{padding: 5px;border-top: 1px solid #fff;border-bottom: 1px solid #fff; cursor: pointer; list-style: none; top: 100%;width: 170px;}
        .par_op{position: relative;float: left;}
        .sz_diy{ cursor: pointer; background:  #2e6da4; padding: 2px;color: #fff;}
        .sz_f{ float: left;}
        .in_search{ width:185px;}
        .pagination li{width:100%; overflow:hidden; margin-top:20px; list-style:none;}
        .pagination a{display:block; height:30px; min-width:30px; text-align:center; font-size:14px;  float:left; margin-left:10px; padding:3px 5px; line-height:30px; text-decoration:none; color:#666;}
        .pagination a:hover,.pagination a.here{background:#337AB7; border-color:#337AB7; color:#FFF;}
        ul{margin: 0 !important;}
  		 </style>
	</head>
</head>
<body>
	<div class="workpage">
		<h1 class="text-center">供应商名录<a style="text-decoration:none;font-size:15px" href="admin/supplierlist_input.php" >供应商详情录入</a></h1>
   
		<form method="GET" action="" class="form-inline">
			<div class="par_op">
			供应商名称关键字<input type="text" name="name"  class="form-control">
			,采购负责部门<select name="Department"  onchange='getDep1($(this))' class="depOne form-control" >
          	<option>请选择</option>
        	</select>
   			  ,细分<select name="Department_two"   class="depTwo form-control" id="xf1" >
            <option>请选择</option>
        	</select>
    		  ,职务<select name="Sector_posts"  class="job_position1 form-control" >
          	<option>请选择</option>
        	</select>
    		  ,姓名<select name="na"  class="department4 Ren form-control" >
          	<option>请选择</option>
        	</select>
          单位类别
                <select class="form-control" name="supply_goods_type" >
                                <option value=""> --选择类别--</option>
                                <option value="0"> 原材料</option>
                                <option value="1"> 物流</option>
                                <option value="2"> 外协</option>
                                <option value="3"> 服务</option>
                                <option value="4"> 礼品</option>
                                <option value="5"> 劳防用品</option>
                                <option value="6"> 办公用品</option>
                                <option value="7"> 设备</option>
                                <option value="8"> 备品备件</option>
                                <option value="9"> 车辆</option>
                                <option value="10"> 技术</option>
                                <option value="11"> 咨询</option>
                                <option value="12"> 顾问</option>
                            </select>
        </div>
        <div class="par_op">
        <ul class="option1"> </ul>
        <input name="supplier_nation" index='0' class="in_search form-control" type="text" placeholder='搜索国家'>
        <select style="display:none">
           <?php 
                for ($i=0; $i < count($county); $i++) { 
                  echo '<option>'.$county[$i].'</option>';
                }
           ?>
        </select>
      </div>
      <div class="par_op">
        <ul class="option1"></ul>
        <input name="supplier_province_state_city" class="in_search form-control" type="text" placeholder='选择省州'>
        <select style="display:none" id="shengshi">
         <option>请选择省州</option>
        </select>
      </div>
      <script>
                $(document).ready(function(){
                        function country(in_val){
                            $('.option1').html('')
                            $('#shengshi').html('');
                            $.ajax({
                                url:'supplier_getshengzhou.php',
                                type:'post',
                                data:'county='+in_val,
                                success:function(data){
                                    if (data!='') {
                                        eval('var d=('+data+')');
                                        for(var i in d){
                                           $('#shengshi').append('<option>'+d[i]+'</option>')
                                        }
                                    };
                                }
                            })
                        }
                    /* 省州的得焦事件 */
                    $('.in_search').focus(function(){
                        $(this).trigger('keyup')
                    })
                    /* 省州的滑过离开事件 */
                    $('.option1').hover(function(){
                       $('.in_search').unbind('blur')
                        }, function(){
                           $('.in_search').bind('blur', function(){
                                $('.option1').hide()
                                if ($(this).attr('name') == 'customer_nation') {
                                    country($(this).val())
                                };
                            })
                    })
                    /* 省州的失焦事件 */
                    $('.in_search').blur(function(){
                        $('.option1').hide()
                        if ($(this).attr('name') == 'customer_nation') {
                            country($(this).val())
                        };
                        
                    })
                    /* 省州的按下事件 */
                    $('.in_search').keyup(function(){
                        var inval = $(this).val()
                        var inobj = $(this)
                        var ul = $(this).prev('ul')
                        ul.show()
                        ul.html('')
                        $(this).next('select').find('option').each(function(){
                            var t = $(this).val().indexOf(inval);
                            if (t >=0) {
                                ul.append('<li>'+$(this).val()+'</li>')
                                $(this).parent().parent().children('ul').find('li:last').click(function(){
                                   var value = $(this).html()
                                   inobj.val(value)
                                    ul.hide()
                                    if (inobj.attr('index') == '0') {
                                        country($(this).html())
                                    };

                                })
                            };
                        })
                    })
                })
        </script>
        	</select>
       		<input type="submit" value="搜索" class="btn btn-primary">
       		</li></ul>
		 </form>
     
		 <br>
		 <table class="table table-bordered table-striped">
		 	<tr align="center">
		 		<th>编号</th>
		 		<th>供应商名称</th>
        <th>地址</th>
		 		<th>供应商类别</th>
		 		<th>操作</th>
		 	</tr>
			<?php 
			$i = (($page-1) * $pageSize);
				//遍历
				if(!empty($data)){
						foreach ( $data as $key => $value) {			
						$i++;
						echo "<tr style='text-align:center;'>";
						echo "<td>{$i}</td>";
						echo "<td><a href='supplier_details.php?supplier_id={$value['supplier_id']}&name={$name}&na={$na}&supplier_province_state_city={$psc}&category={$category}&supplier_nation={$nation}'>{$value['name']}</td>";
            echo "<td><a href='supplier_details.php?supplier_id={$value['supplier_id']}&name={$name}&na={$na}&supplier_province_state_city={$psc}&category={$category}&supplier_nation={$nation}'>{$value['address']}</td>";
            //数据转换
            if(is_numeric($value['supply_goods_type'])){
              switch ($value['supply_goods_type']) {
                  case '0':
                    $xinxi = "原材料";
                    break;
                  case '1':
                    $xinxi = "物流";
                    break;
                  case '2':
                    $xinxi = "外协";
                    break;
                  case '3':
                    $xinxi = "服务";
                    break;
                  case '4':
                    $xinxi = "礼品";
                    break;
                  case '5':
                    $xinxi = "劳动用品";
                    break;
                  case '6':
                    $xinxi = "办公用品";
                    break;
                  case '7':
                    $xinxi = "设备";
                    break;
                  case '8':
                    $xinxi = "备品备件";
                    break;
                  case '9':
                    $xinxi = "车辆";
                    break;
                  case '10':
                    $xinxi = "技术";
                    break;
                  case '11':
                    $xinxi = "咨询";
                    break;
                  case '12':
                    $xinxi = "顾问";
                    break;    
                  
                  default:
                    # code...
                    break;
                }
              }else{
                $xinxi = $value['supply_goods_type'];
              }
						echo "<td><a href='supplier_details.php?supplier_id={$value['supplier_id']}&name={$name}&na={$na}&supplier_province_state_city={$psc}&category={$category}&supplier_nation={$nation}'>{$xinxi}</td>";
						echo "<td><a class='btn btn-primary' href='supplier_details.php?supplier_id={$value['supplier_id']}&name={$name}&na={$na}&supplier_province_state_city={$psc}&category={$category}&supplier_nation={$nation}'>查看</a>&nbsp&nbsp<a class='btn btn-warning' onclick=\"javascript:if(!confirm('确定要删除吗？')){ return false; }\" href='oms_supplier_del_action.php?supplier_id={$value['supplier_id']}&a=del'>删除</a>&nbsp&nbsp<a class='btn btn-info' href='supplier_details_edit.php?supplier_id={$value['supplier_id']}&'>修改</a></td>";
						echo "</tr>";
						}
							$param = array('totalRows'=>$total_row['totalRows'],'pageSize'=>$pageSize,'currentPage'=>$_GET['p'],'baseUrl'=>'supplierlist.php?supplier_nation='.$nation.'&supplier_province_state_city='.$psc.'&name='.$name.'&na='.$na.'&supply_goods_type='.$supply_goods_type);
				$page1 = new Page($param);
			 echo '<tr><td colspan=22><span style="line-height:100%;font-size:14px">当前页码：<span style="color:red">'.$page1->getCurrentPage().'</span>&nbsp;&nbsp;&nbsp;&nbsp;共计&nbsp;'.$page1->pageAmount().'&nbsp;页</span><div style="float:right"><ul class="pagination">'.$page1->pagination().'</ul></div></td></tr>';
        }else{
          echo "<tr><td colspan='22' style='text-align:center'>没有数据!!!</td>";
        }
       ?>
					</td>	
			 	</tr>
		 	</table>
        <?php require_once('footer.php');?>
		</div>
	</body>
</html>
	<script type="text/javascript">
        //这里是加载一级部门
        var o=eval(<?php echo $one;?>);

        for(var i in o){
            $('.depOne').append('<option value='+o[i].Dep+'>'+o[i].Dep+'</option>')
        }
        //根据部门查询部门细分 @param == 部门
        function getDep1(item){
            $.ajax({
                url:'fenlei/lt.php',
                type:'post',
                data:'Dep='+$(item).val(),
                success:function(data){
                   $('DepCache').html('')
                   $('DepCache').html($(item).val())
                  var mudi=$(item).parents('ul'&'li').next().find('.depTwo');
                   $(mudi).html('<option>请选择</option>');
                    if(data){
                        eval('var d=('+data+')');
                        for(var i in d){
                          $(mudi).append('<option value="'+d[i].dep1+'">'+d[i].dep1+'</option>')
                        }
                    }
                }
            })
        }
        $('.job_position1').change(function(){
          
          var mudi=$(this).parents('ul'&'li').next().find('.Ren');
          $(mudi).html('<option>请选择</option>')
           $.ajax({
              url:'ajax_getdep.php',
              type: 'post',
              data:'getRen=yes&dep='+$('DepCache').html()+'&dep1='+$('Dep1Cache').html()+'&pos='+$(this).val(),
              success:function(data){
                 eval('var d=('+data+')');
                 for(var i=0;i< d.length;i++){
                     $(mudi).append('<option value='+d[i].name+'>'+d[i].name+'</option>')
                }
              }
          })
        })
         $('.depTwo').change(function(){
          $('Dep1Cache').html('')
          $('Dep1Cache').html($(this).val())
        })
        //查询职务的东西
         $.ajax({
            url:'ajax_getdep.php',
            type: 'post',
            data: 'getposition=yes',
            success: function(data){
                eval('var d=('+data+')');
                $('.job_position1').html('<option>请选择</option>')
                for(var i=0;i< d.length;i++){
                    $('.job_position1').append('<option value='+d[i].name_of_post_and_position+'>'+d[i].name_of_post_and_position+'</option>')
                }
            }
        })
    </script>