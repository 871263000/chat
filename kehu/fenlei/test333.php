<?php require_once('../config.inc.php');?>
<?php


/*------------------------------------------*/
require_once'lt.php';
echo '<pre>';
$aa= $r->getdata(0);
print_r($aa['person']);
//echo'<textarea style="width: 800px;height:800px">';
//print_r($aa['person']);

//echo $str;
//echo '</textarea>';
print_r($aa['person']);
//foreach($aa['person'] as $k=>$v){
//    $depOne[]=$k;
//    $depTwo[]=$v;
//}
//print_r($depTwo);
//echo '<hr/>';
//foreach($depTwo as $k=>$v){
//
//}
exit;
/*------------------------------------------*/


    //加载时 查询所有部门
    $o=$r->getNode('getone');

$one=json_encode($o);
?>
    <script language="javascript" type="text/javascript" src="<?php echo DOCUMENT_URL;?>/js/jquery-1.8.2.min.js"></script>
<div class="workpage">
    部门:<select name="one" onchange='getDep1($(this).val())'><option>请选择</option></select>
    部门细分:<select name="two" ><option>请选择</option></select>
</div>
<script type="text/javascript">
    var o=eval(<?php echo $one;?>);
    for(var i in o){
        $('[name="one"]').append('<option value='+o[i].Dep+'>'+o[i].Dep+'</option>')
    }
    //根据部门查询部门细分 @param == 部门
    function getDep1(Dep){
        $.ajax({
            url:'lt.php',
            type:'post',
            data:'Dep='+Dep,
            success:function(data){
                $('[name="two"]').html('<option>请选择</option>');
                if(data){
                    eval('var d=('+data+')');
                    for(var i in d){
                         $('[name="two"]').append('<option value="'+d[i].dep1+'">'+d[i].dep1+'</option>')
                    }
                }
            }
        })
    }

</script>

<?php require_once('../footer.php');?>


<html>

<!--
         function getOT($onedep){
            //查一级节点
            $hr_datat=mysql_query('select * from oms_hr where new_department="'.$onedep.'" and new_department_two =""');
            while ($row=mysql_fetch_assoc($hr_datat)) {
                $strr=str_replace(' ','',$row['new_position'].':'.$row['name']);
                $resData[]=<<<li
                <li class='afl' onclick="listu(this)" ondblclick="chat({$row['name']})"path="{$row['path']}" desc="{$row['desc']}" myinner="{$row['name']}" sid="{$row['id']}"
                onmousedown="fun(this)">
                <input class="ltclasscheckbox" type="checkbox">{$strr}
                 <span style="display:none" class="btns">
                 <button class="select_daily_list" depname="{$row['name']}">查看报表</button>
                 <button class="input_daily_job" depname="{$row['name']}">日常工作</button></span></li>
li;
            }

            $data=mysql_query('select * from oms_department where Dep="'.$onedep.'"');
            $hr_data=mysql_fetch_array($data);
            $hr_fpath=$hr_data['pid'].','.$hr_data['id'];
            $hr_sql='select * from oms_department where  path="'.$hr_fpath.'"';
            $hr_result=mysql_query($hr_sql);
            while ($hr_res=mysql_fetch_assoc($hr_result)){
                //查二级节点
                $hr_data=mysql_query('select * from oms_hr where new_department="'.$onedep.'" and new_department_two="'.$hr_res['dep1'].'"');
                $temp=<<<li
                <li class='afl' onclick="listu(this)" ondblclick="chat({$row['name']})"path="{$row['path']}" desc="{$row['desc']}" myinner="{$row['name']}" sid="{$row['id']}"
                onmousedown="fun(this)">
                <input class="ltclasscheckbox" type="checkbox">{$hr_res['dep1']}
                 <span style="display:none" class="btns">
                 <button class="select_daily_list" depname="{$row['name']}">查看报表</button>
                 <button class="input_daily_job" depname="{$row['name']}">日常工作</button></span></li>
li;
                while ($row=mysql_fetch_assoc($hr_data)) {
//                    $resData[$hr_res['dep1']][]=$row['new_position'].':'.$row['name'];
                    $name=$row['new_position'].':'.$row['name'];

                    $resData[$temp][]=<<<cd
					<ul><li class='afl' onclick="listu(this)" ondblclick="chat({$row['name']})" path="{$row['path']}" desc="{$row['desc']}" myinner="{$row['name']}" sid="{$row['id']}" onmousedown="fun(this)"><input class="ltclasscheckbox" type="checkbox">{$name} </li></ul>
cd;
                }
            }

            return $resData;
        }
-->
</html>