<!DOCTYPE html >
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link rel="stylesheet" type="text/css" href="http://www.esaga.com/oms/fenlei/OS.css">
<script src='http://www.esaga.com/oms/fenlei/js/jquery.js'></script>
<style>
.richangjobs{ width:300px; height:150px;}
.lu{ width:300px; border:1px solid #F00;}

.lifocus
{
	background: #00ACEF;
	color:#fff;
}
</style>
</head>

<body>


<div class="richangjobs"></div>

<div class="top">
    <ul class="lu">
        <li class="afl">股东会</li>
        <li class="afl">监事会</li>
        <li class="afl">董事会</li>
   </ul>
</div>
	<script>
		$(".afl").each(function (i){
			$(this).mouseover(function(){
				$(".richangjobs").css("top",$(this).position().top);
				$(".richangjobs").css("left","180px");
				$(".richangjobs").css("display","block");
				$(this).addClass("lifocus");
			});
			
			$(this).mouseout(function(){
				alert(i);
				$(".richangjobs").css("display","none");
				$(this).removeClass("lifocus");
			});
		});		

			
		$(".richangjobs").mouseover(function (){
			$(".richangjobs").css("display","block");
		});
		
		$(".richangjobs").mouseout(function (){
			$(".richangjobs").css("display","none");	
		});

	</script>

</body>
</html>