<?php
// 测试 一
require_once 'curl.php';
header("Content-Type: text/html;charset=utf-8;");
/**
* 请求 实时猫 API
*/

class GetSsmAPI
{
	// 实时猫 的 key
	private  $APP_KEY;
	// 实时猫 的 SECRET
	private  $APP_SECRET;
	// curl 类对象
	private $curlObj;
	// 实时猫的 服务器API 地址
	private $url;
	function __construct($key, $secret)
	{
		$this->APP_KEY = $key;
		$this->APP_SECRET = $secret;
	}

	// public static function getToken($method, $url, $data = array()) 
	// {

	// 	$this->curlObj
	// }
	// 执行
	public  function exec($method, $url, $data = array())
	{
		require_once 'curl.php';
	    $curl = new Curl();
	    $curl->setHeader('X-RTCAT-APIKEY', $this->APP_KEY);
	    $curl->setHeader('X-RTCAT-SECRET', $this->APP_SECRET);

	    switch (strtolower($method)) {
	        case 'get': {
	        	$curl->setOpt('CURLOPT_SSL_VERIFYPEER', false);
	        	$curl->setOpt('CURLOPT_SSL_VERIFYHOST', true);
	            $curl->setHeader('Content-Type', 'application/x-www-form-urlencoded;charset=utf-8;');
	            $curl->get($url, $data);
	            break;
	        }
	        case 'post': {

	            $curl->setHeader('Content-Type', 'application/json;charset=utf-8;');
	            $curl->post($url, $data);
	         //    $curl->setOpt('CURLOPT_SSL_VERIFYPEER', false);
	        	// $curl->setOpt('CURLOPT_SSL_VERIFYHOST', true);
	            break;
	        }
	        case 'patch': {
	        	$curl->setOpt('CURLOPT_SSL_VERIFYPEER', false);
	        	$curl->setOpt('CURLOPT_SSL_VERIFYHOST', true);
	            $curl->setHeader('Content-Type', 'application/json;charset=utf-8;');
	            $curl->patch($url, $data);
	            break;
	        }
	        case 'delete': {
	        	$curl->setOpt('CURLOPT_SSL_VERIFYPEER', false);
	        	$curl->setOpt('CURLOPT_SSL_VERIFYHOST', true);
	            $curl->setHeader('Content-Type', 'application/json;charset=utf-8;');
	            $curl->delete($url, $data);
	            break;
	        }
	        default: {
	        	$curl->setOpt('CURLOPT_SSL_VERIFYPEER', false);
	        	$curl->setOpt('CURLOPT_SSL_VERIFYHOST', true);
	            $curl->setHeader('Content-Type', 'application/x-www-form-urlencoded;charset=utf-8;');
	            $curl->get($url, $data);
	            break;
	        }
	    }
	    $arrRes = array();
	    if ($curl->error) {
	        $ret = false;
	    } else {

	        $arrRes = json_decode($curl->response, true);

	        $ret = true;
	    }
	    $arrRes = json_decode($curl->response, true);
	    $curl->close();

	    return array($ret, $arrRes, $curl->error);
	}
}

//测试 

//     $url = 'http://apis.baidu.com/tianyiweather/basicforecast/weatherapi?area=101010100';
//     $header = array(
//         'apikey: 62a03c4a3789a8465ae9a932de69cf92',
//     );
//     // $getToken = new GetSsmAPI($key,$Secret);
// // $res = $getToken->exec('post', $url, $cData);
//     $curl = new Curl();
//     $curl->setHeader('apikey', '62a03c4a3789a8465ae9a932de69cf92');
//     $curl->setHeader('Content-Type', 'application/x-www-form-urlencoded;charset=utf-8;');
//     $curl->get($url,[]);
//     $res =$curl->response;
// var_dump($res);
// exit();
//     $ch = curl_init();
//     // 添加apikey到header
//     curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//     // 执行HTTP请求
//     curl_setopt($ch , CURLOPT_URL , $url);
//     $res = curl_exec($ch);
// var_dump($res);
//     var_dump(json_decode($res));
//     exit();

//测试 二
$key = "7eb3b686-54e4-4c23-8ef7-90391c29d35d";
$Secret = "22155338-de04-4419-bd20-c0bf91a4c71e";
// // session id
$sessionId = "5670122b-0b47-4da5-ab2c-e8862b567a6e";

$cData = ['type'=>'pub'];

$url = "https://api.realtimecat.com/v0.3/sessions/".$sessionId."/tokens";
$getToken = new GetSsmAPI($key,$Secret);
$res = $getToken->exec('post', $url, $cData);
var_dump($res);
exit();


//// 测试 三
$key = "7eb3b686-54e4-4c23-8ef7-90391c29d35d";
$Secret = "22155338-de04-4419-bd20-c0bf91a4c71e";
// // session id
$sessionId = "5670122b-0b47-4da5-ab2c-e8862b567a6e";
$cData = ['type'=>'pub'];

$url = "https://api.realtimecat.com/v0.3/sessions/".$sessionId."/tokens";

$ch = curl_init();


// $url = "https://api.realtimecat.com/v0.3/sessions";
$header = array( 'X-RTCAT-APIKEY:'.$key,'X-RTCAT-SECRET:'.$Secret );  //  头部 信息

curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);  // 设置 头部
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $cData); //  设置 数据
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查  
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在  
// 执行HTTP请求
curl_setopt($ch , CURLOPT_URL , $url);
$res = curl_exec($ch);
var_dump($res);
exit();


require_once 'lib'.DIRECTORY_SEPARATOR.'curl.php';
require_once 'lib'.DIRECTORY_SEPARATOR.'curl_response.php';
$key = "7eb3b686-54e4-4c23-8ef7-90391c29d35d";
$Secret = "22155338-de04-4419-bd20-c0bf91a4c71e";
$headers['X-RTCAT-APIKEY'] = $key; 
$headers['X-RTCAT-SECRET'] = $Secret;
// // session id
$sessionId = "5670122b-0b47-4da5-ab2c-e8862b567a6e";
$cData = ['type'=>'pub'];
$url = "https://api.realtimecat.com/v0.3/sessions/".$sessionId."/tokens";
// $url = "www.baidu.com";
$ch = new curl();
$ch->headers = $headers;
$res = $ch->post($url, $cData);
echo $res;
var_dump($res);
// print_r($res);


