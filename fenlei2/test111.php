<?php
//数据库连接
include'../config.inc.php';
require_once'lt.php';

if($_GET['deplist']){

    $res=$r->getNode($_GET['deplist']);
    echo'<pre>';
    print_r($res);

}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title>测试</title>
</head>
<body>

<form action="" method="post">

</form>

</body>
</html>