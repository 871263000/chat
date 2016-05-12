<?php
// require_once  'config.inc.php';
require_once  'fileupload/autoload.php';
header('Access-Control-Allow-Origin:*');

use Qiniu\Auth;

$bucket = 'omso2o';
$accessKey = 'MBl_EmG7XILzIMC55uPfhvGXFPqV6lPM_HKFuHBT';
$secretKey = 'azCbvO8ETHEAN5Lp3TxAjOc7U1pDMSzhAsFsCUEw';
$auth = new Auth($accessKey, $secretKey);


//$upToken = $auth->uploadToken($bucket);

$policy = array(
    // 'returnUrl' => 'http://127.0.0.1/chat/qiniudocs-master/demo/simpleuploader/fileinfo.php',
    'returnBody' => '{"fname": $(fname)}',
);
$upToken = $auth->uploadToken($bucket, null, 3600, $policy);

echo $upToken;
