<?php 
require_once("header.php");
error_reporting(E_ALL ^ E_NOTICE);
require_once("config.inc.php");
require_once'fenlei/lt.php';

$d=new database();
$omsid = $_SESSION['oms_id'];
//搜索
$where="";
//单位名称、法人代表搜索
$name1=$_GET['name1'];
$name11=$name1==null?null:' and `peer_name` like "%'.$name1.'%" or `legal_person_name` like "%'.$name1.'%" ';
//公司电话、公司传真搜索
$name2=$_GET['name2'];
$name22=$name2==null?null:' and `company_tel` like "%'.$name2.'%" or `company_fax` like "%'.$name2.'%" ';
//技术、销售负责人搜索
$name3=$_GET['name3'];
$name33=$name3==null?null:' and `technology_person_name` like "%'.$name3.'%" or `sale_person_name` like "%'.$name3.'%" ';
//制造、财务负责人搜索
$name4=$_GET['name4'];
$name44=$name4==null?null:' and `manufacture_person_name` like "%'.$name4.'%" or `finance_person_name` like "%'.$name4.'%" ';
$where=$name11.$name22.$name33.$name44;

//page 分页
$pageSize = 38;
$page = $_GET['p'] ? intval($_GET['p']) : 1;
$limit = (($page-1) * $pageSize) .','. $pageSize;

$total_result = mysql_query("SELECT COUNT(*) AS `totalRows` FROM `oms_peer_friendship_uint` where `state`=0 and `oms_id`=$omsid  $where  ");

$total_row = @mysql_fetch_assoc($total_result);

$sql="SELECT * FROM `oms_peer_friendship_uint` where `state`=0 and `oms_id`=$omsid  $where order by id desc  LIMIT $limit ";
// echo $sql;
$data=$d->findAll($sql);
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>同行及友情单位列表</title>
    <link href="<?php echo DOCUMENT_URL?>/css/bootstrap.min.css" rel="stylesheet">

    <script language='javascript' type='text/javascript' src='<?php echo DOCUMENT_URL?>/js/My97DatePicker/WdatePicker.js'></script>
    <style type="text/css">
        th{text-align: center;}
        .luru{font-size:20px;color:red;}
    </style>

</head>

<body class="visible-xs visible-sm visible-md visible-lg">
  <div class="workpage">
  <h1 class="text-center">同行及友情单位列表<a class="luru" href='./admin/oms_peer_friendship_uint_input1.php'>录入</a></h1>
   <form method="get" class="form-inline text-center" id='form1' action="">
            <div class="input-group">
            <input name="name1" class="form-control" value="<?php echo !empty($name11)?$_GET['name11']:''?>" placeholder="单位名称、法人代表"  />
            </div>
            <div class="input-group">
            <input name="name2" class="form-control" value="<?php echo !empty($name22)?$_GET['name22']:''?>" placeholder="公司电话、公司传真"  />
            </div>
            <div class="input-group">
            <input name="name3" class="form-control" value="<?php echo !empty($name33)?$_GET['name33']:''?>" placeholder="技术、销售负责人"  />
            </div>
            <div class="input-group">
            <input name="name4" class="form-control" value="<?php echo !empty($name44)?$_GET['name44']:''?>" placeholder="制造、财务负责人"  />
            <span class="input-group-btn">
                 <button class="btn btn-primary" type="submit">
            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> 搜索</button>
            </span> 
            </div>
        </form><br>
     <table class="table table-bordered table-striped "> 
        <tr align="center">
         <tr>
            <th class='text-nowrap'>序号</th>
            <th class='text-nowrap'>单位名称</th>
            <th class='text-nowrap'>单位类别</th>
            <th class='text-nowrap'>公司电话</th>
            <th class='text-nowrap'>公司传真</th>
            <th class='text-nowrap'>法人代表</th>
            <th class='text-nowrap'>技术负责人</th>
            <th class='text-nowrap'>销售负责人</th>
            <th class='text-nowrap'>制造负责人</th>
            <th class='text-nowrap'>检验负责人</th>
            <th class='text-nowrap'>财务负责人</th>
            <th>操作</th>
        </tr>
            </tr>
            <?php 
            $i = (($page-1) * $pageSize);
                //遍历
                if(!empty($data)){
                        foreach ( $data as $key => $value) {            
                        $i++;
                        echo "<tr style='text-align:center;'>";
                        echo "<td class='text-nowrap'>{$i}</td>";
                        echo "<td class='text-nowrap'>{$value['peer_name']}</td>";
                        echo "<td class='text-nowrap'>";
                        echo $value['peer_type']==0?'同行':'友情'; 
                        echo "</td>";
                        echo "<td class='text-nowrap'>{$value['company_tel']}</td>";
                        echo "<td class='text-nowrap'>{$value['company_fax']}</td>";
                        echo "<td class='text-nowrap'>{$value['legal_person_name']}</td>";
                        echo "<td class='text-nowrap'>{$value['technology_person_name']}</td>";
                        echo "<td class='text-nowrap'>{$value['sale_person_name']}</td>";
                        echo "<td class='text-nowrap'>{$value['manufacture_person_name']}</td>";
                        echo "<td class='text-nowrap'>{$value['inspection_person_name']}</td>";
                        echo "<td class='text-nowrap'>{$value['finance_person_name']}</td>";
                        echo "<td class='text-nowrap'><a class='btn btn-info btn-xs' href='oms_peer_friendship_uint_details1.php?id={$value['id']}'>查看</a> <a class='btn btn-warning btn-xs' href='oms_peer_friendship_uint_edit1.php?id={$value['id']}'>修改</a> <a class='btn btn-danger btn-xs' onclick=\"javascript:if(!confirm('确定要删除吗？')){ return false; }\" href='oms_peer_friendship_uint_action1.php?id={$value['id']}&a=del'>删除</a></td>";
                        echo "</tr>";
                       
                        }
                        $param = array('totalRows'=>$total_row['totalRows'],'pageSize'=>$pageSize,'currentPage'=>$_GET['p'],'baseUrl'=>'oms_peer_friendship_uint_list1.php?start='.$start.'&stop='.$stop.'&grade='.$grade);
                        $page1 = new Page($param);
                        echo "<tr><td colspan='22'>";
                        echo '<div align="right" ><span>当前页码：'.$page1->getCurrentPage().'&nbsp;共计'.$page1->pageAmount().'页</span>&nbsp;'.'</div>';
                        echo '<div class="pa " align="right">'.$page1->pagination().'</div>';
                        echo "</td></tr>";
                    }else{
                        echo "<tr><th colspan='22'>没有数据！！！</th></tr>";
                    }
                 ?>
          
     </table>
    </div>
  </body>
<?php require_once('./footer.php');?> 
</html>