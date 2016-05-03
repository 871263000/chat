<?php
    require("config.inc.php");
    require("header.php");
    $tabName='oms_peer_friendship_uint';
    $idName='id';
    $idName1='staffid';
    $id=$_GET['id'];
    $oms_id = $_SESSION['oms_id'];//OMS_id
    $d = new database();
    $sid = $_SESSION['staffid'];//获取SESSIOid
    $row = $d->find("SELECT * from `oms_peer_friendship_uint` where oms_id = {$oms_id} and state=0 and id =".$id);

    $start=$start==""?0:strtotime($start);
    $stop=$stop==""?time():strtotime($stop);
    $dt="`peer_name`>=$start and `peer_name`<=$stop";
    $Vehicle_id=$_GET['peer_name'];
    $Vehicle_id1=$Vehicle_license_number==null?null:' and `peer_name` like "%'.$Vehicle_id.'%"';
    $where=$dt.$Vehicle_id1;
    //var_dump($row['input_person']);
      //上一篇下一篇
    $sql_pref="SELECT * FROM  {$tabName} where oms_id=$oms_id and `state`=0 and peer_type='0' and {$idName}<'{$id}' and $where  order by id desc limit 0,1";

    $sql_next="SELECT * FROM  {$tabName} where oms_id=$oms_id and `state`=0 and peer_type='0' and {$idName}>'{$id}' and $where  order by id asc limit 0,1";
    $res=$d->records($sql_pref);
    $row1=$d->findAll($sql_pref);
    foreach ($row1 as $key => $value) {
      $row1=$value;
    }
    if ($res) {
      //上一篇
      $pref="上一篇<a href='oms_peer_friendship_uint_details2.php?id={$row1['id']}&start={$start}&stop={$stop}&Vehicle_id={$peer_name}'>{$row1['peer_name']}</a>";
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
      $next="下一篇<a href='oms_peer_friendship_uint_details2.php?id={$row2['id']}&start={$start}&stop={$stop}&Vehicle_id={$peer_name}'>{$row2['peer_name']}</a>";
    }else{
      $next="下一篇 没有了";
    }
        $data=$d->findAll('select * from '.$tabName.' where oms_id='.$oms_id.' and '.$idName.'='.$id); 
        foreach ($data as $key => $value) {
          foreach ($value as $k=> $v) {
                if(@preg_match("/^[0-9]{10,11}$/",$v) && $v){
                    $data[$key][$k]=date('Y-m-d H:i:s',$v);
                }
          }
        }
/********  聊天  ****************/

//自己公司的名字
if ( !empty($oms_id )) {
  $sql = "SELECT org_name FROM `oms_general_admin_user` WHERE `oms_id` = ".$oms_id;
  $arrOrg_name = $d->find($sql);
}
$org_name = !empty($arrOrg_name['org_name']) ? $arrOrg_name['org_name'] : '公司名字去哪了';//公司名字

//获取客户的oms_id
if (!empty($row['peer_name'])) {

  $sql = 'SELECT `oms_id` FROM `oms_general_admin_user` WHERE `org_name` = "'.$row['peer_name'].'"';
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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://apps.bdimg.com/libs/bootstrap/3.3.0/css/bootstrap.min.css">
    <script src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="http://apps.bdimg.com/libs/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <link href="data:text/css;charset=utf-8," data-href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" rel="stylesheet" id="bs-theme-stylesheet">
    
    <title>同行单位详情</title>
</head>
   <style type="text/css">
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
        th{text-align: center;} 
        td{text-align: center;width:25%;}
        .fanhui{font-size:20px;color:red;}
         #tables-bordered{margin:0;}
    </style>
<body>
<!--  聊天 -->
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

<!--  end -->

   <h2 id="tables-bordered" class="text-center">同行单位详情<a class="fanhui" href="./oms_peer_friendship_uint_list2.php">列表页</a></h2>
   <div class="container">
  <table class="table table-bordered table-striped  table-hover"> 
  <form class="form-horizontal" action='oms_peer_friendship_uint_action.php?id=<?php echo $id?>' method='post'  enctype="multipart/form-data">
        <tr>
          <td class='text-right'>单位名称</td>
          <td class='text-left'><?php echo $row['peer_name'] ?><span oms_id = "<?php echo $customer['oms_id'];?>" class="chat_chain External-staffid">&#xe603;</span></td>
          <td class='text-right'>单位类别</td>
          <td class='text-left'><?php echo $row['peer_type']==0?'同行':'友情' ?></td>
        </tr>
      
        <tr>
          <td class='text-right'>国家</td>
          <td class='text-left'><?php echo $row['peer_nation'] ?></td>
          <td class='text-right'>省州</td>
          <td class='text-left'><?php echo $row['peer_province_state_city'] ?></td>
        </tr>
      
        <tr>
          <td class='text-right'>地址</td>
          <td class='text-left'><?php echo $row['address'] ?></td>
          <td class='text-right'>邮政编码</td>
          <td class='text-left'><?php echo $row['post_code'] ?></td>
        </tr>
      
         <tr>
          <td class='text-right'>网站地址</td>
          <td class='text-left'><?php echo $row['web_url'] ?></td>
          <td class='text-right'>网店</td>
          <td class='text-left'> <?php echo $row['web_shop'] ?></td>
        </tr>

        <tr>
          <td class='text-right'>公司邮箱</td>
          <td class='text-left'> <?php echo $row['company_email'] ?></td>
          <td class='text-right'>公司电话</td>
          <td class='text-left'><?php echo $row['company_tel'] ?></td>
        </tr>
         <tr>
          <td class='text-right'>公司传真</td>
          <td class='text-left'><?php echo $row['company_fax'] ?></td>
          <td class='text-right'>录入人</td>
          <td class='text-nowrap'><?php echo $row['new_department'].'-'. $row['new_department_two'].'-'. $row['new_position'].'-'. $row['input_person'] ?>
         <input type="hidden" name="input_person" class="form-control" value="<?php echo $row['input_person'] ?>">
          <input type="hidden" name="new_department" class="form-control" value="<?php echo $row['new_department'] ?>">
          <input type="hidden" name="new_department_two" class="form-control" value="<?php echo $row['new_department_two'] ?>">
          <input type="hidden" name="new_position" class="form-control" value="<?php echo $row['new_position'] ?>"></td>
        </tr>
       
        <tr>
          <td class='text-right'>营业执照图片</td>
          <td colspan="4">
          <?php 
          if($row['business_license_image']){
            echo "<img width='30%' class='img-thumbnail img-rounded' src='".$row['business_license_image']."'>";
          }else{
            echo "没有上传营业执照";
          }
        ?>
          </td>
        </tr>

        <tr>
          <td  class='text-right'>组织机构代码证图片</td>
          <td colspan="4">
          <?php
          if($row['organizational_structure_code_image']){
            echo "<img width='30%'  class='img-thumbnail img-rounded' src='".$row['organizational_structure_code_image']."'>";
          }else{
            echo "没有上传织机构代码证";
          }
        ?></td>
        </tr>
         <tr>
          <td class='text-right'>税务登记证件图像</td>
          <td colspan="4">
          <?php
          if($row['tax_registration_certificates_image']){
            echo "<img width='30%' class='img-thumbnail img-rounded' src='".$row['tax_registration_certificates_image']."'>";
          }else{
            echo "没有上传税务登记证";
          }
        ?></td>
        </tr>

        <tr>
          <td class='text-right'>法人姓名</td>
          <td class='text-left'> <?php echo $row['legal_person_name'] ?></td>
          <td class='text-right'>法人性别</td>
          <td class='text-left'><?php echo $row['legal_person_sex'] ?></td>
        </tr>
         
        <tr>
          <td class='text-right'>法人电话</td>
          <td class='text-left'> <?php echo $row['legal_person_tel'] ?></td>
          <td class='text-right'>法人手机</td>
          <td class='text-left'><?php echo $row['legal_person_mobile_phone'] ?></td>
        </tr>

        <tr>
          <td class='text-right'>法人邮箱</td>
          <td class='text-left'><?php echo $row['legal_person_email'] ?></td>
        </tr>
        
        <tr>
          <td class='text-right'>技术人员姓名</td>
          <td class='text-left'><?php echo $row['technology_person_name'] ?></td>
          <td class='text-right'>技术人员性别</td>
          <td class='text-left'><?php echo $row['technology_person_sex'] ?></td>
        </tr>
      
         <tr>
          <td class='text-right'>技术人员电话</td>
          <td class='text-left'><?php echo $row['technology_person_tel'] ?></td>
          <td class='text-right'>技术人员手机</td>
          <td class='text-left'> <?php echo $row['technology_person_mobile_phone'] ?></td>
        </tr>

        <tr>
          <td class='text-right'>技术人员邮箱</td>
          <td class='text-left'> <?php  echo $row['technology_person_email'] ?></td>       
        </tr>
       
        <tr>
           <td class='text-right'>销售人员姓名</td>
           <td class='text-left'> <?php echo $row['sale_person_name'] ?></td>
          <td class='text-right'>销售人员性别</td>
          <td class='text-left'><?php echo $row['sale_person_sex'] ?></td>
        </tr>
        
        <tr>
          <td class='text-right'>销售人员电话</td>
          <td class='text-left'><?php echo $row['sale_person_tel'] ?></td>
          <td class='text-right'>销售人员手机</td>
          <td class='text-left'><?php echo $row['sale_person_mobile_phone'] ?></td>   
        </tr>
        <tr>
          <td class='text-right'>销售人员邮箱</td>
          <td class='text-left'><?php echo $row['sale_person_email'] ?></td>
        </tr>
       
         <tr>
          <td class='text-right'>制造人员姓名</td>
          <td class='text-left'><?php echo $row['manufacture_person_name'] ?></td>
          <td class='text-right'>制造人员性别</td>
          <td class='text-left'> <?php echo $row['manufacture_person_sex'] ?></td>
        </tr>
         
        <tr>
          <td class='text-right'>制造人员电话</td>
          <td class='text-left'><?php echo $row['manufacture_person_tel'] ?></td>
          <td class='text-right' >制造人员手机</td>
          <td class='text-left'> <?php echo $row['manufacture_person_mobile_phone'] ?></td>
        </tr>

        <tr>
          <td class='text-right'>制造人员邮箱</td>
          <td class='text-left'><?php echo $row['manufacture_person_email'] ?></td>
        </tr>

        <tr>
          <td class='text-right'>检查人员姓名</td>
          <td class='text-left'><?php echo $row['inspection_person_name'] ?></td>
          <td class='text-right'>检查人员性别</td>
          <td class='text-left'><?php echo $row['inspection_person_sex'] ?></td>
        </tr>
       
         <tr>
          <td class='text-right'>检查人员电话</td>
          <td class='text-left'><?php echo $row['inspection_person_tel'] ?></td>
          <td class='text-right'>检查人员手机</td>
          <td class='text-left'><?php echo $row['inspection_person_mobile_phone'] ?></td>  
        </tr>
        <tr>
          <td class='text-right'>检查人员邮箱</td>
          <td class='text-left'> <?php echo $row['inspection_person_email'] ?></td>
        </tr>
        <tr>
          <td class='text-right'>财务人员姓名</td>
          <td class='text-left'> <?php echo $row['finance_person_name'] ?></td>
          <td class='text-right'>财务人员性别</td>
          <td class='text-left'><?php echo $row['finance_person_sex'] ?> </td>
        </tr>
         
        <tr>
          <td class='text-right'>财务人员电话</td>
          <td class='text-left'><?php echo $row['finance_person_tel'] ?></td>
          <td class='text-right'>财务人员手机</td>
          <td class='text-left'><?php echo $row['finance_person_mobile_phone'] ?></td>
        </tr>
  
        <tr>
          <td class='text-right'>财务人员邮箱</td>
          <td class='text-left'><?php echo $row['finance_person_email'] ?></td>
        </tr>
        <tr>
           <td colspan="10" style="text-align:center">
           <p style="float:left" ><?php echo $pref;?></p>
           <a class="btn btn-info" href="./oms_peer_friendship_uint_list2.php?id=<?php echo $id;?>" >返回</a> 
          <p style="float:right" ><?php echo $next;?></p>
           </td>
        </tr>
    </form>
   </table> 
</div>
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

</body>
<?php require_once('./footer.php');?> 
</html>