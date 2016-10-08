<script>
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
        InitPositionX = obj.offset().left;
        InitPositionY = obj.offset().top;
        console.log(parseInt(mouseDownPosiY));
        console.log(parseInt(InitPositionY));
        console.log(parseInt(obj.css("marginTop")));
        obj.mousemove(function (e) {//这个地方改成$(document).mousemove貌似可以避免因鼠标移动太快而失去焦点的问题
             //貌似只有IE支持这个判断，Chrome和Firefox还没想到好的办法
             //if ($(this).is(":focus")) {
                console.log(parseInt(mouseDownPosiY));
                console.log(parseInt(InitPositionY));
                console.log(parseInt(obj.css("marginTop")));
                 var tempX = parseInt(e.pageX) - parseInt(mouseDownPosiX) + parseInt(InitPositionX)-obj.css("marginLeft").replace('px', '');
                 var tempY = parseInt(e.pageY) - parseInt(mouseDownPosiY) + parseInt(InitPositionY)-obj.css("marginTop").replace('px', '');
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
	
</script>