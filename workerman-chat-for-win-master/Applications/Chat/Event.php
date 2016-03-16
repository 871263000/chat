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
        // $save_path = 'file/audio.mp3';
        // file_put_contents($save_path, $message);
        $adminUid = 1;
        // return;
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
                $exist = (bool)true;//未登录
                //client_id 和 uid 绑定
                if (isset($_SESSION['uid'])) {
                    $uid = $_SESSION['uid'];
                    $room_id = $_SESSION['room_id'];
                    $client_name = $_SESSION['client_name'];
                    $header_img_url = $_SESSION['header_img_url'];
                    $exist = (bool)false;
                } else {
                    // 把房间号昵称放到session中
                    $room_id = $message_data['room_id'];
                    $client_name = htmlspecialchars($message_data['client_name']);
                    $uid = $message_data['uid'];
                    $header_img_url = $message_data['header_img_url'];
                    $_SESSION['room_id'] = $room_id;
                    $_SESSION['client_name'] = $client_name;
                    $_SESSION['uid'] = $uid;
                    $_SESSION['header_img_url'] = $message_data['header_img_url'];
                }
                $logined = Gateway::getClientIdByUid($uid);
                //绑定uid
                Gateway::bindUid($client_id, $uid);
                // 判断是否有房间号
                if(!isset($room_id))
                {
                    throw new \Exception("\$message_data['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$message");
                }
                // 获取房间内所有用户列表 
                $clients_list = Gateway::getClientInfoByGroup($room_id);
                if (!empty($clients_list)) {
                    foreach($clients_list as $tmp_client_id=>$item)
                    {
                        $new_clients_list[$item['uid']]['client_name'] = $item['client_name'];
                        $new_clients_list[$item['uid']]['header_img_url'] = $item['header_img_url'];
                    }
                }
                //判断是否已登陆
                $new_message = array('type'=>'login', 'client_list'=>$clients_list, 'time'=>date('Y-m-d H:i:s'));
                if (empty($logined)) {
                    //未登录
                    //管理员在线发送login次数加一；
                    $adminOnline = Gateway::getClientIdByUid( 1 );
                    if (!empty($adminOnline)) {
                        $adminlogin = ['type'=>'adminLoginNum'];
                        Gateway::sendToUid( $adminUid, json_encode($adminlogin));
                    }
                    $new_clients_list[$uid]['client_name'] = htmlspecialchars($client_name);
                    $new_clients_list[$uid]['header_img_url'] = $header_img_url;
                    $new_message['client_list'] = $new_clients_list;
                    Gateway::sendToGroup($room_id, json_encode($new_message));
                    // 给当前用户发送用户列表 
                    Gateway::sendToCurrentClient(json_encode($new_message));
                    Gateway::joinGroup($client_id, $room_id);
                    //管理员获取所有人的在线人数
                } else {
                    //已登陆
                    // 给当前用户发送用户列表 
                    $new_message['client_list'] = $new_clients_list;
                    Gateway::sendToCurrentClient(json_encode($new_message));
                }
                Gateway::joinGroup($client_id, $room_id);   
                return;
            // 客户端发言 message: {type:say_uid, to_client_id:xx, content:xx}
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
                $header_img_url = $_SESSION['header_img_url'];
                // 私聊
                $message_content = $message_data['content'];
                if($message_data['to_uid_id'] !== 'all')
                {
                    $new_message = array(
                        'type'=>'say_uid',
                        'from_client_id'=>$client_id, 
                        'from_client_name' =>$client_name,
                        'from_uid_id'=>$uid,
                        'header_img_url'=>$header_img_url,
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
                            } else {
                                return ;
                            }
                        }
                    }
                    $new_message['image'] = $message_data['mes_types'];
                    $insert_id = $db1->insert('oms_string_message')->cols(array('room_id'=>$room_id, 'sender_id'=>$uid,'accept_id'=>$message_data['to_uid_id'], 'sender_name'=>$client_name, 'accept_name'=>$message_data['accept_name'],'message_type'=>$message_data['message_type'], 'mesages_types'=>$message_data['mes_types'], 'groupId'=>$message_data['groupId'], 'message_content'=>$message_content, 'session_no'=>$session_no, 'create_time'=>time(), 'update_time'=>time()))->query();
                    $new_message['insert_id'] = $insert_id;
                    $new_message['session_no'] = $session_no;
                    if ($message_data['message_type'] == "groupMessage") {
                        $db1->query("UPDATE `oms_groups_people` SET `mes_state`=1, `mes_num`=`mes_num`+1, `mes_id`=".$insert_id." WHERE `staffid` != $uid AND `pid`=".$message_data['session_no']);
                    } else if ($message_data['message_type'] == "message") {
                        $chat_res = $db1->single('SELECT `id` FROM `oms_chat_message_ist` WHERE `pid`='.$message_data['to_uid_id']);
                        if (!empty($chat_res)) {
                           $db1->query("UPDATE `oms_chat_message_ist` SET `mes_num` = `mes_num`+1, `mes_id`=$insert_id WHERE id=".$chat_res);
                           $insert_id = $chat_res;
                        } else {
                            $insert_id = $db1->insert('oms_chat_message_ist')->cols(array('pid'=>$message_data['to_uid_id'], 'session_no'=>$session_no, 'mes_id'=>$insert_id, 'chat_header_img'=>$header_img_url, 'oms_id'=>$room_id))->query();
                        }
                    }
                    // $new_message['insert_id'] = $insert_id;
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
            //数据操作消息的关闭
            case 'mes_close':
                $db1 = Db::instance('oms');
                // 非法请求
                if(!isset($_SESSION['room_id']))
                {
                    throw new \Exception("\$_SESSION['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
                }
                $room_id = $_SESSION['room_id'];
                $session_no = $message_data['session_no'];
                $uid = $_SESSION['uid'];
                if ($message_data['mestype'] == 'message') {
                    $db1->query("DELETE FROM `oms_chat_message_ist` WHERE `session_no`= '".$session_no."'");
                } else {
                    $db1->query("UPDATE `oms_groups_people` SET `mes_state`=0, `mes_num`=0 WHERE `staffid` = $uid AND `pid`='".$session_no."'");
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
                $mes_list = $db1->query("SELECT a.`id`, a.`message_content`, a.`mesages_types`, a.`create_time`, a.`sender_name`, a.`sender_id`, b.`card_image` FROM `oms_string_message` a LEFT JOIN `oms_hr` b ON a.`sender_id` = b.staffid  WHERE a.`delState` = 0 AND a.`session_no`= "."'{$session_no}' ORDER BY create_time desc limit 0, 10");
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
                    if (empty($va)) {
                        $onlode['type'] = 'onlode';
                        $onlode['save'] = 0;
                        Gateway::sendToClient($client_id, json_encode($onlode));
                       return;
                    }
                    $arrVa = explode(',', $va[0]['group_participants']);
                    if (!in_array($uid, $arrVa)) {
                        return ;
                    }
                    //会话id
                    $session_no = $message_data['session_no'];
                }
                if (!empty($message_data['mes_loadnum'])) {
                    $onlode = $db1->query("SELECT a.`id`, a.`message_content`, a.`mesages_types`, a.`create_time`, a.`accept_name`, a.`sender_id`, a.`sender_name`,b.`card_image` FROM `oms_string_message` a LEFT JOIN `oms_hr` b ON a.`sender_id`=b.`staffid` WHERE a.`delState` = 0 AND a.`session_no`= "."'{$session_no}' ORDER BY a.create_time desc limit ".$message_data['mes_loadnum'].", 10");
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
                // $arrYanzhng = explode(',', $message_data['groupvalue']);
                // if (!in_array($uid, $arrYanzhng)) {
                //     return ;
                // }
                $yanzheng = $db1->select('staffid')->from('oms_groups_people')->where('pid= :pid')->bindValues(array('pid'=>$message_data['session_no']))->column();
                if (!in_array($uid, $yanzheng) ) {
                    Gateway::sendToClient($client_id, json_encode(array('type'=>'mes_chat')));
                    return;
                }
                $group_mes_list = $db1->query("SELECT a.`id`, a.`message_content`, a.`mesages_types`, a.`create_time`, a.`sender_name`, a.`sender_id`,a.`session_no`, b.`card_image` FROM `oms_string_message` a LEFT JOIN `oms_hr` b ON a.`sender_id`= b.staffid WHERE a.`delState` = 0 AND a.`session_no`= '".$message_data['session_no']."' ORDER BY a.create_time desc limit 0, 10");
                if (!empty($group_mes_list)) {
                    foreach ($group_mes_list as $key => $value) {
                            $group_mes_list[$key]['create_time'] = date('Y-m-d H:i:s', $value['create_time']);
                    }
                }
                $group_mes_list['type'] = 'mes_chat';
                Gateway::sendToClient($client_id, json_encode($group_mes_list));
                return;
            //最近联系人
            case 'recentcClose':
                $db1 = Db::instance('oms');
                $uid = $_SESSION['uid'];
                $db1->query("UPDATE `oms_string_message` SET `delState` = 1 WHERE `session_no` = '".$message_data['session']."'");
                return;
            //增加群聊人
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
            //删除群聊里的人
            case 'delgroupman':
                $uid =  $_SESSION['uid'];
                $id = $message_data['id'];
                $groupid = $message_data['groupid'];
                $db1 = Db::instance('oms');
                $arrgrouppeople = $db1->select('*')->from('oms_group_chat')->where('id= :id')->bindValues(array('id'=>$groupid))->row();
                // $arrgrouppeople = $db1->row("SELECT * FROM `oms_groups_people` WHERE `id`=".$uid);
                $arrjoinGroup = explode(',', $arrgrouppeople['group_participants']);
                if ($uid == $arrgrouppeople['group_founder']) {
                    foreach ($arrjoinGroup as $key => $value) {
                        if ($value == $id) {
                            unset($arrjoinGroup[$key]);
                            break;
                        }
                    }
                    $unarrjoinGroup = $arrjoinGroup;
                    $unstrgroupman = implode(",", $unarrjoinGroup);
                        $db1->query("UPDATE `oms_groups_people` SET `all_staffid`= '".$unstrgroupman."' WHERE `pid`=".$arrgrouppeople['id']);
                        $db1->query("DELETE FROM `oms_groups_people` WHERE `staffid`= $id AND `pid`=".$arrgrouppeople['id']);
                        $db1->query("UPDATE `oms_group_chat` SET `group_participants`= '".$unstrgroupman."' WHERE `id`=".$arrgrouppeople['id']);
                }
                return ;
            case 'dissolve_group':
                $db1 = Db::instance('oms');
                $uid = $_SESSION['uid'];
                $groupId = $message_data['groupId'];
                $dissolve_group = $db1->select('*')->from('oms_group_chat')->where('id= :id')->bindValues(array('id'=>$groupId))->row();
                if ($uid == $dissolve_group['group_founder']) {
                    $row_count = $db1->delete('oms_group_chat')->where('id='.$groupId)->query();
                    $row_count = $db1->delete('oms_groups_people')->where('pid='.$groupId)->query();
                }
                break;
            //增加最近联系人
            case 'addContact':
                $db1 = Db::instance('oms');
                $uid = $_SESSION['uid'];
                if ($message_data['mestype'] == "message") {
                    $mes_id = $message_data['mes_id'];
                } else {
                    $mes_id = $message_data['session_no'];
                }
                $conNum = $db1->column('SELECT id FROM `oms_nearest_contact` WHERE `session_no`='.$message_data['session_no']);
                if ( $message_data['mestype'] == "message" ) {
                    $insert_id = $db1->insert('oms_nearest_contact')->cols(array('pid'=>$uid, 'mestype'=>$message_data['mestype'],'session_no'=>$message_data['session_no'], 'sender_name'=>$message_data['sender_name'], 'accept_name'=>$message_data['accept_name'] ,'mes_id'=>$mes_id, 'contacts_name'=>$message_data['accept_name'], 'to_uid_header_img'=>$message_data['to_uid_header_img'], 'timeStamp'=>time()))->query();
                    // $insert_id = $db1->insert('oms_nearest_contact')->cols(array('pid'=>$mes_id, 'mestype'=>$message_data['mestype'],'session_no'=>$message_data['session_no'], 'sender_name'=>$message_data['sender_name'], 'accept_name'=>$message_data['accept_name'], 'contacts_name'=>$message_data['sender_name'], 'mes_id'=>$uid, 'to_uid_header_img'=>$message_data['to_uid_header_img'], 'timeStamp'=>time()))->query();
                } else {
                    $db1->query('UPDATE `oms_groups_people` SET `contacts_id`=0 WHERE `pid`='.$message_data['session_no'].' AND `staffid`='.$uid);
                    if (count($conNum) != 0) {
                        return;
                    }
                    $insert_id = $db1->insert('oms_nearest_contact')->cols(array('mestype'=>$message_data['mestype'],'session_no'=>$message_data['session_no'], 'sender_name'=>$message_data['sender_name'], 'accept_name'=>$message_data['accept_name'] ,'mes_id'=>$mes_id, 'to_uid_header_img'=>$message_data['to_uid_header_img'], 'timeStamp'=>time()))->query();
                }
                
                break;
            //删除最近联系人
            case 'delContact':
                $db1 = Db::instance('oms');
                $uid = $_SESSION['uid'];
                if ($message_data['mestype'] != 'message' ) {
                    $db1->query('UPDATE `oms_groups_people` SET `contacts_id`=1 WHERE `staffid`='.$uid.' AND  `pid`="'.$message_data['session_no'].'"');
                } else {
                    $db1->query('DELETE FROM `oms_nearest_contact` WHERE `pid` ='.$uid.' AND `id`="'.$message_data['id'].'"');
                }
                break;
            //更新联系人
            case 'updContact':
                $db1 = Db::instance('oms');
                $uid = $_SESSION['uid'];
                $db1->query('UPDATE `oms_nearest_contact` SET `timeStamp`='.time().' WHERE `session_no`='.$message_data['session_no']);
                break;
            //群聊人显示
            case 'groupManShow':
                $db1 = Db::instance('oms');
                $uid = $_SESSION['uid'];
                $groupShowManId = $message_data['session_no'];
                $arrGroupMan = $db1->query('SELECT a.*,b.`group_founder`,c.name,c.card_image FROM `oms_groups_people` a LEFT join `oms_group_chat` b ON a.`pid` = b.`id` LEFT join  `oms_hr` c ON a.`staffid` = c.`staffid` WHERE a.`state` = 0 AND a.`pid`='.$groupShowManId);
                $arrGroupMan['type'] = 'showGroupMan';
                Gateway::sendToClient($client_id, json_encode($arrGroupMan));
                break;
            case 'customer':
                $db1 = Db::instance('oms');
                $uid = $_SESSION['uid'];
                return;
            case 'allOnlineNum':
                $uid = $_SESSION['uid'];
                // echo $adminUid;
                if ( $uid == $adminUid) {
                    //防止别人冒充
                    $arrALlonlineMan = [];
                    $allClients_list = Gateway::getALLClientInfo();
                    foreach ($allClients_list as $key => $value) {
                        if ( in_array( $value['uid'], $arrALlonlineMan ) ) {
                            unset( $allClients_list[$key] );
                        } else {
                            $arrALlonlineMan[] = $value['uid'];
                        }
                    }
                    $allOnlineNum = count($allClients_list);
                    $new_allOnlineNum['type'] = 'allOnlineNum';
                    $new_allOnlineNum['allOnlineNum'] = $allOnlineNum;
                    Gateway::sendToCurrentClient(json_encode($new_allOnlineNum));
                }
                return;
            case 'getChainEmployees':
                $Uid = $_SESSION['uid'];
                // $oms_id = $_SESSION['oms_id'];
                $oms_id = $message_data['oms_id'];
                $staff_list = Gateway::getClientInfoByGroup($oms_id);
                $new_staff_list = [];
                if (!empty($staff_list)) {
                    foreach($staff_list as $tmp_client_id=>$item)
                    {
                        $new_staff_list[$item['uid']]['client_name'] = $item['client_name'];
                        $new_staff_list[$item['uid']]['header_img_url'] = $item['header_img_url'];
                    }
                }
                $new_message_data = ['type'=>'chain_staff_list', 'staff_list'=>$new_staff_list];
                Gateway::sendToCurrentClient(json_encode($new_message_data));
                return;
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
        $adminUid = 1;
       if(isset($_SESSION['room_id']))
       {
           $room_id = $_SESSION['room_id'];
           $uid = $_SESSION['uid'];
           $logined = Gateway::getClientIdByUid($uid);
           //uid 还在线
           if (empty($logined)) {

                $new_message = array('type'=>'logout', 'from_uid_id'=>$uid, 'from_client_name'=>$_SESSION['client_name'], 'time'=>date('Y-m-d H:i:s'));
                Gateway::sendToGroup($room_id, json_encode($new_message)); 
                //发给管理员,要看所有的在线人数
                $new_logout = ['type'=> 'loggoutTwo'];
                Gateway::sendToUid( $adminUid, json_encode($new_logout) );

           }

           
       }
   }
  
}
