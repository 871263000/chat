<?php
use \Workerman\Worker;

// 自动加载类
require_once __DIR__ . '/../../Workerman/Autoloader.php';
// 创建一个Worker监听2346端口，使用websocket协议通讯
$ws_worker = new Worker("websocket://0.0.0.0:8333");

// 启动1个进程对外提供服务
$ws_worker->count = 1;
$ws_worker->name = 'audio';
$ws_worker->reloadable = true;
// 当收到客户端发来的数据后返回hello $data给客户端
$ws_worker->onMessage = function($connection, $data)
{
    // 向客户端发送hello $data
    $curDate = date('Ymd');
    //文件保存路径
	$save_path = "../file/".$curDate . "/";
	//文件连接路径
	$save_url = "/chat/file/".$curDate . "/";
	//创建文件夹
	if (!file_exists($save_path)) {
		mkdir($save_path);
	}
	//随机生成10位字符串作为文件名
	$length = 10;
    $randStr = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol)-1;
	for($i=0;$i<$length;$i++){
	    $randStr.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
	}

	
    $new_save_path = $save_path.$randStr.'.wav';
    $new_save_url = $save_url.$randStr.'.wav';
    file_put_contents($new_save_path, $data);
    $connection->send($new_save_url);
};

// 运行worker
Worker::runAll();