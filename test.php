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
$secureKey = 'imooc'; //加密密钥
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
<!DOCTYPE html>
<html>
<head>
    <title>无标题页</title>

    <script type="text/javascript" src="js/jquery.min.js"></script>

    <script type="text/javascript">
        (function ($) {
            $.fn.extend({
                insertAtCaret: function (myValue) {
                    var $t = $(this)[0];
                    if (document.selection) {
                        this.focus();
                        sel = document.selection.createRange();
                        sel.text = myValue;
                        this.focus();
                    } else
                        if ($t.selectionStart || $t.selectionStart == '0') {
                            var startPos = $t.selectionStart;
                            var endPos = $t.selectionEnd;
                            var scrollTop = $t.scrollTop;
                            $t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
                            this.focus();
                            $t.selectionStart = startPos + myValue.length;
                            $t.selectionEnd = startPos + myValue.length;
                            $t.scrollTop = scrollTop;
                        } else {
                            this.value += myValue;
                            this.focus();
                        }
                }
            })
        })(jQuery);

        $(document).ready(function () {
            $("#numd").bind("mouseleave", function () {
                document.getElementById('keybored').style.display = 'none';
                document.getElementById('Nm').blur();
            });

            $("#Nm").focus(function () {
                document.getElementById('keybored').style.display = '';
            });
            $(".readbtns").click(function () {
                $("#Nm").insertAtCaret($(this).val());

            });

        });
        // 获取 查找替换 
        var str = '7uyuuu<img class="cli_em" em_name="72" src="http://localhost//chat/emoticons/images/72.gif">uyyyyyuyyuyyuyyyyyyyyyyyyyyyyyyyyyyyJS 不通过ID 获取HTML标签的文本 - makiyo的专栏 - 博客频道 - ...2012年10月16日 - JS 不通过ID 获取HTML标签的文本 标签: htmlfunction 2012-10-16 14:05ftr<img class="cli_em" em_name="59" src="http://localhost//chat/emoticons/images/59.gif">w<br>ertwertrewrtwertwe';
        str = str.replace(/<img[(\s.*)]class=\"cli_em\"[\s(.*)]em_name=\"/ig, '{|').replace(/\"[\s(.*)]src=\"(.*?)\"[>?]/ig, '|}').replace(/<br>/g, '&br&');
        // str = str.replace(/\"[\s(.*)]src=\"(.*?)\"[>?]/ig, '|}');
        // str = str.replace(/<br>/g, '&br&');
        var str1 = '&br&';
        str1 = str1.replace(/&br&/, "<br>");
        console.log(str1);

console.log(str);
    </script>

</head>
<body>
    <ul>
        <li>
            <input />
            <div>
            </div>
        </li>
    </ul>
    <input id="hid" type="text" value="" style="display: none" />
    <span id="numd" style="border: 1px solid red; clear: both; display: inline-block; font: 800em;">
        <input type="text" id="Nm" name="Nm" value="" />
        <div style="display: none; border: 1px solid #A2B4C6; width: 150px; height: 400px;"
            id="keybored">
            <input type="button" class="readbtns" value="1" />
            <input type="button" class="readbtns" value="2" />
            <input type="button" class="readbtns" value="3" />
            <input type="button" class="readbtns" value="4" />
            <input type="button" class="readbtns" value="5" />
            <input type="button" class="readbtns" value="6" />
            <input type="button" class="readbtns" value="7" />
            <input type="button" class="readbtns" value="8" />
            <input type="button" class="readbtns" value="9" />
        </div>
    </span>
</body>
</html>