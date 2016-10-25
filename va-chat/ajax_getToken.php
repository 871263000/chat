<?php 

require_once 'curl/lib/curl.php';
require_once 'curl/lib/curl_response.php';
require_once '../../config.inc.php';
require_once 'lib/ssmAPI.php';

// 自己的 id
$chat_uid = $_SESSION['staffid'];
// 组织id
$oms_id = $_SESSION['oms_id'];

$chat_uid = 4;
$oms_id = 1;


//// 实时 猫的 验证 key secret
$key = "7eb3b686-54e4-4c23-8ef7-90391c29d35d";
$Secret = "22155338-de04-4419-bd20-c0bf91a4c71e";
// 实时猫的 请求 url 
$url = "";
// 实时猫 请求 参数 数据
$cdata = array();
// 请求 得到的 token 

$token = '';

// 创建 token 用到的 session_id
$sessionId = "";
// get 请求 数据
$get = $_GET;
// post 请求的 数据

$post = $_POST;

// 实例化 curl 类
$curl = new Curl();
// 实例化 实时猫的类

$ssmApi = new ssmApi( $key, $Secret );
// 注入 curl  实例
$ssmApi->setCurl( $curl );  

// token 的 数量
$num = 1;



// 创建 session
if (isset($post['createSession'])) {
        // 创建 一个 sesssion ( 房间 ),
    $url = "https://api.realtimecat.com/v0.3/sessions";
    $cData = ['type'=> 'p2p', 'live_days'=>1,  'label'=>$chat_uid ];
    $res = $ssmApi->handleSession( 'post', $url, $cData );

    $arrSessionId = json_decode($res, true);
    $session = $arrSessionId['uuid']; // 会话的id 房间 id
    echo $session;
    exit;
}
// 创建 token 
if ( isset($post['Invitation']) ) {
    // 创建 一个 token 
    $num = 1;
    $session = $post['session_id'];
} else {
    $num = 2;
    // 创建 一个 sesssion ( 房间 ),
    $url = "https://api.realtimecat.com/v0.3/sessions";
    $cData = ['type'=> 'p2p', 'live_days'=>1, 'label'=>$chat_uid];
    $res = $ssmApi->handleSession( 'post', $url, $cData );

    $arrSessionId = json_decode($res, true);
    $session = $arrSessionId['uuid']; // 会话的id 房间 id
}

// 创建 token 

$url = "https://api.realtimecat.com/v0.3/sessions/".$session."/tokens";
$cData = ['type'=>'pub', 'live_days'=>1, 'number'=> $num];

$res = $ssmApi->getToken( $url, $cData );
echo $res;
exit;
 ?>