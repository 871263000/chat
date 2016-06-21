<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>手机移动端网站触屏可拖动悬浮球</title>
  <style>#touch {
	width: 60px;
	height: 60px;
	position: absolute;
	left: 300px;
	top: 79px;
	margin-left: -30px;
	margin-top: -30px;
	z-index: 999999;
}
#simple-menu {
	width: 60px;
	height: 60px;
	cursor: move;
}
  </style>



</head>

<body>
<!-- 代码开始 -->
<div id="touch" class=" visible-xs-block" style="width: 60px; height: 60px; position: absolute;">
	<img id="simple-menu" src="images/anniu.png" style="width: 60px; height: 60px;" />
</div>
<script>
  var div = document.getElementById('touch');
  div.addEventListener('touchmove', function(event) {
  	console.log(3);
  event.preventDefault();//阻止其他事件
  // 如果这个元素的位置内只有一个手指的话
  if (event.targetTouches.length == 1) {
   var touch = event.targetTouches[0];  // 把元素放在手指所在的位置
   div.style.left = touch.pageX + 'px';
  div.style.top = touch.pageY + 'px';
  div.style.background = "";
   }
  } ,false);
 </script>

 
</body>
</html>