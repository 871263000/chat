// JavaScript Document
 navigator.getUserMedia  = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
     // console.log(b.innerHTML);
    var gRecorder = null;
    var audio = document.querySelector('audio');
    // $('#mesChat_audio').click(function () {
    //     console.log(2)
    // })
    var door = false;
    var ws_audio = null;
    // console.log(audio.duration);
    //  audio.onended = function() {
    //     alert("音频已播放完成");
    // };
    // $('.keydown_voice').mousedown(function (){
    //     if(!door) {
    //         gRecorder.start();
    //         door = true;
    //     }
    // })
    // $('.keydown_voice').mouseup(function (){
    //     if(door) {
    //         audio.src = URL.createObjectURL(gRecorder.getBlob());
    //         // var mesBlob = gRecorder.getBlob();
    //         ws_audio.send(gRecorder.getBlob());
    //         // ws.send(gRecorder.getBlob());
    //         gRecorder.clear();
    //         gRecorder.stop();
    //         door = false;
    //     }
    // })
    // document.onkeydown = function(e) {
    //     if(e.keyCode === 65) {
    //         if(!door) {
    //             gRecorder.start();
    //             door = true;
    //         }
    //     }
    // };
    // document.onkeyup = function(e) {
    //     if(e.keyCode === 65) {
    //         if(door) {
    //             audio.src = URL.createObjectURL(gRecorder.getBlob());
    //             // var mesBlob = gRecorder.getBlob();
    //             ws_audio.send(gRecorder.getBlob());
    //             // ws.send(gRecorder.getBlob());
    //             gRecorder.clear();
    //             gRecorder.stop();
    //             door = false;
    //         }
    //     }
    // }
    //点击播放声音
    $(document).on('click', '.web_voice', function (){
        var web_voiceDur = $(this).attr('web_voice_data');
        var web_voiceDuring = $('.web_voice_playing').attr('web_voice_data');
        $('.web_voice_playing').addClass('web_chat_voice_'+web_voiceDuring+'_play');
        $('.web_voice_playing').removeClass('web_voice_'+web_voiceDuring+'_playing');
        $('.web_voice_playing').removeClass('web_voice_playing');
        $(this).removeClass('web_chat_voice_'+web_voiceDur+'_play');
        $(this).addClass('web_voice_'+web_voiceDur+'_playing');
        $(this).addClass('web_voice_playing');
        var web_voice_src = $(this).attr('web_voice');
        document.querySelector('audio').src = web_voice_src;
        document.querySelector('audio').play();
        
    })
    //音频播放完成
    audio.onended = function() {
        var web_voiceDuring = $('.web_voice_playing').attr('web_voice_data');
        $('.web_voice_playing').addClass('web_chat_voice_'+web_voiceDuring+'_play');
        $('.web_voice_playing').removeClass('web_voice_'+web_voiceDuring+'_playing');
    };
    $('.chat_input_key').click(function (){
        $('.chat_voice_input').css('display', 'none');
        $('.chat_text_voice').css('display', 'block');
        $('#mes_textarea').focus();
    })
    //开始录音了
    $('.keydown_voice').swipe({
        hold: function (event, target){
            if(!door) {
                gRecorder.start();
                door = true;
            }
        },
        click: function (){
            if(door) {
                audio.src = URL.createObjectURL(gRecorder.getBlob());
                // var mesBlob = gRecorder.getBlob();
                // console.log(gRecorder.getBlob());
                ws_audio.send(gRecorder.getBlob());
                gRecorder.clear();
                // console.log(gRecorder.getBlob());
                gRecorder.stop();
                door = false;
            }
        }
    })
    $('#mesChat_audio').swipe({
        click: function (event, phase, direction, distance, duration,fingerCount) {
            if(!navigator.getUserMedia) {
                alert('抱歉您的设备无法语音聊天');
                return false;
            }
            $('.chat_voice_input').css('display', 'block');
            $('.chat_text_voice').css('display', 'none');
            $(".plus_menu_box").hide();
            SRecorder.get(function (rec) {
                gRecorder = rec;
            });
         
            ws_audio = new WebSocket("ws://"+document.domain+":8333");
         
            ws_audio.onopen = function() {
                console.log('握手成功,可以语音了。');
                // ws.send('user:' + a.value);
            };
         
            ws_audio.onmessage = function(e) {
                receive(e.data);
            };
        }
    })
    var SRecorder = function(stream) {
        config = {};
        config.sampleBits = config.smapleBits || 8;
        config.sampleRate = config.sampleRate || (44100 / 6);
     
        var context = new AudioContext();
        var audioInput = context.createMediaStreamSource(stream);
        var recorder = context.createScriptProcessor(4096, 1, 1);
        var recorderDoor = true;//火狐不兼容问题
        var audioData = {
            size: 0          //录音文件长度
            , buffer: []    //录音缓存
            , inputSampleRate: context.sampleRate    //输入采样率
            , inputSampleBits: 16      //输入采样数位 8, 16
            , outputSampleRate: config.sampleRate    //输出采样率
            , oututSampleBits: config.sampleBits      //输出采样数位 8, 16
            , clear: function() {
                this.buffer = [];
                this.size = 0;
            }
            , input: function (data) {
                this.buffer.push(new Float32Array(data));
                this.size += data.length;
            }
            , compress: function () { //合并压缩
                //合并
                var data = new Float32Array(this.size);
                var offset = 0;
                for (var i = 0; i < this.buffer.length; i++) {
                    data.set(this.buffer[i], offset);
                    offset += this.buffer[i].length;
                }
                //压缩
                var compression = parseInt(this.inputSampleRate / this.outputSampleRate);
                var length = data.length / compression;
                var result = new Float32Array(Math.ceil(length));
                var index = 0, j = 0;
                while (index < length) {
                    result[index] = data[j];
                    j += compression;
                    index++;
                }
                return result;
            }
            , encodeWAV: function () {
                var sampleRate = Math.min(this.inputSampleRate, this.outputSampleRate);
                var sampleBits = Math.min(this.inputSampleBits, this.oututSampleBits);
                var bytes = this.compress();
                var dataLength = bytes.length * (sampleBits / 8);
                var buffer = new ArrayBuffer(44 + dataLength);
                var data = new DataView(buffer);
     
                var channelCount = 1;//单声道
                var offset = 0;
     
                var writeString = function (str) {
                    for (var i = 0; i < str.length; i++) {
                        data.setUint8(offset + i, str.charCodeAt(i));
                    }
                };
                
                // 资源交换文件标识符
                writeString('RIFF'); offset += 4;
                // 下个地址开始到文件尾总字节数,即文件大小-8
                data.setUint32(offset, 36 + dataLength, true); offset += 4;
                // WAV文件标志
                writeString('WAVE'); offset += 4;
                // 波形格式标志
                writeString('fmt '); offset += 4;
                // 过滤字节,一般为 0x10 = 16
                data.setUint32(offset, 16, true); offset += 4;
                // 格式类别 (PCM形式采样数据)
                data.setUint16(offset, 1, true); offset += 2;
                // 通道数
                data.setUint16(offset, channelCount, true); offset += 2;
                // 采样率,每秒样本数,表示每个通道的播放速度
                data.setUint32(offset, sampleRate, true); offset += 4;
                // 波形数据传输率 (每秒平均字节数) 单声道×每秒数据位数×每样本数据位/8
                data.setUint32(offset, channelCount * sampleRate * (sampleBits / 8), true); offset += 4;
                // 快数据调整数 采样一次占用字节数 单声道×每样本的数据位数/8
                data.setUint16(offset, channelCount * (sampleBits / 8), true); offset += 2;
                // 每样本数据位数
                data.setUint16(offset, sampleBits, true); offset += 2;
                // 数据标识符
                writeString('data'); offset += 4;
                // 采样数据总数,即数据总大小-44
                data.setUint32(offset, dataLength, true); offset += 4;
                // 写入采样数据
                if (sampleBits === 8) {
                    for (var i = 0; i < bytes.length; i++, offset++) {
                        var s = Math.max(-1, Math.min(1, bytes[i]));
                        var val = s < 0 ? s * 0x8000 : s * 0x7FFF;
                        val = parseInt(255 / (65535 / (val + 32768)));
                        data.setInt8(offset, val, true);
                    }
                } else {
                    for (var i = 0; i < bytes.length; i++, offset += 2) {
                        var s = Math.max(-1, Math.min(1, bytes[i]));
                        data.setInt16(offset, s < 0 ? s * 0x8000 : s * 0x7FFF, true);
                    }
                }
                return new Blob([data], { type: 'audio/wav' });
            }
        };
     
        this.start = function () {
            recorderDoor = true;
            audioInput.connect(recorder);
            recorder.connect(context.destination);
        }
     
        this.stop = function () {
            recorderDoor = false;
            recorder.disconnect();
        }
     
        this.getBlob = function () {
            return audioData.encodeWAV();
        }
     
        this.clear = function() {
            audioData.clear();
        }
     
        recorder.onaudioprocess = function (e) {
            if (recorderDoor) {
                audioData.input(e.inputBuffer.getChannelData(0));
            };
        }
    };
     
    SRecorder.get = function (callback) {
        if (callback) {
            if (navigator.getUserMedia) {
                navigator.getUserMedia(
                    { audio: true },
                    function (stream) {
                        var rec = new SRecorder(stream);
                        callback(rec);
                    }, function () {})
            }
        }
    }
    window.URL = URL || window.URL || window.webkitURL;
    function receive(e) {
        var duration =Math.ceil(audio.duration);
        var voice_urlDur = e + "|"+ duration;
        if (!mesParam.mes_obj()) {
            gRecorder.clear();
            gRecorder.stop();
            door = false;
            return false;
        };

        $('.chat_voice_box').attr('voice_url', voice_urlDur);
        $('.he-ov-box .he_ov').append('<li class="Chat_ri he"><div class="user_ri he"><span class="ri head_ri"><span class="header-img"><img src="'+header_img_url+'" alt=""></span></span> <span class="ri name_ri"><span style="padding: 0 20px 0 0"></span>'+name+'</span> <div class="ri content_ri"><span class="arrow ri"></span><span class="content_font_ri web_chat_voice"><div class="he_ov_mes_audio web_voice web_chat_voice_right_play" web_voice_data = "right" web_voice = "'+e+'"></div></span><span class="chat_duration_right">\"'+duration+'</span> </div></div></li>');
        onSubmit(to_uid, chat_uid, groupId, mes_type, 'voice',session_no);
        // audio.src = window.URL.createObjectURL(e);
    }