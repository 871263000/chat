<?php
require_once('/www/web/default/oms/conn.php');
require_once'./fenlei/lt.php';
$o=$r->getNode('getone');
echo'<pre>';
$t=$r->getNode('','股东会');
print_r($t);
$one=json_encode($o);
$two=json_encode($t);
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>无标题</title>
    <script src='js/jquery-1.8.2.min.js'></script>
</head>
<body>
部门:<select name="one"><option>请选择</option></select>
部门细分:<select name="two"><option>请选择</option></select>
</body>
<script type="text/javascript">
    var o=eval(<?php echo $one;?>);
    for(var i in o){
        $('[name="one"]').append('<option value='+o[i].zwname+'>'+o[i].zwname+'</option>')
    }
    var t=eval(<?php echo $two;?>);
    for(var d in t){
        $('[name="two"]').append('<option value='+o[d].zwname+'>'+o[d].zwname+'</option>')
    }

</script>
</html>