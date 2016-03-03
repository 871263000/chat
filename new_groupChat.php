<?php 
	require_once('config.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0" />
<title>事件</title>
<script src='js/jquery.min.js'></script>
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
<style type="text/css">
*{font-family: '微软雅黑' !important;}
html,body {
	height: 100%;
	/*background: #6699FF;*/
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#6699FF', endColorstr='#6699FF'); /*  IE */
	background-image:linear-gradient(bottom, #6699FF 0%, #6699FF 100%);
	background-image:-o-linear-gradient(bottom, #6699FF 0%, #6699FF 100%);
	background-image:-moz-linear-gradient(bottom, #6699FF 0%, #6699FF 100%);
	background-image:-webkit-linear-gradient(bottom, #6699FF 0%, #6699FF 100%);
	background-image:-ms-linear-gradient(bottom, #6699FF 0%, #6699FF 100%);
	
	/*margin: 0 auto;*/
	/*position: relative;*/
	width: 100%;
}
#name_box{ position:absolute;background:#fff;display:none;width: 100%;margin-top:200px;z-index: 111;}
h2{margin:0;}
.box_con {
	/*filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#6699FF', endColorstr='#6699FF');  /* IE */*/
/*	background-image:linear-gradient(bottom, #6699FF 0%, #6699FF 100%);
	background-image:-o-linear-gradient(bottom, #6699FF 0%, #6699FF 100%);
	background-image:-moz-linear-gradient(bottom, #6699FF 0%, #6699FF 100%);
	background-image:-webkit-linear-gradient(bottom, #6699FF 0%, #6699FF 100%);
	background-image:-ms-linear-gradient(bottom, #6699FF 0%, #6699FF 100%);*/
	
/*	margin: 0 auto;
	position: relative;*/
	width: 100%;
	height: 100%;
}
.login-box {
	width: 100%;
	max-width:500px;
	position: absolute;
	top: 40%;

	margin-top: -100px;
	/*设置负值，为要定位子盒子的一半高度*/
	
}
@media screen and (min-width:500px){
	.login-box {
		left: 50%;
		/*设置负值，为要定位子盒子的一半宽度*/
		margin-left: -250px;
	}
}	

.form {
	width: 100%;
	max-width:500px;
	margin: 25px auto 0px auto;
	padding-top: 25px;
}	
.login-content {
	width: 100%;
	max-width:500px;
	background-color: rgba(255, 250, 2550, .6);
	float: left;
}		
	
	
.input-group {
	margin: 0px 0px 20px 0px !important;
}
.event{
	padding: 0 15px;
	float: left;
	width: 100%;
}
.event input{
	margin: 10px 0;
	height: 40px;
	line-height: 40px;
	font-size: 16px;
	font-family: '微软雅黑';
	width: 100%;
}
#content{
	text-align: top;
	padding: 0;
	/*line-height: 200px;*/
}
.form-control,
.input-group {
	width: 100%;
	height: 50px;
}

.form-group {
	margin-bottom: 0px !important;
}
.login-title {
	padding: 10px 10px;
	background-color: rgba(0, 0, 0, .6);
}
.login-title h1 {
	margin-top: 10px !important;
}
.login-title small {
	color: #fff;
}
.link p {
	line-height: 20px;
	margin-top: 30px;
}
.btn-sm {
	padding: 8px 24px !important;
	font-size: 16px !important;
}
/*滚动条*/
.group_pep::-webkit-scrollbar{width:100px ;display: block;}
.group_pep::-webkit-scrollbar{width:10px;height:10px}
.group_pep::-webkit-scrollbar-track{background:#fff}
.group_pep::-webkit-scrollbar-thumb{background:#ccc;border-radius:10px}
.group_pep::-webkit-scrollbar-corner{background:#82afff}
.group_pep::-webkit-scrollbar-resizer{background:#ff0bee}
.group_pep{text-align: center;max-height: 300px; overflow: auto;}
.group_pep.ul li{padding: 5px 0; border-bottom: 1px solid #dedede;}
</style>

</head>

<body>
<div id="name_box">
        <?php
        	include('fenlei2/OS.php');
        ?>
        <div style="clear:both;width:100%;margin:50px 10px 0px 30%"><button style="width:60px;height:50px;font-size:18px" id="b_no" >取&nbsp;消</button><button style="margin-left:50px;width:60px;height:50px;font-size:18px" id="b_is">确&nbsp;定</button></div>
        <script type="text/javascript">
          // console.log(sidList)
          //button确定
          // var = 'to_uid';
          $('#b_is').click(function (){
            var jsonText = JSON.stringify(sidList);
            $('.group_pep').html('')
            to_uid = sidList.join(',');
            $('#group_participants').val(to_uid);
            $.ajax({
              url:'getndp.php',
              data:'jsonText='+jsonText,
              type:'post',
              success:function(data){
                var d=eval('('+data+')')
                for (var i = 0; i < d.length; i++) {
                  $('.group_pep').append("<li class='list-group-item'>"+d[i]['name']+"</li>");    
                }
              }
            })
            $('#name_box').hide();
            $('.selected').find('div').html('<ul></ul>');
            $('#No1').find('.ltclasscheckbox').attr('checked',false);
            $('.select_member_num').html($('.selected').find('sid').length+'/'+$('#No1').find('sid').length);
            sidList=[];
          })
          //button取消
          $('#b_no').click(function(){
            $('#name_box').hide();
          })
        </script>
</div>

<div class="box">
		<div class="login-box">
			<div class="login-title text-center">
				<h1><small>新建群聊</small></h1>
			</div>
			<div class="box_con">
				<div class="login-content ">
				<div class="form">
				<form action="oms_group_chat_add_action.php" method="post">
					<div class="form-group">
						<div class="col-xs-12  ">
							<div class="input-group">
								<input type="text" id="group_name" name="group_name" class="form-control" placeholder="新群聊名称">
							</div>
						</div>
					</div>
					<div>
						<h2 style="margin-left: 16px;" id="s_man" class="btn btn-sm btn-info">选择参加人</h2>
						<!--<small>(不需要选择自己)</small>-->
						<input type="hidden" id="group_participants" name="group_participants" />
					</div>
					<div>
						<ul class="group_pep list-group">
						</ul>
					</div>
					<div class="form-group form-actions">
						<div style="text-align: center;" class="">
							<button type="submit" class="btn btn-sm btn-info"> 提交</button>
						</div>
					</div>
				</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
//选择人
$('#s_man').click(function(){
  $('#name_box').show();
})
</script>
</body>
<?php require_once('header.php')?>
</html>