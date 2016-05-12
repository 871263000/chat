<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>
<body>
  
</body>
<script>
window.onbeforeunload=function (){ 
    if(event.clientX>document.body.clientWidth && event.clientY < 0 || event.altKey){ 
        console.log(22);
        return "必您确定要退出页面吗？";
    }else{ 
         console.log(33);
         return "必您确定要退出页面吗？";
    } 
}
 // window.onbeforeunload = function(){
 //  console.log(33);
 //   return "必您确定要退出页面吗？";
 //   }
</script>
</html>