
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset = "utf-8"/>
        <title>Chat by Web Sockets</title>
        <script type="text/javascript" src="js/recorder.js"> </script>
        <script type="text/javascript" src="js/jquery.min.js"> </script>
         
        <style type='text/css'>
            
        </style>
    </head>
    <body>
        <audio controls autoplay src=""></audio>
        
       <input type="button" id="record" value="Record">
       <input type="button" id="export" value="Export">
       <div id="message"></div>
    </body>
     
    <script type='text/javascript'>
            var onFail = function(e) {
                console.log('Rejected!', e);
            };
         
            var onSuccess = function(s) {
                var context = new webkitAudioContext();
                var mediaStreamSource = context.createMediaStreamSource(s);
                rec = new Recorder(mediaStreamSource);
                //rec.record();
         
                // audio loopback
                // mediaStreamSource.connect(context.destination);
            }
         
            //window.URL = URL || window.URL || window.webkitURL;
            navigator.getUserMedia  = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
         
            var rec;
            var audio = document.querySelector('audio');
         
            function startRecording() {
                if (navigator.getUserMedia) {
                    navigator.getUserMedia({audio: true}, onSuccess, onFail);
                } else {
                    console.log('navigator.getUserMedia not present');
                }
            }
            startRecording();
            //--------------------      
            $('#record').click(function() {
                rec.record();
//                var dd = ws.send("start");
                $("#message").text("Click export to stop recording");
     
                // export a wav every second, so we can send it using websockets
//                 intervalKey = setInterval(function() {

//                 }, 3000);
            });
             
            $('#export').click(function() {
                // first send the stop command
                rec.stop();
                rec.exportWAV(function(blob) {
                         
//                         rec.clear();
//                         ws.send(blob);
					// console.log(blob);
					audio.src = URL.createObjectURL(blob);
                    });
//                 clearInterval(intervalKey);
                 
//                 ws.send("analyze");
                $("#message").text("");
            });
             
            var ws = new WebSocket("ws://127.0.0.1:7272");
            ws.onopen = function () {
                console.log("Openened connection to websocket");
            };
            ws.onclose = function (){
                 console.log("Close connection to websocket");
            }
//             ws.onmessage = function(e) {
//                 audio.src = URL.createObjectURL(e.data);
//             }
             
            
        </script>
</html>