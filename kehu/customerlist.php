<?php 
require_once('config.inc.php');
require_once('header.php');
// require_once'fenlei/lt.php';
require_once("array_country_city.php");

//加载时 查询所有部门
// $o=$r->getNode('getone');
// $one=json_encode($o);

function county($arr){
	foreach ($arr as $key => $value) {
		$Countylist[]=$value['name'];
	}
	return $Countylist;
}

$county=county($countryCityArray);


$d=new database();
//搜索
$where="";
//客户名称
$search_s = $_GET['search_s'];
$name2=$search_s==null?null:' and (`name`like"%'.$search_s.'%" or `our_sales` like "%'.$search_s.'%")';
//部门 部门细分 职务 姓名
$Department=$_GET['Department'];
$Department_two=$_GET['Department_two'];
$Sector_posts=$_GET['Sector_posts'];
$na=$_GET['na'];
$Department2=$Department=="请选择"||$Department==null?null:' and  `our_sales` like "%'.$Department.'%"';

$Department_two2=$Department_two=="请选择"||$Department_two==null?null:' and `our_sales` like "%'.$Department_two.'%"';

$Sector_posts2=$Sector_posts=="请选择"||$Sector_posts==null?null:' and `our_sales` like "%'.$Sector_posts.'%"';

$nas=$na=="请选择"||$na==null?null:' and `our_sales` like "%'.$na.'%"';


//国家 州省
$nation=$_GET['customer_nation'];
$psc=$_GET['customer_province_state_city'];
$category=$_GET['category'];
$nation2=$nation=="请选择国家"||$nation==null?null:" and `customer_nation`='".$nation."'";
$psc2=$psc=="请选择省州"||$psc==null?null:' and `customer_province_state_city` like "%'.$psc.'%"';
$category2=$category=="请选择产品大类"||$category==null?null:" and `category`='".$category."'";

$where=$name2.$Department2.$Department_two2.$Sector_posts2.$nas.$nation2.$psc2.$category2;

//page 分页
$pageSize = 38;
$page = $_GET['p'] ? intval($_GET['p']) : 1;
$limit = (($page-1) * $pageSize) .','. $pageSize;

$total_result = $d->find("SELECT COUNT(*) AS `totalRows` FROM `oms_customer` where `oms_id`=".$_SESSION['oms_id']." and `state`=0 $where");
$total_row = $total_result['totalRows'];
$sql="SELECT * FROM `oms_customer` where `oms_id`=".$_SESSION['oms_id']." and `state`=0 $where order by customer_id desc  LIMIT $limit ";
// echo $sql;
$data=$d->findAll($sql);
$omsid = $_SESSION['oms_id'];
$sqls = "select product_category from oms_product_classify where oms_id=$omsid and state=0";

$categorys = $d->findAll($sqls);
$category1 = array();
foreach ($categorys as $key => $value) {
    $category1[] = $value['product_category'];
}
$category1=array_filter(array_unique($category1));
// var_dump($category1);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <title>客户名录</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css" />
	<script language="javascript" type="text/javascript" src="js/My97DatePicker/WdatePicker.js"></script>
	<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>

	<style>
    		li{list-style: none;}
        *{margin: 0;padding: 0;font-family: inherit;}
	    .pa li{width:100%; overflow:hidden; margin-top:20px; list-style:none;}
	    .pa a{display:block; height:30px; min-width:30px; text-align:center; font-size:14px;  float:left; margin-left:10px; padding:3px 5px; line-height:30px; text-decoration:none; color:#666;}
	    .pa a:hover,.pa a.here{background:#FF4500; border-color:#FF4500; color:#FFF;}
	    li{list-style-type:none;}
	    ul{
	  	    float: left;
	    }
        .option, .option1{ border: 1px solid #2e6da4; position: absolute;top:100%;z-index: 2; background: #2e6da4;color:#fff;}
        .option li, .option1 li{padding: 5px;border-top: 1px solid #fff;border-bottom: 1px solid #fff; cursor: pointer; list-style: none; top:0;width: 170px;}
        .par_op{position: relative;display:inline-block;}
        .sz_diy{ cursor: pointer; background:  #2e6da4; padding: 2px;color: #fff;}
        .sz_f{ float: left;}
        .in_search{ width:185px;}
	</style>
</head>
</head>
<body>
<!-- 连接外部人员 -->




<div class="container-fluid">
	<h1 style="text-align:center;">客户名录</h1>
    <a href="admin/customerlist_input.php" target='_blank' class='btn btn-info'>跳转到 " 客户详情录入"</a>
	<DepCache style='display:none'></DepCache>
	<Dep1Cache style='display:none'></Dep1Cache>
	<form method="GET" action="" class="form-inline">
        <div class="input-group">
            <span class="input-group-addon">关键字</span>
            <input type="text" name="search_s" class="form-control" placeholder='客户名称、我方销售负责人'>
        </div>
  		<!-- 关键字<input type="text" name="search_s" class="form-control" placeholder='客户名称、我方销售负责人'> -->
        <div class="input-group">
            <span class="input-group-addon">销售负责人部门</span>
            <select name="Department"  onchange='getDep1($(this))' class="depOne form-control" >
                <option>请选择</option>
            </select>
        </div>
  		<!-- 销售负责人部门<select name="Department"  onchange='getDep1($(this))' class="depOne form-control" >
            <option>请选择</option>
        </select> -->
        <div class="input-group">
            <span class="input-group-addon">部门细分</span>
            <select name="Department_two"   class="depTwo form-control" id="xf1" >
                <option>请选择</option>
            </select>
        </div>
        <!-- 部门细分<select name="Department_two"   class="depTwo form-control" id="xf1" >
          <option>请选择</option>
        </select> -->
        <div class="input-group">
            <span class="input-group-addon">职务</span>
            <select name="Sector_posts"  class="job_position1 form-control" >
                <option>请选择</option>
            </select>
        </div>
        <!-- 职务<select name="Sector_posts"  class="job_position1 form-control" >
            <option>请选择</option>
        </select> -->
        <div class="input-group">
            <span class="input-group-addon">姓名</span>
            <select name="na"  class="department4 Ren form-control" >
                <option>请选择</option>
            </select>
        </div>
        <!-- 姓名<select name="na"  class="department4 Ren form-control" >
            <option>请选择</option>
        </select> -->
        <div class="par_op">
        <ul class="option1"> </ul>
        <input name="customer_nation" index='0' class="in_search form-control" type="text" placeholder='搜索国家'>
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
        <input name="customer_province_state_city" class="in_search form-control" type="text" placeholder='选择省州'>
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
                    url:'getshengzhou.php',
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
        <script>
          //     $('[name="customer_nation"]').change(function(){
          //         $('[name="customer_province_state_city"]').html('<option>请选择省州</option>');
          //         $.ajax({
          //             url:'getshengzhou.php',
          //             type:'post',
          //             data:'county='+$('[name="customer_nation"]').val(),
          //             success:function(data){
          //                 eval('var d=('+data+')');
          //                 for(var i in d){
          //                     $('[name="customer_province_state_city"]').append('<option>'+d[i]+'</option>')
          //                 }
          //             }
          //         })
          //     })
        </script>
        <div class="input-group">
            <span class="input-group-addon">产品大类</span>
            <select name="category" class="form-control">
                <option>请选择产品大类</option>
                <?php
                foreach ($category1 as $key => $value) {
                ?>
                <option value="<?php echo $value?>"><?php echo $value?></option>
                <?php
                }
                ?>
            </select>
            <span class="input-group-btn">
                <button class="btn btn-primary" id='sub' type="submit">
                <span class="glyphicon glyphicon-search" aria-hidden="true"></span> 搜索</button>
            </span>
        </div>
        <!-- <select name="category" class="form-control">
            <option>请选择产品大类</option>
            <?php
            foreach ($category1 as $key => $value) {
            ?>
            <option value="<?php echo $value?>"><?php echo $value?></option>
            <?php
            }
            ?> -->
              <!-- <option value="电缆料">电缆料</option>
              <option value="安防产品">安防产品</option>
              <option value="聚乙烯纤维">聚乙烯纤维</option> -->
        <!-- </select>
        <input type="submit" class="btn btn-primary" value="搜索/重置"> -->
	</form>
    <div class="table-responsive">
	<table class="table table-bordered table-striped">
		<tr style="text-align:center">
		    <th style="text-align:center">编号</th>
	 		<th style="text-align:center">客户名称</th>
	 		<th style="text-align:center">地址</th>
	 		<th style="text-align:center">操作</th>
		</tr>
		<?php 
		$i = (($page-1) * $pageSize);
		//遍历
		if(!empty($data)){
			foreach ( $data as $key => $value) {
				$i++;
				echo "<tr style='text-align:center;'>";
				echo "<td>{$i}</td>";
				echo "<td><a href='customer_details.php?customer_id={$value['customer_id']}&name={$name}&na={$person}&customer_province_state_city={$psc}&category={$category}&customer_nation={$nation}'>{$value['name']}</a></td>";
				echo "<td><a href='customer_details.php?customer_id={$value['customer_id']}&name={$name}&na={$person}&customer_province_state_city={$psc}&category={$category}&customer_nation={$nation}'>{$value['address']}</a></td>";
				echo "<td>
                <a href='customer_details.php?customer_id={$value['customer_id']}&name={$name}&na={$person}&customer_province_state_city={$psc}&category={$category}&customer_nation={$nation}' class='btn btn-info btn-sm'>查看</a>
                <a onclick=\"javascript:if(!confirm('确定要删除吗？')){ return false; }\" href='oms_customer_del_action.php?customer_id={$value['customer_id']}&a=del' class='btn btn-danger btn-sm'>删除</a>
                <a href='customer_details_edit.php?customer_id={$value['customer_id']}&p={$_GET['p']}' class='btn btn-warning btn-sm'>修改</a></td>";
				echo "</tr>";
			}
			$param = array('totalRows'=>$total_row,'pageSize'=>$pageSize,'currentPage'=>$_GET['p'],'baseUrl'=>'customerlist.php?search_s='.$search_s.'&Department='.$Department.'&Department_two='.$Department_two.'&Sector_posts='.$Sector_posts.'&na='.$na.'&customer_nation='.$customer_nation.'&customer_province_state_city='.$psc.'&category='.$category);
				$page1 = new Page($param);
			echo <<<html
            <td colspan="22">
        <li>当前页码：{$page1->getCurrentPage()}共计{$page1->pageAmount()}页</li><li class="pa">{$page1->pagination()}</li>
        </td> 
html;
        // echo "</td></tr>";   
        }else{
          echo "<tr><td colspan='22' style='text-align:center'>没有数据!!!</td>";
        }
       ?>	
			</tr>
		</table>
        <div style="height: 200px"></div>
        </div>
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
                    $('.depTwo').html('<option>请选择</option>');
                    if(data){
                        eval('var d=('+data+')');
                        for(var i in d){
                          $('.depTwo').append('<option value="'+d[i].dep1+'">'+d[i].dep1+'</option>')
                        }
                    }
                }
            })
        }
        $('.job_position1').change(function(){
            var mudi=$(this).parents('ul li').next().find('.Ren');
            $(mudi).html('<option>请选择</option>')
            $.ajax({
                url:'ajax_getdep.php',
                type: 'post',
                data:'getRen=yes&dep='+$('DepCache').html()+'&dep1='+$('Dep1Cache').html()+'&pos='+$(this).val(),
                success:function(data){
                    eval('var d=('+data+')');
                    $('.Ren').html('<option>请选择</option>')
                    for(var i=0;i< d.length;i++){
                     $('.Ren').append('<option value='+d[i].name+'>'+d[i].name+'</option>')
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
    <?php require_once('footer.php');?>
</div>
</body>
</html>