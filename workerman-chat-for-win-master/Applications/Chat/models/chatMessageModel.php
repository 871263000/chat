<?php 

namespace models;

use \GatewayWorker\Lib\Db;

/**
* 单例模式
* 消息的处理数据
*/
class chatMessageModel
{
	//数据连接资源
	public $db = null;
	//自己的信息
	public $selfInfo;
	//客户端发来的消息
	public $messageData;
	static private $_instance  = null;

	private function __construct()
	{
		// $this->db = Db::instance('oms');
		// $this->selfInfo = $selfInfo;
		// $this->messageData = $messageData;
	}
	// 获取 单例对象
	static public function getInstance () 
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new chatMessageModel();
		}
		return self::$_instance;

	}
	// 初始数据
	public function init( $selfInfo, $messageData ) 
	{
		if ( is_null($this->db) ) {
			$this->db = Db::instance('oms');
		}
		$this->selfInfo = $selfInfo;
		$this->messageData = $messageData;
	} 
	//判断是不是在一个房间
	public function isSameRooomModel ()
	{
		if (!empty($this->messageData['to_uid'])) {
			$resRoomId = $this->db->select('oms_id')->from('oms_hr')->where('staffid= :staffid')->bindValues(array('staffid'=>$this->messageData['to_uid']))->row();
			if ( $resRoomId['oms_id'] == $this->selfInfo['room_id'] ) {
				return true;
			} else {
				return false;
			}
		}
		return false;
		
	}

	//聊天选择人
	public function selectManModel()
	{

		$mes_list = $this->db->query("SELECT a.`id`, a.`message_content`, a.`mesages_types`, a.`create_time`, a.`sender_name`, a.`sender_id`, b.`card_image_smail` as card_image, a.`delState`, a.`accept_name` FROM `oms_string_message` a LEFT JOIN `oms_hr` b ON a.`sender_id` = b.staffid  WHERE a.`dialog` = 1 AND  a.`session_no`= '".$this->messageData['session_id']."' ORDER BY id desc limit 0, 10");
		if (!empty($mes_list)) {
            foreach ($mes_list as $key => $value) {
                   $mes_list[$key]['create_time'] = date('Y-m-d H:i:s', $value['create_time']);
            }
        }
        $mes_list['type'] = 'mes_chat';
		return $mes_list;
	}
	public function chatAdminModel() 
	{
		return $this->selectManModel();
		// $arrChatAdmin = $this->db->select('*')->from('oms_string_message')->where('session_no= :session_no')->bindValues(array('session_no'=> 'ca'))->query();
	}
	//选择群聊天
	public function selectGroupChat () 
	{
		$yanzheng = $this->db->select('staffid')->from('oms_groups_people')->where('pid= :pid')->bindValues(array('pid'=>$this->messageData['session_id']))->column();
		if (!is_array($yanzheng)) {
			return array('type'=>'mes_chat');
		}
        if (!in_array($this->selfInfo['uid'], $yanzheng) ) {
        	return array('type'=>'mes_chat');
        }
        $group_mes_list = $this->selectManModel();

        //群聊的信息
        $group_mes_list['type'] = 'mes_chat';
        return $group_mes_list;
	}
	//判断是不是好友
	public function isFriendsModel () 
	{
		if (!empty($this->messageData['to_uid'])) {
			$selectyz = $this->db->select('id')->from('oms_friend_list')->where('pid = :pid AND staffid= :staffid AND state= :state')->bindValues(array('pid'=> $this->selfInfo['uid'], 'staffid'=>$this->messageData['to_uid'], 'state'=> 2))->row();
			if ( empty( $selectyz )) {
				return false;
			} else {
				return true;
			}
		}
		return false;
	}
	//消息列表的的插入
	/**
	 * @$selfInfo, $this->messageData['to_uid']  接收人的 id， $this->messageData['accept_name'] 接收人的 名字， $this->messageData['mes_types'] 类型 ，$this->messageData['content'] 内容 ，$this->messageData['session_id']  会话id
	 */
	public function messageInsertModel () 
	{

		if ( $this->messageData['mes_types'] == 'image') {
			$this->messageData['content'] =  str_replace(['img[http://7xq4o9.com1.z0.glb.clouddn.com/', ']'], '', $this->messageData['content']);
			$this->messageData['content'] = $this->image64tofile( $this->messageData['content'] );
			$this->messageData['content'] = 'img['.$this->messageData['content'].']';
		}
		$insert_id = $this->db->insert('oms_string_message')->cols(array('room_id'=>$this->selfInfo['room_id'], 'sender_id'=>$this->selfInfo['uid'],'accept_id'=>$this->messageData['to_uid'], 'sender_name'=>$this->selfInfo['client_name'], 'accept_name'=>$this->messageData['accept_name'],'message_type'=>$this->messageData['message_type'], 'mesages_types'=>$this->messageData['mes_types'], 'message_content'=>$this->messageData['content'], 'session_no'=>$this->messageData['session_id'], 'create_time'=>time(), 'update_time'=>time()))->query();
		return $insert_id;
	}
	//消息通知的记录插入
	public function noticeInsertModel ( $insert_id ) 
	{
		//类型是message
		if ( $this->messageData['message_type'] == 'message' ) {
			$chat_res = $this->db->single('SELECT `id` FROM `oms_chat_message_ist` WHERE  `pid`= '.$this->messageData['to_uid'].' AND `session_no`="'.$this->messageData['session_id'].'"');
            if (!empty($chat_res)) {
               $this->db->query("UPDATE `oms_chat_message_ist` SET `mes_num` = `mes_num`+1, `mes_id`=".$insert_id ." WHERE id=".$chat_res);
               $insert_id = $chat_res;

            } else {
                $insert_id = $this->db->insert('oms_chat_message_ist')->cols(array('pid'=>$this->messageData['to_uid'], 'session_no'=>$this->messageData['session_id'], 'mes_id'=>$insert_id, 'chat_header_img'=>$this->selfInfo['header_img_url'], 'oms_id'=>$this->selfInfo['room_id']))->query();
            }
		} else if ( $this->messageData['message_type'] == 'groupMessage' ){
			$pattern = '/{@(.+)@}/U';
			preg_match_all($pattern,$this->messageData['content'] , $matches);
			if ( !empty($matches[1]) ) {
				$mentionUid = [];
				foreach ($matches[1] as $key => $value) {
					$nameUid = explode('|', $value);
					$mentionUid[] = $nameUid[1];
				}
				$mentionUidStr = implode(',', $mentionUid);
				$this->db->query("UPDATE `oms_groups_people` SET `mention`=concat(`mention`, '" . $this->selfInfo['client_name'] . ",') WHERE `staffid` in (".$mentionUidStr.") AND `pid`=".$this->messageData['session_id']);
			}
			$this->db->query("UPDATE `oms_groups_people` SET `mes_state`=1, `mes_num`=`mes_num`+1, `mes_id`=".$insert_id." WHERE `staffid` != ".$this->selfInfo['uid']." AND `pid`=".$this->messageData['session_id']);

		} elseif ( $this->messageData['message_type'] == 'adminMessage' ) {
			$this->db->query("UPDATE `oms_hr` SET  `mes_num`=`mes_num`+1, `mes_id`=".$insert_id." WHERE `staffid` != ".$this->selfInfo['uid']." AND `general_admin`=1");
		}
		$sendMessageData = array(
			'type'=> 'say_uid',
			'accept_name'=> $this->selfInfo['client_name'],
			'sender_id'=> $this->selfInfo['uid'],
			'card_image'=> $this->selfInfo['header_img_url'],
			'mestype'=> $this->messageData['message_type'],
			'mesages_types'=> $this->messageData['mes_types'],
			'message_content'=> $this->messageData['content'],
			'id'=> $insert_id,
			'session_no'=> $this->messageData['session_id'],
			'create_time'=>date('Y-m-d H:i:s'),
			);
		return $sendMessageData;
	}
	//通知消息的录入
	public function messageNoticeCloseModel() 
	{
		if ( isset($this->messageData['session_id'])) {
			
			$this->db->query("DELETE FROM `oms_chat_message_ist` WHERE `session_no`= '".$this->messageData['session_id']."'");
			
		}
        $this->db->query("UPDATE `oms_friend_list` SET `state`=0 WHERE `pid`= '".$this->messageData['to_uid']."' and `staffid`= ".$this->selfInfo['uid']);

        $arrChat_notice = $this->db->select('*')->from('oms_friend_list')->where('staffid= :staffid AND state= :state')->bindValues(array("staffid"=> $this->selfInfo['uid'], "state"=> 0))->query();
        
        $arrChat_notice['type'] = 'mes_notice_close';
        return $arrChat_notice;
	}
	//验证是不是在群聊里
	public function isInGroupModel () 
	{
		//验证是否在群聊
		$va = $this->db->select('`staffid`,`group_name`')->from('oms_groups_people')->where('pid = :pid')->bindValues(array('pid'=> $this->messageData['session_id']))->query();
	    // $arrVa = explode(',', $va['group_participants']);
	    $uid = $this->selfInfo['uid'];
	    $group_name = '';
	    $arrVa = array();
	    $strVa = '';
	    $is = false;
	    if (empty($va)) {
	    	return;
	    }
	    foreach ($va as $key => $value) {
	    	if ( $uid == $value['staffid'] ) {
	    		$is = true;
	    	} else {
	    		$arrVa[] = $value['staffid'];
	    	}
	    }
	    $strVa = implode(',', $arrVa);
	    if ($is) {
	    	$resData = array(
	    		'to_uid_id'=> $arrVa,
	    		'group_name'=> $va[0]['group_name'],
	    		'group_participants' => $strVa,
	    		);
	        return $resData;
	    }
	    return false;
	}
	//翻页
	public function mesLoadModel() 
	{
		if (!empty($this->messageData['mes_loadnum'])) {
	        $onlode = $this->db->query("SELECT a.`id`, a.`message_content`, a.`mesages_types`, a.`create_time`, a.`accept_name`, a.`sender_id`, a.`sender_name`,b.`card_image_smail` as card_image  FROM `oms_string_message` a LEFT JOIN `oms_hr` b ON a.`sender_id`=b.`staffid` WHERE a.`dialog` = 1 AND a.`session_no`= '".$this->messageData['session_id']."' ORDER BY a.create_time desc limit ".$this->messageData['mes_loadnum'].", 10");

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
	//图片64 转 文件
	public function image64tofile( $string ) {
		$pa = $string;
        if (preg_match("/^(data:\s*image\/(\w+);base64,)/", $pa, $result)){
            $type = $result[2];
            //创建文件夹
            $save_path = "../chatImage/".$this->messageData['to_uid'] . "/";
            $save_url = "/chat/chatImage/".$this->messageData['to_uid'] . "/";
            if (!file_exists($save_path)) {
                mkdir($save_path);
            }
            //新文件名
            $new_file_name = date("YmdHis") . rand(1000, 9999) . '.' . $type;
            if (file_put_contents($save_path.$new_file_name, base64_decode(str_replace($result[1], '', $pa)))){
                return $message_content = $save_url.$new_file_name;
            } else {
                return false;
            }
        }

	}
	//消息的关闭
	public function messageCloseModel() 
	{
		$to_uid = '';
		if ( $this->messageData['message_type'] == 'message' ) {
			$to_uid = $this->messageData['to_uid'];
			$mesNum = $this->db->select('mes_num')->from('oms_chat_message_ist')->where('session_no= :session_no')->bindValues(array('session_no'=>$this->messageData['session_id']))->row();
            $this->db->query("DELETE FROM `oms_chat_message_ist` WHERE `session_no`= '".$this->messageData['session_id']."'");
        } else if ($this->messageData['message_type'] == 'groupMessage') {

			$mesNum = $this->db->select('mes_num')->from('oms_groups_people')->where('pid= :pid AND staffid= :staffid')->bindValues(array('pid'=>$this->messageData['session_id'], 'staffid'=> $this->selfInfo['uid'] ))->row();

			$to_uid = $this->db->select('staffid')->from('oms_groups_people')->where('pid= :pid')->bindValues(array('pid'=>$this->messageData['session_id']))->query();

            $this->db->query("UPDATE `oms_groups_people` SET `mes_state`=0, `mention` = 0, `mes_num`=0 WHERE `staffid` = ".$this->selfInfo['uid']." AND `pid`='".$this->messageData['session_id']."'");
        } else if ( $this->messageData['message_type'] == 'adminMessage') {
        	$this->db->query("UPDATE `oms_hr` SET `mes_num`=0 AND `mes_id` =0 WHERE `staffid` = ".$this->selfInfo['uid']);
        	return false;
        }
        if ( !empty($mesNum)  ) {
			return $data = ['type'=> 'mesClose','mesType'=>$this->messageData['message_type'], 'to_uid'=> $to_uid, 'mesNum'=> $mesNum['mes_num'], 'session_id'=> $this->messageData['session_id']];
        }
	}
	//增加群聊
	public function addGroupManModel () 
	{
		//如果在群聊里 返回 群聊信息
		$resData = $this->isInGroupModel();
		$arr = [];
		$addMan = [];
		$groupParticipants = $resData['group_participants'];
		$arrGroupParticipants = explode(',', $groupParticipants);
		$sidList = $this->messageData['sidList'];
		$sidList = array_unique($sidList);
        foreach ($sidList as $key => $value) {
            if (in_array($value, $arrGroupParticipants)) {
                unset($sidList[$key]);
            }
        }
        if (!empty($sidList)) {
        	$sAddGroupMan = implode(",", $sidList);
	        foreach ($sidList as $k => $val) {

	            $arrvalue[] = "('".$this->messageData['session_no']."', '".$val."', '".$resData['group_participants']."', '".$resData['group_name']."', ".time()." ,".time().")";

	        }

	        $strvalue = implode(",", $arrvalue);
	        $this->db->query("INSERT INTO `oms_groups_people` (`pid`, `staffid`, `all_staffid`, `group_name`, `create_time`, `update_time`) value".$strvalue);
	        $this->db->query("UPDATE `oms_group_chat` SET `group_participants` = concat(`group_participants`, ',".$sAddGroupMan."') WHERE id=".$this->messageData['session_id']);
	        $this->db->query("UPDATE `oms_groups_people` SET `all_staffid`= concat(`all_staffid`, ',".$sAddGroupMan."') WHERE `pid`=".$this->messageData['session_id']);
        }
        

	}
	//退出该群
	public function signOut() 
	{
		//如果在群聊里 返回 群聊信息
		$resData = $this->isInGroupModel();
		$groupParticipants = $resData['group_participants'];
		$arrGroupParticipants = explode(',', $groupParticipants);
		
		foreach ($arrGroupParticipants as $key => $value) {
			if ( $value == $selfInfo['uid'] ) {
				unset($arrGroupParticipants[$key]);
			}
		}
		$strGroupParticipants = implode(',', $arrGroupParticipants);
		$this->db->query("UPDATE `oms_group_chat` SET `group_participants` = $strGroupParticipants   WHERE id=".$this->messageData['session_id']);
		$this->db->delete('oms_groups_people')->where('pid='.$this->messageData['session_id'].' AND `staffid`='.$selfInfo['uid'])->query();
	    $this->db->query("UPDATE `oms_groups_people` SET `all_staffid`= $strGroupParticipants  WHERE `pid`=".$this->messageData['session_id']);
	}
	//删除群聊人
	public function updateGroupMan ()
	{
		$arrgrouppeople = [];
		if ( !empty($this->messageData['groupid']) ) {
			$arrgrouppeople = $this->db->select('*')->from('oms_group_chat')->where('id= :id')->bindValues(array('id'=>$this->messageData['groupid']))->row();
		}
        $arrjoinGroup = explode(',', $arrgrouppeople['group_participants']);

        if ($this->selfInfo['uid'] == $arrgrouppeople['group_founder']) {
            foreach ($arrjoinGroup as $key => $value) {
                if ( $value == $this->messageData['id'] ) {
                    unset($arrjoinGroup[$key]);
                    break;
                }
            }
            $unarrjoinGroup = $arrjoinGroup;
            $unstrgroupman = implode(",", $unarrjoinGroup);
                $this->db->query("UPDATE `oms_groups_people` SET `all_staffid`= '".$unstrgroupman."' WHERE `pid`=".$arrgrouppeople['id']);
                $this->db->query("DELETE FROM `oms_groups_people` WHERE `staffid`= ".$this->messageData['id']." AND `pid`=".$arrgrouppeople['id']);
                $this->db->query("UPDATE `oms_group_chat` SET `group_participants`= '".$unstrgroupman."' WHERE `id`=".$arrgrouppeople['id']);
        }
	}
	//群聊解散
	public function dissoleGroup() 
	{

		$dissolve_group = $this->db->select('group_founder')->from('oms_group_chat')->where('id= :id')->bindValues(array('id'=>$this->messageData['groupId']))->row();

        if ( $this->selfInfo['uid'] == $dissolve_group['group_founder']) {

            $row_count = $this->db->delete('oms_group_chat')->where('id='.$this->messageData['groupId'])->query();
            $row_count = $this->db->delete('oms_groups_people')->where('pid='.$this->messageData['groupId'])->query();
            $this->db->delete('oms_nearest_contact')->where('session_no='.$this->messageData['groupId'].' AND pid='.$this->selfInfo['uid'])->query();
        }
	}
	// 退出 群 
	public function escGroup ( ) 
	{
		$dissolve_group = $this->db->select('staffid')->from('oms_groups_people')->where('pid= :pid')->bindValues(array('pid'=>$this->messageData['groupId']))->column();

		if ( !empty( $dissolve_group ) ) {

			foreach ($dissolve_group as $key => $value) {
				if ( $this->selfInfo['uid'] == $value ) {
					unset( $dissolve_group[$key] );
				}
			}
			if ( !empty( $dissolve_group ) ) {
				$str = implode(',', $dissolve_group);
				$this->db->update('oms_group_chat')->cols(array('group_participants'))->where('id='.$this->messageData['groupId'])->bindValues(array('group_participants'=>$str))->query();
				$this->db->delete('oms_groups_people')->where('pid = :pid AND staffid =:staffid')->bindValues(array('pid'=> $this->messageData['groupId'], 'staffid'=> $this->selfInfo['uid']))->query();
			}
		}

	}
	//增加最近联系人
	public function  addCcontactModel ()
	{
		if ($this->messageData['mestype'] == "message") {
            $mes_id = $this->messageData['mes_id'];
        } else {
            $mes_id = $this->messageData['session_no'];
        }
        $conNum = $this->db->column('SELECT id FROM `oms_nearest_contact` WHERE `pid` = "'.$this->selfInfo['uid'].'" AND `session_no`="'.$this->messageData['session_no'].'"');
        if ( empty($conNum)) {
            $insert_id = $this->db->insert('oms_nearest_contact')->cols(array('pid'=>$this->selfInfo['uid'], 'mestype'=>$this->messageData['mestype'],'session_no'=>$this->messageData['session_no'], 'sender_name'=>$this->messageData['sender_name'], 'accept_name'=>$this->messageData['accept_name'] ,'mes_id'=>$mes_id, 'contacts_name'=>$this->messageData['accept_name'], 'to_uid_header_img'=>$this->messageData['to_uid_header_img'], 'timeStamp'=>time()))->query();
        }
	}
	//删除联系人
	public function delContactModel () 
	{

            $this->db->query('DELETE FROM `oms_nearest_contact` WHERE `pid` ='.$this->selfInfo['uid'].' AND `id`="'.$this->messageData['id'].'"');
	}
	//更新最近联系人
	public function updContactModel () 
	{
		$this->db->query('UPDATE `oms_nearest_contact` SET `timeStamp`='.time().' WHERE `pid` = '.$this->selfInfo['uid'].' AND `session_no`="'.$this->messageData['session_id'].'"');
	}

	//群聊显示
	public function groupManShowModel () 
	{
		$this->messageData['session_id'];
		if (!empty( $this->messageData['session_id'] )) {

			$groupShowManId = $this->messageData['session_id'];
        	$arrGroupMan = $this->db->query('SELECT a.*,b.`group_founder`,c.name,c.`card_image_smail` as `card_image` FROM `oms_groups_people` a LEFT join `oms_group_chat` b ON a.`pid` = b.`id` LEFT join  `oms_hr` c ON a.`staffid` = c.`staffid` WHERE a.`state` = 0 AND a.`pid`='.$groupShowManId);

		}   
		
        $arrGroupMan['type'] = 'showGroupMan';
        $arrGroupMan['Callback'] = $this->messageData['Callback'];
        return $arrGroupMan;
	}
	//根据roomid 获取 所有公司的名字
	public function getCompanyName( $rooms ){
		if (is_array( $rooms )) {
			$strRoom = implode(',', $rooms);
			$res = $this->db->query('SELECT `oms_id`, `org_name` FROM `oms_general_admin_user` WHERE oms_id in ('.$strRoom.')');
			$comInfo = [];
			if (!empty( $res )) {
				foreach ($res as $key => $value) {
					$comInfo[$value['oms_id']] = $value['org_name'];
				}
			}
			return $comInfo;

		}
	}
	//系统通知
	public function sysNotice(){
		$uid = $this->selfInfo['uid'];
		$session_id = $uid.'sn';
		$res = [];
		//查询结果
		if (!empty($uid)) {
			$res = $this->db->select('id,sender_name,message_content')->from('oms_string_message')->where('session_no = :session_no')->orderByASC(array('id'), false)->bindValues(array('session_no'=> $session_id))->query();
		}
		$res['type'] = 'sysNotice';
		return $res;
	}
	// 判断是不是系统管理员 是 返回 所有管理员的id
	public function isAdmin() {
		$uid = $this->selfInfo['uid'];
		$res = $this->db->select('staffid')->from('oms_hr')->where('general_admin = :general_admin AND state= :state')->bindValues(array('general_admin'=> 1, 'state'=> 0))->column();
		if ( in_array($uid , $res) ) {
			foreach ($res as $key => $value) {
				if ($value == $uid) {
					unset($res[$key]);
				}
			}
			return $res;
		}
		return false;
	}
	// 删除好友
	public function delFriend()
	{
		$uid = $this->selfInfo['uid'];
		if (!empty($this->messageData['uid'])) {
			$this->db->delete('oms_friend_list')->where('(pid = :pid AND staffid =:staffid) or (pid = :pid1 AND staffid =:staffid1)')->bindValues(array('pid'=> $uid, 'staffid'=> $this->messageData['uid'], 'pid1'=>$this->messageData['uid'] , 'staffid1'=> $uid))->query();
			// $this->db->delete('oms_friend_list')->where('pid = :pid AND staffid =:staffid')->bindValues(array('pid'=>$this->messageData['uid'] , 'staffid'=> $uid))->query();
		}
		return ['type'=> 'default'];
	}
	// 删除 聊天信息
	public function delChatMesModel()
	{
		$uid = $this->selfInfo['uid'];
		if (empty($this->messageData['mes_id'])) {
			return false;
		}
		if ( $this->messageData['dataMan'] == "self" ) {
			$this->db->update('oms_string_message')->cols(array('mesages_types', 'message_content'))->where('id ='.$this->messageData['mes_id'].' AND sender_id ="'.$uid.'"')->bindValues(['mesages_types'=>'revoke', 'message_content'=>'撤销了一条消息'])->query();
			return ;
		}
		$session_id = $this->selfInfo['uid'] < $this->messageData['uid'] ? $this->selfInfo['uid']."-".$this->messageData['uid'] : $this->messageData['uid']."-".$this->selfInfo['uid'];

		$this->db->update('oms_string_message')->cols(array('mesages_types', 'message_content', 'delState', 'dialog'))->where('id ='.$this->messageData['mes_id'].' AND session_no ="'.$session_id.'"')->bindValues(['mesages_types'=>'del', 'message_content'=>'撤销了一条消息', 'delState'=>$uid, 'dialog'=>0])->query();
		// $this->db->delete('oms_string_message')->where('id =:id AND sender_id =:sender_id')->bindValues(array('id'=>$this->messageData['mes_id'], 'sender_id'=>$uid))->query();
	}
	// 好友查找
	public function friendAddModel()
	{
		$res = [];
		// print_r($this->messageData);
		if ( $this->messageData['actType'] == "lookup" ) {
			$this->messageData['name'] = htmlspecialchars($this->messageData['name']);
			if ( empty( $this->messageData['name'] ) ) {
				$res['type'] = "default";
				return $res;
			}
			$res = $this->db->select('oms_hr.name, oms_hr.card_image_smail as card_image,oms_general_admin_user.org_name, oms_hr.staffid')->from('oms_hr')->innerJoin('oms_general_admin_user', 'oms_hr.oms_id = oms_general_admin_user.oms_id')->where('oms_hr.oms_id != '.$this->selfInfo['room_id'].' and name like "%'.$this->messageData['name'].'%" and oms_hr.state=0')->query();
				$res['type'] = 'searchFriends';
				return $res;
		} else if ($this->messageData['actType'] == "add") { // 好友的添加
			$sendData = ['type'=>'default'];
			if ( !empty( $this->messageData['staffid'] ) ) {
				$staffidInfo = $this->getStaffid( $this->messageData['staffid'] );

				$selectCol = $this->db->select('state')->from('oms_friend_list')->where('pid= :pid AND  staffid= :staffid')->bindValues(array('pid' => $this->selfInfo['uid'], 'staffid'=> $this->messageData['staffid']))->limit(2)->column();
				if ( !empty($selectCol) ) {
                    if ( count($selectCol) == 1) {
                         if ($selectCol[0] == 1) {

                            return;
                         } else if ( $selectCol[0] == 0 ) {
                            $this->db->update('oms_friend_list')->cols(array("state"))->where('pid= '.$this->selfInfo['uid'].' AND  staffid= '.$this->messageData['staffid'])->bindValue('state', 1)->query();
                         } else if ( $selectCol[0] == 2 ) {
                                return;
                         }
                    } else {

                        return;
                    }

                } else {
                    $this->db->insert('oms_friend_list')->cols(array("pid"=> $this->selfInfo['uid'], "staffid"=> $this->messageData['staffid'], "pid_name"=>$this->selfInfo['client_name'], "pid_header_url"=>$this->selfInfo['header_img_url'], "additional_Information" =>$staffidInfo['org_name'], "create_time"=> time(), "update_time"=> time(), "oms_id"=> $staffidInfo['oms_id']))->query();

                }
                // 通知消息
                //消息列表的的插入
				/**
				 * @$selfInfo, $this->messageData['to_uid']  接收人的 id， $this->messageData['accept_name'] 接收人的 名字， $this->messageData['mes_types'] 类型 ，$this->messageData['content'] 内容 ，$this->messageData['session_id']  会话id
				 */
				$to_uid = $this->messageData['staffid'];
				$this->messageData = ['to_uid'=>$this->messageData['staffid'], 'accept_name'=>$staffidInfo['name'], 'mes_types'=> 'notice','content'=>$staffidInfo['org_name'], 'session_id'=> $this->selfInfo['uid'].'t', 'message_type'=>'message'];
                $insert_id = $this->messageInsertModel();
                //通知消息的插入 返回 一组发给客户端的数据
				$sendData = $this->noticeInsertModel( $insert_id );
                $sendData['to_uid'] = $to_uid;
			}
			// $res['type'] = 'addFriends';
			return $sendData;
		}

	}
	// 客服的model  
	public function kefuSayModel() 
	{
		$allKefuId = [1,2,4];
		$kefuId = $this->messageData['to'];
		$data =[];

		if ( in_array($kefuId, $allKefuId) ) {
			$this->messageData['to_uid'] = $kefuId;
			$this->messageData['accept_name'] = '客服';
			$this->messageData['message_type'] = 'message';
			$this->messageData['mes_types'] = 'kefu';
			$this->messageData['session_id'] =   $this->selfInfo['uid'] > $kefuId ? $kefuId.'-'.$this->selfInfo['uid'] : $this->selfInfo['uid'].'-'.$kefuId;
			$insertId = $this->messageInsertModel();
			$this->noticeInsertModel($insertId);
		}
		$sendMessageData = array(
			'type'=> 'say_uid',
			'accept_name'=> $this->selfInfo['client_name'],
			'sender_id'=> $this->selfInfo['uid'],
			'card_image'=> $this->selfInfo['header_img_url'],
			'mestype'=> 'message',
			'mesages_types'=> 'kefu',
			'message_content'=> $this->messageData['content'],
			'id'=> $insertId,
			'session_no'=> $this->messageData['session_id'],
			'create_time'=>date('Y-m-d H:i:s'),
			'to_uid'=> $kefuId,
		);
		return $sendMessageData;
	}

	// 查找一个人的信息
	/**
	 * @staffid 员工的id
	 */
	public  function getStaffid( $staffid )
	{
		$res = [];
		if ( !empty( $staffid ) ) {
			$res = $this->db->select('oms_hr.name, oms_general_admin_user.org_name, oms_hr.card_image_smail as card_image, oms_hr.oms_id')->from('oms_hr')->innerJoin('oms_general_admin_user', 'oms_general_admin_user.oms_id = oms_hr.oms_id')->where('oms_hr.staffid =:staffid')->bindValues(array('staffid'=>$staffid))->row();
		}
		return $res;
	}
}
 ?>