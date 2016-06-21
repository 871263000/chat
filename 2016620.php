<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <script type="text/javascript" src="http://www.omso2o.com/chat/js/swfobject.js"></script>
    <script type="text/javascript" src="http://www.omso2o.com/chat/js/web_socket.js"></script>
    <script type="text/javascript" src="http://www.omso2o.com/chat/js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="http://www.omso2o.com/chat/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="http://www.omso2o.com/chat/js/web_message.js"></script>
    <script type="text/javascript" src="http://www.omso2o.com/chat/js/touchSwipe.js"></script>
    <script src="http://www.omso2o.com/chat/js/chatViewer.min.js"></script>
    <link rel="stylesheet" href="http://www.omso2o.com/chat/css/viewer.min.css">
    <link rel="stylesheet" href="http://www.omso2o.com/chat/css/webRight.css" type="text/css"/>
    <link rel="stylesheet" href="http://www.omso2o.com/chat/css/jquery-ui.css" type="text/css"/>
    <link href="http://www.omso2o.com/chat/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="http://www.omso2o.com/chat/css/app.css" type="text/css"/>
    <link rel="stylesheet" href="http://www.omso2o.com/chat/css/font-awesome.min.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="http://www.omso2o.com/css/OMS.css">
    <link rel="shortcut icon" href="favicon.ico">

    <!--<style>
          a:link,a:visited {
          text-decoration: none;
          color:black;
          font-size: 16px;
          }
          a:hover,a:active {
          text-decoration: none;
          color:#EF3C37;
          }


  #galleryUl{position:absolute; padding:0; margin:0; font-size:12px; top:20px; z-index:9999; width:100%;
   color:#000; background-color:#fff; border:1px solid #ccc; border-top:none;}
  #galleryUl a:link, #galleryDl a:visited, #galleryDl a:active{font-size:12px; color: #000;text-decoration: none}
    </style>
  <script>
  var _hmt = _hmt || [];
  (function() {
    var hm = document.createElement("script");
    hm.src = "//hm.baidu.com/hm.js?c339989673606f22bcbe43fd711f4a2d";
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(hm, s);
  })();
  </script>-->

<!--jquery滑动横向二级菜单特效-->

<style type="text/css">
*{margin:0;padding:0;list-style-type:none;font-family: 'Microsoft YaHei', SimSun, sans-serif !important;}
.mes_radio{ width: 21px; height: 21px; display: block; border-radius: 50%; background: red; text-align: center; line-height: 21px; margin: 9px 0 0 10px; cursor: pointer; color: #fff; text-align: center; line-height: 21px;  }
.online_man{ text-align: center;margin: 0 auto;box-shadow: 0 0 5px #999;}
.online_man ul li, .group_pep li{line-height: 30px;text-align: center;list-style: none;padding: 5px;border-bottom: 1px solid #dedede;}
.online_man ul{margin-left: auto;margin-right: auto;padding: 0;}
#name_box{
    position:absolute;background:#fff;display:none;width: 100%;
    z-index: 111;
}
/*聊天*/
.mes_dclose{display: block !important;}
.chat-container  .container{ margin: auto !important;}
@media (min-width: 600px) {
    .chat-container .mes_dclose{ cursor: pointer; position: absolute;  top: 3px;  font-size: 33px;  color: #000;  right: 0;}
}
@media (max-width: 600px) {
    .chat-container .mes_dclose{ cursor: pointer; position: absolute;  top: 3px;  font-size: 33px;  color: #fff;  right: 0;}
}
.mes_chakan_close{line-height: 30px; cursor: pointer;}
a,img{border:0;}
body{font:14px/180% microsoft yahei;}
.content22 li{float:left;font-size:1em;}
.content22 li a{float:left;display:block;height:20px;line-height:20px;color:#000;text-decoration:none;position:relative;overflow:hidden;}
/*.content22,.content22{background:#50ADE4;color:#fff;}*/
.content22 li .box{z-index: 99; width:100%;height: auto;position:absolute;top:100%;left:0px;*left:190px;left:198px\0;background:#50ADE4;display:none;}
.content22 li .box a{display:block;height:30px;float:left;color:#fff;line-height:30px;border:none;background:none;padding:5px;width:100px;text-align:center;}
.content22 li .box a:hover{text-decoration:none;color:#000;opacity:0.6;filer:(opacity=60)}
.chat-container{display:none;z-index:999999;position: fixed; top: 0; left: 50%; margin-left: -300px;height: 100%; }
/*快速通道*/
.galleryBox{color:#f39801; background-color:#fff; border:1px solid #f39801;}
#galleryUl{position:absolute; left:0; z-index:9999; width:100%; color:#000; background-color:#fff; border:1px solid #f39801; border-top:none;}
#galleryUl li{ font-weight:normal;}
#galleryUl a{ display:block;}
#galleryUl a:link, #galleryUl a:visited, #galleryUl a:active{color: #000;text-decoration: none}
#galleryUl a:hover { background:#F00; color: #fff; text-decoration: underline;}

/*语言栏*/
.galleryBox1{color:#f39801; background-color:#fff; border:1px solid #f39801;}
#galleryUl1{position:absolute; left:0; z-index:9999; width:100%; color:#000; background-color:#fff;border-top:none;}
#galleryUl1 li{ font-weight:normal;}
#galleryUl1 a{ display:block;}
#galleryUl1 a:link, #galleryUl1 a:visited, #galleryUl1 a:active{color: #000;text-decoration: none}
#galleryUl1 a:hover { background:#F00; color: #fff; text-decoration: underline;}
#name_box{ position: fixed !important;top: 0 }
@media (max-width: 600px){
    .chat-container{ position: fixed;top: 0;left: 0;height: 100%;width: 100%;background: rgba(0,0,0,.5)!important;margin-left: 0 !important;}
    #edui149_iframe{width: auto !important;}
    #edui149_content{width: auto !important;}
}
@media(min-width: 601px)and(max-width: 1000px){
    #edui149_iframe{width: auto !important;}
    #edui149_content{width: auto !important;}

}

</style>

<!--<script language="javascript" type="text/javascript" src="--><!--/js/jquery-1.8.2.min.js"></script>-->
<script type="text/javascript">
    var chat_uid;
    $(function (){
        connect();
    })
    var mes_online = 1;
    var oms_id = "1";
    chat_uid = "409"; // 发送人id
    var room_id = "1";//房间id
    var to_uid = 0;// 接收人id
    var chat_name ="张冬林";
    var mes_type = "message";
    var session_no = "0";//会话id
    var document_url = "http://www.omso2o.com";
    var groupId = 0;
    var header_img_url = "https://www.omso2o.com/upload/photos/20150707105356.jpg";
    //保存输入的数据
    var inputSave = "";
    // 当前的页面
    var webUrl = '';
    // 加载图片的 index
    var imgIndex = 0;
    // 右边消息的个数
    $(function () {
        //-----------------imgRemoveHeight 移除指定图片高度
        $('.shake-little').removeAttr('height')
        //-----------------end
        var navLi=$(".content22 li");
        navLi.mouseover(function () {
            $(this).find("a").addClass("current");
            $(this).find(".box").stop().show();
        })
        navLi.mouseleave(function(){
            $(this).find("a").removeClass("current");
            $(this).find(".box").stop().hide();
        })
    })
    nearestContact = new Object();
    nearestContact = [];
    /*******************   消息列表session集合   *********************/

    var arrMessageList = new Array();
    arrMessageList = [];
</script>

<script>
    $.fn.smartFloat = function() {
        var position = function(element) {
            var top = element.position().top; //当前元素对象element距离浏览器上边缘的距离
            var pos = element.css("position"); //当前元素距离页面document顶部的距离
            $(window).scroll(function() { //侦听滚动时
                var scrolls = $(this).scrollTop();

                if (scrolls > top) { //如果滚动到页面超出了当前元素element的相对页面顶部的高度
                    if (window.XMLHttpRequest) { //如果不是ie6
                        element.css({ //设置css
                            position: "fixed", //固定定位,即不再跟随滚动
                            top: 0, //距离页面顶部为0
                            //left:$('#OMStop').position().left
                        }).addClass("shadow"); //加上阴影样式.shadow
                    } else { //如果是ie6
                        element.css({
                            top: scrolls  //与页面顶部距离
                        });
                    }
                }else {
                    element.css({ //如果当前元素element未滚动到浏览器上边缘，则使用默认样式
                        position: pos,
                        top: top
                    }).removeClass("shadow");//移除阴影样式.shadow
                }
            });
        };
        return $(this).each(function() {
            position($(this));
        });
    };
</script>



</head>
<body>

        <!-- 消息通知 -->
    <div class="chat_message_notice">
        <i class= "chat_close">&times;</i>
        <div class="chat_message_notice_con"></div>
    </div>
    <!-- 消息提示 -->
    <div class="mesNoticeContainer"></div>
    <!--  end -->
    <!-----通知消息--->
    <div class="chat_notice_container">
        <div class="chat_notice_close close">&times;</div>
        <div class="chat_notice_list_box">
            <div class="chat_notice_list">
                <div class="chat_notice_img">
                    <img src="/chat/images/chatNotice.png" alt="">
                </div>
                <div class="chat_notice_con">
                    <span></span>
                    <span></span>
                </div>
                <div class="chat_notice_agree chat_notice_sel"></div>
                <div class="chat_notice_unagree chat_notice_sel"></div>
            </div>
        </div>
    </div>
<!--发送的图片-->
    <div class="img-box">
        <div class="img-box-title"><span>发送的图片</span><span style="color: #000;" class="com-close com-close-act">&times;</span></div>
        <div class="sending-img-box"></div>
        <div class="img-box-act"><span class="btn btn-success com-close-act">取消</span><span class="btn btn-info send-clipboard-img">发送</span></div>
    </div>
<!--消息-->
    <div class="chat-container">
            <div class="mes_title">
                <h2 class="mes_title_con"></h2><span aria-hidden="true" class="mes_dclose">&times;</span>
            </div>
            <div class="mes_con_box">
                <div class="">
                    <div class="">
                        <div class="he-ov-box mes-scroll pc_he-ov-box">
                            <div class="loader">
                                <div id="mes_load" style="display:none;">10</div>
                                <div class="loading-3">
                                    <i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i>
                                </div>
                            </div>
                            <ul class="he_ov session-box">
<!--                                <div class='onload'  style='text-align: center;'><span style='color: #fff;padding: 5px 0;'>---查看更多---</span></div>-->
                            </ul>
                        </div>
                    </div>
                    <div class="pc_mes_input_box">
                        <div class="pc_emoji_box">

                        </div>
                        <div class="pc_mes_tool">
                            <ul>
                                <li class="pc_mes_tool_emoji pc_mes_tool_list"></li>
<!--                                <li class="pc_mes_tool_img pc_mes_tool_list"></li>-->
                                <li class="pc_mes_tool_file pc_mes_tool_list"></li>
                            </ul>
                        </div>

                        <div class="pc_mes_input" contenteditable="true" id="pc_mes_input"></div>

                        <div class="pc_mes_send">
                            <span style = "color: #aaa">按Esc 关闭，Shift + Enter 换行， Enter提交</span>
                            <div class="chat_btn">发送</div>
                        </div>
                    </div>
                    <div class="mes_footer mb_mes_footer mb_mes_footer">
                        <div class="plus_menu_box">
                            <div class="plus_menu">
                                <span class="header_icon plus-list"><img src="/chat/images/header_icon.png" alt=""></span><input style="display:none" type="file" multiple id="send-upimg"><span id="cli-upFile"><img src="/chat/images/uploadfile.png" alt=""></span>
                            </div>
                            <i class="icon-caret-down"></i>
                        </div>

                        <!-- <form onsubmit="onSubmit(); return false;"> -->
                        <div class="mes_input">
                            <i class="plus_icon"></i>
                            <div class="mes_inout textarea" id="mes_textarea" style="height:auto;" contenteditable="true"></div>
                            <textarea style="display:none" class="mes_inout" ></textarea>
                            <input type="submit" class="btn btn-primary btnSend" id="submit" value="发送" />
                            <div style="clear:both"></div>
                        </div>
                        <div class="emoticons"></div>
                        <!-- </form> -->
                        <div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <div class="mes_fixed_big">
        <div class="mes_abs">
            <div class="mes_fixed">
                <div class="mes_ico_box" cata-box='ren'>
                    <div class="mes_ico mes_hide" style="background-position:-6px -50px"></div>
                    <div class="mes_ico mes_hide" style="background-position:-100px -100px;text-align: center;color:#fff;">
                        <span class="mes_hide" style='line-height: 50px;'><span class='online_ren'>0</span>人</span>
                    </div>
                </div>
                <div class="mes_ico_box" cata-box='mes'>
                    <div class="mes_ico mes_hide" style="background-position:-6px 0"></div>
                    <div class="mes_ico mes_hide" style="background-position:-100px -100px">
                        <div class="mes_radio mes_hide">0</div>
                    </div>
                </div>
<!--                <div class="kefu-icon">-->
                    <div class="kefu" mes_id="customer" style="color: #fff; text-align: center;height: 40px;line-height: 20px;width: 40px;" group-name ="客服">联系客服</div>
<!--                </div>-->
<!--                <div class="mes_move"><i class="icon-sort"></i></div>-->
<!--                <div id="rightArrow" class="show_opinion_xm">-->
<!--                    <a class="" href="javascript:;" style="font-family: Microsoft YaHei;"  title="意见建议">用户反馈</a>-->
<!--                </div>-->
            </div>
            <div class="online_man" >
                <div class="man_tittle">
                    <span>人员列表</span>
                    <span class ="close" style='cursor: pointer; margin-right: 5px;background: #000;color: #fff; position:absolute;width:20px;height:20px;border-radius: 50%;right: 0; top: 7px; '>&times;</span>
                </div>
                <div class="onlinesSroll-box">
                    <div class="onlinesSroll"></div>
                </div>
                <!-- 人员列表 -->
                <!-- 搜索框 -->
                <div class="search_box">
                    <input type="text" class= "search_in" id="search_in" placeholder= "搜索">
                    <span class="search_staff"></span>
                    <ul class="staff-list-group search_result">

                    </ul>
                </div>
                <ul class="list-group"> </ul>
            </div>
            <div class="mes_con" style="display: none;">
                <div class="mes_tittle">
                    <span>消息列表</span>
                    <span class ="close" style='cursor: pointer; margin-right: 5px;background: #000;color: #fff; position:absolute;width:20px;height:20px;border-radius: 50%;right: 0; top: 7px; '>&times;</span>
                </div>
                <!-- 消息列表 -->
                                <!-- end -->
            </div>
        </div>
    </div>
    <!-- 加载图片的保存 -->
    <div class="" >
        <ul class="loadImg" style="display:none">
        </ul>
    </div>
    <!-- img放大 -->
    <!-- 弹出框 -->
    <div class="cd-popup" role="alert">
        <div class="cd-popup-container">
            <p class="mes_alert_con">请选择聊天对象</p>
            <ul class="cd-buttons">
                <li><a href="#" class="clo_alert">确定</a></li>
                <!-- <li><a href="#">否</a></li> -->
            </ul>
            <a href="#" class="cd-popup-close img-replace">关闭</a>
        </div> <!-- cd-popup-container -->
    </div> <!-- cd-popup -->
    </div>
    <!-- 上传文件-->
    <div class="file-main">
        <ul>
            <li>
                <input id="token" name="token" class="ipt" value="">
            </li>
            <li>
                <input id="key" name="key" class="ipt" value="">
            </li>
            <li>
                <input id="file_zdl" name="file" class="ipt" type="file" />
            </li>
            <li>
                <input id="filename" type="text" value="">
            </li>
        </ul>
    </div>
    <!-- 上传进度 -->
    <div id="progressbar"><div class="progress-label"></div><div id="formatSpeed"></div></div>
  <!--头部总的三层部分-->
  <div class="OMStop" id="OMStop">
      <!--头部第一层：LOGO、名称和国旗部分-->
      <div class="Logonamenationflag">
          <!--LOGO部分-->
<!--          <div class="headerlogo">-->
<a href="http://www.omso2o.com/welcomepage.php">
                  <img src="http://www.omso2o.com/image/2015-11-08_142249-02.jpg" alt="" width="100%"></a>

<!--          </div>-->
          <!--名称部分-->
<!--          <div class="name">-->
<!--      	      <img src="--><!--/image/OMS图片-5-05.png" alt="" width="80%">-->
<!--              OMS：&nbsp;&nbsp;一掌控&nbsp;&nbsp;全掌控&nbsp;&nbsp;一掌全控-->
<!--              One&nbsp;&nbsp;Hand&nbsp;&nbsp;Hold,&nbsp;&nbsp;Can&nbsp;&nbsp;Hold&nbsp;&nbsp;All,-->
<!--              &nbsp;&nbsp;One&nbsp;&nbsp;Hand&nbsp;&nbsp;Hold&nbsp;&nbsp;All-->
<!--      	  </div>-->
      	  <!--国旗部分-->
<!--          <div class="name">-->
<!--              <img src="--><!--/image/掌控文字-06.png" alt="" width="70%">-->
<!--          </div>-->
<!--          <div class="name">-->
<!--              <img src="--><!--/image/掌控文字-07.png" alt="" width="70%">-->
<!--          </div>-->
<!--          <div class="name">-->
<!--              <img src="--><!--/image/掌控文字-08.png" alt="" width="90%">-->
<!--          </div>-->
      </div>


      <!--头部第二层：欢迎条搜索框部分-->
      <div class="topwelcome">
          <div class="welcomeword">
              欢迎张冬林第400次登陆
          </div>

          <!--安全退出按钮部分-->
          <!--<a href="http://www.omso2o.com/logout.php"><div class="safegoout">安全退出</div></a>-->
          
          <div id="galleryBox" style=" position:relative; width:21%; background:#f60; color:#fff; text-align:center; font-size:17px; font-weight:bold; float:right;">快速通道
                
                <ul id="galleryUl" style="display:none;">
                    <li><a href="http://www.omso2o.com/welcomepage.php">欢迎页</a></li>
                    <li><a href="http://www.omso2o.com/workpage.php">工作页</a></li>
                    <li><a href="http://www.omso2o.com/bring_workpage.php">日常工作页</a></li>
                    <li><a href="http://www.esaga.com/indexs.php">“萨嘎全球”</a></li>
                    <li><a href="http://www.omso2o.com/password_edit.php">修改密码</a></li>
                    <li><a href="http://www.omso2o.com/bind_mobile_phone.php">绑定手机(可登录)</a></li>
                    <!--<li><a href="http://www.omso2o.com/bind_email.php">绑定邮箱(可登录)</a></li>-->
                    <li><a href="http://www.omso2o.com/set_timezone.php">设定时区</a></li>
                                                            <li><a href="http://www.omso2o.com/logout.php">安全退出</a></li>
                </ul>
                
            </div>
             <script type="text/javascript">
            $('#galleryBox').mouseenter(function (){
				$('#galleryUl').css('display', '');
                $('#galleryUl').css('z-index', '150000');
			});
			
			
			$('#galleryBox').mouseleave(function (){
				$('#galleryUl').css('display', 'none');	
			});
			
			$('#galleryBox').click(function (){
				var display = $('#galleryUl').css('display');
				if(display == 'none')
				{
					$('#galleryUl').css('display', '');
				}
				else
				{
					$('#galleryUl').css('display', 'none');	
				}
			});
            </script>

          <!--语言切换部分-->
          <div id="galleryBox1" style=" position:relative; width:21%; background:#ECF6F2; text-align:center; font-size:17px; font-weight:bold; float:right;">Language

                  <ul id="galleryUl1" style="display:none;">
                      <li><a href="http://www.omso2o.com/#.php">中文</a></li>
                      <li><a href="http://www.omso2o.com/#.php">English</a></li>
                      <li><a href="http://www.omso2o.com/#.php">Français</a></li>
                      <li><a href="http://www.omso2o.com/#.php">Español</a></li>
                      <li><a href="http://www.omso2o.com/#.php">Português</a></li>
                      <li><a href="http://www.omso2o.com/#.php">Русский</a></li>
                      <li><a href="http://www.omso2o.com/#.php">Deutsch</a></li>
                      <li><a href="http://www.omso2o.com/#.php">日本語</a></li>
                      <li><a href="http://www.omso2o.com/#.php">Arabic</a></li>
                      <li><a href="http://www.omso2o.com/#.php">Turkish</a></li>
                  </ul>

          </div>
          <script type="text/javascript">
              $('#galleryBox1').mouseenter(function (){
                  $('#galleryUl1').css('display', '');
                  $('#galleryUl1').css('z-index', '15000');
              });


              $('#galleryBox1').mouseleave(function (){
                  $('#galleryUl1').css('display', 'none');
              });

              $('#galleryBox1').click(function (){
                  var display = $('#galleryUl1').css('display');
                  if(display == 'none')
                  {
                      $('#galleryUl1').css('display', '');
                  }
                  else
                  {
                      $('#galleryUl1').css('display', 'none');
                  }
              });
          </script>

          <!--头部第二层搜索框部分
          <div class="search">
          s
          </div>-->
      </div>
  </div>

    <script>
        $("#OMStop").smartFloat();
    </script>



<!--    --><!--<!--引导页-->
<!--    --><!--        --><!--    --><!--    --><!--    --><!---->
<!--    <style>-->
<!--        body{overflow: hidden}-->
<!--        .header-btn-color{background-color: #fff;color:#000;text-align: center;display: block;padding:5px 10px;}-->
<!--    </style>-->
<!--    <link rel="stylesheet" href="--><!--/css/gethelp.css">-->
<!--    <div class="gehelp_wi">-->
<!--        <div class="gehelp_model ma">-->
<!--            <div class="gehelp_helplogin --><!--">-->
<!--                <a href="javascript:" class="gehelp_wi_a">-->
<!--                    <img src="img/oms_bg_1.png" alt="">-->
<!--                </a>-->
<!--                <a class="login_oupa" href="--><!--/logout.php">安全退出</a>-->
<!--            </div>-->
<!--            <div class="gehelp_task --><!--">-->
<!--                --><!--                --><!--                --><!--                    --><!--                    <!-- 判断是否有颜色 -->
<!--                    <!-- 0 0 35px #F8FFF7 -->
<!--                    --><!--                        --><!--                    --><!--                        --><!--                    --><!--                    --><!--                        --><!--                        --><!--                        --><!--                        --><!--                        --><!--                    --><!--                        --><!--                        --><!--                    --><!--                    --><!--                        --><!--                    --><!--                        --><!--                    --><!--                    --><!--                            --><!--                        --><!--                    <div class="gehelp_task_for posi_weizhi_--><!--" url="--><!--" data-state="1" data-font="1" style="--><!--" data-id="--><!--" data-if="--><!--" data-order="--><!--" href="--><!--">-->
<!--                        <div class="gehelp_bor_wi" style="--><!--">-->
<!--                            <div class="gehelp_bor action" >-->
<!--                                <p>--><!--</p>-->
<!--                                --><!--                                    恭喜您已经完成-->
<!--                                --><!--                                    <span class="gehelp_task_font_size">--><!--</span>-->
<!--                                --><!--                            </div>-->
<!--                            <div class="bg_class" style="--><!--"></div>-->
<!--                        </div>-->
<!--<!--                        <div class="gehelp_task_show_font action" val="你需要添加组织架构">-->
<!--<!--                        </div>-->
<!--                        <div class="task_bor"></div>-->
<!--                    </div>-->
<!--                --><!--                <div class="gehelp_daibai">-->
<!--                    <div class="gethep_font_pro">-->
<!--                        <img width="300" src="img/hueihua.png" alt="">-->
<!--                        --><!--                        --><!--                        <strong>您好！请您务必先完成圆圈中的--><!--项任务! </strong>-->
<!--                    </div>-->
<!--                    <div class="gehelp_ren">-->
<!--                        <img width="300" src="img/dabai.png" alt="">-->
<!--                        <button class="btn-default">我已完成</button>-->
<!--                        --><!--                            <span class="pro_show"></span>-->
<!--                            <img class="pro_img" src="img/pro.png" alt="">-->
<!--                        --><!--                        <a class="login_oup" href="--><!--/logout.php">安全退出</a>-->
<!--                    </div>-->
<!--                </div>-->
<!--                --><!--                    <div class="gehelp_bg_bor">-->
<!---->
<!--                    </div>-->
<!--                --><!--                --><!--                --><!--                    --><!--                --><!--                    --><!--                --><!--                <div class="model_show_start" --><!-->
<!--                    <p>你已完成了"必须"完成的1项任务</p>-->
<!--                    <p>接下来请你到OMS任何页面的"快速通道"下拉菜单里面的"添加日常工作"、"添加查看报表"、"确定权限"三个选项中去完成相应基础数据录入工作。若不清楚如何操作,请参照"快速通道"下拉菜单里面的"引导页"说明进行操作。</p>-->
<!--                    <div class="">-->
<!--                        <a class="header-btn-color" href="welcomepage.php">点击进入OMS</a>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--                <!-- 			<a href="javascript:" class="img_dabai">-->
<!--                                <span>亲</span>-->
<!--                                <span>,</span>-->
<!--                                <span>请</span>-->
<!--                                <span>耐</span>-->
<!--                                <span>心</span>-->
<!--                                <span>按</span>-->
<!--                                <span>照</span>-->
<!--                                <span>下</span>-->
<!--                                <span>列</span>-->
<!--                                <span>步</span>-->
<!--                                <span>骤</span>-->
<!--                                <span>操</span>-->
<!--                                <span>作</span>-->
<!--                                <span>填</span>-->
<!--                                <span>写</span>-->
<!--                                <span>相</span>-->
<!--                                <span>关</span>-->
<!--                                <span>资</span>-->
<!--                                <span>料</span>-->
<!--                                <span>,</span>-->
<!--                                <span>这</span>-->
<!--                                <span>些</span>-->
<!--                                <span>操</span>-->
<!--                                <span>作</span>-->
<!--                                <span>不</span>-->
<!--                                <span>会</span>-->
<!--                                <span>花</span>-->
<!--                                <span>您</span>-->
<!--                                <span>太</span>-->
<!--                                <span>多</span>-->
<!--                                <span>时</span>-->
<!--                                <span>间</span>-->
<!--                                <span>。</span>-->
<!--                                <br>-->
<!--                                <span>完</span>-->
<!--                                <span>成</span>-->
<!--                                <span>后</span>-->
<!--                                <span>您</span>-->
<!--                                <span>就</span>-->
<!--                                <span>可</span>-->
<!--                                <span>享</span>-->
<!--                                <span>用</span>-->
<!--                                <span>O</span>-->
<!--                                <span>M</span>-->
<!--                                <span>S</span>-->
<!--                                <span>的</span>-->
<!--                                <span>强</span>-->
<!--                                <span>大</span>-->
<!--                                <span>功</span>-->
<!--                                <span>能</span>-->
<!--                                <span>!</span>-->
<!--                            </a> -->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <script src="--><!--/js/jquery-1.8.2.min.js"></script>-->
<!--    <script src="--><!--/js/jquery.cookie.js"></script>-->
<!--    <script src="--><!--/js/gehelp.js"></script>-->
<!--引导页end-->
            <!--意见建议开始-->
            






        <script type="text/javascript" src="http://www.omso2o.com/chat/js/web_index.js"></script>
        <title>工作页</title>
<link rel="stylesheet" href="css/home.css" media="screen" title="no title" charset="utf-8">
<!--意见建议开始-->
    <style>
        .show_opinion{width: 100%;height: 100%;position: fixed;background-color: rgba(10, 10, 10, 0.61);top:0px;left:0px;display: none;z-index:10000;}
        .show_opinion_box{width: 90%;height: 60%;background-color: #fff;position: absolute;left:2.5%;top:5%;border-radius: 5px;box-shadow: 0 5px 15px rgba(0,0,0,.5);}
        .show_opinion_box h3{border-bottom: 1px solid #ddd;line-height: 45px;text-indent: 20px;font-size: 18px; }
        .show_opinion_box a{text-decoration: none; }
        .show_opinion_box a:hover{text-decoration:none }
        .show_opinion_close{display: block;width:100px;border:1px solid #ddd;height: 32px;line-height: 30px;text-align: center;text-decoration: none;font-size: 14px;color:#000;margin-left: 10%;float:left;cursor: pointer;background-color:#fff;}
        .show_opinion_submit{margin-right:10%;display: block;width:100px;border:1px solid #ddd;height: 32px;line-height: 30px;text-align: center;text-decoration: none;font-size: 14px;color:#000;float:right;font-size:14px;font-family: Microsoft YaHei;cursor: pointer;background-color:white;}
        #rightArrow{width:50px;height:42px;position:fixed;z-index:10000;text-align: center;}
        #rightArrow a{display:block;height:41px;color: #fff;line-height: 17px;padding: 1px 5px;font-style: normal;font-weight: bold;text-decoration: none;background-color: #F60;}
        #rightArrow a:hover{color: #fff;background: #ccc;}
        #yijianjianyi{width:80%;resize: none;margin-left:10%;border-color:#CCCCCC #999999 #999999 #CCCCCC;border-style:solid;border-width:1px;font-family:arial,sans-serif;font-size:13px;height:160px;outline-color:-moz-use-text-color;outline-style:none;outline-width:medium;padding:2px;background-color:transparent;color: #666464;
        }
        /*.mes_fixed{height: 160px !important;top: 40%;}*/
        .mes_input .btnSend{
            color: #fff;
            background-color: #3276b1;
            border-color: #285e8e;
            height: 50px;
            display: inline-block;
            padding: 6px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: normal;
            line-height: 1.428571429;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            cursor: pointer;
            background-image: none;
            border: 1px solid transparent;
            border-radius: 4px;
            -webkit-user-select: none;}
    </style>
<!--意见建议end-->
<table width="100%"></table>
    <p class="workpage_header_font" style="font-size: 24px;padding-top: 10px">上海斯瑞科技有限公司</p>
<div class="home_modal">
<!-- 头像手机 -->
<div class="home_user_tender pull-height">
    <img src="https://www.omso2o.com/upload/photos/20150707105356.jpg" alt="" />
    <p class="workpage_header_wap">上海斯瑞科技有限公司</p>
    <a id="show_img2" class="show_img">
        我的二维码
    </a>
</div>
<!-- 头像手机end -->
<div class="home_header_user">
    <div class="pull-margin home_header_wi">
        <div class="homde_tender pull-left">
                            <span class="homde_tender_img"><img src="https://www.omso2o.com/upload/photos/20150707105356.jpg" alt="" /></span>
                        <a id="show_img2" class="show_img">
                我的二维码
            </a>
        </div>
        <div class="homde_tender_nav pull-left">
            <ul>
                <!--               <li><a href="#">日常工作</a></li>
                              <li><a href="#">查看报表</a></li> -->
                <li><a href="http://www.omso2o.com/admin/oms_inorout_file_upload_input.php">文件资料传递</a></li>
                <li><a href="http://www.omso2o.com/admin/oms_staff_backlog_items_input.php">布置任务</a></li>
                <li><a href="http://www.omso2o.com/admin/oms_call_meeting_input.php">召集安排会议</a></li>
                <li><a href="http://www.omso2o.com/admin/oms_news_and_dynamic_input.php?type=1">发布新闻</a></li>
                <li><a href="http://www.omso2o.com/admin/oms_administration_department_notice_input.php">发布公告</a></li>
                <li><a href="http://www.omso2o.com/admin/oms_general_using_pay_input1.php">支付OMS使用费</a></li>
                <li><a class="show_opinion_xm" href="jvascript:">用户反馈</a></li>
                <li><a class="show_opinion_xm" href="http://www.omso2o.com/admin/oms_news_and_dynamic_input.php?type=2">微生活录入</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="welcome_wi pull-height">
<div class="welcome_wi_fro pull-height pull-left hidden-md pc_hide">
    <ul>
        <li class="pull-left welcome_wi_fro_parent_wi">
            <a href="http://www.omso2o.com/admin/oms_staff_backlog_items_input.php" class="welcome_wi_fro_parent pull-display skin_e pull-bottom box pull-difont">
                <span class="iconfont">&#xe648;</span>
                <span class="welcome_font_bottom">布置任务</span>
            </a>
            <a href="http://www.omso2o.com/admin/oms_call_meeting_input.php" class="font_next_find_er_left skin_a pull-display">
                <span class="iconfont">&#xe644;</span>
                <span class="font_fond_home">召集安排会议</span>
            </a>
        </li>
        <li class="pull-left welcome_wi_fro_parent_wi">
            <a href="http://www.omso2o.com/admin/oms_news_and_dynamic_input.php?type=1" class="font_next_find_er_left skin_a pull-display  pull-bottom">
                <span class="iconfont">&#xe645;</span>
                <span class="font_fond_home">发布新闻</span>
            </a>
            <a href="http://www.omso2o.com/admin/oms_administration_department_notice_input.php" class="font_next_find_er_left skin_e pull-display  pull-bottom">
                <span class="iconfont">&#xe642;</span>
                <span class="font_fond_home">发布公告</span>
            </a>
            <a href="http://www.omso2o.com/admin/oms_general_using_pay_input1.php" class="font_next_find_er_left skin_d pull-display  pull-bottom">
                <span class="iconfont">&#xe643;</span>
                <span class="font_fond_home">支付OMS使用费</span>
            </a>
        </li>
    </ul>
</div>
<div class="welcome_wi_fro pull-height pull-left">
    <ul>
        <li class="welcome_wi_fro_li pull-height file_upload">
            <a href="http://www.omso2o.com/admin/oms_news_and_dynamic_input.php?type=2" target="_blank" class="pull-height pull-display skin_e pull-height-ico">
                <span class="iconfont ioc_font_left font_ico_size">&#xe668;</span>
                <span style="font-size:18px" class="pull-left ico_font_right welcome_font_1 pull-left-pa">微生活录入</span>
            </a>
        </li>
        <li class="welcome_wi_fro_li pull-height file_upload">
            <a href="http://www.omso2o.com/admin/oms_inorout_file_upload_input.php" target="_blank" class="pull-height pull-display skin_d pull-height-ico">
                <span class="iconfont ioc_font_left font_ico_size">&#xe654;</span>
                <span style="font-size:18px" class="pull-left ico_font_right welcome_font_1 pull-left-pa">文件资料传递</span>
            </a>
        </li>
        <li class="welcome_wi_fro_li pull-height">
            <a href="http://www.omso2o.com/bring_workpage.php" target="_blank" class="pull-height pull-display skin_a pull-height-ico">
                <span class="iconfont ioc_font_left font_ico_size">&#xe606;</span>
                <span style="font-size:24px" class="pull-left ico_font_right welcome_font_1 pull-left-pa">日常工作</span>
            </a>
        </li>
        <li class="welcome_wi_fro_li">
            <div class="welcome_wi_fro_li_find_er  pull-left">
                <a href="http://www.omso2o.com/oms_schedule.php" class="skin_d box pull-display font_a_1_parent pull-relative">
                    <span class="iconfont" style="padding:50px 0;">&#xe629;</span>
                    <span class="welcome_font_bottom">日程安排</span>
                                    </a>
            </div>
            <div class="welcome_wi_fro_li_find_find pull-left pull-bottom">
                <a href="http://www.omso2o.com/chat/chat_index.php" target="_blank" class="skin_a box pull-display wfont_next_find_er_left">
                    <span class="iconfont">&#xe626;</span>
                    <span class="font_fond_home">聊天</span>
                </a>
            </div>
            <div class="welcome_wi_fro_li_find_find pull-left">
                <a id="linksmm" target="_blank" class="skin_d box pull-display wfont_next_find_er_left pull-relative">
                    <span class="iconfont">&#xe63c;</span>
                    <span class="font_fond_home">收发邮件email</span>
                    <!--<dt class="pre_span">0</dt>-->
                </a>
            </div>
        </li>
        <li class="welcome_wi_fro_li pull-height">
            <div class="welcome_wi_fro_li_find_find_er pull-left">
                <a href="http://www.omso2o.com/oms_guest_reception_list.php" class="skin_d welcome_for_wi_ma box wfont_next_find_er_left pull-align  pull-relative">
                    <span class="iconfont">&#xe653;</span>
                    <span class="font_fond_home">宾客接待安排</span>
                                    </a>
            </div>
            <div class="welcome_wi_fro_li_find_find_er pull-left">
                <a href="http://www.omso2o.com/oms_rubbish_bin.php" class="skin_e box wfont_next_find_er_left  pull-relative">
                    <span class="iconfont">&#xe64b;</span>
                    <span class="font_fond_home">垃圾箱</span>
                    <!--<dt class="pre_span">0</dt>-->
                </a>
            </div>
        </li>
    </ul>
</div>
<div class="welcome_wi_fro pull-height pull-left">
    <ul>
        <li class="welcome_wi_fro_li pull-height">
            <div class="welcome_wi_fro_li_find_find_er pull-left">
                <a href="http://www.omso2o.com/oms_company_contactslist.php" class="skin_e font_next_find_er_left  pull-relative">
                    <span class="iconfont">&#xe625;</span>
                    <span class="font_fond_home">通讯录</span>
                    <dt class='pre_span pre_span_skin'>337</dt>                </a>
            </div>
            <div class="welcome_wi_fro_li_find_find_er pull-left">
                <a href="http://www.omso2o.com/oms_usual_approval.php" class="skin_a font_next_find_er_left  pull-relative">
                    <span class="iconfont">&#xe637;</span>
                    <span class="font_fond_home">常用表格</span>
                    <dt class='pre_span pre_span_skin'>26</dt>                </a>
            </div>
        </li>
<!--        <li class="welcome_wi_fro_li">-->
<!--            <a href="--><!--/oms_usual_approval.php" class="pull-height pull-display skin_d pull-height-ico  pull-relative">-->
<!--                <span class="iconfont ioc_font_right font_ico_size">&#xe637;</span>-->
<!--                <span class="pull-left ico_font_left welcome_font_1 pull-left-pa">常用表格</span>-->
<!--                --><!--            </a>-->
<!--        </li>-->
        <li class="welcome_wi_fro_li pull-height">
            <div class="welcome_wi_fro_li_find_find_er pull-left">
                <a href="http://www.omso2o.com/oms_inorout_file_upload_list.php" class="skin_a  font_next_find_er_left  pull-relative">
                    <span class="iconfont">&#xe656;</span>
                    <span class="font_fond_home">文件资料传递回复</span>
                                                        </a>
            </div>
            <div class="welcome_wi_fro_li_find_find_er pull-left">
                <a href="http://www.omso2o.com/staffdoinghimself.php?task_type=6" class="skin_e font_next_find_er_left  pull-relative">
                    <span class="iconfont">&#xe62f;</span>
                    <span class="font_fond_home">需阅卷宗</span>
                                    </a>
            </div>
        </li>
<!--        <li class="welcome_wi_fro_li">-->
<!--            <a href="--><!--/staffdoinghimself.php?task_type=需阅卷宗" class="pull-height pull-display skin_e pull-height-ico  pull-relative">-->
<!--                <span style="padding-left:20px" class="iconfont ioc_font_left font_ico_size">&#xe62f;</span>-->
<!--                <span class="pull-left ico_font_right welcome_font_1 pull-left-pa">需阅卷宗</span>-->
<!--                --><!--            </a>-->
<!--        </li>-->
        <li class="welcome_wi_fro_li pull-height">
            <div class="welcome_wi_fro_li_find_find_er pull-left">
                <a href="admin/oms_reporting_input.php" class="skin_e  font_next_find_er_left  pull-relative">
                    <span class="iconfont">&#xe65d;</span>
                    <span class="font_fond_home">汇报通报录入</span>
                    <!--                    -->                </a>
            </div>
            <div class="welcome_wi_fro_li_find_find_er pull-left">
                <a href="oms_reporting_list.php" class="skin_a  font_next_find_er_left  pull-relative">
                    <span class="iconfont">&#xe65e;</span>
                    <span class="font_fond_home">汇报通报批复</span>
                    <dt class='pre_span pre_span_skin'>1</dt>                                    </a>
            </div>
        </li>
        <li class="welcome_wi_fro_li pull-height">
            <div class="welcome_wi_fro_li_find_find_er pull-left">
                <a href="http://www.omso2o.com/oms_myapply_list.php" class="skin_a font_next_find_er_left  pull-relative">
                    <span class="iconfont">&#xe636;</span>
                    <span class="font_fond_home">本人发出的审批审核</span>
                    <dt class='pre_span pre_span_skin'>7</dt>                </a>
            </div>
            <div class="welcome_wi_fro_li_find_find_er pull-left">
                <a href="http://www.omso2o.com/oms_approval_list.php" class="skin_e  font_next_find_er_left  pull-relative">
                    <span class="iconfont">&#xe638;</span>
                    <span class="font_fond_home">本人收到的审批审核</span>
                                    </a>
            </div>
        </li>
    </ul>
</div>
<div class="welcome_wi_fro pull-height pull-left">
    <ul>
        <li class="welcome_wi_fro_li pull-height">
            <div class="pull-height pull-left parent_find_left">
                <a class="box skin_c pull-bottom font_next_find_er_left pull-display  pull-relative" href="http://www.omso2o.com/staffgetmeetingnotification.php?staffid=409">
                    <span class="iconfont">&#xe62c;</span>
                    <span class="font_fond_home">我收到的会议通知</span>
<!--                    --><!--                    -->                    <dt class='pre_span pre_span_skin'>2</dt>                                    </a>
                <a class="box skin_e pull-bottom font_next_find_er_left pull-display  pull-relative" href="http://www.omso2o.com/staffsendmeetingnotification.php">
                    <span class="iconfont">&#xe639;</span>
                    <span class="font_fond_home">我发出的会议通知</span>
                                    </a>
            </div>
            <div class="pull-height pull-left parent_find_right">
                <a class="welocme_font_a_right box skin_a pull-difont  pull-relative" href="http://www.omso2o.com/appointment_meetinglist.php">
                    <span class="iconfont">&#xe61e;</span>
                    <span class="welcome_font_bottom">会议室预约及占用状态</span>
                    <dt class='pre_span pre_span_skin'>1</dt>                </a>
            </div>
        </li>
        <li class="welcome_wi_fro_li pull-height" style="padding:0px">
            <div class="welcome_wi_fro_li_find_find_er pull-left">
                <a href="http://www.omso2o.com/meeting_summary_list.php" class="skin_d font_next_find_er_left  pull-relative">
                    <span class="iconfont">&#xe640;</span>
                    <span class="font_fond_home">查阅会议纪要</span>
                    <dt class='pre_span pre_span_skin'>1</dt>                </a>
            </div>
            <div class="welcome_wi_fro_li_find_find_er pull-left">
                <a href="http://www.omso2o.com/admin/meeting_summary_writing_input1.php" class="skin_e font_next_find_er_left  pull-relative">
                    <span class="iconfont">&#xe639;</span>
                    <span class="font_fond_home">录入会议纪要</span>
                </a>
            </div>
        </li>
        <li class="welcome_wi_fro_li pull-height">
            <div class="welcome_wi_fro_li_find_find_er pull-left">
                <a href="http://www.omso2o.com/admin/oms_work_note_input.php" class="skin_e font_next_find_er_left  pull-relative">
                    <span class="iconfont">&#xe641;</span>
                    <span class="font_fond_home">笔记本</span>
                </a>
            </div>
            <div class="welcome_wi_fro_li_find_find_er pull-left">
                <a href="http://www.omso2o.com/work_note.php" class="skin_d font_next_find_er_left  pull-relative">
                    <span class="iconfont">&#xe63f;</span>
                    <span class="font_fond_home">查阅笔记</span>
                                    </a>
            </div>
        </li>
    </ul>
</div>
<div class="welcome_wi_fro pull-height pull-left">
    <ul>
        <li class="welcome_wi_fro_li">
            <a href="http://www.omso2o.com/customer_web_list.php" class="pull-height pull-display skin_d pull-height-ico  pull-relative">
                <span class="iconfont ioc_font_right font_ico_size">&#xe620;</span>
                <span class="pull-left ico_font_left welcome_font_1 pull-left-pa">客户网站</span>
                <dt class='pre_span pre_span_skin'>1722</dt>            </a>
        </li>
        <li class="welcome_wi_fro_li pull-height">
            <div class="welcome_wi_fro_li_find_find_er pull-left">
                <a href="http://www.omso2o.com/library_books_others.php" class="skin_e box font_next_find_er_left pull-display pull-align  pull-relative">
                    <span class="iconfont">&#xe622;</span>
                    <span class="font_fond_home">资料库</span>
                    <dt class='pre_span pre_span_skin'>111</dt>                </a>
            </div>
            <div class="welcome_wi_fro_li_find_find_er pull-left">
                <a href="http://www.omso2o.com/admin/oms_library_books_input.php" class="skin_e box font_next_find_er_left pull-display pull-align  pull-relative">
                    <span class="iconfont">&#xe61f;</span>
                    <span class="font_fond_home">录入或上传资料</span>
                </a>
            </div>
        </li>
        <li class="welcome_wi_fro_li">
            <a href="http://www.omso2o.com/web_list_of_suppliers.php" class="pull-height pull-display skin_a pull-height-ico  pull-relative">
                <span class="iconfont ioc_font_right font_ico_size">&#xe631;</span>
                <span class="pull-left ico_font_left welcome_font_1 pull-left-pa">供应商网站</span>
                <dt class='pre_span pre_span_skin'>187</dt>            </a>
        </li>
        <li class="welcome_wi_fro_li">
<!--            <a href="--><!--/custom_web.php?type=1" class="pull-height pull-display skin_d pull-height-ico  pull-relative">-->
<!--                <span class="iconfont ioc_font_right font_ico_size" style="padding-right:20px;">&#xe62b;</span>-->
<!--                <span class="pull-left ico_font_left welcome_font_1 pull-left-pa">同行网站</span>-->
<!--                --><!--            </a>-->
            <div class="welcome_wi_fro_li_find_find_er pull-left">
                <a href="http://www.omso2o.com/oms_peer_friendship_uint_list.php" class="skin_e font_next_find_er_left  pull-relative">
                    <span class="iconfont">&#xe655;</span>
                    <span class="font_fond_home">友情链接</span>
                    <dt class='pre_span pre_span_skin'>14</dt>                </a>
            </div>
            <div class="welcome_wi_fro_li_find_find_er pull-left">
                <a href="http://www.omso2o.com/oms_peer_friendship_uint_list2.php" class="skin_d font_next_find_er_left  pull-relative">
                    <span class="iconfont">&#xe62b;</span>
                    <span class="font_fond_home">同行网站</span>
                    <dt class='pre_span pre_span_skin'>1</dt>                </a>
            </div>
        </li>
    </ul>
</div>
<div class="welcome_wi_fro pull-height pull-left hidden-md">
    <ul>
        <li class="pull-left welcome_wi_fro_parent_wi">
            <a href="http://www.omso2o.com/staffdoinghimself.php?task_type=1" class="welcome_wi_fro_parent  pull-relative pull-display skin_c pull-bottom box pull-difont">
                <span class="iconfont">&#xe61d;</span>
                <span class="welcome_font_bottom">主办任务</span>
                <dt class='pre_span pre_span_skin'>1</dt>                           </a>
            <a href="http://www.omso2o.com/staffdoinghimself.php?task_type=2" class="font_next_find_er_left  pull-relative skin_d pull-display">
                <span class="iconfont">&#xe635;</span>
                <span class="font_fond_home">协办任务</span>
                                           </a>
        </li>
        <li class="pull-left welcome_wi_fro_parent_wi">
            <a href="http://www.omso2o.com/staffdoinghimself.php?task_type=3" class="font_next_find_er_left  pull-relative skin_d pull-display  pull-bottom">
                <span class="iconfont">&#xe627;</span>
                <span class="font_fond_home">督办任务</span>
                                           </a>
            <a href="http://www.omso2o.com/staffdoinghimself.php?task_type=4" class="font_next_find_er_left  pull-relative skin_d pull-display  pull-bottom">
                <span class="iconfont">&#xe621;</span>
                <span class="font_fond_home">验证任务</span>
                                           </a>
            <a href="http://www.omso2o.com/staffdoinghimself.php?task_type=5" class="font_next_find_er_left  pull-relative skin_d pull-display  pull-bottom">
                <span class="iconfont">&#xe634;</span>
                <span class="font_fond_home">须知任务</span>
                                           </a>
        </li>
        <li class="welcome_wi_fro_li">
            <a href="http://www.omso2o.com/all_tasks_already_arranged.php" class="pull-height pull-display skin_a pull-height-ico  pull-relative">
                <span class="iconfont ioc_font_right font_ico_size" style="padding-right:20px;">&#xe63d;</span>
                <span class="pull-left ico_font_left welcome_font_1 pull-left-pa">已布置的任务</span>
                                <dt class='pre_span'>1</dt>            </a>
        </li>
        <li class="welcome_wi_fro_li pc_hide">
            <a href="javascript:" class="show_opinion_xm pull-height pull-display skin_c pull-height-ico  pull-relative">
                <span class="iconfont ioc_font_right font_ico_size" style="padding-right:20px;">&#xe646;</span>
                <span class="pull-left ico_font_left welcome_font_1 pull-left-pa">用户反馈</span>
            </a>
        </li>
    </ul>
</div>
</div>
        <div class="show_opinion">
            <div class="show_opinion_box">
                <h3><span style="margin-left:10%;font-family:微软雅黑;font-size: 20px; font-weight: bold;">欢迎您给我们提出意见与建议，您留下的每个字都将被用于改善我们的平台质量和服务。</span></h3>
                <form action="" id="formId_opinion" onsubmit="return subxm_opinion()">
                    <textarea name="opinion_and_suggestion_content" id="yijianjianyi" rows="10" cols="30"></textarea>
                    <div>
                        <br>
                        <a class="show_opinion_close">关闭</a>
                        <button type="submit" class="show_opinion_submit">提交</button>
                    </div>
                </form>
            </div>
        </div>
<script>
    var mesnum = parseInt($('.mes_radio').html());//消息的个数
    //显示
    $(".show_opinion_xm").click(function(){
        $(".show_opinion").show(400)
        // $("body").css("overflow","hidden")
    })
    //关闭
    $(".show_opinion_close").click(function(){
        var yijianjianyi= $("#yijianjianyi").val();
        if (yijianjianyi !== '') {
            if (confirm('您确定要关闭吗？关闭后输入的信息将会被清空。')) {
                $(".show_opinion").hide(400)
                // $("body").css("overflow","auto")
                $("#yijianjianyi").val("")
            }
        }else{
            $(".show_opinion").hide(400)
        }
    })
    //提交
    $(".show_opinion_submit").click(function(){
        var yijianjianyi= $("#yijianjianyi").val();
        if (yijianjianyi == '')
        {
            alert ('请输入意见建议！');
            return false;
        }else{
            $(".show_opinion").hide(400)
        }
    })
    function IsPCc() {
        var userAgentInfo = navigator.userAgent;
        var Agents = ["Android", "iPhone",
            "SymbianOS", "Windows Phone",
            "iPad", "iPod"];
        var flag = true;
        for (var v = 0; v < Agents.length; v++) {
            if (userAgentInfo.indexOf(Agents[v]) > 0) {
                flag = false;
                break;
            }
        }
        return flag;
    }
    $('#linksmm').click(function(){
        if(!IsPCc())
            window.open('emailSystem/mIndex_mb.php?loginFrom=workpage')
        else
            window.open('emailSystem/mIndex.php?loginFrom=workpage')
    })
    function subxm_opinion()
    {
        $.ajax({
            url:'http://www.omso2o.com/suggestions_add_action.php',
            data:$('#formId_opinion').serialize(),// 你的formid
            type:'post',
        });
        return false;
    }
</script>
<!--        意见建议结束-->
    <div style="width: 100%;height:200px">
    </div>
<!DOCTYPE html>
<html>
<head>
<!-- <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no" /> -->
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/qrcode.js"></script>
<style>
	#contentQrcode{
		z-index:11;
		position:absolute;
		top:50%;
		left:50%;
		transform:translate(-50%,-50%);
		display:none;
		width:350px;
		height:500px;
		background-color:#fff;
		border-radius:3px;
	}
	#qrcodeXSX{
		width:266px;
		height:266px;
		border:3px solid #369;
		margin:0 auto;
	}
	#touxiang{
		height: 130px;
		width:260px;
		margin:0 auto;
		vertical-align: middle;
		margin-top: 30px;
		position:relative;
	}
	#beijingQrcode{
		height:100%;
		width:100%;
		background-color:#000;
		position:fixed;
		z-index:10;
		opacity:0.4;
		display:none;
		top:0;
	}
	#staffQrcode{
		position:relative;
		top:0px;
	}
</style>
</head>
<body>
<div id="beijingQrcode"></div>
<input id="textxsx" type="hidden" value="1--409" style="width:80%" /><br/>
<div id="contentQrcode">
	<div id="touxiang">
		<img  src="https://www.omso2o.com/upload/photos/20150707105356.jpg" style="width:100px;height:100px;border-radius:5px;float:left"/>
		<span style="line-height: 30px;height: 30px;width:100px;float:left">张冬林</span>
		<span style="line-height: 30px;height: 30px;width:100px;float:left">程序员</span>
	</div>
	<div id="qrcodeXSX" style="">
	</div>
	<br>
	<p style="text-align:center;font-size:16px;color:#666;">上海斯瑞科技有限公司</p>
</div>
<script type="text/javascript">
var qrcode = new QRCode(document.getElementById("qrcodeXSX"), {
	width : 260,
	height : 260,
});

function makeCode () {
	var elText = document.getElementById("textxsx");
	// if (!elText.value) {
	// 	alert("Input a text");
	// 	elText.focus();
	// 	return;
	// }
	qrcode.makeCode(elText.value);
}

// $("#textxsx").on("blur", function () {
// 	makeCode();
// 	$('#contentQrcode').slideDown()
// 	$('#beijingQrcode').slideDown()
// })
$('#show_img2').click(function () {
    makeCode();
    $('#contentQrcode').slideDown()
    $('#beijingQrcode').slideDown()
})

$('#guanbi').click(function(){
	$('#contentQrcode').slideUp()
	$('#beijingQrcode').slideUp()
})

$('#qrcodeXSX').mouseover(function(){
	var title = "上海斯瑞科技有限公司";
	$('#qrcodeXSX').attr('title',title)
})
$('#beijingQrcode').click(function(){
	$('#contentQrcode').slideUp()
	$('#beijingQrcode').slideUp()
})
</script>
</body><div class="footer">
<img src="http://www.omso2o.com/image/2015-04-21_122729.png" alt="" width="100%">
</div>
</body>
</html>