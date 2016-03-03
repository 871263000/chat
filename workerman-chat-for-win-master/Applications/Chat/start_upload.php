<?php 
use Workerman\Worker;
// 自动加载类
require_once __DIR__ . '/../../Workerman/Autoloader.php';

$worker = new Worker('BinaryTransfer://0.0.0.0:8333');
// 保存文件到tmp下
$worker->onMessage = function($connection, $data)
{
    $save_path = 'file/'.$data['file_name'];
    file_put_contents($save_path, $data['file_data']);
    $connection->send("upload success. save path $save_path");
};

Worker::runAll();
?>