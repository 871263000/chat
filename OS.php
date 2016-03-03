<?php
require_once('config.php');
// error_reporting(0);
/* sql
	create table presonfl(
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `zwname` varchar(255) DEFAULT NULL,
	  `pid` int(10) DEFAULT NULL,
	  `path` varchar(255) DEFAULT NULL,
	  `desc` int(10)  AUTO_INCREMENT,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 

	create table chat(
		`nodeid` int(11) unsigned not null primary key,
		`nodename` varchar(255) not null,
		`chattime` int(11) not null,
		`content` text,
		`image` varchar(255)
	)engine=innodb default charset=utf8

	create table joblist(
		`nodeid` int(11) unsigned not null primary key,
		`jobcontent` text
	)engine=innodb default charset=utf8
*/
	include(PHP);
   	$data=$r->getdata(0);
 ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" /> 
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<title>联系人分组</title>
	<link rel="stylesheet" type="text/css" href="OS.css">
	<script src='<?php echo JQ;?>'></script>
	<script src='<?php echo JS;?>'></script>
    <style>
    *{
    	font-family: Consolas;
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
    </style>
</head>
<body  scroll=no>
<!--<div class='richangjobs'></div>-->
<div class="jobmenu">
	<li sid="$('.richangjobs').attr('sid')" class='deljob'>删除</li>
</div>
<div id="select_input_big_div" style="display: none;z-index: 10000;position: absolute;width: 100%;height: 1500px;background-color: #F0F0F0;"></div>

<div style="margin:10px;width:400px;overflow-y:scroll;border:1px solid #abcddf;border-radius:10px;float:left">

        <!-- <a onclick='udtop()' style="font-size: 1.5em;"><?php echo COMPANY;?></a> -->
        <div id='No1' class='top' style="font-size: 0.6em;height:500px;">
            <ul>
                <?php echo $r->Coc($data['person']); ?>
            </ul>
        </div>
</div>
<div style="margin:10px 5px;float:left;line-height:115px;">
	<button class='add_select' style="margin-bottom:60px" title="增加选中">add -></button><br/>
	<button class='all_add_select'style="margin-bottom:60px" title="增加全部">all -></button><br/>
	<button class='del_select'style="margin-bottom:60px" title="删除选中">sed -x</button><br/>
	<button class='all_del_select' title="删除全部">del -x</button>
</div>
<div class="selected" style="margin:10px;float:left;width:400px;overflow-y:scroll;border:1px solid #abcddf;border-radius:10px">
	 <div style="font-size: 0.6em;height:500px;">
           	 <ul></ul>
        </div>
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
	<div class="cce" style="display:none"></div>
	<script>
	var sidList=[],nameList=[],InfoList=[];
		function isTag(name,tn){
	              return $('[name="'+name+'"]').is(tn);
	           }
	        function istype(tag,type){
	            return $(tag).attr('type')==type?true:false;
	        }

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
			depList=[];
			posList=[];
			var closeb='<x class="xcb" onclick="xcbme(this)" title="删除">X</x>';
			$('.selected').children('div').children('ul').html('');
			$('.selected').children('div').children('ul').append($('.top').children('ul').html());
			$('.selected').find('.afl').append(closeb);
			$('.selected').find('li').each(function(){
				if($(this).children('sid').length){
					nameList.push($(this).children('sid').attr('name'));
					depList.push($(this).children('sid').attr('new_department'));
					posList.push($(this).children('sid').attr('new_position'));
					sidList.push($(this).children('sid').html());

				}

			})
			InfoList.push(nameList); InfoList.push(sidList)
			console.log(InfoList);
		})
		
		//单选
		$('.add_select').click(function(){
			if($('.selected').find('li').length==$('.top').find('li').length){
				return alert('超过总人数！');
			}
			$('.top').find('.ltclasscheckbox').each(function(){
				if($(this).attr('checked'))
				{					
					if(typeof($(this).parent().next('ul').html()) != 'undefined')
					{
						$('.selected').find('ul').append($(this).parent('li').clone());
						$('.selected').find('ul').append($(this).parent().next('ul').clone(true));
					}
					else if(typeof($(this).parent().parent().parent().find('input').attr('checked')) == 'undefined')
					{
						$('.selected').find('ul').append($(this).parent('li').clone());
					}
				}
			})
		})
		//全删
		$('.all_del_select').click(function(){
			$('.selected').children('div').children('ul').html('');
		})
		//单删
		function xcbme(item){
			var next_node=$(item).parent().next('ul');
			if(next_node.html()!=''){
				next_node.find('ltclasscheckbox').each(function(){
					sidList=arr_del(sidList,$(this).parent().find('sid').html());
				})
			}
			sidList=arr_del(sidList,$(item).parent('li').find('sid').html());
			$(item).parent().next().remove();
			$(item).parent().remove();
			console.log(sidList)
		}
		//选删
		$('.del_select').click(function(){
			$('.selected').find('.ltclasscheckbox').each(function(){
				if($(this).attr('checked')){
					// for (var i = 0; i < sidList.length; i++) {
					// 	if(sidList[i]==$(this).parent().find('sid').html())
					sidList=arr_del(sidList,$(this).parent().find('sid').html());
					// }
					$(this).parent('li').next('ul').remove();
					$(this).parent('li').remove();
				}
			})
			console.log(sidList)
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
		document.getElementsByTagName('listdiv').onclick=function(en){
			notecho();
		}
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
			// $('.afl').each(function(){
			// 	if ($(this).html()=='8，纤维事业部') {
			// 		// $(this).next('ul').css('display','block');
			// 	};
			// })
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
	</script>
</body>
</html>