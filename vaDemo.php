<?php 

require_once 'curl/lib/curl.php';
require_once 'curl/lib/curl_response.php';
/**
* 实时猫 API 
*/
class ssmApi 
{
    private $_curl = null;

    private $key;

    private $Secret;

    private $header;
    /**
     * $key是项目的API Key，
     * $Secret是这个API Key的Secret。
     */
    function __construct( $key, $Secret )
    {
         $this->key = $key;
         $this->Secret = $Secret;
    }
    // get  token
    public  function getToken( $url, $cData ) 
    {
        $res = $this->_curl->post( $url, $cData );
        return $res;
        
    }

    // 创建一个 会话
    public function handleSession ( $meThod='', $url, $cData =array(); ) 
    {
        if ( $meThod == 'post' ) {
            $res = $this->_curl->post( $url, $cData );
        } elseif ( $meThod == 'get' ) {
            $res = $this->_curl->( $url, $cData );
        }
        return $res;
    }
    // 设置 curl  实例
    public function setCurl( $curl ) 
    {
        $this->_curl = $curl;
        // 设置  curl  header
        $this->_curl->headers = $this->header = array( 'X-RTCAT-APIKEY'=> $this->key, 'X-RTCAT-SECRET'=>$this->Secret );
    }
}

//// 测试 三
$key = "7eb3b686-54e4-4c23-8ef7-90391c29d35d";
$Secret = "22155338-de04-4419-bd20-c0bf91a4c71e";

// 实例化 curl 类
$curl = new Curl();
// 实例化 实时猫的类

$ssmApi = new ssmApi( $key, $Secret );


$url = "https://api.realtimecat.com/v0.3/sessions";

$ssmApi->setCurl( $curl );  
// 创建一个 会话 
$cData = ['type'=> 'p2p', 'live_days'=>1];
$res = $ssmApi->handleSession( 'post', $url, $cData );

$arrSessionId = json_decode($res, true);
$session = $arrSessionId['uuid']; // 会话的id

// 创建一个 token
$url = "https://api.realtimecat.com/v0.3/sessions/".$session."/tokens";

$cData = ['type'=>'pub', 'live_days'=>1, 'number'=> 2];

$res = $ssmApi->getToken( $url, $cData );

// var_dump(json_decode($res, true));
// exit();
 ?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>

    <meta charset="utf-8">
    <title>视频语音</title>

    <!-- jQuery -->
    <script src="//cdn.realtimecat.com/realtimecat/jquery.min.js"></script>

    <!-- 实时猫 RealTimeCat JavaScript SDK -->
    <script src="//cdn.realtimecat.com/realtimecat/realtimecat-0.2.min.js"></script>
    <script src="//cdn.realtimecat.com/realtimecat/realtimecat-0.2.min.js"></script>

</head>

<body>

    <div id="media-list"></div>
    <script>
        (function ($) {
            // 浏览器检测对象
            var detect = new RTCat.Detect();  
            var browser =  detect.getBrowser();  
            var version = detect.getVersion();
            var supported = detect.isSupported();
            var webrtc = detect.WebRtcSupport();
            detect.getInputDevices(function(err,devices){
                if(err){
                    console.log(err);
                }else{
                    console.log(devices);
                }
            });
            var ssmOptions = {
                data: <?php echo !empty($res) ? $res : 0;?>,
                options: {video: false, audio: true, data: true},
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
                // Video container
                var videoContainer = document.createElement("div");
                videoContainer.setAttribute('style', "width: 300px; height:300px;");
                // Video player
                var videoPlayer = document.createElement('div');
                videoPlayer.setAttribute("id", "peer-" + id);
                videoContainer.appendChild(videoPlayer);
                mediaList.appendChild(videoContainer);
                stream.play("peer-" + id);
                videoPlayer.childNodes[0].setAttribute('poster', '')
            }

            /**************************************
            *               建立会话
            ***************************************/

            // 使用token新建会话，请将此处的Token替换为
            // 从http://dashboard.shishimao.com/生成的Token
            session = new RTCat.Session( ssmOptions.data.uuid );

            session.connect();

            session.on('connected', function (users) {
                console.log('Session connected');
                initStream(ssmOptions.options, function (stream) {
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

        }).apply(this, [jQuery]);
    </script>
</body>
</html>