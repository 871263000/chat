<?php
session_start();
//假设用户登录成功获得了以下用户数据
$userinfo = array(
    'uid'  => 10000,
    'name' => 'spark',
    'email' => 'spark@imooc.com',
    'sex'  => 'man',
    'age'  => '18'
);
header("content-type:text/html; charset=utf-8");

/* 将用户信息保存到session中 */
$_SESSION['uid'] = $userinfo['uid'];
$_SESSION['name'] = $userinfo['name'];
$_SESSION['userinfo'] = $userinfo;
echo "welcome ".$_SESSION['name'] . '<br>';

//* 将用户数据保存到cookie中的一个简单方法 */
$secureKey = '1111111111111111'; //加密密钥
$str = serialize($userinfo); //将用户信息序列化
echo "用户信息加密前：".$str;
$str = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $secureKey, $str, MCRYPT_MODE_ECB));
echo "用户信息加密后：".$str;
//将加密后的用户数据存储到cookie中
setcookie('userinfo', $str);

//当需要使用时进行解密
$str = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $secureKey, base64_decode($str), MCRYPT_MODE_ECB);
$uinfo = unserialize($str);
echo "解密后的用户信息：<br>";
var_dump($uinfo);


// setcookie('test', time());

// setcookie('test1', 1, 0);

//在这里试着删除test的cookie值
setcookie('test',time()-1);

var_dump($_COOKIE);

?>