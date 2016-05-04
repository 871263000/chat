<?php 
/**
 *  curl 的 测试 1
 */

// $url = "www.baidu.com";

// //初始化
// $ch = curl_init( $url );

// //执行
// curl_exec($ch);

// //关闭curl;
// curl_close($ch);

/**
 * 
 * curl 替换网页内容 测试  2
 */

// 初始化
// $curl = curl_init();
// //设置url 
// $url = "www.baidu.com";

// curl_setopt($curl, CURLOPT_URL, $url); //设置连接 url;

// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true ); //执行之后不要打印出来

// $outPut = curl_exec($curl);// 执行保存
// //关闭资源
// curl_close($curl);
// //输出内容
// echo str_replace('百度', '屌丝', $outPut );

/**
 * curl 天气的获取  测试3
 */

$curlData = 'theCityName=上海';
 ?>