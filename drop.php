<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3***/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3***/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<style type="text/css">
#drop_area{ width:300px; height:200px; background-color:#CCC; border:2px dashed #000000; text-align:center;}
</style>
<script type="text/javascript">
function over(e){

e.preventDefault();
}
function drop(e) {
e.preventDefault();
e.stopPropagation();
//获取图片名称
/*var filename = e.dataTransfer.files[0].name;*/
var file = e.dataTransfer.files[0];
//创建form表单，用来提交数据
var form1 = new FormData();
form1.append("aa", file);
console.log(file);
var img = document.createElement("img");
// img.src = xhr.responseText;
// img.width = 100;
// img.height = 100;
e.target.appendChild(img);
	
}

</script>
</head>
<body>
<div id="drop_area" ondragover="over(event)" ondrop="drop(event)">将图片拖拽到此区域</div> 
</body>
</html>