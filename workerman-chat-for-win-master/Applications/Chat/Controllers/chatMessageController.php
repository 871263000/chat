<?php 
namespace Controllers;

use models;
/**
* 单例模式
*/
class chatMessageController 
{
	//消息类型
	public $mesType;

	//数据模型
	public $model;

	//返回客户端的数据
	public $data;

	//自己的信息
	public $selfInfo;

	//客户端发来的数据
	public $messageData;
	// 单例对象
	static private $_instance  = null;
	/*******
	*@param 数组  $selfInfo ， 自己的基本信息
	*@param 数组  $messageData ， 客户端传来的数据
	*
	*****/
	private function __construct(  )
	{
		
	}
	// 获取 单例对象
	static public function getInstance () 
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new chatMessageController();
		}
		return self::$_instance;

	}
	/******
	*
	* return $data 返回的数据
	* $type 方法类型
	*
	********/
	public function init( $type, $selfInfo, $messageData )
	{
		// 初始化自己的 信息
		$this->selfInfo = $selfInfo;
		//初始化 客户端发来的消息
		$this->messageData = $messageData;

		$this->model = \models\chatMessageModel::getInstance();
		$this->model->init( $this->selfInfo, $this->messageData );
		// 客户端发来的控制器  调用相应的 方法
		return $data = $this->$type();
		
	}

	//发来的消息
	public function sayUid()
	{
		$insert_id= 0;
		if ($this->messageData['to_uid'] != 'all') {
			if ( $this->messageData['message_type'] == 'message' ) {
				//验证是不是在同一个房间
				$resRoom = $this->isSameRooom();
				if ( !$resRoom ) {
					$resIs = $this->isFriends();
					if ($resIs) {
						// 消息 内容 插入 返回 插入id
						$insert_id = $this->messageInsert();
						//通知消息的插入 返回 一组发给客户端的数据
						$sendData = $this->noticeInsert( $insert_id );
						$sendData['to_uid'] = $this->messageData['to_uid'];
						$sendData['id'] = $insert_id;
						return $sendData;
					}
					$sendData['to_uid'] = 0;
					return false;
				}
				//获得插入的id
				$insert_id = $this->messageInsert();
				//通知消息的插入 返回 一组发给客户端的数据
				$sendData = $this->noticeInsert( $insert_id );
				$sendData['to_uid'] = $this->messageData['to_uid'];
			// 群聊 插入
			} else if ( $this->messageData['message_type'] == 'groupMessage' ) {
				//是否在群聊里 如果在返回 群信息
				$getData = $this->isInGroup();
				if ( $getData ) {

					//获得插入的id
					$insert_id = $this->messageInsert();
					//通知消息的插入 返回 一组发给客户端的数据
					$sendData = $this->noticeInsert( $insert_id );
					$sendData['group_name'] = $getData['group_name'];
                    $sendData['to_uid'] = $getData['to_uid_id'];
				}
			} else if ( $this->messageData['message_type'] == 'adminMessage' ) {
				$getAdmin = $this->model->isAdmin();
				if ($getAdmin) {
					//获得插入的id
					$insert_id = $this->messageInsert();
					//通知消息的插入 返回 一组发给客户端的数据
					$sendData = $this->noticeInsert( $insert_id );
					$sendData['group_name'] = '管理员群';
                    $sendData['to_uid'] = $getAdmin;
				}
			}
			$sendData['id'] = $insert_id;
            return $sendData;
			
		}
		// $this->model->sayUid();
		$sendData['to_uid'] = 0;
		return $sendData;

	}
	// 语音取消
	public function vaChat() 
	{
		$res = $this->sayUid();
		$res['type'] = 'vaCancel';
		return $res;
	}
	//选择人聊天
	public function mes_chat () 
	{
		return $this->model->selectManModel();
	}
	//管理员 聊天
	public function chatAdmin() {
		$res = $this->model->chatAdminModel();
		return $res;
	}
	//选择群聊天
	public function mes_groupChat () 
	{
		return $this->model->selectGroupChat();
	}

	//分页
	public function mes_load () {
		if ( $this->messageData['message_type'] !== 'message' ) {
			$getData = $this->isInGroup();
			//如果在群聊里返回群信息
			if ( !$getData ) {
				$onlode['type'] = 'onlode';
	            $onlode['save'] = 0;
				return $onlode;
			}
		}
		return $this->model->mesLoadModel();
	}

	//消息的关闭
	public function mes_close() 

	{
		$this->model->messageCloseModel();
		return array( 'type'=>'default' );
	}

	//通知消息的关闭
	public function mes_notice_close() 
	{
		return $this->model->messageNoticeCloseModel();
	}
	//系统消息的通知 
	public function sysNotice() 
	{
		return $this->model->sysNotice();
	}
	//增加群聊
	public function addGroupMan () 
	{
		$this->model->addGroupManModel();
		return array('type'=>'default');
	}

	//删除群聊中的一个人
	public function delgroupman () 
	{
		$this->model->updateGroupMan();
		return array('type'=>'default');
	}
	//群聊解散
	public function  dissolve_group() 
	{
		$this->model->dissoleGroup();
		return array('type'=>'default');
	}
	// 退出群
	public function esc_group () {
		$this->model->escGroup();
	}
	//增加最近联系人
	public function addContact () 
	{
		$this->model->addCcontactModel();
		return array('type'=>'default');
	}
	//删除最近联系人
	public function delContact () 
	{
		$this->model->delContactModel();
		return array('type'=>'default');
	}
	//更新最近联系人
	public function updContact ()
	{
		$this->model->updContactModel();
		return array('type'=>'default');
	}
	public function delFriend() {
		return $this->model->delFriend();
	}
	//统计 所有在线人数的 详情信息
	public function allOnlineNum() {

		$uid = $this->selfInfo['uid'];

        $arrALlonlineInfo = []; //储存所有在线人信息 ps: 没有重复
        
        // $arrALlonlineMan = [];// 保留 所有的 uid  没有重复

        $arrAllRoomId = [];  //储存所有的 房间信息
		
        $uniqueRoomId = [];//房间id去重 房间的id  和 数量

        $roomInfo = []; //房间里的详情信息

        //获取所有的 client_id  在线人的信息
        $allClients_list = $this->messageData;
        if (!empty($allClients_list)) {
            foreach ($allClients_list as $key => $value) {
            	if (!empty( $value )) {
	                if ( isset( $arrALlonlineInfo[$value['uid']] ) ) {
	                    unset( $allClients_list[$key] );
	                } else {
	                    // $arrALlonlineMan[] = $value['uid'];
	                    $arrALlonlineInfo[$value['uid']] = $value['room_id'];
	                    if ( isset($roomInfo[$value['room_id']]) ) {
	                    	$uniqueRoomId[$value['room_id']] ++;
	                    } else {
	                    	$uniqueRoomId[$value['room_id']] = 1;
	                    }
	                    $roomInfo[$value['room_id']][] = $value['client_name'];
	                    // $arrAllRoomId[] = $value['room_id'];
	                }
            	}
            }
            $new_allOnlineNum['type'] = 'allOnlineNum'; // 发送客户端的类型
            //所有的在线人的信息
            $new_allOnlineNum['arrALlonlineInfo'] = $arrALlonlineInfo; 
            //获取 房间的id 
            $rooms = array_keys($uniqueRoomId);
            //获取 公司的名字
            $comInfo = $this->model->getCompanyName($rooms);
            $new_allOnlineNum['comInfo'] = $comInfo;   //公司的名字
            $new_allOnlineNum['roomInfo'] = $roomInfo;  //所有房间的 信息
            $new_allOnlineNum['arrAllRoomId'] = $uniqueRoomId;   //所有的 房间信息
            return $new_allOnlineNum;
        }
	}
	//验证是否在同一个房间
	public function isSameRooom()
	{
		return $this->model->isSameRooomModel();
	}

	//判断是不是好友
	public function isFriends () {

		return $resData = $this->model->isFriendsModel();

	}
	// 好友的 查找
	public function friendAdd()
	{
		$resData = $this->model->friendAddModel();
		return $resData;
	}
	
	//消息内容的插入
	public function messageInsert () 
	{
		return $this->model->messageInsertModel();
	}
	// 删除 聊天信息
	public function delChatMes() 
	{
		$this->model->delChatMesModel();
		return array('type'=>'default');
	}
	//消息通知的记录插入
	public function noticeInsert( $insert_id ) 
	{
		return $this->model->noticeInsertModel( $insert_id );
	}
	//验证是不是在群聊
	public function isInGroup () 
	{
		return $this->model->isInGroupModel();
	}
	//群聊显示
	public function groupManShow() 
	{
		return $this->model->groupManShowModel();
	}
}

 ?>