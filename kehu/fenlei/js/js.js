$(function(){})
	
	function chat(name){
		var msgtitle=document.getElementById('msgtitle');
		$('#msg').slideDown(300);
		msgtitle.innerHTML=name;
	}
	function udtop(){
		$('.top').slideToggle(300);
	}
	function listu(obj){
		$(obj).next('ul').slideToggle(300);
	}
	// document.oncontextmenu=function(){return false;}
	document.onclick=function(){$('.richangjobs').fadeOut(300);
	$(menu).hide(250);}
	function addson(obj){
        var objpath=$(obj).attr('path');
        if(objpath.indexOf(',')>= 0){
            alert('对不起，您不能在里添加三级部门！');
            return false;
        }
		var fpath=obj.attributes['path'].nodeValue;
		var sid=obj.attributes['sid'].nodeValue;
		var spath=fpath+','+sid;	//子类path=父类path+父类id
		showt(2,sid,spath);
	}
	function rename(obj){
		var sid=obj.attributes['sid'].nodeValue;
		var newname=prompt('请输入新名称','');
		if(!newname){
			return false;
		}
		ajax('',3,sid,'',replaceAll(newname,'"',"'"));
	}
	
	function del(obj){
		var id=obj.attributes['sid'].nodeValue;
		var spath=obj.attributes['path'].nodeValue;
		var tips=confirm('是否删除这个节点以及下属节点?');
		if(!tips){
			return false;
		}
		ajax('',4,id,spath);
	}
	function getEvent(){
	     if(window.event){return window.event;}
	     func=getEvent.caller;
	     while(func!=null){
	         var arg0=func.arguments[0];
	         if(arg0){
	             if((arg0.constructor==Event || arg0.constructor ==MouseEvent
	                || arg0.constructor==KeyboardEvent)
	                ||(typeof(arg0)=="object" && arg0.preventDefault
	                && arg0.stopPropagation)){
	                 return arg0;
	             }
	         }
	         func=func.caller;
	     }
	     return null;
	}
	function notecho(en){
		 var e=getEvent();
		    if(window.event){
		        e.cancelBubble=true;
		     }else if(e.preventDefault){
		        e.stopPropagation();
		    }
	}
	function msch(obj,str){
		obj.title='';
		if(!obj.innerHTML){
			obj.innerHTML=str;
			obj.style.color='#abcdef';
		}
	}
	function getClassName(tagN,classN){
		var tag=document.getElementsByTagName(tagN);
		for (var i=tag.length-1; i >=0; i--) {
			if(tag[i].className==classN){
				$(tag[i]).remove();
			}
		}
	}
	function movefun(obj,dobj){
		var objPath=dobj.getAttribute('path');	//beiyidongzhe
		var objSid=dobj.getAttribute('sid');	
		var desc=obj.getAttribute('desc');	
		var dobjPath=obj.getAttribute('path');	//mubiao
		var dobjSid=obj.getAttribute('sid');
		if(objPath && objSid && dobjPath && dobjSid){
			ajax(objSid,7,dobjSid,dobjPath,objPath,desc);	
		}else{
			alert('移动失败');
			return false;
		}
	}
	function jbhide(){
		$('.richangjobs').fadeOut(300);
	}
	function replaceAll(s,f,d) {
		for(var i=0;i<s.length;i++){ 
			if(s[i]==f){
				s=s.replace(f,d);
			}
		}
		return s;
	}
	function alldel(){
		var tips=confirm("要删除所有节点？");
		 tips=tips?confirm("警告：此操作不可逆恢复,确定删除？！"):false;
		 tips=tips?ajax('',5,'','','','alldel'):false;
		return tips;
	}
	function ajax(p1,m,sid,spath,ne,cmd,content,fpath)
	{
		var fname=p1;
		var model=m;
		var path=spath;
		var id=sid;
		var newn=ne;
		var command=cmd;
		var con=content;
		if(window.XMLHttpRequest){
			var xhr = new XMLHttpRequest();
		}else{
			var xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xhr.onreadystatechange = function()
		{
			if(xhr.readyState == 4 && xhr.status == 200){
				var res=xhr.responseText;
				if(model==6){
					getClassName('li','roptions');
					var list=document.getElementById('list');
					eval('var lists='+res);
					if(!lists){
						var li  = document.createElement("li");
						li.className='roptions';
						li.innerHTML='<p style="color:red"><img title=""  src="./img/err'+Math.floor(Math.random()*10)+'.gif"/> <br>找不到与【<n style="color:#333;font-size:14px">'+con+'</n>】 相关的';
						list.appendChild(li);
						return false;
					}
					 for(i=0;i<lists.length;i++){
						var li  = document.createElement("li");
						li.className='roptions';
						li.setAttribute('sid',lists[i].id);
						li.setAttribute('path',lists[i].path);
						li.setAttribute('desc',lists[i].desc);
						li.setAttribute('onclick','movefun(this,movelist)');
						li.innerHTML=lists[i].zwname.replace(con,'<n title="编号" class="searchnum">['+lists[i].id+']</n> <a style="color:pink">'+con+'</a>');
						li.title='移动到 '+li.innerHTML.replace(/<[^>]+>/g,"")+'上面';
						list.appendChild(li);
					}
				}else if(model==9){
					if(res){
						var url=replaceAll(res,',','');
						//'<h3 style="float:right;color:red;cursor:pointer;margin-bottom:10px" onclick="jbhide()">X</h3>'+
						$('.richangjobs').html(url);
						for(var i=0;i<$('.richangjobs>a').length;i++){
							var des=$('.richangjobs>a')[i].innerHTML;
							var tis=$('.richangjobs>a')[i];
							var did=$('.richangjobs').attr('id');
							$(tis).attr('des',des);
							$(tis).attr('oncontextmenu',"confirm('是否删除这个日常工作？')?ajax("+did+",10,'"+des+"'):''");
							des='';
						}
					}else{
						//<h3 style="float:right;color:red;cursor:pointer;margin-bottom:10px" onclick="jbhide()">X</h3>
						$('.richangjobs').html('<n style="color:#fff">暂时没有日常工作</n>');
					}
					$('.richangjobs>a').attr('class','space');
				}else{
					alert(res);
					window.location.reload();
				}
			}
		}
		xhr.open('POST',"./OS.php");
		xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded;charset=utf-8");
		switch(model || m)
		{
			case 1:xhr.send('fname='+fname+'&model='+model);break;
			case 2:xhr.send('fname='+fname+'&model='+model+'&sid='+id+'&spath='+path);break;
			case 3:xhr.send('id='+id+'&newname='+newn+'&model='+model);break;
			case 4:xhr.send('model='+model+'&spath='+path+'&id='+id);break;
			case 5:xhr.send('model='+model+'&cmd='+command);break;
			case 6:xhr.send('model='+model+'&cmd='+command+'&con='+con);break;
			case 7:xhr.send('model='+model+'&id='+p1+'&sid='+id);break;
			case 8:xhr.send('model='+m+'&arr='+p1+'&id='+sid+'&ac='+spath);break;
			case 9:xhr.send('nodeid='+p1+'&model='+m+'&action=read');break;
			case 10:xhr.send('nodeid='+p1+'&model='+m+'&action=del'+'&val='+sid);break;
			default:alert('模式有误，请重新输入');break;
		}
	}
	function set(obj){
		$('#shuxing').css('display','block');
		$('#shuxing').attr('sid',$(obj).attr('sid'));
		$('#shuxing').children('h2').html('给'+$(obj).attr('myinner')+'添加日常工作<close style="cursor:pointer;float:right;color:red;margin-right:10px;" onclick="'+"$('#shuxing').css('display','none')"+'" >X</close>');
	}
	function showt(num,sid,spath){
		if(num==1){
			var onefl=prompt('请输入一级节点名称','');
			if(!onefl){
				return false;
			}
			ajax(onefl ,1);
		}else{
			var onefl=prompt('请输入节点名称','');
			if(!onefl){
				return false;
			}
			ajax(onefl,2,sid,spath);
		}
	}
	function addjobs(){
		var divs=document.getElementById('listheight');
		divs.scrollTop = divs.scrollHeight;
		$('.joblistul').append('<div class="addjobline" ><li>name:<input placeholder="工作名称" class="jobname"></li><li style="margin-left:25%;" ><a style="color:#abcdef">href :</a><input placeholder="工作链接" class="jobhref"></li><button class="clear_jobs"  onclick="revoke(this)">x</button></div>');
	}
	function revoke(obj){
		confirm('删除这一块？')?$(obj).parent().remove():'';
	}
	//替换一维数组的
	function myreplace(arr,rep,des){
		var tmp=[];
		for(var i=0;i<arr.length;i++){
			tmp.push(arr[i].replace(rep,des));
		}
		return tmp;
	}
	//一维数组转json
	function arrtojson(arr){
		var str='"'+myreplace(arr,',','":"')+'"',tmp1=[],tmp='{';
		tmp+=str+'}';
		tmp=tmp.replace(',','","');
		return tmp;
	}
	function sendmsg(con){
		alert('暂不可用!');
	}
	function json2str(t3){
		var obj = eval(t3);
		for(var i=0;i<obj.length;i++){
			alert(obj[i])
			for(var j=0;j<obj[i].length;j++){
				alert(obj[i][j]);
			}
		}
		return JSON.stringify(obj);
	}
	function readjobs(obj){
		ajax($(obj).attr('sid'),9);
	}