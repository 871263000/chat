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
     $save_path = 'file/20160305.mp3';
    file_put_contents($save_path, $data);
    $connection->send($save_path);
};

// 运行worker
Worker::runAll();