<?php 
require_once 'curl/lib/curl.php';
require_once 'curl/lib/curl_response.php';
require_once 'lib/ssmAPI.php';
error_reporting(E_ALL ^ E_NOTICE^ E_DEPRECATED);
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
$session = "";

// get 请求 数据
$get = $_GET;

// post 请求的 数据
$post = $_POST;

// 传输类型
$type = '';

// 房间id
$session = $get['session_id'];
// 有 token
if ( isset($get['uuid']) ) {

    $token = $_GET['uuid'];

} else if ( isset($_GET['Invitation']) ) {
    // 实例化 curl 类
    $curl = new Curl();
    // 实例化 实时猫的类

    $ssmApi = new ssmApi( $key, $Secret );
    // 注入 curl  实例
    $ssmApi->setCurl( $curl );  
    // 创建 一个 token 
    $num = 1;
   
    // 创建 token 
    $url = "https://api.realtimecat.com/v0.3/sessions/".$session."/tokens";
    $cData = ['type'=>'pub', 'live_days'=>1, 'number'=> $num, 'label'=>$chat_uid ];
    $res = $ssmApi->getToken( $url, $cData );
    $arrRes = json_decode($res, true );
    $token = $arrRes['uuid'];
}
// a 语音 v 是 视频
$type = $get['act'];

if ( empty($type) ) {
    $type = 'v';
}
 ?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <title>视频语音</title>

    <!-- jQuery -->
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    
    <!-- 实时猫 RealTimeCat JavaScript SDK -->
    <script src="//cdn.realtimecat.com/realtimecat/realtimecat-0.2.min.js"></script>
    <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <!-- css -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="fonts/css/font-awesome.min.css">

    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <style>
        body{ background-color: #181818;width: 100%;height: 100%; }
        #navbar{ margin: auto; }
        #media-list{width: 0;margin: auto;max-width: 100%;}
        /*#peer-self{ width: 200px; height: 170px;} */
        .vaIng{ background-color: #CCC;}
        .va-box{ display: inline-block;margin: 0 10px; }
        .big-va-box { position: fixed; width: 800px;height: 800px;top: 50%; left: 50%; margin-left: -400px;margin-top: -400px;z-index: 111;}
        .big-va-box video{ width: 800px;height: 800px;transition:all 1s;
            -moz-transition:all 1s;
             /* Firefox 4 */
            -webkit-transition:all 1s;
            /* Safari and Chrome */
            -o-transition:all 1s; /* Opera */ }
        @media (max-width: 500px) {
            .va-box{ width: 90% !important;height: 100% !important; margin: auto;display: block !important;}
            .va-box video{ width: 100% !important; height: auto !important; margin: auto;}
        }
    </style>
    <!-- <link rel="stylesheet" href="css/main.min.css"> -->

</head>

<body>
<!-- 模态框 -->
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">把以下链接发给你的好友邀请加入</h4>
                </div>
                <div class="modal-body">
                    https://www.omso2o.com/chat/va-chat/vaChat.php?session_id=<?php echo $session; ?>&Invitation=1
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary">确定</button>
                </div>
            </div>
        </div>
    </div>
    <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-center">
            <li>
                <a data-toggle="modal" data-target="#myModal" data-hover="tooltip"
                data-placement="bottom" title="" data-original-title="邀请好友">
                    <i class="fa fa-share-square-o"></i>
                </a>
            </li>
            <li>
                <a id="chat-toggle" href="#"
                data-hover="tooltip" data-placement="bottom" title="" data-original-title="聊天">
                    <i class="fa fa-comment-o">
                    </i>
                </a>
            </li>
            <li>
                <a id="video-toggle" href="#"
                data-hover="tooltip" data-placement="bottom" class="va-toggle" vaType = "Video" title="" data-original-title="关闭视频">
                    <i class="fa fa-eye-slash">
                    </i>
                </a>
            </li>
            <li>
                <a id="audio-toggle" href="#"
                data-hover="tooltip" data-placement="bottom" class="va-toggle" vaType = "Audio" title="" data-original-title="关闭音频">
                    <i class="fa fa-microphone-slash">
                    </i>
                </a>
            </li>
            <li>
                <a id="screen-toggle" href="#"
                data-hover="tooltip" data-placement="bottom" title="" data-original-title="分享屏幕">
                    <i class="fa fa-television">
                    </i>
                </a>
            </li>
            <li>
                <a id="whiteboard-toggle" href="#"
                data-hover="tooltip" data-placement="bottom" title="" data-original-title="白板">
                    <i class="fa fa-object-group">
                    </i>
                </a>
            </li>
            <li>
                <a id="leave-room" href="#"
                data-hover="tooltip" data-placement="bottom" title="" data-original-title="离开房间">
                    <i class="fa fa-sign-out">
                    </i>
                </a>
            </li>
        </ul>
    </div>
<!--     <div id="ask-for-allowance">
        <img id="ask-for-allowance-image" src="./images/ask-for-allowance.png" alt="请点击允许按钮">
    </div> -->
    <div id="media-list">
        <h2 style="text-align: center;">点击放大</h2>
    </div>
    <script>
        (function ($) {
            //拖动
            
            // 浏览器检测对象
            // var detect = new RTCat.Detect();  
            // var browser =  detect.getBrowser();  
            // var version = detect.getVersion();
            // var supported = detect.isSupported();
            // var webrtc = detect.WebRtcSupport();
            // detect.getInputDevices(function(err,devices){
            //     if( err ){
            //         console.log(err);
            //     } else {
            //         console.log(devices);
            //     }
            // });
            var ssmOptions = {
                token: "<?php echo !empty($token) ? $token : 0;?>",
                options: {video: true, audio: true, data: true},
            }
            // 声明变量
            var session;
            var localStream;
            var mediaList = document.querySelector('#media-list');

            /********************************
             *           工具函数
             ********************************/
            // 初始化流
            function initStream(options, callback) {
                localStream = new RTCat.Stream(options);
                localStream.on('access-accepted', function () {
                        session.send({stream: localStream, data: true});
                        callback(localStream);
                    }
                );
                localStream.on('access-failed', function (err) {
                    console.log(err);
                });

                localStream.on('play-error', function (err) {
                    console.log(err);
                });
                localStream.init();
            }

            // 显示流
            function displayStream(id, stream) {
                var vWidth = '300px';
                var listWidth = '';
                                // Video container
                var videoContainer = document.createElement("div");
                videoContainer.setAttribute('style', "width: "+vWidth+"; height:300px;");
                videoContainer.className = 'va-box';

                // Video player
                var videoPlayer = document.createElement('div');
                videoPlayer.setAttribute("id", "peer-" + id);
                $(videoPlayer).dblclick(function (event) {
                    var is = $(this).hasClass('big-va-box');
                    if ( !is ) {
                        $('.va-box .big-va-box').removeClass('big-va-box')
                        $(this).addClass('big-va-box');
                        $( ".big-va-box" ).draggable();
                    } else {
                        $(this).removeClass('big-va-box')
                    }
                })
                // videoPlayer.onclick = function (event) {
                //     var obj = event.srcElement ? event.srcElement : event.target; 
                //     var addClass = 'big-va-box';
                //     var is = $(obj).parent().hasClass('big-va-box');
                //     console.log(is);
                //     if ( !is ) {
                //         $('.va-box .big-va-box').removeClass('big-va-box')
                //         $(obj).parent().addClass('big-va-box');
                //         $( ".big-va-box" ).draggable({
                //             start: function() {
                //             },
                //             drag: function() {
                //             },
                //             stop: function() {
                //                 is = false;
                //             }
                //         });
                //         // videoContainer.childNodes[0].onclick = function () {
                //         //     window.event? window.event.cancelBubble = true : evt.stopPropagation();
                //         //     $('.va-box .big-va-box').removeClass('big-va-box');
                //         //     console.log(4);
                //         // }
                //     } else {
                //         $('.va-box .big-va-box').removeClass('big-va-box')
                //         // videoPlayer.className = '';
                //     }
                // }
                videoContainer.appendChild(videoPlayer);
                mediaList.appendChild(videoContainer);
                stream.play("peer-" + id);
                videoPlayer.childNodes[0].setAttribute('poster', '');

                listWidth = parseInt( $('#media-list').css('width').replace('px', '') );
                listWidth +=320;
                $('#media-list').css('width', listWidth+"px");
                // Video container
                // var videoContainer = document.createElement("div");
                // if ( id == "self" ) {
                //     videoContainer.setAttribute('style', "width: 200px; height:160px;float: right");
                // } else {
                //     videoContainer.setAttribute('style', "width: 400px; height:300px;");
                // }
                
                // // Video player
                // var videoPlayer = document.createElement('div');
                // videoPlayer.setAttribute("id", "peer-" + id);
                // videoContainer.appendChild(videoPlayer);
                // mediaList.appendChild(videoContainer);
                // stream.play("peer-" + id);
                // if ( id == "self" ) {
                //     videoPlayer.childNodes[0].setAttribute('width', '200px');
                //     videoPlayer.childNodes[0].setAttribute('height', '170px');
                // } else {
                //     videoPlayer.childNodes[0].setAttribute('width', '400px');
                //     videoPlayer.childNodes[0].setAttribute('height', '300px');
                // }
            }

            /***************************************
                           建立会话
            ***************************************/

            // 使用token新建会话，请将此处的Token替换为
            // 从http://dashboard.shishimao.com/生成的Token
            session = new RTCat.Session( ssmOptions.token );

            session.connect();

            session.on('connected', function (users) {
                console.log('Session connected');
                initStream( ssmOptions.options, function (stream) {
                    displayStream('self', stream)
                });
            });

            session.on('in', function (token) {
                if (localStream) {
                    session.sendTo({to: token, stream: localStream, data: true});
                }
                console.log('someone in');
            });

            session.on('out', function (token) {
                console.log('someone out');
            });

            session.on('remote', function (r_channel) {
                var id = r_channel.getId();
                r_channel.on('stream', function (stream) {
                    displayStream(id, stream);
                });
                r_channel.on('close', function () {
                    $('#peer-' + id).parent().remove();
                });
            });

            var localStream = new RTCat.Stream( ssmOptions.token );
            $('.va-toggle').click( function () {
                var res = colorChange($(this));
                var vaType = $(this).attr('vaType');
                if ( res == false ) {
                    if ( vaType == "Video" ) {
                        localStream.disableVideo();

                    } else {
                        localStream.disableAudio();
                    }
                } else {
                    if ( vaType == "Video" ) {
                        localStream.enableVideo();

                    } else {
                        localStream.enableAudio();
                    }
                }
                
            })            
            // $('#audio-toggle').click( function () {
            //     localStream.disableAudio();
                
            // })
            function colorChange(obj) {
                if ( obj.hasClass('vaIng') == false ) {
                    obj.addClass('vaIng');
                    return false;
                } else {
                    obj.removeClass('vaIng');
                    return true;
                }
            }
        }).apply(this, [jQuery]);
    </script>
</body>
</html>