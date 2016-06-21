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
 * @license http://www.opensource.org/licenses/mit-license.php MIT License`
 */

/**
 * 聊天主逻辑
 * 主要是处理 onMessage onClose 
 */
use \GatewayWorker\Lib\Gateway;
use \GatewayWorker\Lib\Store;
use \GatewayWorker\Lib\Db;
use \Controllers\chatMessageController;

class Events
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
   		//管理员id
        $adminUid = 554;

        $message = str_replace(['<', '>'], ["&lt;", "&gt;"] , $message);
        $message_data = json_decode($message, true);
        if(!$message_data)
        {
            return ;
        }
        // 客户端回应服务端的心跳
        if ( $message_data['type'] == 'pong') {
            return;
        }
        //所有的控制器
        $arrType = array( 'sayUid', 'mes_chat', 'mes_groupChat', 'mes_load','mes_close', 'mes_notice_close', 'addGroupMan', 'delgroupman', 'dissolve_group', 'addContact', 'delContact', 'updContact', 'groupManShow', 'signOut', 'allOnlineNum', 'sys_mes_close' , 'sysNotice', 'chatAdmin');
        //发来的类型
        $type = $message_data['type'];
        //自己的信息
        $selfInfo = array(
            'room_id'=>$_SESSION['room_id'],
            'client_name'=>$_SESSION['client_name'],
            'uid'=>$_SESSION['uid'],
            'header_img_url'=>$_SESSION['header_img_url'],
            );

        //传来的type 是不是在数组里面
        if (in_array($type, $arrType)) {
            switch ( $type ) {
                case 'allOnlineNum':
                   //获取所有的 client_id  在线人的信息
                    $allClients_list = Gateway::getALLClientInfo();
                    if ( $selfInfo['uid'] != $adminUid ) {
                        return;
                    }
                    $message_data = $allClients_list;
                    break;
                case 'mes_chat':
                    $session_id = $selfInfo['uid'] < $message_data['to_uid'] ? $selfInfo['uid']."-".$message_data['to_uid'] : $message_data['to_uid']."-".$selfInfo['uid'];
                    $message_data['session_id'] = $session_id;
                    break;
                 case 'sys_mes_close':
                 	$message_data['session_id'] = $selfInfo['uid'].'sn';
                 	$type = 'mes_close';
                 	break;
                case 'mes_notice_close':
                    $message_data['session_id'] = $message_data['to_uid'].'t';
                break;
                case 'mes_groupChat':
                case 'addGroupMan':
                case 'signOut':
                case 'chatAdmin':
                    $message_data['session_id'] = $message_data['session_no'];
                    break;
                case 'sayUid':
                case 'mes_load':
                case 'mes_close':
                case 'updContact':
                    if ( $message_data['message_type']  == 'message') {
                        $session_no = $selfInfo['uid'] > $message_data['to_uid'] ? $message_data['to_uid']."-".$selfInfo['uid'] : $selfInfo['uid']."-".$message_data['to_uid'];
                        $message_data['session_id'] = $session_no;
                    } else {
                        $message_data['session_id'] = $message_data['session_no'];
                    }
                    break;
                default:
                    break;
            }

            //$selfInfo 自己的一些信息 $message_data 客户端发来的数据
           $chatMessageData =new \Controllers\chatMessageController($selfInfo, $message_data);

            //根据客户端传来的类型调用相应的方法
           $resMessageData = $chatMessageData->init($type);
           if ($type == 'sayUid') {
                Gateway::sendToUid($resMessageData['to_uid'], json_encode($resMessageData));

           } else {
                Gateway::sendToClient($client_id, json_encode($resMessageData));
           }

        } else {
            switch($message_data['type'])
            {
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
                     // 判断是否有房间号
                	if(!isset($room_id))
                    {
                        throw new \Exception("\$message_data['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$message");
                    }
                    //判断是否已经登录
                    $logined = Gateway::getClientIdByUid($uid);
                    //绑定uid
                    Gateway::bindUid($client_id, $uid);
                    
                    // 获取房间内所有用户列表 
                    
                    $new_clients_list = [];
                    $clients_list = [];
                    $clients_list = Gateway::getClientInfoByGroup($room_id);
                    //还没有登录
                    if (empty( $logined )) {
                        $clients_list[$client_id]['uid'] = $uid;
                        $clients_list[$client_id]['header_img_url'] = $header_img_url;
                        $clients_list[$client_id]['client_name'] = htmlspecialchars($client_name);
                    };
                    if (!empty($clients_list)) {
                        foreach($clients_list as $tmp_client_id=>$item)
                        {
                            $new_clients_list[$item['uid']]['client_name'] = $item['client_name'];
                            $new_clients_list[$item['uid']]['header_img_url'] = $item['header_img_url'];
                        }
                    }
                    //判断还没有登陆
                    $new_message = array('type'=>'login', 'client_list'=>$clients_list, 'time'=>date('Y-m-d H:i:s'));
                    if (empty($logined)) {
                        //未登录
                        //管理员在线发送自己的信息；
                        $adminOnline = Gateway::getClientIdByUid( $adminUid );
                        if (!empty($adminOnline)) {
                            $selfInfo = array(
                                'room_id'=>$_SESSION['room_id'],
                                'client_name'=>$_SESSION['client_name'],
                                'uid'=>$_SESSION['uid'],
                                'header_img_url'=>$_SESSION['header_img_url'],
                                );
                            $adminlogin = ['type'=>'adminLoginNum', 'client_num'=>$selfInfo ];
                            Gateway::sendToUid( $adminUid, json_encode($adminlogin));

                        }
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
                //admin 登录 
                case 'adminLogin':

                    $res = ['type'=> 'adminLogin'];
                    Gateway::sendToClient($client_id, json_encode($res));
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
                case 'companyFollow':
                    $uid = $_SESSION['uid'];
                    $oms_id = $_SESSION['room_id'];
                    $followid = $message_data['chinaOms_id'];
                    $db1 = Db::instance('oms');
                    $newTime = time();
                    /**********************************/
                    if ( $message_data['customerStatu'] == 0 ) {

                        $arrCompanyFollow = $db1->select('followid')->from('oms_company_follow')->where('oms_id = :oms_id AND followid= :followid')->bindValues(array('oms_id'=>$oms_id, 'followid'=>$followid))->row();
                        if ( empty( $arrCompanyFollow ) ) {
                            $db1->insert('oms_company_follow')->cols(array("oms_id"=> $oms_id, "followid"=> $followid, "create_time"=> $newTime, "update_time"=> $newTime))->query();
                        }
                    // } else if ( $message_data['customerStatu'] == 1 ) {
                    //     $db1->update('oms_company_follow')->cols(array("statu"=> 1))->where('oms_id = :oms_id AND followid = :followid')->bindValues(array('oms_id'=> $oms_id, 'followid'=> $followid))->query();
                    // }
                    } else if ( $message_data['customerStatu'] == 2 ) {
                        $db1->delete('oms_company_follow')->where('oms_id = :oms_id AND followid = :followid')->bindValues(array('oms_id'=> $oms_id, 'followid'=> $followid))->query();
                    }
                    // if ( $message_data['customerStatu'] == 0 ) {
                    //     $db1->insert('')
                    // }
                    // $db1->select('followid')->from('')
                    return;
                case "addFriends":
                    //实例化数据
                    $db1 = Db::instance('oms');

                    $uid = $_SESSION['uid'];
                    $room_id = $_SESSION['room_id'];
                    $client_name = $_SESSION['client_name'];
                    $header_img_url = $_SESSION['header_img_url'];
                    $to_uid = $message_data['uid'];
                    $nowTime = time();
                    
                    /****************   验证是否存在   *******************/
                    $selectCol = $db1->select('state')->from('oms_friend_list')->where('pid= :pid AND  staffid= :staffid')->bindValues(array('pid' => $uid, 'staffid'=> $message_data['uid']))->limit(2)->column();
                    if ( !empty($selectCol) ) {

                        if ( count($selectCol) == 1) {

                             if ($selectCol[0] == 1) {

                                return;
                             } else if ( $selectCol[0] == 0 ) {

                                $db1->update('oms_friend_list')->cols(array("state"))->where('pid= '.$uid.' AND  staffid= '.$message_data['uid'])->bindValue('state', 1)->query();
                             } else if ( $selectCol[0] == 2 ) {
                                    return;
                             }
                        } else {

                            return;
                        }

                    } else {

                        $db1->insert('oms_friend_list')->cols(array("pid"=> $uid, "staffid"=> $message_data['uid'], "pid_name"=>$client_name, "pid_header_url"=>$header_img_url, "additional_Information" =>$message_data['companyName'], "create_time"=> $nowTime, "update_time"=> $nowTime, "oms_id"=> $message_data['oms_id']))->query();

                    }

                    $session_no = $uid.'t';

                    $insert_id = $db1->insert('oms_string_message')->cols(array('room_id'=>$room_id, 'sender_id'=>$uid,'accept_id'=>$to_uid, 'sender_name'=>$client_name, 'accept_name'=>$message_data['accept_name'],'message_type'=> 'message', 'mesages_types'=> 'notice','dialog'=> 0, 'message_content'=>$message_data['companyName'], 'session_no'=>$session_no, 'create_time'=>time(), 'update_time'=>time()))->query();

                        $new_message['insert_id'] = $insert_id;

                    /**********   向客户端发送数据  *****************/
                    $new_message = array(
                            'type'=>'say_uid',
                            'from_client_id'=>$client_id, 
                            'from_client_name' =>$client_name,
                            'from_uid_id'=>$uid,
                            'header_img_url'=>$header_img_url,
                            'mestype'=> 'message',
                            'mes_types'=> 'notice',
                            'session_no'=>$session_no,
                            'insert_id'=> $room_id,
                            'content'=>$message_data['companyName'],
                            'time'=>date('Y-m-d H:i:s'),
                        );
                    /******************************   消息列表插入  ***********************************/

                    $chat_res = $db1->single('SELECT `id` FROM `oms_chat_message_ist` WHERE `pid`='.$message_data['uid'].' AND `mews_types`="notice"');

                    if (!empty($chat_res)) {

                       $db1->query("UPDATE `oms_chat_message_ist` SET `mes_num` = `mes_num`+1, `mes_id`=$insert_id WHERE id=".$chat_res);
                       $insert_id = $chat_res;

                    } else {

                        $insert_id = $db1->insert('oms_chat_message_ist')->cols(array('pid'=>$to_uid, 'session_no'=>$session_no, 'mes_id'=>$insert_id, 'chat_header_img'=>$header_img_url, 'oms_id'=>$room_id, 'mews_types'=>'notice'))->query();
                    }
                        // $new_message['insert_id'] = $insert_id;
                    Gateway::sendToUid($to_uid, json_encode($new_message));
                    return;
                case 'chat_notice_sel':
                    $uid = $_SESSION['uid'];
                    $room_id = $_SESSION['room_id'];
                    $client_name = $_SESSION['client_name'];
                    $header_img_url = $_SESSION['header_img_url'];
                    $db1 = Db::instance('oms');
                    $senderId = $message_data['senderId'];
                    $dataParm = $message_data['dataParm'];
                    
                    if ( !empty($senderId) ) {
                        if ( $dataParm == "unagree" ) {
                            $db1->delete('oms_friend_list')->where('pid = '.$senderId.' AND staffid='.$uid)->query();
                        } else {

                            $db1->query('UPDATE `oms_friend_list` SET `state` = 2 WHERE pid = '.$senderId.' AND staffid='.$uid);
                             $insert_id1 = $db1->insert('oms_friend_list')->cols( array('pid'=>$uid,'pid_name'=>$client_name,'pid_header_url'=> $header_img_url,'additional_Information'=>'同意', 'staffid'=>$senderId,'state'=> 2,'oms_id'=>$message_data['oms_id'], 'create_time'=>time(), 'update_time'=>time()))->query();
                            $session_no = $uid > $message_data['senderId'] ? $message_data['senderId']."-".$uid : $uid."-".$message_data['senderId'];

                            $insert_id = $db1->insert('oms_string_message')->cols(array('room_id'=>$room_id, 'sender_id'=>$uid,'accept_id'=>$senderId, 'sender_name'=>$client_name,'message_type'=> 'message', 'mesages_types'=> 'notice_respond', 'message_content'=>'可以会话了', 'session_no'=>$session_no,'dialog'=> 0, 'create_time'=>time(), 'update_time'=>time()))->query();
                            $new_message = array(
                                'type'=>'say_uid',
                                'from_client_id'=>$client_id, 
                                'from_client_name' =>$client_name,
                                'from_uid_id'=>$uid,
                                'header_img_url'=>$header_img_url,
                                'mestype'=> 'message',
                                'mes_types'=> 'notice_respond',
                                'session_no'=>$session_no,
                                'insert_id'=>$insert_id,
                                'content'=>'可以对话了',
                                'time'=>date('Y-m-d H:i:s'),

                            );
                            $insert_id = $db1->insert('oms_chat_message_ist')->cols(array('pid'=>$senderId, 'session_no'=>$session_no,'mes_id'=>$insert_id, 'chat_header_img'=>$header_img_url, 'oms_id'=>$room_id, 'mews_types'=>'notice_respond'))->query();
                            Gateway::sendToUid($senderId, json_encode($new_message));
                        }
                    }
                return;
            }
        }
        return;
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
        $adminUid = 554;
       if(isset($_SESSION['room_id']))
       {
            $room_id = $_SESSION['room_id'];
            $uid = $_SESSION['uid'];
            $logined = Gateway::getClientIdByUid($uid);
            //判断是否$uid 还在线
            // $isOnline = Gateway::isUidOnline($uid);
            //uid 不在线
            if (empty($logined)) {
                $new_message = array('type'=>'logout', 'from_uid_id'=>$uid, 'from_client_name'=>$_SESSION['client_name'], 'time'=>date('Y-m-d H:i:s'));
                Gateway::sendToGroup($room_id, json_encode($new_message)); 
                
                $isAdminOnline =  Gateway::getClientIdByUid($adminUid);
                if ( empty($logined) ) {
                    //发给管理员,要看所有的在线人数
                    $new_logout = ['type'=> 'loggoutTwo', 'from_uid'=>$uid ];
                    Gateway::sendToUid( $adminUid, json_encode($new_logout) );
                }

            }

           
       }
   }
  
}
