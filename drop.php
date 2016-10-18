<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3***/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3***/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<style type="text/css">
#drop_area{ width:300px; height:200px; background-color:#CCC; border:2px dashed #000000; text-align:center;}
</style>
<script src="js/jquery.min.js"></script>
<script type="text/javascript">
function over(e){

e.preventDefault();
}
function drop(e) {
	console.log(e.dataTransfer);
e.preventDefault();
e.stopPropagation();
//获取图片名称
// /*var filename = e.dataTransfer.files[0].name;*/
// var file = e.dataTransfer.files[0];
// //创建form表单，用来提交数据
// var form1 = new FormData();
// form1.append("aa", file);
// console.log(e);
// var reader = new FileReader();  
// //将文件以Data URL形式读入页面  
// reader.readAsDataURL(file);  
// reader.onload=function(e){
// 	var img = new Image();
// 	img.src = this.result;
// 	document.body.appendChild(img);
// 		//显示文件  
// }
	
}

</script>
</head>
<body>
<textarea name="" ondragover="over(event)" ondrop="drop(event)" id="" cols="30" rows="10"></textarea>
<span style="color: #000;">asdfasdfsadf</span>

<div id="drop_area" ondragover="over(event)" ondrop="drop(event)">将图片拖拽到此区域</div> 
</body>
</html>