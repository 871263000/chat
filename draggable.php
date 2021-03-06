<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml" >
 <head>
     <title>使用鼠标拖动层代码</title>
     <style type="text/css">
         #Main
         {
             width:400px;
             height:400px;
             position:absolute;
             top:10px;
             left:0;
             border:1px solid #CCC;
             border-radius:5px;
             background-color:Green;
         }
         h3
         {
             margin:0;
             width:400px;
             height:40px;
             background-color:Gray;
             cursor:move;
             line-height:40px;
             border-radius:5px 5px 0 0;
         }
     </style>
     <script type="text/javascript" src="js/jquery.min.js"></script>
     <script type="text/javascript">
         //注册一个Jquery的鼠标拖动函数,参数为要拖动的对象
         $.fn.extend({ SliderObject: function (objMoved) {
             var mouseDownPosiX;
             var mouseDownPosiY;
             var InitPositionX;
             var InitPositionY;
             var obj = $(objMoved) == undefined ? $(this) : $(objMoved);
             $(this).mousedown(function (e) {
                 //当鼠标按下时捕获鼠标位置以及对象的当前位置
                 mouseDownPosiX = e.pageX;
                 mouseDownPosiY = e.pageY;

                 InitPositionX = obj.css("left").replace("px", "");
                 InitPositionY = obj.css("top").replace("px", "");
                 obj.mousemove(function (e) {//这个地方改成$(document).mousemove貌似可以避免因鼠标移动太快而失去焦点的问题
                     //貌似只有IE支持这个判断，Chrome和Firefox还没想到好的办法
                     //if ($(this).is(":focus")) {
                         var tempX = parseInt(e.pageX) - parseInt(mouseDownPosiX) + parseInt(InitPositionX);
                         var tempY = parseInt(e.pageY) - parseInt(mouseDownPosiY) + parseInt(InitPositionY);
                         obj.css("left", tempX + "px").css("top", tempY + "px");
                     //};
                     //当鼠标弹起或者离开元素时，将鼠标弹起置为false，不移动对象
                 }).mouseup(function () {
                     obj.unbind("mousemove");
                 }).mouseleave(function () {
                     obj.unbind("mousemove");
                 });
             })
         }
         });
     $(document).ready(function () {
         $("h3").SliderObject($("#Main"));
     })
     </script>
 </head>
 <body>
     <div id="Main">
         <h3>鼠标放在这里拖动我</h3>
         <div id="Container">可以使用鼠标拖动的层</div>
     </div>
 </body>
 </html>