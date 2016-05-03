<?php
require_once("../config.inc.php");
require_once'../fenlei/lt.php';
//加载时 查询所有部门
$o=$r->getNode('getone');
$one=json_encode($o);
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>人员录入</title>
	<script language="javascript" type="text/javascript" src="../js/My97DatePicker/WdatePicker.js"></script>
	<script src='../js/jquery-1.8.2.min.js'></script>
		<script>
            $(function(){ });
	    </script>
</head>
<body>
<div class="workpage">
	<center>
	<h1>人员录入</h1>
	<DepCache style='display:none'></DepCache>
	<Dep1Cache style='display:none'></Dep1Cache>
	<form action="../oms_hr_action.php?a=add" method="POST" enctype="multipart/form-data">
	<table>
		<tr><td>姓名:</td><td>
			<input name='name' type='text' onkeydown='this.onkeyup();' onkeyup='this.size=(this.value.length>20?this.value.length:20);' size='20'></td>
		</tr>
		<tr><td>性别:</td><td>
			<input name='sex' type='radio'value="男">男
			<input name='sex' type='radio'value="女">女</td>
		</tr>
        
        <tr>
        	<td>出生日期:</td>
            <td><input name='birthday' type='text'class='Wdate' onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" value="" /></td>
		</tr>
        
		<tr><td>民族:</td><td>
			<input name='nation' type='text' onkeydown='this.onkeyup();' onkeyup='this.size=(this.value.length>20?this.value.length:20);' size='20'></td>
		</tr>
        
        <tr>
        	<td>婚否:</td><td>
			<input name='marry' type='radio' value="已婚">已婚
			<input name='marry' type='radio' checked="checked" value="未婚">未婚</td>
		</tr>
     
		<tr><td>身份证号:</td><td>
			<input name='card' type='text' onkeydown='this.onkeyup();' onkeyup='this.size=(this.value.length>20?this.value.length:20);' size='20'></td>
		</tr>
        
        <tr>
        	<td>照片:</td>
            <td><input type="file" name="card_image" value="" /></td>
		</tr>
        
        <tr>
        	<td>合同类别:</td><td>
			<input name='contract_type' type='radio' checked="checked" value="劳动合同">劳动合同
			<input name='contract_type' type='radio' value="实习协议">实习协议
            <input name='contract_type' type='radio' value="兼职协议">兼职协议
            <input name='contract_type' type='radio' value="聘用协议">聘用协议</td>
		</tr>
        
        <tr>
        	<td>合同期限(固定期限):</td>
            <td>
			初签：<input name='contract_period_one_begin' type='text'class='Wdate' onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="" />起,至<input name='contract_period_one_end' type='text'class='Wdate' onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="" />止；<br>续签：<input name='contract_period_two_begin' type='text'class='Wdate' onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="" />起,至<input name='contract_period_two_end' type='text'class='Wdate' onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="" />止；</td>
		</tr>
        
        <tr>
        	<td>合同期限(无固定期限):</td>
            <td><input name='contract_period_fixed' type='text'class='Wdate' onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="" />起；</td>
		</tr>
        
        <tr>
        	<td>劳动合同签约状态:</td><td>
			<input name='contract_state' type='radio' checked="checked" value="已签">已签
			<input name='contract_state' type='radio' value="未签">未签</td>
		</tr>
        
        <tr>
        	<td>下次劳动合同签约时间:</td>
            <td><input name='contract_time' type='text'class='Wdate' onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="" /></td>
		</tr>
        
		<tr><td>户籍地址:</td><td>
			<input name='home_address' type='text' onkeydown='this.onkeyup();' onkeyup='this.size=(this.value.length>20?this.value.length:20);' size='20'></td>
		</tr>
		<tr><td>现居住地:</td><td>
			<input name='live_address' type='text' onkeydown='this.onkeyup();' onkeyup='this.size=(this.value.length>20?this.value.length:20);' size='20'></td>
		</tr>
		<tr><td>籍贯:</td><td>
			<input name='native_place' type='text' onkeydown='this.onkeyup();' onkeyup='this.size=(this.value.length>20?this.value.length:20);' size='20'></td>
		</tr>
		<tr><td>户口性质 ：</td>
	 		<td><input type="radio" name="residence"  value='1'>上海城镇
	 		<input type="radio" name="residence"  value='2'>外地城镇
	 		<input type="radio" name="residence"  value='3'>上海非城镇
	 		<input type="radio" name="residence"  value='4'>外地非城镇</td>
		</tr>
		<tr><td>家庭电话:</td><td>
			<input name='home_tel' type='text' onkeydown='this.onkeyup();' onkeyup='this.size=(this.value.length>20?this.value.length:20);' size='20'></td>
		</tr>
		<tr><td>学历:</td><td>
			<input name='education' type='text' onkeydown='this.onkeyup();' onkeyup='this.size=(this.value.length>20?this.value.length:20);' size='20'></td>
		</tr>
		<tr><td>政治面貌:</td><td>
			<input name='party_status' type='text' onkeydown='this.onkeyup();' onkeyup='this.size=(this.value.length>20?this.value.length:20);' size='20'></td>
		</tr>
		<tr><td>职称:</td><td>
			<input name='technical_title' type='text' onkeydown='this.onkeyup();' onkeyup='this.size=(this.value.length>20?this.value.length:20);' size='20'></td>
		</tr>
		<tr><td>手机:</td><td>
			<input name='mobile_phone' type='text' onkeydown='this.onkeyup();' onkeyup='this.size=(this.value.length>20?this.value.length:20);' size='20'></td>
		</tr>
		<tr><td>员工编号:</td><td>
			<input name='number' type='text' onkeydown='this.onkeyup();' onkeyup='this.size=(this.value.length>20?this.value.length:20);' size='20'></td>
		</tr>
		<tr><td>入职时间:</td><td>
		<input name='entry_time' class='Wdate' onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"style='width:150px'></td>
		</tr>
		<tr><td>人员类别 ：</td>
	 		<td><input type="radio" name="personnel_category"  value='1'>正式
	 		<input type="radio" name="personnel_category"  value='2'>实习
	 		<input type="radio" name="personnel_category"  value='3'>试用
	 		<input type="radio" name="personnel_category"  value='4'>退聘</td>
		</tr>
		<tr><td>离职时间:</td><td>
		<input name='leaving_time' class='Wdate' onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"style='width:150px'></td>
		</tr>
		<tr><td>工作部门1:</td><td>
			<select name="job_department1"  onchange='getDep1($(this))' class="depOne" id="mu1">
        	<option>请选择</option>
        </select></td>
		</tr>
		<tr><td>工作部门细分1:</td><td>
			<select name="job_department_two1"  class="depTwo" id="xf1">
        	    <option>请选择</option>
            </select></td>
		</tr>
		<tr><td>工作职位1:</td><td>
                <select name="job_position1"  onchange="G2B()">
                    <option>请选择</option>
                </select></td>
			</td>
		</tr>
		<tr><td>工作职位1开始时间:</td><td>
		<input name='job_start_time1' class='Wdate' onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"style='width:150px'></td>
		</tr>
		<tr><td>工作职位1结束时间:</td><td>
		<input name='job_end_time1' class='Wdate' onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"style='width:150px'></td>
		</tr>
		<tr><td>工作部门2:</td><td>
			<select name="job_department2" onchange='getDep1($(this))' class="depOne" id="mu2">
        	<option>请选择</option>
        </select></td>
		</tr>
		<tr><td>工作部门细分2:</td><td>
			<select name="job_department_two2"  class="depTwo" id="xf2">
        	<option>请选择</option>
        </select>
			</td>
		</tr>
		<tr><td>工作职位2:</td><td>
                <select name="job_position2"  class="job_position1" id="job_position1">
                    <option>请选择</option>
                </select>
		</td>
		</tr>
		<tr><td>工作职位2开始时间:</td><td>
		<input name='job_start_time2' class='Wdate' onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"style='width:150px'></td>
		</tr>
		<tr><td>工作职位2结束时间:</td><td>
		<input name='job_end_time2' class='Wdate' onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"style='width:150px'></td>
		</tr>
		<tr><td>工作部门3:</td><td>
			<select name="job_department3" onchange='getDep1($(this))' class="depOne" id="mu3">
        	<option>请选择</option>
        </select></td>
		</tr>
		<tr><td>工作部门细分3:</td><td>
			<select name="job_department_two3"  class="depTwo" id="xf3">
        	<option>请选择</option>
        </select></td>
		</tr>
		<tr><td>工作职位3:</td><td>
                <select name="job_position3"  class="job_position1" id="job_position1">
                    <option>请选择</option>
                </select>
            </td>
		</tr>
		<tr><td>工作职位3开始时间:</td><td>
		<input name='job_start_time3' class='Wdate' onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"style='width:150px'></td>
		</tr>
		<tr><td>工作职位3结束时间:</td><td>
		<input name='job_end_time3' class='Wdate' onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"style='width:150px'></td>
		</tr>
		<tr><td>工作部门4:</td><td>
			<select name="job_department4" onchange='getDep1($(this))' class="depOne" id="mu4">
        	<option>请选择</option>
        </select></td>
		</tr>
		<tr><td>工作部门细分4:</td><td>
			<select name="job_department_two4"  class="depTwo" id="xf4">
        	<option>请选择</option>
        </select></td>
		</tr>
		<tr><td>工作职位4:</td><td>
                <select name="job_position4"  class="job_position1" id="job_position1">
                    <option>请选择</option>
                </select>
            </td>
        </tr>
		<tr><td>工作职位4开始时间:</td><td>
		<input name='job_start_time4' class='Wdate' onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"style='width:150px'></td>
		</tr>
		<tr><td>工作职位4结束时间:</td><td>
		<input name='job_end_time4' class='Wdate' onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"style='width:150px'></td>
		</tr>
		<tr><td>工作部门5:</td><td>
			<select name="job_department5" onchange='getDep1($(this))' class="depOne" id="mu5">
        	<option>请选择</option>
        </select></td>
		</tr>
		<tr><td>工作部门细分5:</td><td>
			<select name="job_department_two5"  class="depTwo" id="xf5">
        	<option>请选择</option>
        </select></td>
		</tr>
		<tr><td>工作职位5:</td><td>
                <select name="job_position5"  class="job_position1" id="job_position1">
                    <option>请选择</option>
                </select>
            </td>
        </tr>
		<tr><td>工作职位5开始时间:</td><td>
		<input name='job_start_time5' class='Wdate' onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"style='width:150px'></td>
		</tr>
		<tr><td>工作职位5结束时间:</td><td>
		<input name='job_end_time5' class='Wdate' onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"style='width:150px'></td>
		</tr>
		<tr><td>社保情况 ：</td>
	 		<td><input type="radio" name="social_insurance"  value='1'>上海城保五险
	 		<input type="radio" name="social_insurance"  value='2'>外地城保五险
	 		<input type="radio" name="social_insurance"  value='3'>外地城保三险
	 		<input type="radio" name="social_insurance"  value='4'>其他
	 		</td>
		</tr>
		<tr><td>银行卡号:</td><td>
			<input name='Card_No' type='text' onkeydown='this.onkeyup();' onkeyup='this.size=(this.value.length>20?this.value.length:20);' size='20'></td>
		</tr>
		<td>开户行 ：</td>
	 		<td><input type="radio" name="bank_of_deposit"  value='1'>建设银行
	 		<input type="radio" name="bank_of_deposit"  value='2'>南京银行
	 		<input type="radio" name="bank_of_deposit"  value='3'>浦发银行
	 		<input type="radio" name="bank_of_deposit"  value='4'>上海农商银行
	 		<input type="radio" name="bank_of_deposit"  value='5'>其他
	 		</td>
	 	<tr><td>姓名拼音:</td><td>
			<input name='name_pinyin' type='text' onkeydown='this.onkeyup();' onkeyup='this.size=(this.value.length>20?this.value.length:20);' size='20'></td>
		</tr>
		<tr><td>邮箱:</td><td>
			<input name='email' type='text' onkeydown='this.onkeyup();' onkeyup='this.size=(this.value.length>20?this.value.length:20);' size='20'></td>
		</tr>
	 	
	 		<input id="xz" name='new_department' type='hidden'value="">
	 		<input id="mux" name='new_department_two' type='hidden'value="">
	 		<input id="zw" name='new_position' type='hidden'value="">
	 	<tr>
	 		<td>
			<input type="submit"   value="提交">
 			</td>
 		</tr>
	</table>
	
	</form>
	</center>
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
                url:'../fenlei/lt.php',
                type:'post',
                data:'Dep='+$(item).val(),
                success:function(data){
                	 $('DepCache').html('')
                	 $('DepCache').html($(item).val())
                	var mudi=$(item).parents('tr').next().find('.depTwo');
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
        	 $.ajax({
	            url:'../ajax_getdep.php',
	            type: 'post',
	            data:'getRen=yes&dep='+$('DepCache').html()+'&dep1='+$('Dep1Cache').html()+'&pos='+$(this).val(),
	            success:function(data){
	            	 eval('var d=('+data+')');
	            	 console.log(d)
	            }
	        })
        })
         $('.depTwo').change(function(){
        	$('Dep1Cache').html('')
        	$('Dep1Cache').html($(this).val())
        })
        //查询职务的鬼东西
         $.ajax({
            url:'../ajax_getdep.php',
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
</body>
</html>