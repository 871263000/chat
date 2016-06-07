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
    <input type="submit" onclick="test(this)" id="submit">
    <input id="hid" type="text" value="" style="" />
    <div onclick="test(this)" style="width:50px;height:50px;background:#000" id="testCursor"></div>
    <div contentEditable="true" style="height:50px; border:2px solid red;" id="test"></div>
    <script type="text/javascript" src="js/jquery.min.js"></script>

    <script type="text/javascript">
 
function test1(){  
    var div = document.getElementById("msgdiv");  
    div.innerHTML += "这是测试";  
    var range = document.createRange();  
    var len = div.childNodes.length;  
    range.setStart(div, len);  
    range.setEnd(div, len);  
    getSelection().addRange(range);  
    div.focus();  
} 


//1.获取光标位置
var hid = document.getElementById('test');
function getCursortPosition (ctrl) {//获取光标位置函数
     var CaretPos = 0; 
     var Sel = document.createRange ();
      console.log(Sel) 
     // IE Support 
    if (document.selection) {
        ctrl.focus ();
        var Sel = document.selection.createRange ();
            Sel.moveStart ('character', -ctrl.value.length); 
            CaretPos = Sel.text.length; 
    } 
     // Firefox support 
    else if (ctrl.selectionStart || ctrl.selectionStart == '0') {
        console.log(55) 
        CaretPos = ctrl.selectionStart;
        return (CaretPos); 
    }
}
var test = function (obj) {
    console.log(2);
hid.focus();
//     cursor = getCursortPosition(hid);
// console.log(cursor);
}
//2.设置光标位置
function setCaretPosition(ctrl, pos){//设置光标位置函数 
    if(ctrl.setSelectionRange)
    { 
        console.log(33);
        ctrl.focus();
        ctrl.setSelectionRange(pos,pos); 
    } else if (ctrl.createTextRange) { 
        var range = ctrl.createTextRange(); 
        range.collapse(true); 
        range.moveEnd('character', pos);
        range.moveStart('character', pos); 
        range.select(); 
    } 
}
// 向光标后插入 内容
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
        (function ($) {
        // 获取 查找替换 
        var str = '7uyuuu<img class="cli_em" em_name="72" src="http://localhost//chat/emoticons/images/72.gif">uyyyyyuyyuyyuyyyyyyyyyyyyyyyyyyyyyyyJS 不通过ID 获取HTML标签的文本 - makiyo的专栏 - 博客频道 - ...2012年10月16日 - JS 不通过ID 获取HTML标签的文本 标签: htmlfunction 2012-10-16 14:05ftr<img class="cli_em" em_name="59" src="http://localhost//chat/emoticons/images/59.gif">w<br>ertwertrewrtwertwe';
        str = str.replace(/<img[(\s.*)]class=\"cli_em\"[\s(.*)]em_name=\"/ig, '{|').replace(/\"[\s(.*)]src=\"(.*?)\"[>?]/ig, '|}').replace(/<br>/g, '&br&');
        // str = str.replace(/\"[\s(.*)]src=\"(.*?)\"[>?]/ig, '|}');
        // str = str.replace(/<br>/g, '&br&');
        var str1 = '&br&';
        str1 = str1.replace(/&br&/, "<br>");
    })
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

</script>
</html>