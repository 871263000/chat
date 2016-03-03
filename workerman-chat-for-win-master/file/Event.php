<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 聊天主逻辑
 * 主要是处理 onMessage onClose 
 */
use \GatewayWorker\Lib\Gateway;
use \GatewayWorker\Lib\Store;
use \GatewayWorker\Lib\Db;

class Event
{
   
   /**
    * 有消息时
    * @param int $client_id
    * @param string $message
    */
   public static function onMessage($client_id, $message)
   {
        // debug
        // echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id session:".json_encode($_SESSION)." onMessage:".$message."\n";

        // 客户端传递的是json数据
        $message = str_replace("script", "'script'" , $message);
        $message_data = json_decode($message, true);
        if(!$message_data)
        {
            return ;
        }
        // echo $message_data['type'];
        // 根据类型执行不同的业务
        switch($message_data['type'])
        {
            // 客户端回应服务端的心跳
            case 'pong':
                return;
            // 客户端登录 message格式: {type:login, name:xx, room_id:1} ，添加到客户端，广播给所有客户端xx进入聊天室
            case 'login':
                //是否已登录
                // $exist = (bool)false;
                //client_id 和 uid 绑定
                $logined = Gateway::getClientIdByUid($message_data['uid']);
                //绑定uid
                Gateway::bindUid($client_id, $message_data['uid']);
                // 把房间号昵称放到session中
                $room_id = $message_data['room_id'];
                $uid = $message_data['uid'];
                // 判断是否有房间号
                if(!isset($message_data['room_id']))
                {
                    throw new \Exception("\$message_data['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$message");
                }
                $client_name = htmlspecialchars($message_data['client_name']);
                $_SESSION['room_id'] = $room_id;
                $_SESSION['client_name'] = $client_name;
                $_SESSION['uid'] = $uid;
                $_SESSION['header_img_url'] = $message_data['header_img_url'];
                // 获取房间内所有用户列表 
                $clients_list = Gateway::getClientInfoByGroup($room_id);
                foreach($clients_list as $tmp_client_id=>$item)
                {

                    $new_clients_list[$item['uid']]['client_name'] = $item['client_name'];
                    $new_clients_list[$item['uid']]['header_img_url'] = $item['header_img_url'];
                }

                //判断是否已登陆
                $new_message = array('type'=>$message_data['type'], 'client_list'=>$clients_list, 'time'=>date('Y-m-d H:i:s'));
                if (empty($logined)) {
                    // print_r($clients_list);
                    $new_clients_list[$uid]['client_name'] = htmlspecialchars($client_name);
                    $new_clients_list[$uid]['header_img_url'] = $message_data['header_img_url'];
                    $new_message['client_list'] = $new_clients_list;
                    Gateway::sendToGroup($room_id, json_encode($new_message));
                    // 给当前用户发送用户列表 
                    Gateway::sendToCurrentClient(json_encode($new_message));
                    Gateway::joinGroup($client_id, $room_id);
                } else {
                    // 给当前用户发送用户列表 
                    $new_message['client_list'] = $new_clients_list;
                    Gateway::sendToCurrentClient(json_encode($new_message));
                }
                Gateway::joinGroup($client_id, $room_id);   
                return;
                
            // 客户端发言 message: {type:say, to_client_id:xx, content:xx}
            case 'say':
                // 非法请求
                if(!isset($_SESSION['room_id']))
                {
                    throw new \Exception("\$_SESSION['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
                }
                $room_id = $_SESSION['room_id'];
                $client_name = $_SESSION['client_name'];
                
                // 私聊
                if($message_data['to_client_id'] != 'all')
                {
                    $new_message = array(
                        'type'=>'say',
                        'from_client_id'=>$client_id, 
                        'from_client_name' =>$client_name,
                        'to_client_id'=>$message_data['to_client_id'],
                        'content'=>"<b>对你说: </b>".nl2br(htmlspecialchars($message_data['content'])),
                        'time'=>date('Y-m-d H:i:s'),
                    );
                    Gateway::sendToClient($message_data['to_client_id'], json_encode($new_message));
                    $new_message['content'] = "<b>你对".htmlspecialchars($message_data['to_client_name'])."说: </b>".nl2br(htmlspecialchars($message_data['content']));
                    return Gateway::sendToCurrentClient(json_encode($new_message));
                }
                
                $new_message = array(
                    'type'=>'say', 
                    'from_client_id'=>$client_id,
                    'from_client_name' =>$client_name,
                    'to_client_id'=>'all',
                    'content'=>nl2br(htmlspecialchars($message_data['content'])),
                    'time'=>date('Y-m-d H:i:s'),
                );
                return Gateway::sendToGroup($room_id ,json_encode($new_message));
            case 'say_uid':
                $db1 = Db::instance('oms');
                // 非法请求
                if(!isset($_SESSION['room_id']))
                {
                    throw new \Exception("\$_SESSION['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
                }
                $room_id = $_SESSION['room_id'];
                $client_name = $_SESSION['client_name'];
                $uid = $_SESSION['uid'];
                // 私聊
                $message_content = $message_data['content'];
                if($message_data['to_uid_id'] !== 'all')
                {
                    $new_message = array(
                        'type'=>'say_uid',
                        'from_client_id'=>$client_id, 
                        'from_client_name' =>$client_name,
                        'from_uid_id'=>$uid,
                        'groupId'=>$message_data['groupId'],
                        'mestype'=>$message_data['message_type'],
                        'content'=>$message_content,
                        'time'=>date('Y-m-d H:i:s'),
                    );
                    if ($message_data['message_type'] == "message") {
                        $session_no = $uid > $message_data['to_uid_id'] ? $message_data['to_uid_id']."-".$uid : $uid."-".$message_data['to_uid_id'];
                        $new_message['mestype'] = "message";
                        $to_uid = $message_data['to_uid_id'];
                    } else {
                        //验证是否在群聊
                        $va = $db1->query("SELECT `group_participants`, `group_name` FROM `oms_group_chat` WHERE `id`=".$message_data['session_no']);
                        $arrVa = explode(',', $va[0]['group_participants']);
                        if (!in_array($uid, $arrVa)) {
                            return ;
                        }
                        $session_no = $message_data['session_no'];
                        $new_message['mestype'] = "groupMessage";
                        $new_message['group_name'] = $va[0]['group_name'];
                        $message_data['to_uid_id'] = $va[0]['group_participants'];
                        $new_message['to_uid_id'] = $va[0]['group_participants'];
                        foreach ($arrVa as $key => $value) {
                            if ($value == $uid) {
                                unset($arrVa[$key]);
                            }
                        }
                        $to_uid = $arrVa;
                    };
                    if ($message_data['mes_types'] == 'image') {
                        $pa = $message_content;
                        if (preg_match("/^(data:\s*image\/(\w+);base64,)/", $pa, $result)){
                            $type = $result[2];
                            //创建文件夹
                            $save_path = "../chatImage/".$message_data['to_uid_id'] . "/";
                            $save_url = "/chat/chatImage/".$message_data['to_uid_id'] . "/";
                            if (!file_exists($save_path)) {
                                mkdir($save_path);
                            }
                            //新文件名
                            $new_file_name = date("YmdHis") . rand(1000, 9999) . '.' . $type;
                            if (file_put_contents($save_path.$new_file_name, base64_decode(str_replace($result[1], '', $pa)))){
                                $message_content = "<img src='".$save_url.$new_file_name."' class='send-img'>";
                                $new_message['content'] = $message_content;
                                $new_message['image'] = $message_data['mes_types'];
                            } else {
                                return ;
                            }
                        }
                    }
                    $insert_id = $db1->insert('oms_string_message')->cols(array('room_id'=>$room_id, 'sender_id'=>$uid,'accept_id'=>$message_data['to_uid_id'], 'sender_name'=>$client_name, 'accept_name'=>$message_data['accept_name'],'message_type'=>$message_data['message_type'], 'mesages_types'=>$message_data['mes_types'], 'groupId'=>$message_data['groupId'], 'message_content'=>$message_content, 'session_no'=>$session_no, 'create_time'=>time(), 'update_time'=>time()))->query();
                    $new_message['insert_id'] = $insert_id;
                    $new_message['session_no'] = $session_no;
                    $new_message['header_img_url'] = $_SESSION['header_img_url'];
                    if ($message_data['message_type'] == "groupMessage") {
                        $db1->query("UPDATE `oms_groups_people` SET `mes_state`=1, `mes_id`=".$insert_id." WHERE `staffid` != $uid AND `pid`=".$message_data['session_no']);
                    } 
                    Gateway::sendToUid($to_uid, json_encode($new_message));
                    return ;
                }
                // 组聊
                $new_message = array(
                    'type'=>'say_uid', 
                    'from_client_id'=>$client_id,
                    'from_client_name' =>$client_name,
                    'to_client_id'=>'all',
                    'content'=>"{$client_name}说: ".nl2br(htmlspecialchars($message_data['content'])),
                    'time'=>date('Y-m-d H:i:s'),
                );
                $insert_id = $db1->insert('oms_string_message')->cols(array('room_id'=>$room_id, 'sender_id'=>$message_data['senderid'],'accept_id'=>0,'accept_name'=>$client_name ,'message_type'=>$message_data['message_type'], 'message_content'=>nl2br(htmlspecialchars($message_data['content'])), 'create_time'=>time(), 'update_time'=>time()))->query();
                return Gateway::sendToGroup($room_id ,json_encode($new_message));

            //审核消息
            case 'audit':
                 $db1 = Db::instance('oms');
                // 非法请求
                if(!isset($_SESSION['room_id']))
                {
                    throw new \Exception("\$_SESSION['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
                }
                $room_id = $_SESSION['room_id'];
                $client_name = $_SESSION['client_name'];
                $uid = $_SESSION['uid'];
                // 私聊
                if($message_data['to_uid_id'] != 'all')
                {
                    $new_message = array(
                        'message_url'=>$message_data['message_url'],
                        'type'=>'audit',
                        'from_client_id'=>$client_id, 
                        'from_client_name' =>$client_name,
                        'content'=>nl2br(htmlspecialchars($message_data['content'])),
                        'time'=>date('Y-m-d H:i:s'),
                    );

                    // Gateway::sendToClient($message_data['to_client_id'], json_encode($new_message));
                    $newTime = time();
                    $session_no = $newTime.$client_id;
                    if (!empty($message_data['to_uid_id'])) {
                        foreach ($message_data['to_uid_id'] as $key => $value) {
                            $arrValue[] = "($value, '".$message_data['senderid']."', '".$message_data['message_type']."', '".$message_data['content']."', '1', '".$message_data['message_url']."', '".$session_no."', ".$newTime.", ".$newTime.")";
                        }
                    }
                    $value = implode(',', $arrValue);
                    //插入数据
                    $insert_id = $db1->query("INSERT INTO `oms_string_message` (`accept_id`, `sender_id`, `message_type`, `message_content`, `oms_id`, `message_url`, `session_no`,  `create_time`, `update_time`) VALUE".$value);
                    $new_message['session_no'] = $session_no;
                    Gateway::sendToUid($message_data['to_uid_id'], json_encode($new_message));
                    return ;
                }
                // 组聊
                $new_message = array(
                    'type'=>'say_uid', 
                    'from_client_id'=>$client_id,
                    'from_client_name' =>$client_name,
                    'to_client_id'=>'all',
                    'content'=>"{$client_name}说: ".nl2br(htmlspecialchars($message_data['content'])),
                    'time'=>date('Y-m-d H:i:s'),
                );
                $insert_id = $db1->insert('oms_string_message')->cols(array('room_id'=>$room_id, 'sender_id'=>$message_data['senderid'],'accept_id'=>0,'accept_name'=>$client_name ,'message_type'=>$message_data['message_type'], 'message_content'=>nl2br(htmlspecialchars($message_data['content'])), 'create_time'=>time(), 'update_time'=>time()))->query();
                return Gateway::sendToGroup($room_id ,json_encode($new_message));

            return;
            //数据操作消息的关闭
            case 'mes_close':
                $db1 = Db::instance('oms');
                // 非法请求
                if(!isset($_SESSION['room_id']))
                {
                    throw new \Exception("\$_SESSION['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
                }
                $room_id = $_SESSION['room_id'];
                $mesid = $message_data['mesid'];
                $session_no = $message_data['session_no'];
                $uid = $_SESSION['uid'];
                if ($message_data['mestype'] == 'message') {
                    $db1->query("UPDATE `oms_string_message` SET `state`=1 WHERE  `state`=0 AND `session_no`= "."'{$session_no}'");
                } else {
                    $db1->query("UPDATE `oms_groups_people` SET `mes_state`=0 WHERE `staffid`=$uid AND `pid`=".$session_no);
                }
                return ;
            // 选择人后聊天信息
            case 'mes_chat':
                $db1 = Db::instance('oms');
                // 非法请求
                if(!isset($_SESSION['room_id']))
                {
                    throw new \Exception("\$_SESSION['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
                }
                $room_id = $_SESSION['room_id'];
                $uid = $_SESSION['uid'];
                $session_no = $uid < $message_data['mes_para'] ? $uid."-".$message_data['mes_para'] : $message_data['mes_para']."-".$uid;
                $mes_list = $db1->query("SELECT a.`id`, a.`message_content`, a.`create_time`, a.`sender_name`, a.`sender_id`, b.`card_image` FROM `oms_string_message` a LEFT JOIN `oms_hr` b ON a.`sender_id` = b.staffid  WHERE a.`delState` = 0 AND a.`session_no`= "."'{$session_no}' ORDER BY create_time desc limit 0, 10");
                if (!empty($mes_list)) {
                    foreach ($mes_list as $key => $value) {
                            $mes_list[$key]['create_time'] = date('Y-m-d H:i:s', $value['create_time']);
                    }
                }
                $mes_list['type'] = 'mes_chat';
                Gateway::sendToClient($client_id, json_encode($mes_list));
                return;
            //滚动消息的分页
            case 'mes_load':
                $db1 = Db::instance('oms');
                // 非法请求
                if(!isset($_SESSION['room_id']))
                {
                    throw new \Exception("\$_SESSION['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
                }
                $room_id = $_SESSION['room_id'];
                $uid = $_SESSION['uid'];
                if ($message_data['message_type'] == "message") {
                    //会话id
                    $session_no = $uid > $message_data['to_uid'] ? $message_data['to_uid']."-".$uid : $uid."-".$message_data['to_uid'];
                } else {
                    //验证是否在群聊
                    $va = $db1->query("SELECT `group_participants` FROM `oms_group_chat` WHERE `id`=".$message_data['session_no']);
                    $arrVa = explode(',', $va[0]['group_participants']);
                    if (!in_array($uid, $arrVa)) {
                        return ;
                    }
                    //会话id
                    $session_no = $message_data['session_no'];
                }
                if (!empty($message_data['mes_loadnum'])) {
                    $onlode = $db1->query("SELECT a.`id`, a.`message_content`, a.`create_time`, a.`accept_name`, a.`sender_id`, a.`sender_name`,b.`card_image` FROM `oms_string_message` a LEFT JOIN `oms_hr` b ON a.`sender_id`=b.`staffid` WHERE a.`delState` = 0 AND a.`session_no`= "."'{$session_no}' ORDER BY a.create_time desc limit ".$message_data['mes_loadnum'].", 10");
                    if (!empty($onlode)) {
                        foreach ($onlode as $key => $value) {
                                $onlode[$key]['create_time'] = date('Y-m-d H:i:s', $value['create_time']);
                        }
                        $onlode['type'] = 'onlode';
                        $onlode['save'] = 1;
                    } else {
                        $onlode['type'] = 'onlode';
                        $onlode['save'] = 0;
                    }
                    Gateway::sendToClient($client_id, json_encode($onlode));
                }
                
            return ;
            //群聊
            case 'mes_groupChat':
                $db1 = Db::instance('oms');
                // 非法请求
                if(!isset($_SESSION['room_id']))
                {
                    throw new \Exception("\$_SESSION['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
                }
                $room_id = $_SESSION['room_id'];
                $uid = $_SESSION['uid'];
                $arrYanzhng = explode(',', $message_data['groupvalue']);
                if (!in_array($uid, $arrYanzhng)) {
                    return ;
                }
                $group_mes_list = $db1->query("SELECT a.`id`, a.`message_content`, a.`create_time`, a.`sender_name`, a.`sender_id`,b.`card_image` FROM `oms_string_message` a LEFT JOIN `oms_hr` b ON a.`sender_id`= b.staffid WHERE a.`delState` = 0 AND a.`session_no`= '".$message_data['session_no']."' ORDER BY a.create_time desc limit 0, 10");
                if (!empty($group_mes_list)) {
                    foreach ($group_mes_list as $key => $value) {
                            $group_mes_list[$key]['create_time'] = date('Y-m-d H:i:s', $value['create_time']);
                    }
                }
                $group_mes_list['type'] = 'mes_chat';
                Gateway::sendToClient($client_id, json_encode($group_mes_list));
                return;
            case 'recentcClose':
                $db1 = Db::instance('oms');
                $uid = $_SESSION['uid'];
                $db1->query("UPDATE `oms_string_message` SET `delState` = 1 WHERE `session_no` = '".$message_data['session']."'");
                return;
            case 'addGroupMan':
                $db1 = Db::instance('oms');
                $uid = $_SESSION['uid'];
                $arrAddGroupMan = $db1->query("SELECT `group_participants`,`group_name` FROM `oms_group_chat` WHERE id=".$message_data['session_no']);
                $arr = explode(",", $arrAddGroupMan[0]['group_participants']);
                if (!in_array($uid, $arr)) {
                    return;
                }
                foreach ($message_data['sidList'] as $key => $value) {
                    if (!in_array($value, $arr)) {
                        $arr[] = $value;
                        $addMan[] = $value;
                    }
                }
                $sAddGroupMan = implode(",", $arr);
                foreach ($addMan as $k => $val) {
                    $arrvalue[] = "('".$message_data['session_no']."', '".$val."', '".$arrAddGroupMan[0]['group_name']."', ".time()." ,".time().")";
                }
                $strvalue = implode(",", $arrvalue);
                $db1->query("INSERT INTO `oms_groups_people` (`pid`, `staffid`, `group_name`, `create_time`, `update_time`) value".$strvalue);
                $db1->query("UPDATE `oms_group_chat` SET `group_participants`='".$sAddGroupMan."' WHERE id=".$message_data['session_no']);
                $db1->query("UPDATE `oms_groups_people` SET `all_staffid`='".$sAddGroupMan."' WHERE `pid`=".$message_data['session_no']);
                return;
            case 'delgroupman':
                $uid =  $_SESSION['uid'];
                $id = $message_data['id'];
                $db1 = Db::instance('oms');
                $arrgrouppeople = $db1->query("SELECT * FROM `oms_groups_people` WHERE `id`=".$message_data['groupid']);
                $arrjoinGroup = explode(',', $arrgrouppeople[0]['all_staffid']);
                foreach ($arrjoinGroup as $key => $value) {
                    if ($value == $id) {
                        unset($arrjoinGroup[$key]);
                    }
                }
                $unarrjoinGroup = $arrjoinGroup;
                $unstrgroupman = implode(",", $unarrjoinGroup);
                if (in_array($uid, explode(',', $arrgrouppeople[0]['all_staffid']))) {
                    $db1->query("UPDATE `oms_groups_people` SET `all_staffid`= '".$unstrgroupman."' WHERE `pid`=".$arrgrouppeople[0]['pid']);
                    $db1->query("UPDATE `oms_groups_people` SET `state`= 1 WHERE `id`=".$message_data['groupid']);
                    $db1->query("UPDATE `oms_group_chat` SET `group_participants`= '".$unstrgroupman."' WHERE `id`=".$arrgrouppeople[0]['pid']);
                }
                return ;
            case 'active':
                // 非法请求
                if(!isset($_SESSION['room_id']))
                {
                    throw new \Exception("\$_SESSION['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
                }
                $room_id = $_SESSION['room_id'];
                $client_name = $_SESSION['client_name'];
                
                // 私聊
                if($message_data['to_uid_id'] != 'all')
                {
                    $new_message = array(
                        'type'=>'say',
                        'from_client_id'=>$client_id, 
                        'from_client_name' =>$client_name,
                        'to_client_id'=>$message_data['to_client_id'],
                        'content'=>"<b>对你说: </b>".nl2br(htmlspecialchars($message_data['content'])),
                        'time'=>date('Y-m-d H:i:s'),
                    );
                    Gateway::sendToClient($message_data['to_client_id'], json_encode($new_message));
                    $new_message['content'] = "<b>你对".htmlspecialchars($message_data['to_client_name'])."说: </b>".nl2br(htmlspecialchars($message_data['content']));
                    return Gateway::sendToCurrentClient(json_encode($new_message));
                }
                
                $new_message = array(
                    'type'=>'say', 
                    'from_client_id'=>$client_id,
                    'from_client_name' =>$client_name,
                    'to_client_id'=>'all',
                    'content'=>nl2br(htmlspecialchars($message_data['content'])),
                    'time'=>date('Y-m-d H:i:s'),
                );
                return Gateway::sendToGroup($room_id ,json_encode($new_message));

        }
   }
   
   /**
    * 当客户端断开连接时
    * @param integer $client_id 客户端id
    */
   public static function onClose($client_id)
   {
       // debug
       // echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id onClose:''\n";
       // 从房间的客户端列表中删除
       if(isset($_SESSION['room_id']))
       {
           $room_id = $_SESSION['room_id'];
           $uid = $_SESSION['uid'];
           $logined = Gateway::getClientIdByUid($uid);
           //uid 还在线
           if (empty($logined)) {
                $new_message = array('type'=>'logout', 'from_uid_id'=>$uid, 'from_client_name'=>$_SESSION['client_name'], 'time'=>date('Y-m-d H:i:s'));
                Gateway::sendToGroup($room_id, json_encode($new_message)); 
           }

           
       }
   }
  
}
