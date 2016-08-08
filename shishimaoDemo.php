<?php
/**
* 实时猫 API 
*/
class ssmApi 
{
	
	function __construct()
	{
		
	}
	// get  token
	public static function getToken( $Method = 'post', $url, $cData ) 
	{

	}
}

//// 测试 三
$key = "7eb3b686-54e4-4c23-8ef7-90391c29d35d";
$Secret = "22155338-de04-4419-bd20-c0bf91a4c71e";
// // session id
$sessionId = "5670122b-0b47-4da5-ab2c-e8862b567a6e";
$cData = ['type'=>'pub', 'live_days'=>1];

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
curl_close($ch);
var_dump(json_decode($res, true));
exit();
 ?>
