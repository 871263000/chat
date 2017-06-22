<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3***/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3***/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<style type="text/css">
/*#drop_area{ width:300px; height:200px; background-color:#CCC; border:2px dashed #000000; text-align:center;}*/
</style>
<script src="js/jquery.min.js"></script>
<script type="text/javascript">
$(function(){ 
    //...接上部分 
    var box = document.getElementById('drop_area'); //拖拽区域 
    box.addEventListener("drop",function(e){ 
        e.preventDefault(); //取消默认浏览器拖拽效果 
        var fileList = e.dataTransfer.files; //获取文件对象 
        //检测是否是拖拽文件到页面的操作 
        if(fileList.length == 0){ 
            return false; 
        } 
        //检测文件是不是图片 
        if(fileList[0].type.indexOf('image') === -1){ 
            alert("您拖的不是图片！"); 
            return false; 
        } 
         console.log(fileList[0]);
        //拖拉图片到浏览器，可以实现预览功能 
        var img = window.webkitURL.createObjectURL(fileList[0]); 
        var filename = fileList[0].name; //图片名称 
        var filesize = Math.floor((fileList[0].size)/1024);  
        if(filesize>500){ 
            alert("上传大小不能超过500K."); 
            return false; 
        } 
        var str = "<img src='"+img+"'><p>图片名称："+filename+"</p><p>大小："+filesize+"KB</p>"; 
        $("body").append(str); 
         
        //上传 
        // xhr = new XMLHttpRequest(); 
        // xhr.open("post", "upload.php", true); 
        // xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest"); 
         
        // var fd = new FormData(); 
        // fd.append('mypic', fileList[0]); 
             
        // xhr.send(fd); 
    },false); 
}); 

</script>
</head>
<body>
<textarea name=""  id="drop_area" cols="30" rows="10"></textarea>
<span style="color: #000;">asdfasdfsadf</span>

</body>
</html>