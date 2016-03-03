<html>  
<head>  
    <title>JS效果-浏览器title新消息提示</title>  
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />  
    <script type="text/javascript">  
        // 使用message对象封装消息  
        var message = {  
            time: 0,  
            title: document.title,  
            timer: null,  
            // 显示新消息提示执行方法  
            show: function () { 
            console.log(6) 
            console.log(document.title);
                var title = message.title;  
                // 定时器，设置消息切换频率闪烁效果就此产生  
                message.timer = setTimeout(function () {  
                    message.time++;  
                    message.show();  
                    if (message.time % 2 == 0) {  
                        document.title = "【新消息】" + title  
                    } else {  
                        document.title = "【　　　】" + title  
                    };  
                }, 600);  
                return [message.timer, message.title];  
            },  
            // 取消新消息提示方法  
            clear: function () {  
                clearTimeout(message.timer);  
                document.title = message.title;  
            }  
        };  
        message.show();  
        // 页面加载时绑定点击事件，单击取消闪烁提示  
        function bind() {  
            document.onclick = function () { 
            console.log(5) 
                message.clear();  
            };  
        }  
    </script>  
</head>  
<body onload="bind();">  
    点击页面取消消息闪烁提示  
</body>  
</html>  