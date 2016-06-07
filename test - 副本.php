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


<script>
var messageShow = function (data, text) {
    var mes_time;
    var content;
    var content2 = "";
    $(".he_ov").html();
    for (var i in data) {
        var addVoiceClass = "";
        if (i == 'type') {
            $(".he_ov").prepend(" <div class='onload'  style='text-align: center;'><span style='color: #000;padding: 5px 0;'>---"+text+"---</span></div>");
            $(".he-ov-box").scrollTop($(".he_ov")[0].scrollHeight);
            return false;
        };
        // console.log(data);
        mes_time = data[i].create_time;
        switch (data[i].mesages_types) {
            case 'text':
                content = data[i].message_content.replace(/\{\|/g, '<img width="24px" class="cli_em" src="/chat/emoticons/images/');
                content = content.replace(/\|\}/g, '.gif">');
                content = content.replace(/%5C/g,"\\").replace(/\&br\&/g,"<br/>")
                break;
            case 'file':
                var fileArray = new Array();
                fileArray = data[i].message_content.split('|');
                content = "<div class='file-box'><div><i class='icon-folder-open icon-2x'> </i><span>"+fileArray[0]+"</span></div><div class='right'><a href='http://7xq4o9.com1.z0.glb.clouddn.com/"+fileArray[1]+"?attname='><i class='icon-cloud-download icon-2x'></i></a></div></div>";
                break;
            case 'image':
                content = data[i].message_content;
                break;
            case 'images':
                content = "<img src='http://7xq4o9.com1.z0.glb.clouddn.com/"+data[i].message_content+"' class='send-img'>";
                break;
            case 'voice':
                var voiceArray = new Array();
                voiceArray = data[i].message_content.split('|');
                if (data[i].sender_id == chat_uid) {
                  content = '<div class="he_ov_mes_audio web_voice web_chat_voice_right_play" web_voice_data = "right" web_voice = "'+voiceArray[0]+'"></div>';
                  content2 = '<span class="chat_duration_right">'+voiceArray[1]+'\"</span';
                } else {
                  content = '<div class="he_ov_mes_audio web_voice web_chat_voice_left_play" web_voice_data = "left" web_voice = "'+voiceArray[0]+'"></div>';
                  content2 = '<span class="chat_duration_left">'+voiceArray[1]+'\"</span';
                }
                voiceArray = data[i].message_content.split('|');
                addVoiceClass = "web_chat_voice";
                break;
            default:
                break;
        }
        if (data[i].sender_id == chat_uid) {
            $(".he_ov").prepend('<li class="Chat_ri he"><div class="user_ri he"><span class="ri head_ri"><span class="header-img"><img src="'+header_img_url+'" alt=""></span></span> <span class="ri name_ri"><span style="padding: 0 20px 0 0">'+mes_time+'</span>'+chat_name+'</span> <div class="ri content_ri"><span class="arrow ri"></span><span class="content_font_ri '+addVoiceClass+'">'+content+'</span> '+content2+' </div></div></li>');
        } else {
            $(".he_ov").prepend('<li class="Chat_le"><div class="user"><span class="head le"><span class="header-img"><img src="'+data[i].card_image+'" alt=""></span></span> <span class="name le">'+data[i].sender_name+'<span style="padding: 0 0 0 20px">'+mes_time+'</span></span><div class="mes_content le"><span class="jian le"></span> <span class="content-font '+addVoiceClass+' le">'+content+'</span>'+content2+'</div></div></li>');
        }
    }
}
function insertHtmlAtCaret(html) {
    var sel, range;
    if (window.getSelection) {
        // IE9 and non-IE
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.deleteContents();
            // Range.createContextualFragment() would be useful here but is
            // non-standard and not supported in all browsers (IE9, for one)
            var el = document.createElement("div");
            el.innerHTML = html;
            var frag = document.createDocumentFragment(), node, lastNode;
            while ( (node = el.firstChild) ) {
                lastNode = frag.appendChild(node);
            }
            range.insertNode(frag);
            // Preserve the selection
            if (lastNode) {
                range = range.cloneRange();
                range.setStartAfter(lastNode);
                range.collapse(true);
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    } else if (document.selection && document.selection.type != "Control") {
        // IE < 9
        document.selection.createRange().pasteHTML(html);
    }
}
</script>
</html>