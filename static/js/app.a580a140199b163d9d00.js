webpackJsonp([0],[,,,function(e,n,t){"use strict";var s=t(5);t.d(n,"a",function(){return a});var i=function(e){return e=e.replace(/\n/g,"&lbrg").replace(/\t/g," ")},a=function(e){if(e)return e=e.replace(/&lbrg/g,"<br>").replace(/face\[([^\s\[\]]+?)\]/g,function(e){var n=e.replace(/^face/g,"");return'<img alt="'+n+'" title="'+n+'" src="'+faces[n]+'">'}).replace(/【(.*?)】/g,function(e,n){var t=s.a.find(function(e){return e.name==n});return'<img width="20px"  src="/static/emoji/'+t.num+'@2x.png" alt="'+t.name+'" />'})};n.b=i},,function(e,n,t){"use strict";var s=[{dh:"weiXiao",name:"微笑",num:"001"},{dh:"pieZui",name:"撇嘴",num:"002"},{dh:"se",name:"色",num:"003"},{dh:"faDai",name:"发呆",num:"004"},{dh:"deYi",name:"得意",num:"005"},{dh:"liuLei",name:"流泪",num:"006"},{dh:"haiXiu",name:"害羞",num:"007"},{dh:"biZui",name:"闭嘴",num:"008"},{dh:"shui",name:"睡",num:"009"},{dh:"daKu",name:"大哭",num:"010"},{dh:"ganGa",name:"尴尬",num:"011"},{dh:"faNu",name:"发怒",num:"012"},{dh:"tiaoPi",name:"调皮",num:"013"},{dh:"ciYa",name:"呲牙",num:"014"},{dh:"jingYa",name:"惊讶",num:"015"},{dh:"nanGuo",name:"难过",num:"016"},{dh:"ku",name:"酷",num:"017"},{dh:"jiong",name:"囧",num:"018"},{dh:"zhuaKuang",name:"抓狂",num:"019"},{dh:"tu",name:"吐",num:"020"},{dh:"touXiao",name:"偷笑",num:"021"},{dh:"keAi",name:"可爱",num:"022"},{dh:"baiYan",name:"白眼",num:"023"},{dh:"aoMan",name:"傲慢",num:"024"},{dh:"jiE",name:"饥饿",num:"025"},{dh:"kun",name:"困",num:"026"},{dh:"jingKong",name:"惊恐",num:"027"},{dh:"liuHan",name:"流汗",num:"028"},{dh:"hanXiao",name:"憨笑",num:"029"},{dh:"youXian",name:"悠闲",num:"030"},{dh:"fenDou",name:"奋斗",num:"031"},{dh:"zhouMa",name:"咒骂",num:"032"},{dh:"yiWen",name:"疑问",num:"033"},{dh:"xu",name:"嘘",num:"034"},{dh:"yun",name:"晕",num:"035"},{dh:"fengLe",name:"疯了",num:"036"},{dh:"shuai",name:"衰",num:"037"},{dh:"kuLou",name:"骷髅",num:"038"},{dh:"qiaoDa",name:"敲打",num:"039"},{dh:"zaiJian",name:"再见",num:"040"},{dh:"caHan",name:"擦汗",num:"041"},{dh:"kouBi",name:"抠鼻",num:"042"},{dh:"guZhang",name:"鼓掌",num:"043"},{dh:"qiuDaLe",name:"糗大了",num:"044"},{dh:"huaiXiao",name:"坏笑",num:"045"},{dh:"zuoHengHeng",name:"左哼哼",num:"046"},{dh:"youHengHeng",name:"右哼哼",num:"047"},{dh:"haQian",name:"哈欠",num:"048"},{dh:"bishi",name:"鄙视",num:"049"},{dh:"weiQu",name:"委屈",num:"050"},{dh:"kuaiKu",name:"快哭",num:"051"},{dh:"yinXian",name:"阴险",num:"052"},{dh:"qinQin",name:"亲亲",num:"053"},{dh:"xia",name:"吓",num:"054"},{dh:"keLian",name:"可怜",num:"055"},{dh:"caiDao",name:"菜刀",num:"056"},{dh:"xiGua",name:"西瓜",num:"057"},{dh:"piJiu",name:"啤酒",num:"058"},{dh:"lanQiu",name:"篮球",num:"059"},{dh:"pingPa",name:"乒乓",num:"060"},{dh:"kaFei",name:"咖啡",num:"061"},{dh:"fan",name:"饭",num:"062"},{dh:"zhuTou",name:"猪头",num:"063"},{dh:"meiGui",name:"玫瑰",num:"064"},{dh:"diaoXie",name:"凋谢",num:"065"},{dh:"zuiChun",name:"嘴唇",num:"066"},{dh:"aiXin",name:"爱心",num:"067"},{dh:"xinSui",name:"心碎",num:"068"},{dh:"danGao",name:"蛋糕",num:"069"},{dh:"shanDian",name:"闪电",num:"070"},{dh:"zhaDan",name:"炸弹",num:"071"},{dh:"dao",name:"刀",num:"072"},{dh:"zouqiu",name:"足球",num:"073"},{dh:"piaoChong",name:"瓢虫",num:"074"},{dh:"bianBian",name:"便便",num:"075"},{dh:"yueLiang",name:"月亮",num:"076"},{dh:"taiYan",name:"太阳",num:"077"},{dh:"liWu",name:"礼物",num:"078"},{dh:"baoBao",name:"抱抱",num:"079"},{dh:"qiang",name:"强",num:"080"},{dh:"ruo",name:"弱",num:"081"},{dh:"woshou",name:"握手",num:"082"},{dh:"shengLi",name:"胜利",num:"083"},{dh:"baoQuan",name:"抱拳",num:"084"},{dh:"gouYin",name:"勾引",num:"085"},{dh:"quanTou",name:"拳头",num:"086"},{dh:"chaJin",name:"差劲",num:"087"},{dh:"aiNi",name:"爱你",num:"088"},{dh:"NO",name:"NO",num:"089"},{dh:"OK",name:"OK",num:"090"},{dh:"aiQing",name:"爱情",num:"091"},{dh:"feiWen",name:"飞吻",num:"092"},{dh:"tiaoTiao",name:"跳跳",num:"093"},{dh:"faDou",name:"发抖",num:"094"},{dh:"ouHuo",name:"怄火",num:"095"},{dh:"zhuanQuan",name:"转圈",num:"096"},{dh:"keTou",name:"磕头",num:"097"},{dh:"huiTou",name:"回头",num:"098"},{dh:"tiaoSheng",name:"跳绳",num:"099"},{dh:"jiDong",name:"激动",num:"100"},{dh:"lunWu",name:"乱舞",num:"101"},{dh:"xianWen",name:"献吻",num:"102"},{dh:"youTaiJi",name:"左太极",num:"103"},{dh:"touXiang",name:"投降",num:"104"}];n.a=s},,function(e,n,t){"use strict";var s=t(4),i=t.n(s),a=t(2),o=t(0),c=t(15),u=t(3),r=t(19);a.a.use(o.a);var m=new Date,A=new o.a.Store({state:{user:{id:5,name:"coffce",img:"../static/images/1.jpg"},friends:[{groupname:"公司全员",id:1,list:[{username:"测试1",id:3,avatar:"../static/images/1.jpg",status:"offline"}]},{groupname:"我的好友",id:2,list:[]}],group:[],sessions:[{id:1,user:{name:"示例介绍",img:"../static/images/2.png"},messageNum:0,type:"message",messages:[{id:1,content:"Hello，这是一个基于Vue + Vuex + Webpack构建的简单chat示例，聊天记录保存在localStorge, 有什么问题可以通过Github Issue问我。",date:m},{id:2,content:"项目地址: https://github.com/coffcer/vue-chat",date:m}]},{id:4,user:{name:"webpack",img:"../static/images/3.jpg"},type:"message",messageNum:1,messages:[]}],indexTab:1,currentSession:{id:"",name:"",img:""},currentSessionType:"message",filterKey:"",searchFriend:[],userSet:{voice:0,desktop:0}},mutations:{INIT_DATA:function(e,n){e.user.id=n.data.mine.id,e.user.name=n.data.mine.username,e.user.img=n.data.mine.avatar?n.data.mine.avatar:"../static/images/1.jpg",e.friends=n.data.friend,e.group=n.data.group;var t=localStorage.getItem("vue-chat-session"),s=localStorage.getItem("chat-set");t&&(e.sessions=JSON.parse(t)),s&&(e.userSet=JSON.parse(s))},SEND_MESSAGE:function(e,n){var s=e.sessions,i=e.currentSession,a=e.user,o=(e.friends,e.currentSessionType,{}),c=s.find(function(e){return parseInt(e.id)===parseInt(n.dialogueId)&&e.type===n.type});n.content=t.i(u.a)(n.content),o=n.senderId==a.id?{id:n.id,content:n.content,date:new Date,self:!0}:{id:n.id,name:n.name,img:n.img,content:n.content,date:new Date},c?(n.dialogueId!=i.id&&c.messageNum++,c.messages.length>10&&c.messages.shift(),c.messages.push(o)):(c={id:n.dialogueId,user:{name:n.sessionName,img:n.sessionImg},messageNum:0,type:n.type,messages:[]},n.dialogueId!=i.id&&c.messageNum++,c.messages.push(o),s.unshift(c))},SELECT_SESSION:function(e,n){var t=e.sessions.find(function(e){return parseInt(e.id)===parseInt(n.id)&&e.type==n.type});t&&0!=t.messageNum&&c.a.sendMessage({type:"mes_close",to_uid:n.id,session_no:n.id,message_type:n.type}),t&&0!=t.messageNum&&(t.messageNum=0),e.currentSession.id=n.id,e.currentSession.name=n.name,e.currentSession.img=n.img,e.currentSessionType=n.type},DELSESSION:function(e,n){e.sessions.splice(n,1)},SELECT_TAB:function(e,n){e.indexTab=n},SET_FILTER_KEY:function(e,n){e.filterKey=n},USER_SET:function(e,n,t){console.log(e.userSet)}},actions:{initData:function(e,n){var t=e.commit,s=e.dispatch;t("INIT_DATA",n),c.a.on("open",function(){c.a.sendMessage({type:"login",oms_id:n.data.mine.oms_id,uid:n.data.mine.id,header_img_url:n.data.mine.avatar,token:n.data.mine.token,client_name:n.data.mine.username,room_id:n.data.mine.oms_id})}),c.a.on("comeMessage",function(e){var n=e.type;s(n,e)})},sendMessage:function(e,n){var s=(e.commit,e.state),i={};n.dialogueId=s.currentSession.id,n.senderId=s.user.id,n.name=s.currentSession.name,n.img=s.currentSession.img,n.type=s.currentSessionType,n.content=t.i(u.b)(n.content),i={type:"sayUid",to_uid:s.currentSession.id,groupId:0,accept_name:s.currentSession.name,message_type:s.currentSessionType,mes_types:"text",content:n.content},"message"!=s.currentSessionType&&(i.session_no=s.currentSession.id),c.a.sendMessage(i)},acceptMes:function(e,n){e.commit,e.state},selectSession:function(e,n){(0,e.commit)("SELECT_SESSION",n)},delSession:function(e,n){(0,e.commit)("DELSESSION",n)},selectTab:function(e,n){return(0,e.commit)("SELECT_TAB",n)},search:function(e,n){return(0,e.commit)("SET_FILTER_KEY",n)},userSet:function(e,n,t){return(0,e.commit)("USER_SET",n,t)}},modules:{events:r.a}});A.watch(function(e){return e.sessions},function(e){console.log("CHANGE: ",e),localStorage.setItem("vue-chat-session",i()(e))},{deep:!0}),n.a=A},function(e,n,t){t(24),t(25);var s=t(1)(t(9),t(46),"data-v-22a805ff",null);e.exports=s.exports},function(e,n,t){"use strict";Object.defineProperty(n,"__esModule",{value:!0});var s=t(0),i=t(2),a=t(52),o=t(41),c=t.n(o),u=t(43),r=t.n(u),m=t(42),A=t.n(m),d=t(45),l=t.n(d),g=t(44),h=t.n(g);i.a.use(a.a),n.default={name:"app",components:{Card:c.a,menuList:r.a,List:A.a,Message:h.a,TextAre:l.a},computed:t.i(s.b)({complete:function(e){return e.events.complete}}),data:function(){return{chatMainShow:!1}},created:function(){var e=this;this.$http.get("./static/omsIm/demo/json/json.js").then(function(n){e.$store.dispatch("initData",n.data)}).catch(function(e){console.log(e)}),this.$http.get("./static/omsIm/demo/json/json1.js").then(function(n){e.$store.dispatch("acceptMes",n.data)}).catch(function(e){console.log(e)})},methods:{transf:function(){}}}},function(e,n,t){"use strict";Object.defineProperty(n,"__esModule",{value:!0});var s=t(0);n.default={computed:t.i(s.b)({user:function(e){return e.user},filterKey:function(e){return e.filterKey}}),methods:{onKeyup:function(e){this.$store.dispatch("search",e.target.value)}}}},function(e,n,t){"use strict";Object.defineProperty(n,"__esModule",{value:!0});var s=t(0),i=t(2);n.default={data:function(){return{mousedownShow:!1,mousedowInfo:{id:1,index:1}}},methods:{selectSession:function(e,n,t,s){var i={id:e,type:n,name:t,img:s};this.$store.dispatch("selectSession",i)},mousedown:function(e,n,t){var s=this;3==t.which&&(t.sta,this.mousedownShow=!0,this.$nextTick(function(){s.$refs.recentContactsDel.style.top=t.pageY+2+"px",s.$refs.recentContactsDel.style.left=t.pageX+2+"px"}),this.mousedowInfo.id=e.id,this.mousedowInfo.index=n)},removeZjlxr:function(){this.$store.dispatch("delSession",this.mousedowInfo.index)}},computed:t.i(s.b)({currentId:function(e){return e.currentSession.id},sessions:function(e){return e.sessions},searchFriend:function(e){if(""==e.filterKey)return[];var n=[];for(var t in e.friends)e.friends[t].list&&(n=n.concat(e.friends[t].list.filter(function(n){return n.username.includes(e.filterKey)})));return n},indexTab:function(e){return e.indexTab},friends:function(e){return e.friends},group:function(e){return e.group}}),created:function(){var e=this;document.addEventListener("click",function(n){e.mousedownShow&&(e.mousedownShow=!e.mousedownShow)})}},i.a.directive("oncontextmenu",{bind:function(e){e.oncontextmenu=function(){return!1}}})},function(e,n,t){"use strict";Object.defineProperty(n,"__esModule",{value:!0});var s=t(4),i=t.n(s),a=t(0);n.default={data:function(){return{selfInfo:!1,setShow:!1,sets:{voice:["开启声音","关闭声音"],desktop:["开启桌面通知","关闭桌面通知"]}}},computed:t.i(a.b)({user:function(e){return e.user},filterKey:function(e){return e.filterKey},indexTab:function(e){return e.indexTab},userSet:function(e){return e.userSet}}),methods:{selectTab:function(e){this.$store.dispatch("selectTab",e)},showInfo:function(e){this.selfInfo||(this.selfInfo=!this.selfInfo)},setUser:function(e){var n=this.userSet;n[e]=0==n[e]?1:0,this.$store.dispatch("userSet",e,n[e]),localStorage.setItem("chat-set",i()(n))}},created:function(){var e=this;document.addEventListener("click",function(n){e.selfInfo&&(e.selfInfo=!e.selfInfo),e.setShow&&(e.setShow=!e.setShow)})}}},function(e,n,t){"use strict";Object.defineProperty(n,"__esModule",{value:!0});var s=t(0),i=t(2),a=t(3);n.default={computed:t.i(s.b)({user:function(e){return e.user},session:function(e){return e.sessions.find(function(n){return n.id===e.currentSession.id})},dialogName:function(e){return e.currentSession.name}}),filters:{time:function(e){return"string"==typeof e&&(e=new Date(e)),e.getHours()+":"+e.getMinutes()}},methods:{content:function(e){return t.i(a.a)(e)}}},i.a.directive("scroll-bottom",function(e){i.a.nextTick(function(){e.scrollTop=e.scrollHeight-e.clientHeight})})},function(e,n,t){"use strict";Object.defineProperty(n,"__esModule",{value:!0});var s=t(0),i=t(17),a=t(16),o=t(5);n.default={data:function(){return{content:"",selShow:!1,currentSel:1,emShow:!1,emojis:o.a}},computed:t.i(s.b)({start:function(e){return e.currentSession.id}}),methods:{onKeydown:function(e){if(0==this.currentSel)e.ctrlKey&&13===e.keyCode&&this.content.length&&this.send();else{if(e.shiftKey||e.ctrlKey)return;13===e.keyCode&&this.content.length&&(e.preventDefault(),this.send())}},send:function(){var e={content:this.content};this.content="",this.$store.dispatch("sendMessage",e)},changeSend:function(e){this.currentSel=e,localStorage.setItem("currentSel",e)},emojiInsert:function(e){t.i(i.a)(this.$refs.textarea,"【"+e.name+"】"),this.content=this.$refs.textarea.value},fileUpload:function(e){var n="";if(void 0===(n=void 0===e.target?e[0]:e.target.files[0]))return!1;/image\/\w+/.test(n.type)?console.log("img"):console.log("noimg");Math.floor(n.size/1024);a.a.Qiniu_upload({key:"/44/44/44/44/4",tokenurl:"/uptoken.php",f:n,QiniuUrl:"http://up.qiniu.com"},function(e){e.error?console.log(4444):console.log(e)})}},created:function(){var e=this,n=localStorage.getItem("currentSel");document.addEventListener("click",function(n){e.emShow&&(e.emShow=!e.emShow),e.selShow&&(e.selShow=!e.selShow)}),null!=n&&(this.currentSel=parseInt(n))}}},function(module,__webpack_exports__,__webpack_require__){"use strict";var __WEBPACK_IMPORTED_MODULE_0_babel_runtime_core_js_json_stringify__=__webpack_require__(4),__WEBPACK_IMPORTED_MODULE_0_babel_runtime_core_js_json_stringify___default=__webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_babel_runtime_core_js_json_stringify__),ws=new WebSocket("ws://"+document.domain+":7272"),callbacks={},act={COME_MESSAGE:function COME_MESSAGE(e){var data=eval("("+e.data+")");callbacks.comeMessage&&callbacks.comeMessage(data)},SEND_MESSAGE:function(e){var n=__WEBPACK_IMPORTED_MODULE_0_babel_runtime_core_js_json_stringify___default()(e);ws.send(n)},OPEN:function(){callbacks.open&&callbacks.open()}},websocket={on:function(e,n){callbacks[e]=n},sendMessage:function(e){act.SEND_MESSAGE(e)}};ws.onopen=act.OPEN,ws.onmessage=act.COME_MESSAGE,ws.onclose=function(){console.log("连接关闭，定时重连"),connect()},ws.onerror=function(){console.log("出现错误")},__webpack_exports__.a=websocket},function(e,n,t){"use strict";var s=function(){this.v=1};s.prototype.getQiniuToken=function(e,n){var t,s;t=window.XMLHttpRequest?new XMLHttpRequest:new ActiveXObject("Microsoft.XMLHTTP"),t.onreadystatechange=function(){4==t.readyState&&(200==t.status?n(t.responseText):alert("get uptoken other than 200 code was returned"))},s=e,t.open("GET",s,!0),t.send()},s.prototype.Qiniu_upload=function(e,n){var e=e||{key:"",tokenurl:"",f:"",QiniuUrl:""};this.getQiniuToken(e.tokenurl,function(){var t=new XMLHttpRequest;t.open("POST",e.QiniuUrl,!0);var s;s=new FormData,null!==e.key&&void 0!==e.key&&s.append("key",e.key),console.log(e.token),s.append("token",e.token),s.append("file",e.f);t.onreadystatechange=function(e){if(4==t.readyState&&200==t.status&&""!=t.responseText){var s=JSON.parse(t.responseText);return console.log(44),"function"==typeof n&&n(s),!1}if(200!=t.status&&t.responseText){var s=JSON.parse(t.responseText);return console.log(55),"function"==typeof n&&n(s),!1}},t.send(s)})};var i=new s;n.a=i},function(e,n,t){"use strict";var s=function(e,n){if(document.selection)e.focus(),sel=document.selection.createRange(),sel.text=n,sel.select();else if(e.selectionStart||"0"==e.selectionStart){var t=e.selectionStart,s=e.selectionEnd,i=e.scrollTop;e.value=e.value.substring(0,t)+n+e.value.substring(s,e.value.length),i>0&&(e.scrollTop=i),e.focus(),e.selectionStart=t+n.length,e.selectionEnd=t+n.length}else e.value+=n,e.focus()};n.a=s},function(e,n,t){"use strict";Object.defineProperty(n,"__esModule",{value:!0});var s=t(2),i=t(8),a=t.n(i),o=t(7);s.a.config.productionTip=!1,new s.a({el:"#app",template:"<App/>",components:{App:a.a},store:o.a})},function(e,n,t){"use strict";var s=(t(3),function(){var e=document.createElement("audio");e.src="/static/omsIm/source/layui/css/modules/layim/voice/default.wav",e.play()}),i={complete:!1},a={SELF_LOGIN:function(e,n){e.complete=!0},LOGIN:function(e){console.log(e)},SAYUID:function(e,n){console.log(n)}},o={selfLogin:function(e,n){e.state;(0,e.commit)("SELF_LOGIN",n)},login:function(e){return!1},ping:function(e){},say_uid:function(e,n){var t=(e.state,e.commit),i=(e.rootState,void 0),a=void 0,o=void 0,c=void 0,u=void 0;"message"==n.mestype?(o=n.accept_name,i=n.accept_name,c=n.card_image,u=n.card_image,a=n.sender_id):(c=n.card_image,u="../../static/chat/images/ren.png",o=n.group_name,i=n.accept_name,a=n.session_no);var r={id:n.id,content:n.message_content,senderId:n.sender_id,dialogueId:a,sessionName:o,name:i,sessionImg:u,img:c,type:n.mestype};s(),t("SEND_MESSAGE",r)},mesClose:function(e){},resSayUid:function(e,n){var t=(e.state,e.commit),s=e.rootState,i=void 0,a=void 0,o=void 0,c=void 0;"message"==n.mestype?(a=s.currentSession.name,i=s.currentSession.img,o=s.currentSession.name,c=s.currentSession.img):"groupMessage"==n.mestype&&(a=n.group_name,i="../../static/chat/images/ren.png",o=s.currentSession.name,c=s.currentSession.img);t("SEND_MESSAGE",{id:n.id,content:n.message_content,senderId:n.sender_id,dialogueId:s.currentSession.id,name:o,sessionName:a,sessionImg:i,img:c,type:n.mestype})}},c={state:i,actions:o,mutations:a};n.a=c},,,,,function(e,n){},function(e,n){},function(e,n){},function(e,n){},function(e,n){},function(e,n){},function(e,n){},,,function(e,n){e.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAACuUlEQVRYR72XjTEEQRCFnwyIABFwESACRIAInAgQASJABE4EiAARIAMZUN/WvK25MT+7V1e6asqVm+l+/fr3VjReNiStS9pNnj5L+pL0OUblysDLGD2VdCCJz5aX8GEn+h8AZpJuhoBpAViVdCXpOHiHYg7e5gRWAMmBpTtJZ5K+S47WAKDkVhJ3pkHZQMK6a4C+lvQj6bAEugQAg3h+H4wXPWgggkFAHAU9hGVOcgBsHOp4vAy5kHQewjGnMwVADJ8kXUri0TLFIPbicMQAoOtD0mOI3zKNWxdJuS9p04kZA4AaEocyWzTmLdA4SZkChFB3GY5gFO9PFsj2ltH0e+cYLHwaAN6nTSZ+yHcPgRli+Fawuh1yCE8pPXpGTtyspgaAQppLR0tG+M7drpagTjRU0CXTdh3nwpakCQBMfw0xuUFTQmr3zBT3auH0vU0AuPRabRl6Sc7WsMEhQlAKk1mgQ+5h1LS1AIxNttZ9AJwZAJTg4X8KDM0MgOYwKViHdsC1qE+fuyLWCnp7AK7LUgi6WFVGcIm1Vm71IWhd7Gt2ZHxavaVPwlYZosj9ewyGGvC5MkQp8eBQ76kY4JjxPNduMzqZBeTItuNOKbI00J9zYoVDZoWbVg0wiQ2zF+kwqrVZUAOytOfF+yObVI5NnMNZHOoqK858vmDz7Wd1hgpiB3IWToeNax2dYXFFeWkIeedgNesWnnQhIXHYiOj3JUEJlcNx8/IwY2jVdgmA8a7fOdLaR+Fr2OlLk3FMJcR3YQ6GaXj9nMg1HycRD0ikZYiN/0niUvczCCjj0aIrGuFijJM72QqqTUDCQUzpWHhA4gwFgmHoJowe+dnx3BrBKEIJByD+acbmnBM8pWvyF90A5yz00yw2QNailNCwSlk8IeMfrO+hV9Avmoy1GMh5ibH4cAcg8RmcuL/eI67B6V3F9AAAAABJRU5ErkJggg=="},function(e,n){e.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAADb0lEQVRYR8VXXVLTYBQ9N2UGpnmwPGiL0w51BcAKgEcNjmUF1hWAK6CswLIC6wooI9VHYAXWFVimHU31gTJjGRhNrnPz1y9pmlApQ546SXq/8517vnNPCA980QOvj6kAfP5RKFsW1olQDgG3eQCyz4zir/a0G0oFcHKRy11dLeyAuQLQatICzDwgoqaWwf7zJbNzGzCJAI57+V0w9ogo5xRjvgRwClBop0woE7ABYFlZtJHVr99uLg4GSUBiAciuh8OFQ6+oLHxkg+ovS+ZpUjGvRTUStogeCSMM2k763xgAh/Lh/IlH9zlgVabtrVPj90IThHUXsLU2qcYYgONevk2gFQZ/1fWbjTQKkxg57hUaBLx2tWFvxoEIAWh1CzUQ9maxuA/MBwFw2yj216KAAwBO//7yF1dwkym7jbLVd9yWLohoRaBvjKLZUJ8HAETxBHoH8IFR7O9Ou1DS+5++P6mwrR3GsaACcHo/y92roFrd/EBOhpbBM9UjAgCtXoHlD0bRDOlCWsPA8osl80wtmHTftqxcVHCtbuFUToXN2FSPZRgA48womWIozhXWxag1k+5/7BY2NMKJa1rYN0pmza/lCzyqgwgDYaVGCgbgJt0f6chxzSOj1K/cHoDXo2gLnGPEKNtATaUu7r5rQPMN15rtqtqG9BZ4PbpvEUbrqy2oAnjPwIetoim/Z3YFx5D5iIkGmQxq/kkIAHj0deSozJqFVq8go3lZhOk67WiToSOnmFE7q99s3mUOjFkx40ybQ9W28E1mw1apvyjvjA2jVjffBNErca27ggjmAPOlNkerQrvPhi/22HE8HM6fuq6IDmBt/884DvKEhBiyN/waviNOBCC0eIGkLqPUoUmzt188/dlMU6UbSHgHjKqXokJ5Qp5LCwCcG0XTyZWJkazVy9cBkoKOqzluBl6B5kUyCaMa5Zg5RyAxHSWs8kFWv6mpOgraq7hkMgAvH4BZ0k05LZTKzgBuahmqR0OpYsXnWf161Qd2OwAe9xJUCFQHh2M5Ew8AutD166O4kzPaeVgP6S3wGYgZLjEe780gSc2jixn7MqBc8GF7TgXgDB3wLsiuJZ0EV1y8C1cHajRXkISno/8g9cMkTflxz1u9x84HTFb/05Hh5PhKZDzfK4AoKJkFlqUN4r4P7oWBaVh7cAD/AEs9Hz/qVD98AAAAAElFTkSuQmCC"},function(e,n){e.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAABU0lEQVRYR+1X203EMBDcrQDogBKgg4wbATq4DoAKgA6oJAMdHBVwVHC5ChZtdIny8OUcKSJ82H+RdzaTcTTjVVl56crvlygBkoWZWYycqh4AbGN7UzivDyF8DnE9AiSfROQxQZVKRNAQIXkvIi8icpmAfQDw3tS1BEg6eJ/QoCnZArj1h7IsK1W9SMRWAK5iBAoRYWKTugxA/QEko8d1qleD8/2uAplAViArsLoC1yLyneoDZvYVQrhZzAeOjdwi7xJJtJZKcnO04hToMwC3/HqNwoikf9U5T98B2HXflohzG+4FWZSAmU36uqr+xAgk4EZJOkzDvziCNwB+ZP0jIDnrJxSRNg1zGGUFsgJZgaUUmHUt76bhnGu5mR1CCG3WDK04KdW8iaoW3cHEzF7PzQZH3CY6mAySzS8np9Yo0ZpCH82m8hjAx3D/fw6nKbeKpWp+AchGFzByLeL6AAAAAElFTkSuQmCC"},function(e,n){e.exports="data:image/gif;base64,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"},function(e,n){e.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAEjUlEQVRoQ+1aXU4bMRD2BOe59AQl0o5fCyconKDhBC0nKD1B6QmanqD0BKUnaDhB6au9UuAG8ByUqSbyUmPsXc9uUvWhlhAo+Ge+mc/z54Da4LDW7gPAG6XUvt/2kIiuAOCWiK4B4AIRv2/wSAWb2Kyu6zer1eoMAPYK9rtVSs201p8nkwn/PWgMArBYLPaWy+U3AGg0LhGGhT9GxLlkUTy3NwBPlx9Kqd2MADdKqWv/v1c5IYnoxBhz3hdELwAtwl8CwGxnZ2ce04PXKKVO/R15JO8QEGIAi8Vi9/7+fhFp/k4pNS2hg6fdBQC8jLR+VLJ+MIWsteeRFm+01oeTyaShSycbvBKYNq+byeyljDGTzsXRBJEFWHte+802d0R0aIy5kh7MIJbL5Ty0RB8qiQDUdT0joneBsB8R8UwqfDPfOXeolGJHsB4cM4wxB5L9RACstYvQ12utnw/15dZaDnQP90FrPZHQUQTAOUeBdr4j4lSirdRc59ypUupT8D/RZS4GEJtbKTWIPjkaSff9D2ADFHp0kbdmgdiFEtFXY8zboQCstW8B4MvW7wAf4JzjBOyZP+wWEZ8PBeCcu4gC2oEkrhTfARY0jsJ9Ak8IOBEYbxCxJCV/2EYEIBF4rsfj8UHfWOCc+8Y5VADqPSLOJFYVAfA04vw9TI/niHgkOdTvwxH8Q7DuTmu9J1WGGIBPpX+GAnMKMB6Pj0oPt9Z+AYBHDgAAjquq4vsgGmIA/i7EnoM/7iwVc6XnEI/WC4CnQJwChJpjmnGG2tS87Ou5oHlSvQ0Rng/sDSCwBF+6xrWKzC8NWqnNBwFgShARA8jVxa2AuIgZjUZnVVV9lSJv5vcCwO6UiPgiinx2TkjfMzr5KyWlc45TX+Z/anCFxvUuN7GuiGh9BwBgl4j2iWgPANjv5yg3Q8T3EmuILJByf/6wdTei1A3WdT0lIlbCk3YLEZ0bY05KQRQDSERNLgF/AcBpH9N7T8ZUnCU6FMXBsQhAohZm4TeSjfrinkFwT/VhAMDnqqpyVP0zr8tU3tycszyMoUlc6sxEacl3pzM6t1og1cTalOZTIBLZbmey2ArAORcnXJeIyFF1a8M5FyeLrbV3FkBG+6Jiow/KRLJ461styVZ8FkBc6m2TOjFQSeGUBRCbkoi2rv0GSMIKWeq2AQibWOJSrw99wjXOOW4Wv/CfZevvJIBYA6U+eajQ4fo49uQYkASwrS6cBGDCAyZbjjkAg/qVEkFzcxMNhORT1D8LIJEBJDsWpRTi993jTWi2dI9E8lhOIZ8phl04/miutT6R9O5LhQ3n+WYXtxrDiH+HiMmqr82NJot2ztcB4BwRL/sI2ML510Q0jdstfn624dWaCyUe9MLz2UJN94Gtc1NqHa9l9vFNt4J/JzXclQF01gM+pZB0HhhY7tEv2VrJWIXL09OuR/BOALy5T+yYUvzTt4VSyjh+c+bvUsxKOn1FAKIIOV2tVszVtuK8VNhm3roZMBqNLkrr6mahGEDkMfjVnmnReIzQc3DLpcllwu9NrD2a34fvzlWJpnMa+Q26FZNPMxXQBQAAAABJRU5ErkJggg=="},function(e,n){e.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAACsElEQVRYR+1XwXHiQBCcoVZvcASHqjT7tRyBIQLbERxEcDiCsyOwMzgTwXERGEdg3pKr5Azse0swV02tKFloJXMff9gPVdQy0+rp7hFMX3z4i/vTEcCRgU4GsiwbrNfri81mM2LmIREtiehJRPC5PVmWDfM8vyCimJkHRLQyxszDMHztEnkrgCRJJsx8R0QoWj9LY8xVGIbvDsCCmU9rl+5F5LoNhBeAa/6LiP6q6iwIggWaJUkS93q9iar+UNVVEARjfF9tkqbpiIhuiOiciBYicuUD0QgAtBdFkaG5MSauN0CxNE1nRHSnqnNr7aSpQZIkD8z8XVWn1tqHpjuNAMrizHwVRdHChz5NU+jg3Bhz0gTSPchKVd+stWeHANgWFpFWjZRAiWhcFWVtHHiAC18tHwMAcCoiJ20Cenl5uVTV3x0AoIWfBwEoZ+ejtgSVpum2eAeAVjYbGag44FZE0GTvYL55nj8zM4sI8mHvwDHM/Ohy4/LTGsDFJElWztd783XN75gZ6r8Wkft6cXfnkZljVT2z1q4OBQD0oK+vqg+9Xm+pqki2kaoioIZVCyKMiqL45pogB2DTQZsFcbdL5SgEChsPGIiiaF7JBYyrX14GYGaeicifg4Moz/OSYvz2ye2AbR1VHTIzZgp20GQKGzrfYyyI7hj2c40R29Om3bDHQG128yAIbnxLxYkV8weQvbRzgDAKOOVdVcd1LXwAUGvujc8qndVF5EtO5wboSY0xZ9UH+gCg9HWXcJoUXxQFVN43xoRNsexAPGOUIjIua+wAOBXjAnZ9o2c/mYre7GgKrh2AytN7PdsGwDnhVVXVWhv6wqsoireqfasAsDRiX6p1NXcAttHcFjxug+72zAcAzPwaRRFU+1+nfBHBC4wv+ZxzJiKCjDn+LzgycGSA/gGLM6YwGnwomAAAAABJRU5ErkJggg=="},function(e,n){e.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAA6ElEQVRYR+2XwQ3CMAxFXydBTAAjsAFsQJkAmADYACYANoBNYAPYgA2KXMVIqJe4iZocYinKxfb/+nESuyKxVYnxEQJT4Ox2Xz4PYAXIHmRC4AWMgLtnQiE8d3HjIHRoFWiAE7AxJDsCaxdvCOu6KoEDsDdkEt9dDgQMnDuuH2DbV4EZICvE5MibvgRCgDW2raOUBNo6yobABZA1pNVArQoMCfyHpQSuiRRYZlMD1pcwxpHldQuKAkWBokBRoChgbctj/AW/lkwHk5vnYBIDXIabBfDW0Uy6oUmMzIYcT+2IDDHxXZNPx18AfFVs1ylQrAAAAABJRU5ErkJggg=="},function(e,n){e.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAC8klEQVRoQ+1Y3W0aQRCeEftuSuCk232NXYFNB6QC4wqcDoIrSFxBcAWhg+AKQl53kc7pgDxzaKKJ5qSNdRz7g0mQ7iQkxM0u88038+3MIpz5g2fuP/QA/jWDPQM9A5kR6FMoM4DZy3sG9oXQOXcDANS811o/Z4e7ZYOjMVBV1aiu61simiDiZZuzRLRCxIVS6qkoipdjAMoGUFXVcLvdfkLEqTj0k4iWiPhCRH+cRMQREY0QcQIAF2L3WSn1UBTFJgdIFgBr7SUifgOAIQBwisy01ssuhyS1ZgBwDQAbIhobY1apIJIBWGuniPgFAH5x9MuyXMQ4sV6vJ0Q0Z0aI6M4Yw9+jnyQAEvnv7DwR3aRGUPZhxhjEVco+0QA45+u6rji1u5wXu/tDBeuBIKVUEVsT0QCstXNEvEXE911pI7nO9fGgteac3/tIOn1FxMeyLD/E5FEUAJFKjv6z1pp1fu8TA4A3cc5xKl0LC8ESGwXAOceR/AgA40C1CWJAAHBAgu2byEUBsNbyQTTUWo8O0RzLgIBgWa2MMVeH9k8C4JwjInoyxjSH1tFSiDdq6ktrHRzYYMPYiMbaCwPBKRrNQONQ6KGTAsA7HA/W2CkB7E0z7pWMMUVjcBIAIbrODonkdrYHiLjydT9G5aIZkBwNLuJQFfHt3rSIRSW4a7zwaU9xdN8a5xzLKKdV6zzRti5YhVJVIhRgStHz3lEAvFZiqbUehzoXYneSVsI/bA41cyFONzYna+ZEXbid5maLCzprmpKANFMdKqVGb95Oe3/KA03WSOiPpCcbaFoOnQ0i3iWOlDySDkNP92wVer2BPxICAPfzPLyEDPXcknP7nDWSRqtQWwR4dNztdjMiupf3nFYLvlbhD//GVyretQrfYPBVy+NgMJjF5vxrH6JktEtZRGKncrH1rs2WiH7Ixdb8v7nY6jhV/xo5D6VWjOz6tkdjINWB3HU9gNwI5q7vGciNYO76noHcCOau7xnIjWDu+rNn4DdGy71AcSz+twAAAABJRU5ErkJggg=="},function(e,n,t){t(28);var s=t(1)(t(10),t(49),"data-v-64ddad67",null);e.exports=s.exports},function(e,n,t){t(29);var s=t(1)(t(11),t(50),"data-v-94df2816",null);e.exports=s.exports},function(e,n,t){t(27);var s=t(1)(t(12),t(48),"data-v-5c061ef4",null);e.exports=s.exports},function(e,n,t){t(30);var s=t(1)(t(13),t(51),"data-v-c6a57a00",null);e.exports=s.exports},function(e,n,t){t(26);var s=t(1)(t(14),t(47),"data-v-29892104",null);e.exports=s.exports},function(e,n,t){e.exports={render:function(){var e=this,n=e.$createElement,s=e._self._c||n;return s("div",{attrs:{id:"app"}},[e.chatMainShow?e._e():s("div",{staticClass:"chat-min",on:{click:function(n){e.chatMainShow=!e.chatMainShow}}},[e._m(0)]),e._v(" "),s("transition",{attrs:{name:"show"}},[e.chatMainShow?s("div",{staticClass:"chat-main",attrs:{id:"chat-main"}},[e.complete?e._e():s("div",{staticClass:"mask"},[s("div",{staticClass:"img"},[s("img",{attrs:{src:t(36),alt:""}})])]),e._v(" "),s("div",{staticClass:"term"},[s("menuList")],1),e._v(" "),s("div",{staticClass:"sidebar"},[s("card"),e._v(" "),s("list")],1),e._v(" "),s("div",{staticClass:"main"},[s("div",{staticClass:"windowList"},[s("ul",[s("li",{on:{click:function(n){n.stopPropagation(),e.chatMainShow=!e.chatMainShow}}},[e._v("X")])])]),e._v(" "),s("message"),e._v(" "),s("TextAre")],1)]):e._e()])],1)},staticRenderFns:[function(){var e=this,n=e.$createElement,s=e._self._c||n;return s("i",[s("img",{attrs:{src:t(34),alt:""}})])}]}},function(e,n,t){e.exports={render:function(){var e=this,n=e.$createElement,s=e._self._c||n;return e.start?s("div",{staticClass:"text"},[s("div",{staticClass:"chat-tool"},[e.emShow?s("div",{staticClass:"emoticon"},[s("ul",{staticClass:"emoticon-list"},e._l(e.emojis,function(n,t){return s("li",{on:{click:function(t){t.stopPropagation(),e.emojiInsert(n)}}},[s("img",{attrs:{src:"/static/emoji/"+n.num+"@2x.png",title:n.name,alt:""}})])}))]):e._e(),e._v(" "),s("ul",[s("li",{on:{click:function(n){n.stopPropagation(),e.emShow=!0}}},[s("img",{attrs:{src:t(33),alt:""}})]),e._v(" "),s("li",[e._m(0),s("input",{staticStyle:{display:"none"},attrs:{type:"file",id:"chat-file"},on:{change:function(n){e.fileUpload(n)}}})])])]),e._v(" "),s("textarea",{directives:[{name:"model",rawName:"v-model",value:e.content,expression:"content"}],ref:"textarea",attrs:{placeholder:""},domProps:{value:e.content},on:{keydown:function(n){if(!("button"in n)&&e._k(n.keyCode,"enter",13))return null;e.onKeydown(n)},input:function(n){n.target.composing||(e.content=n.target.value)}}}),e._v(" "),s("div",{staticClass:"send-act"},[s("div",{staticClass:"text-right"},[s("span",{staticClass:"send-btn btn btn-default",on:{click:function(n){e.send()}}},[e._v("发送")]),e._v(" "),s("div",{staticClass:"send-select-box",on:{click:function(n){n.stopPropagation(),e.selShow=!e.selShow}}},[s("span",{staticClass:"send-select"})]),e._v(" "),e.selShow?s("ul",{ref:"ssl",staticClass:"send-select-list"},[s("li",{class:[1==e.currentSel?"active":""],on:{click:function(n){e.changeSend(1)}}},[e._v("按Enter 键发送")]),e._v(" "),s("li",{class:[1==e.currentSel?"":"active"],on:{click:function(n){e.changeSend(0)}}},[e._v("按Ctrl + Enter 键发送")])]):e._e()])])]):e._e()},staticRenderFns:[function(){var e=this,n=e.$createElement,s=e._self._c||n;return s("label",{attrs:{for:"chat-file"}},[s("img",{attrs:{src:t(39),alt:""}})])}]}},function(e,n,t){e.exports={render:function(){var e=this,n=e.$createElement,s=e._self._c||n;return s("div",{staticClass:"menu"},[s("ul",[s("li",{staticStyle:{position:"relative"},on:{click:function(n){n.stopPropagation(),e.showInfo()}}},[s("img",{attrs:{src:e.user.img,width:"40px",height:"40px",alt:""}}),e._v(" "),e.selfInfo?s("div",{ref:"si",staticClass:"selfInfo"},[s("div",[s("span",[e._v(e._s(e.user.name))])]),e._v(" "),s("div")]):e._e()]),e._v(" "),s("li",{class:{actionColor:1==e.indexTab}},[s("img",{attrs:{src:t(40),alt:"",width:"40px",height:"40px"},on:{click:function(n){e.selectTab(1)}}})]),e._v(" "),s("li",{class:{actionColor:2==e.indexTab}},[s("img",{attrs:{src:t(37),alt:"",width:"30px",height:"30px"},on:{click:function(n){e.selectTab(2)}}})]),e._v(" "),s("li",{class:{actionColor:3==e.indexTab}},[s("img",{attrs:{src:t(38),alt:"",width:"30px",height:"30px"},on:{click:function(n){e.selectTab(3)}}})])]),e._v(" "),s("div",{staticClass:"chat-set",attrs:{title:"设置"}},[s("i",{on:{click:function(n){n.stopPropagation(),e.setShow=!e.setShow}}},[s("img",{attrs:{src:t(35),alt:""}})]),e._v(" "),s("transition",{attrs:{name:"fade"}},[e.setShow?s("ul",{staticClass:"chat-set-list"},[s("li",[e._v("新建群聊")]),e._v(" "),e._l(e.sets,function(n,t){return s("li",{on:{click:function(n){n.stopPropagation(),e.setUser(t)}}},[e._v(e._s(n[e.userSet[t]]))])})],2):e._e()])],1)])},staticRenderFns:[]}},function(e,n){e.exports={render:function(){var e=this,n=e.$createElement,t=e._self._c||n;return t("div",{staticClass:"card"},[t("footer",[t("input",{staticClass:"search",attrs:{type:"text",placeholder:"搜索"},on:{keyup:e.onKeyup}})])])},staticRenderFns:[]}},function(e,n){e.exports={render:function(){var e=this,n=e.$createElement,t=e._self._c||n;return t("div",{staticClass:"list"},[e.mousedownShow?t("div",{ref:"recentContactsDel",staticClass:"rcDel"},[t("ul",[t("li",{on:{click:function(n){e.removeZjlxr()}}},[e._v("移除该会话")])])]):e._e(),e._v(" "),0!=e.searchFriend.length?t("div",{staticClass:"search"},[t("ul",e._l(e.searchFriend,function(n){return t("li",{class:{active:n.id===e.currentId},on:{click:function(t){e.selectSession(n.id,"message",n.username,n.avatar)}}},[t("img",{staticClass:"avatar",attrs:{width:"30",height:"30",alt:n.username,src:"./static"+n.avatar}}),e._v(" "),t("p",{staticClass:"name"},[e._v(e._s(n.username))])])}))]):e._e(),e._v(" "),1==e.indexTab?t("div",[t("ul",{directives:[{name:"oncontextmenu",rawName:"v-oncontextmenu",value:e.sessions,expression:"sessions"}],attrs:{id:"zjlxr"}},e._l(e.sessions,function(n,s){return t("li",{class:{active:n.id===e.currentId},attrs:{id:e.currentId,item:n.id},on:{click:function(t){e.selectSession(n.id,n.type,n.user.name,n.user.img)},mousedown:function(t){if(!("button"in t)&&3!==t.keyCode)return null;e.mousedown(n,s,t)}}},[t("img",{staticClass:"avatar",attrs:{width:"30",height:"30",alt:n.user.name,src:n.user.img}}),e._v(" "),t("p",{staticClass:"name"},[e._v(e._s(n.user.name))]),e._v(" "),n.messageNum?t("span",{staticClass:"message-num"},[e._v(e._s(n.messageNum))]):e._e()])}))]):e._e(),e._v(" "),2==e.indexTab?t("div",[t("ul",{staticClass:"friend-list"},e._l(e.friends,function(n){return t("li",{},[t("p",[e._v(e._s(n.groupname))]),e._v(" "),t("ul",e._l(n.list,function(n){return t("li",{class:{active:n.id===e.currentId},on:{click:function(t){e.selectSession(n.id,"message",n.username,"./static"+n.avatar)}}},[t("img",{attrs:{src:"/static/"+n.avatar,alt:"",width:"30",height:"30"}}),e._v(" "),t("p",{staticClass:"name"},[e._v(e._s(n.username))])])}))])}))]):e._e(),e._v(" "),3==e.indexTab?t("div",[t("ul",{staticClass:"group-list"},e._l(e.group,function(n){return t("li",{on:{click:function(t){e.selectSession(n.id,"groupMessage",n.groupname,"./static"+n.avatar)}}},[t("img",{staticClass:"avatar",attrs:{src:"./static"+n.avatar,width:"30",height:"30"}}),e._v(" "),t("p",{staticClass:"name"},[e._v(e._s(n.groupname))])])}))]):e._e()])},staticRenderFns:[]}},function(e,n){e.exports={render:function(){var e=this,n=e.$createElement,t=e._self._c||n;return t("div",{staticClass:"message"},[t("div",{staticClass:"dialog-title"},[t("span",{staticClass:"text-left dialogue-title-name"},[e._v(e._s(e.dialogName))])]),e._v(" "),e.session?t("ul",{directives:[{name:"scroll-bottom",rawName:"v-scroll-bottom",value:e.session.messages,expression:"session.messages"}]},e._l(e.session.messages,function(n){return t("li",[t("p",{staticClass:"time"},[t("span",[e._v(e._s(e._f("time")(n.date)))])]),e._v(" "),t("div",{staticClass:"main",class:{self:n.self}},[t("img",{staticClass:"avatar",attrs:{width:"30",height:"30",src:n.self?e.user.img:n.img}}),e._v(" "),t("div",{staticClass:"text",domProps:{innerHTML:e._s(e.content(n.content))}})])])})):e._e()])},staticRenderFns:[]}},,,function(e,n){}],[18]);
//# sourceMappingURL=app.a580a140199b163d9d00.js.map