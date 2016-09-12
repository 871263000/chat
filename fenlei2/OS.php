<?php
require_once(DOCUMENT_ROOT.'/config.inc.php');
include(DOCUMENT_ROOT.'/fenlei2/lt.php');
   	$data=$r->getdata(0);
 ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" /> 
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<title>联系人分组</title>
	<link rel="stylesheet" type="text/css" href="OS.css">
	<!--<script src='/chat/js/jquery.js'></script>-->
	<script src='/chat/fenlei2/js/js.js'></script>
    <style>
    *{
    	font-family: Consolas; padding: 0px;margin:0px;
    }
    .newcolor{ background:#00ACEF; color:#fff;}
    .btns{
        display: none;
    }
    ::-webkit-scrollbar {display:none} 
    .xcb{
    	float:right;color:#fff;font-size:20px;margin-right:10px;
    	line-height: 30px;
    }
    .xcb:hover{
    	color:red;
    }
    .radio{ border-radius: 100%; }
    .myradio{
    	border: 1px solid #cfcfcf;
    	width:50px; height: 50px;
    	cursor: pointer;
	background:url(img/radioimg.jpg) no-repeat ;
	background-size:50px 50px;
    }
    .myradio_select{
    	background:url(img/radio_select.png) no-repeat center;
    	background-size:35px 35px;
    }
     .all_del_select{
     	color:#f00;

     }
    </style>
</head>
<body>
<!--<div class='richangjobs'></div>-->
<div class="jobmenu">
	<li sid="$('.richangjobs').attr('sid')" class='deljob'>删除</li>
</div>
<div id="select_input_big_div" style="display: none;z-index: 10000;position: absolute;width: 100%;height: 1500px;background-color: #F0F0F0;"></div>
<div style="width:100%">
    <p class="select_member_num"  style="color:#abcdef;margin-left:70%;font-size:16px"></p>
</div>
<div style="width:100%"> 
    <div style="width:48%;height:400px;overflow-y:scroll;border:1px solid #abcddf;border-radius:10px;float:left">
        <div id='No1' class='top' style="font-size: 0.5em;">
            <ul>
                <?php echo $r->startJz($data['person']); ?>
            </ul>
        </div>
    </div>
    <div class="selected" style="margin-left:1px;height:400px;float:right;width:48%;overflow-y:scroll;border:1px solid #abcddf;border-radius:10px">
        <div style="font-size: 0.5em;">
            <ul class="unique_ul"></ul>
        </div>
    </div>
</div>

<div style="clear:both;width:100%;margin:0 auto;padding:5px 10px">

    <button class='add_select' style="margin-left:20%;float:left;margin:5px 10px;" title="增加选中">添 &nbsp;&nbsp; 加</button>
    <button class='all_add_select' style="float:left;margin:5px 10px;" title="增加全部">添加全部</button>
    <button class='del_select' style="float:left;margin:5px 10px;" title="删除选中">删除选中</button>
    <button class='all_del_select' style="float:left;margin:5px 10px;" title="删除全部">清 &nbsp;&nbsp; 空</button>

</div>

<div id='shuxing'>
		<h2 style='border-bottom:1px solid #cdcdcd;font-weight:0'></h2>
		<button onclick='addjobs()'>添加</button>
		<button onclick='savejobs()'>保存</button>
			日常工作:
			 Target:<select id='target'>
					<option value='none'>没有Target</option>
					<option value='all'>所有</option>
				 </select>
				 <input type='text' style="width:80px;display:none" class='target'>
		<div id='listheight' style="height:72%;overflow-y:auto">
			<div class='joblistul'></div>
		</div>
	</div>
	<div id="menu">
		<ul id='c' class='mnav' >
			<li><a href="http://www.esaga.com/oms/admin/oms_email_list_input.php">给Ta发邮件</a></li>
     			       <li><a href="http://www.esaga.com/oms/admin/oms_message_list_input.php">给Ta发信息</a></li>
			<div>
				<ul id='list' class='listdiv'>
					<li id='seach' contentEditable='true'></li>
				</ul>
			</div>
			</li>
<!--			<li onclick='set(this)'>添加日常工作</li>-->
		</ul>
	</div>
	<temp></temp>
	<div class="cce" style="display:none"></div>
<script>
var sidList=[],maxPnId=[],nameList=[],pathPid=[],InfoList=[],closeb='<x class="xcb" onclick="xcbme(this)" title="删除">X</x>';
function isTag(name,tn){
    return $('[name="'+name+'"]').is(tn);
}
function istype(tag,type){
    return $(tag).attr('type')==type?true:false;
}
//计数显示定位
$('.select_member_num').css('top',$('.selected').position().top).css('background','#fff')
$('.all_del_select').css('top',$('.selected').position().top).css('background','#fff')

function arr_del(arr,d){
    var tmpArr=[];
    for (var i = 0; i < arr.length; i++) {
        if(arr[i]!==d){
            tmpArr.push(arr[i]);
        }
    }
    return tmpArr;
}
var search=document.getElementById('seach');
for(var i=0;i<$('.afl').length;i++){
    $('.afl').eq(i).mouseover(function(){
        $('li').removeClass('newcolor');$('li').removeClass('mstop');
        $(this).addClass('newcolor');$(this).addClass('mstop');
        $('.richangjobs').css('top',$(this).position().top+$(this).height());
        $('.richangjobs').css('left',$(this).position().left);
        $('.richangjobs').css('display','block');
        $('.richangjobs').attr('id',$(this).attr('sid'));
        $('.richangjobs').html('<img src="fenlei/img/loading.gif" width="30" />');
        readjobs(this);
    });

    $(this).mouseout(function(){
        $(".richangjobs").css("display","none");
    });
}
//全选
$('.all_add_select').click(function(){
    sidList=[];
    nameList=[];
    pathPid=[];
    maxPnId=[];
    $('.selected').children('div').children('ul').html('');
    $('.selected').children('div').children('ul').append($('.top').children('ul').html());
    $('.selected').find('li').each(function(){
        if($(this).children('sid').length){
            maxPnId.push($(this).children('sid').attr('maxpnid'));
            sidList.push($(this).children('sid').html());
            pathPid.push($(this).children('sid').attr('pathid'))
        }
    })
    $('.selected').find('.ltclasscheckbox').remove();
    $('.selected').find('li').each(function(){
        var myi=$(this).find('state').html();
        if(myi!==undefined){
            $(this).find('state').remove();
            $(this).html('<state style="font-size:20px;margin-right:10px;">'+myi+'</state><input class="ltclasscheckbox" type="checkbox"><x onclick="xcbme(this)" title="删除">'+$(this).html()+'</x>');
        }else{
            $(this).html('<input class="ltclasscheckbox" type="checkbox"><x onclick="xcbme(this)" title="删除">'+$(this).html()+'</x>');
        }
    })
    $('.selected').find('.afl').on('mouseover',function(){
        $(this).css('text-decoration','line-through');
    }).on('mouseout',function(){
        $(this).css('text-decoration','none');
    });
    //移除右边计数
    $('.selected').find('member').remove();
    $('.select_member_num').html($('.selected').find('sid').length+'/'+$('#No1').find('sid').length);

    console.log(pathPid);
    sildup('.selected');
})
$('.select_member_num').html($('.selected').find('sid').length+'/'+$('#No1').find('sid').length);

/****************************************/
//单选
$('.add_select').click(function(){
    if($('.selected').find('li').length==$('.top').find('li').length) return alert('超过总人数！');
    $('#No1').find('.ltclasscheckbox').each(function()
    {
        if($(this).attr('checked'))
        {
            if($(this).parent().next('ul').length>0)
            {
                if(!$(this).parents('ul').prev('li').find('.ltclasscheckbox').attr('checked'))
                {
                    $('.selected').find('.unique_ul').append($(this).parent().clone(true)).append($(this).parent().next().clone());
                }
            }else{
                if($(this).parents('ul').prev('li').find('.ltclasscheckbox').attr('checked')==undefined)
                {
                    $('.selected').find('.unique_ul').append($(this).parent().clone());
                }
            }
        }
    })
    //清空数组 将得到的sid放入数组最后
    sidList=[];pathPid=[];maxPnId=[]; $('.selected').find('sid').each(function(){ sidList.push($(this).html());pathPid.push($(this).attr('pathid')); maxPnId.push($(this).attr('maxpnid'));})
    // $('#No1').find('.ltclasscheckbox').attr('checked',false);
    $('#No1').find('.ltclasscheckbox').each(function(){
        if($(this).attr('checked'))
        {
            $(this).parent().append('<a class="select_ed" style="margin-left:10px;color:#0f8aa8;text-align:right">已选</a>')

            //把选中的复选框弄成不可选
            $(this).attr('disabled',true);
        }
        $(this).attr('checked',false);
    });
    //增加删除线
    $('.selected').find('.afl').on('mouseover',function(){
        $(this).css('text-decoration','line-through');
    }).on('mouseout',function(){
        $(this).css('text-decoration','none');
    });
    //移除右边显示人数
    $('.selected').find('member').remove();
    //重置已选人数统计
    $('.select_member_num').html($('.selected').find('sid').length+'/'+$('#No1').find('sid').length);

    //附加删除功能
    $('.selected').find('.ltclasscheckbox').remove();

    $('.selected').find('li').each(function(){
        var myi=$(this).find('state').html();
        if(myi!==undefined)
        {
            $(this).find('state').remove();
            $(this).html('<state style="font-size:20px;margin-right:10px;">'+myi+'</state><input class="ltclasscheckbox" type="checkbox"><x onclick="xcbme(this)" title="删除">'+$(this).html()+'</x>');
        }else{
            $(this).html('<input class="ltclasscheckbox" type="checkbox"><x onclick="xcbme(this)" title="删除">'+$(this).html()+'</x>');
        }
    })
    //点击确定按钮时候移除 已选 ，和清空selected的内容
    $('#b_is').click(function(){
        $('.select_ed').remove();
        $('.selected').find('div').children('ul').html('');
    })
})

/****************************************/

$('.selected').find('.afl').click(function(){
    xcbme($(this).find('.ltclasscheckbox'));
})
//全删
$('.all_del_select').click(function(){
    sidList=[];pathPid=[];maxPnId=[]; $('.selected').children('div').children('ul').html('');
    //重置统计
    $('.select_member_num').html($('.selected').find('sid').length+'/'+$('#No1').find('sid').length);
    $('.select_ed').remove();
    $('#No1').find('.ltclasscheckbox').attr('disabled',false);
})
//单删
function xcbme(item){
    if($(item).find('sid').html() == undefined)
    {
        if($(item).parent().next().find('li').length > 0)
        {
            $(item).parent().next('ul').find('sid').each(function()
            {
                sidList=arr_del(sidList,$(this).html());pathPid=arr_del(pathPid,$(this).attr('pathid'));
                $(this).parents('li').remove();
            })
            if($(item).find('sid').html()==undefined)
            {
                $('#No1').find('[myinner="'+$(item).parent('li').attr('myinner')+'"]').find('.ltclasscheckbox').removeAttr('disabled');
                $('#No1').find('[myinner="'+$(item).parent('li').attr('myinner')+'"]').find('.select_ed').remove();

                $(item).parent('li').next().remove();
                $(item).parent('li').remove();
            }
        }else{
            $('#No1').find('[myinner="'+$(item).parent('li').attr('myinner')+'"]').find('.ltclasscheckbox').removeAttr('disabled');
            $('#No1').find('[myinner="'+$(item).parent('li').attr('myinner')+'"]').find('.select_ed').remove();
            $(item).parent('li').remove();
        }
    }else{
        sidList=arr_del(sidList,$(item).find('sid').html());pathPid=arr_del(pathPid,$(item).find('sid').attr('pathid'));
        $(item).parent('li').remove();
    }
    $('#No1').find('sid').parent().find('.select_ed').css('display','none');
    $('#No1').find('sid').parent().find('.ltclasscheckbox').attr('disabled',false);
    //Remove member select and cancel checked.
    $('#No1').find('sid').each(function(){
        for (var i = 0; i < sidList.length; i++) {
            if($(this).html()==sidList[i])
            {
                $(this).parent().find('.select_ed').css('display','inline-block');
                $(this).parent().find('.ltclasscheckbox').attr('disabled',true);
            }
        }
    })
    console.log(pathPid);
    //重置统计
    $('.select_member_num').html($('.selected').find('sid').length+'/'+$('#No1').find('sid').length);
}
//选删
$('.del_select').click(function(){
    var checked_num=0;
    $('.selected').find('.ltclasscheckbox').each(function()
    {
        if($(this).attr('checked'))
        {
            ++checked_num
        }
    })
    if(checked_num==0) return alert('请选中一个，再重试！');
    $('.selected').find('.ltclasscheckbox').each(function(){
        if($(this).attr('checked')){
            sidList=arr_del(sidList,$(this).parent().find('sid').html());pathPid=arr_del(pathPid,$(this).parent().find('sid').attr('pathid'));
            $(this).parent('li').next('ul').remove();
            $(this).parent('li').remove();
        }
    })
    console.log(pathPid);
    //重置统计
    $('.select_member_num').html($('.selected').find('sid').length+'/'+$('#No1').find('sid').length);
})

$('li').on('mouseout',function(){
    $('.newcolor').removeClass('newcolor');
})
$('.richangjobs').on('mouseover',function(){
    $('.mstop').addClass('newcolor');
})
$('.richangjobs').on('mouseout',function(){
    $('.newcolor').removeClass('newcolor');
})


$(".richangjobs").mouseover(function (){
    $(this).css("display","block");
});

$(".richangjobs").mouseout(function (){
    $(this).css("display","none");
});

search.onclick=function(en){
    notecho();
    if(this.innerHTML=='O、Search'){
        this.innerHTML='';
    }
}
search.onkeypress=function(event){
    notecho();
    if(event && event.keyCode==13){
        event.returnValue=false;
    }
}
search.onmouseover=function(){
    this.contentEditable='true';
    this.focus();
    this.innerHTML='';
}
search.onmouseout=function(){
    this.contentEditable='false';
    this.blur();
}
$('#target').on('change',function(){
    switch($(this).val()){
        case 'none':$('.target').fadeOut(200);break;
        case 'all':$('.target').hide().fadeIn(300);break;
    }
})
function savejobs(){
    var jobs=[],tmp=[],l=$('.joblistul').children('div').children('li').children('input'),
        t=$('.target').val()?$('.target').val():'';
    if(l.length==0) {
        alert('请先添加工作项！');return false;
    }
    for(var i=0;i<l.length;i++){
        if(!l[i].value){
            alert('请填写第'+(i+1)+'个文本框！');return false;
        }
        if(l[i].className=='jobname'){
            tmp=[];
            tmp.push('<a href="'+l[i+1].value+'" target="'+t+'">');
            tmp.push(l[i].value+'</a>');
            jobs.push(tmp.toString());
        }
    }
    var str=myreplace(jobs,',','');
    ajax(str,8,$('#shuxing').attr('sid'),'setjobs');
}
function ms(obj){
    $('.jobmenu').css('top',$(obj).position().top);
    $('.jobmenu').show();
}
$(document).on('keyup','#seach',function(){
    getClassName('li','roptions');
    if(this.innerHTML){
        ajax('',6,'','','','search_node',this.innerHTML);
    }else{
        return false;
    }
})
function fun(obj){
    var menu=document.getElementById('menu');
    var seach=document.getElementById('seach');
    var lis= document.getElementById("c").getElementsByTagName("li");
    var sd=obj.getAttribute('sid');
    var sp=obj.getAttribute('path');
    var desc=obj.getAttribute('desc');
    var inner=obj.getAttribute('myinner');
    obj.oncontextmenu=function(en){
        var e = en || window.event;
        menu.style.left = e.clientX+'px';
        menu.style.top = e.clientY+'px';
        $(menu).css('display','block')?$(menu).hide().show(1):$(menu).show(1);
    }
    for (var i = 0;i<lis.length; i++) {
        lis.item(i).setAttribute("path",sp);
        lis.item(i).setAttribute("sid",sd);
        lis.item(i).setAttribute("desc",desc);
        lis.item(i).setAttribute("myinner",inner);
    }
}

function fun11(){
    $('.afl').siblings('ul').css('display','none');
    $('sid').css('display','none');
}
setTimeout(fun11(),'1000');
$(".input_daily_job").on("click",function(event){
    if($(this).parent().parent().attr("myinner")==$(this).parent().parent().attr('myparent')){
        $(this).parent().parent().attr('myinner','');
    }
    $.ajax({
        url:"./fenlei/select_input_daily_job.php",
        type:"post",
        data:"depname="+$(this).parent().parent().attr('myparent')+'&dep1='+$(this).parent().parent().attr('myinner'),
        async:false,
        success:function(data){
            $(window).scrollTop(0)
            eval("var d=("+data+")");
            $('#select_input_big_div').css('display','block').html('');
            for(var i in d ){
                var temp=$('#select_input_big_div').html();
                $('#select_input_big_div').html(temp+'<a class="small_div_hide" href="'+d[i].program_file_link_the_name+'">'+d[i].name_of_routine_input_work+'</a>');
            }
        }
    });
    $('.small_div_hide').on('mousedown',function(){
        $(this).css('background','none');
    });
    $('.small_div_hide').on('click',function(){
        $('#select_input_big_div').css('display','none');
    });
    event.stopPropagation();
})

$(".select_daily_list").on("click",function(event){
    if($(this).parent().parent().attr("myinner")==$(this).parent().parent().attr('myparent')){
        $(this).parent().parent().attr('myinner','');
    }
    $.ajax({
        url:"./fenlei/select_input_daily_job.php",
        type:"post",
        data:"depname1="+$(this).parent().parent().attr('myparent')+'&dep1='+$(this).parent().parent().attr('myinner'),
        async:false,
        success:function(data){
            eval("var d=("+data+")");
            $('#select_input_big_div').css('display','block').html('');
            $(window).scrollTop(0)
            for(var i in d ){
                var temp=$('#select_input_big_div').html();
                $('#select_input_big_div').html(temp+'<a class="small_div_hide" href="'+d[i].program_file_link_the_name+'">'+d[i].name_of_routine_select_work+'</a>');
            }
        }
    });
    $('.small_div_hide').on('mousedown',function(){
        $(this).css('background','none');
    });
    $('.small_div_hide').on('click',function(){
        $('#select_input_big_div').css('display','none');
    });
    event.stopPropagation();
});

$('li').on('mouseover',function(){
    $(this).find('span').css('display','inline-block');
});
$('li').on('mouseout',function(){
    $(this).find('span').css('display','none');
});

$('#No1').find('li').each(function(){
    if($(this).next('ul').length>0)
    {

        if($(this).next('ul').is(':hidden') && $(this).next('ul').find('sid').length>0)
        {
            $(this).prepend('<state style="font-size:20px;margin-right:10px;">+</state>');
        }
    }
})
sildup('#No1');
function sildup(crl){
    $(crl).find('li').click(function(){
        var state=$(this).find('state');
        state.html()=='+'?state.html('-'):state.html('+');
    })
}

$(document).change(function(){
    $('li').children('.ltclasscheckbox').click(function(event){
        if($(this).attr('checked')){
            $(this).parent().next().children().find('.ltclasscheckbox').attr('checked',true)
        }else{
            $(this).parent().next().children().find('.ltclasscheckbox').attr('checked',false)
        }
        event.stopPropagation();
    })
})


$('#No1').find('li').each(function(){
    if($(this).find('sid').html()==undefined){
        $(this).append('<member style="float:right;margin-right:20%;color:#abdddf"><total>'+$(this).next('ul').find('sid').length+'</total>人</member>')
    }
})
</script>
</body>
</html>