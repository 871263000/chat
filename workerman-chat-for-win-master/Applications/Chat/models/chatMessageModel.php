<?php 
namespace models;

use \GatewayWorker\Lib\Db;

/**
* 消息的处理数据
*/
class chatMessageModel
{
	//数据连接资源
	public $db;
	//自己的信息
	public $selfInfo;
	//客户端发来的消息
	public $messageData;

	function __construct($selfInfo, $messageData)
	{
		$this->db = Db::instance('oms');
		$this->selfInfo = $selfInfo;
		$this->messageData = $messageData;
	}
	//判断是不是在一个房间
	public function isSameRooomModel ()
	{
		if (!empty($this->messageData['to_uid_id'])) {
			$resRoomId = $this->db->select('oms_id')->from('oms_hr')->where('staffid= :staffid')->bindValues(array('staffid'=>$this->messageData['to_uid_id']))->row();
			if ( $resRoomId['oms_id'] == $this->selfInfo['room_id'] ) {
				return true;
			} else {
				return false;
			}
		}
		return false;
		
	}

	//聊天选择人
	public function selectManModel(){

		$mes_list = $this->db->query("SELECT a.`id`, a.`message_content`, a.`mesages_types`, a.`create_time`, a.`sender_name`, a.`sender_id`, b.`card_image` FROM `oms_string_message` a LEFT JOIN `oms_hr` b ON a.`sender_id` = b.staffid  WHERE a.`dialog` = 1 AND a.`session_no`= '".$this->messageData['session_id']."' ORDER BY create_time desc limit 0, 10");
		if (!empty($mes_list)) {
            foreach ($mes_list as $key => $value) {
                    $mes_list[$key]['create_time'] = date('Y-m-d H:i:s', $value['create_time']);
            }
        }
        $mes_list['type'] = 'mes_chat';
		return $mes_list;
	}

	//选择群聊天
	public function selectGroupChat () 
	{

		$yanzheng = $this->db->select('staffid')->from('oms_groups_people')->where('pid= :pid')->bindValues(array('pid'=>$this->messageData['session_id']))->column();

        if (!in_array($this->selfInfo['uid'], $yanzheng) ) {
        	return array('type'=>'mes_chat');
        }

        $group_mes_list = $this->db->query("SELECT a.`id`, a.`message_content`, a.`mesages_types`, a.`create_time`, a.`sender_name`, a.`sender_id`,a.`session_no`, b.`card_image` FROM `oms_string_message` a LEFT JOIN `oms_hr` b ON a.`sender_id`= b.staffid WHERE a.`delState` = 0 AND a.`dialog` = 1 AND a.`session_no`= '".$this->messageData['session_no']."' ORDER BY a.create_time desc limit 0, 10");

        if ( !empty($group_mes_list) ) {
            foreach ($group_mes_list as $key => $value) {

                    $group_mes_list[$key]['create_time'] = date('Y-m-d H:i:s', $value['create_time']);
            }
        }

        //群聊的信息
        $group_mes_list['type'] = 'mes_chat';
        return $group_mes_list;
	}
	//判断是不是好友
	public function isFriendsModel () 
	{
		if (!empty($this->messageData['to_uid_id'])) {
			$selectyz = $this->db->select('id')->from('oms_friend_list')->where('pid = :pid AND staffid= :staffid AND state= :state')->bindValues(array('pid'=> $this->selfInfo['uid'], 'staffid'=>$this->messageData['to_uid_id'], 'state'=> 2))->row();
			if ( empty( $selectyz )) {
				return false;
			} else {
				return true;
			}
		}
		return false;
	}
	//消息列表的的插入
	public function messageInsertModel () 
	{
		if ( $this->messageData['mes_types'] == 'image') {

			$this->messageData['content'] = $this->image64tofile( $this->messageData['content'] );
		}
		$insert_id = $this->db->insert('oms_string_message')->cols(array('room_id'=>$this->selfInfo['room_id'], 'sender_id'=>$this->selfInfo['uid'],'accept_id'=>$this->messageData['to_uid_id'], 'sender_name'=>$this->selfInfo['client_name'], 'accept_name'=>$this->messageData['accept_name'],'message_type'=>$this->messageData['message_type'], 'mesages_types'=>$this->messageData['mes_types'], 'groupId'=>$this->messageData['groupId'], 'message_content'=>$this->messageData['content'], 'session_no'=>$this->messageData['session_id'], 'create_time'=>time(), 'update_time'=>time()))->query();

		return $insert_id;
	}
	//消息通知的记录插入
	public function noticeInsertModel ( $insert_id ) 
	{
		//类型是message
		if ( $this->messageData['message_type'] == 'message' ) {
			$chat_res = $this->db->single('SELECT `id` FROM `oms_chat_message_ist` WHERE `session_no`="'.$this->messageData['session_id'].'"');
            if (!empty($chat_res)) {
               $this->db->query("UPDATE `oms_chat_message_ist` SET `mes_num` = `mes_num`+1, `mes_id`=".$insert_id ." WHERE id=".$chat_res);
               $insert_id = $chat_res;

            } else {
                $insert_id = $this->db->insert('oms_chat_message_ist')->cols(array('pid'=>$this->messageData['to_uid_id'], 'session_no'=>$this->messageData['session_id'], 'mes_id'=>$insert_id, 'chat_header_img'=>$this->selfInfo['header_img_url'], 'oms_id'=>$this->selfInfo['room_id']))->query();
            }
		} else if ( $this->messageData['message_type'] == 'groupMessage' ){
			$this->db->query("UPDATE `oms_groups_people` SET `mes_state`=1, `mes_num`=`mes_num`+1, `mes_id`=".$insert_id." WHERE `staffid` != ".$this->selfInfo['uid']." AND `pid`=".$this->messageData['session_id']);
		}
		$sendMessageData = array(
			'type'=> 'say_uid',
			'from_client_name'=> $this->selfInfo['client_name'],
			'from_uid_id'=> $this->selfInfo['uid'],
			'header_img_url'=> $this->selfInfo['header_img_url'],
			'mestype'=> $this->messageData['message_type'],
			'mes_types'=> $this->messageData['mes_types'],
			'content'=> $this->messageData['content'],
			'insert_id'=> $insert_id,
			'session_no'=> $this->messageData['session_id'],
			'time'=>date('Y-m-d H:i:s'),
			);
		return $sendMessageData;
	}
	//通知消息的录入
	public function messageNoticeCloseModel() 
	{
		if (!isset($this->messageData['session_no'])) {
			$this->db->query("DELETE FROM `oms_chat_message_ist` WHERE `session_no`= '".$this->messageData['session_no']."'");
			
		}
        $this->db->query("UPDATE `oms_friend_list` SET `state`=0 WHERE `staffid`= ".$this->selfInfo['uid']);

        $arrChat_notice = $this->db->select('*')->from('oms_friend_list')->where('staffid= :staffid AND state= :state')->bindValues(array("staffid"=> $this->selfInfo['uid'], "state"=> 0))->query();
        
        $arrChat_notice['type'] = 'mes_notice_close';
        return $arrChat_notice;
	}
	//验证是不是在群聊里
	public function isInGroupModel () 
	{
		//验证是否在群聊
		$va = $this->db->select('`group_participants`,`group_name`')->from('oms_group_chat')->where('id = :id')->bindValues(array('id'=> $this->messageData['session_id']))->row();
	    $arrVa = explode(',', $va['group_participants']);
	    $uid = $this->selfInfo['uid'];
	    if (in_array($this->selfInfo['uid'], $arrVa)) {
	    	foreach ($arrVa as $key => $value) {
		    	if ($value == $uid ) {
		    		unset($arrVa[$key]);
		    	}
		    }
	    	$resData = array(
	    		'to_uid_id'=> $arrVa,
	    		'group_name'=> $va['group_name'],
	    		);
	        return $resData;
	    }
	    return false;
	}
	//翻页
	public function mesLoadModel() 
	{
		if (!empty($this->messageData['mes_loadnum'])) {
	        $onlode = $this->db->query("SELECT a.`id`, a.`message_content`, a.`mesages_types`, a.`create_time`, a.`accept_name`, a.`sender_id`, a.`sender_name`,b.`card_image` FROM `oms_string_message` a LEFT JOIN `oms_hr` b ON a.`sender_id`=b.`staffid` WHERE a.`dialog` = 1 AND a.`session_no`= '".$this->messageData['session_id']."' ORDER BY a.create_time desc limit ".$this->messageData['mes_loadnum'].", 10");

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
	        return $onlode;
	    }
	    return false;
	}
	public function image64tofile( $string ) {
		$pa = $string;
        if (preg_match("/^(data:\s*image\/(\w+);base64,)/", $pa, $result)){
            $type = $result[2];
            //创建文件夹
            $save_path = "../chatImage/".$this->messageData['to_uid_id'] . "/";
            $save_url = "/chat/chatImage/".$this->messageData['to_uid_id'] . "/";
            if (!file_exists($save_path)) {
                mkdir($save_path);
            }
            //新文件名
            $new_file_name = date("YmdHis") . rand(1000, 9999) . '.' . $type;
            if (file_put_contents($save_path.$new_file_name, base64_decode(str_replace($result[1], '', $pa)))){
                return $message_content = "<img src='".$save_url.$new_file_name."' class='send-img'>";
            } else {
                return false;
            }
        }

	}
	//消息的关闭
	public function messageCloseModel() 
	{
		if ( $this->messageData['mestype'] == 'message' ) {
            $this->db->query("DELETE FROM `oms_chat_message_ist` WHERE `session_no`= '".$this->messageData['session_id']."'");
        } else {
            $this->db->query("UPDATE `oms_groups_people` SET `mes_state`=0, `mes_num`=0 WHERE `staffid` = ".$this->selfInfo['uid']." AND `pid`='".$this->messageData['session_id']."'");
        }
	}
}
 ?>