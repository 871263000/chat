<?php 
require_once("header.php");
error_reporting(E_ALL ^ E_NOTICE);
require_once("config.inc.php");
require_once'fenlei/lt.php';

$d=new database();
$omsid = $_SESSION['oms_id'];
//搜索
$where="";
//单位名称、公司电话搜索
$name1=$_GET['name1'];
$name11=$name1==null?null:' and `peer_name` like "%'.$name1.'%" or `company_tel` like "%'.$name1.'%" ';
//网址搜索
$name2=$_GET['name2'];
$name22=$name2==null?null:' and `web_url` like "%'.$name2.'%"';

//搜索
$where=$name11.$name22;
//page 分页
$pageSize = 38;
$page = $_GET['p'] ? intval($_GET['p']) : 1;
$limit = (($page-1) * $pageSize) .','. $pageSize;

$total_result = mysql_query("SELECT COUNT(*) AS `totalRows` FROM `oms_peer_friendship_uint` where `state`=0 and `oms_id`=$omsid  and peer_type='0' $where  ");

$total_row = @mysql_fetch_assoc($total_result);

$sql="SELECT * FROM `oms_peer_friendship_uint` where `state`=0 and `oms_id`=$omsid  and peer_type='0' $where  order by id desc  LIMIT $limit ";
// echo $sql;
$data=$d->findAll($sql);
//var_dump($data);die;
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>同行单位列表</title>
    <link href="<?php echo DOCUMENT_URL?>/css/bootstrap.min.css" rel="stylesheet">

    <script language='javascript' type='text/javascript' src='<?php echo DOCUMENT_URL?>/js/My97DatePicker/WdatePicker.js'></script>
    <style type="text/css">
        th{text-align: center;}
        .luru{font-size:20px;color:red;}
    </style>

</head>

<body class="visible-xs visible-sm visible-md visible-lg">
  <div class="workpage">
  <h1 class="text-center">同行单位列表<a class="luru" href='./admin/oms_peer_friendship_uint_input2.php'>录入</a></h1>
   <form method="get" class="form-inline text-center" id='form1' action="">
            <div class="input-group">
            <input name="name1" class="form-control" value="<?php echo !empty($name11)?$_GET['name11']:''?>" placeholder="单位名称、公司电话"  />
            </div>
            <div class="input-group">
            <input name="name2" class="form-control" value="<?php echo !empty($name22)?$_GET['name22']:''?>" placeholder="网址"  />
            <span class="input-group-btn">
                 <button class="btn btn-primary" type="submit">
            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> 搜索</button>
            </span> 
            </div>
        </form><br>
     <table class="table table-bordered table-striped table-hover"> 
        <tr align="center">
         <tr>
            <th class='text-nowrap'>序号</th>
            <th class='text-nowrap'>单位名称</th>
            <th class='text-nowrap'>公司电话</th>
            <th class='text-nowrap'>网址</th>
            <th>操作</th>
        </tr>
            </tr>
            <?php 
            $i = (($page-1) * $pageSize);
                //遍历
                if(!empty($data)){
                        foreach ( $data as $key => $value) {            
                        $i++;
                    if(strstr($value['web_url'],"http") or $value['web_url'] == ''){
                        $url = $value['web_url'];
                    }else{
                        $url="http://".ltrim($value['web_url'],'.');
                    }
                        echo "<tr style='text-align:center;'>";
                        echo "<td class='text-nowrap'>{$i}</td>";
                        echo "<td class='text-nowrap'><a href='./oms_peer_friendship_uint_details2.php?id={$value['id']}'>{$value['peer_name']}</a></td>";
                        echo "<td class='text-nowrap'><a href='./oms_peer_friendship_uint_details2.php?id={$value['id']}'>{$value['company_tel']}</a></td>";
                        echo "<td class='text-nowrap'><a href='{$url}'>{$value['web_url']}</a></td>";
                        echo "<td class='text-nowrap'><a class='btn btn-info btn-xs' href='oms_peer_friendship_uint_details2.php?id={$value['id']}'>查看</a></td>"; 
                        echo "</tr>";
                       
                        }
                        $param = array('totalRows'=>$total_row['totalRows'],'pageSize'=>$pageSize,'currentPage'=>$_GET['p'],'baseUrl'=>'oms_peer_friendship_uint_list2.php?start='.$start.'&stop='.$stop.'&grade='.$grade);
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