<?php 
namespace Controllers;

use models;
/**
* 
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
	/*******
	*@param 数组  $selfInfo ， 自己的基本信息
	*@param 数组  $messageData ， 客户端传来的数据
	*
	*****/
	function __construct( $selfInfo, $messageData )
	{
		$this->selfInfo = $selfInfo;
		$this->messageData = $messageData;
	}

	/******
	*
	* return $data 返回的数据
	* $type 方法类型
	*
	********/
	public function init( $type )
	{
		$this->model = new \models\chatMessageModel($this->selfInfo, $this->messageData);
		return $data = $this->$type();
		
	}

	//发来的消息
	public function sayUid()
	{
		if ($this->messageData['to_uid'] != 'all') {
			if ( $this->messageData['message_type'] == 'message' ) {
				//验证是不是在同一个房间
				$resRoom = $this->isSameRooom();
				if ( !$resRoom ) {
					$resIs = $this->isFriends();
					if ($resIs) {
						//获得插入的id
						$insert_id = $this->messageInsert();
						//通知消息的插入 返回 一组发给客户端的数据
						$sendData = $this->noticeInsert( $insert_id );
						$sendData['to_uid'] = $this->messageData['to_uid'];
						return $sendData;
					}
					return false;
				}
				//获得插入的id
				$insert_id = $this->messageInsert();
				//通知消息的插入 返回 一组发给客户端的数据
				$sendData = $this->noticeInsert( $insert_id );
				$sendData['to_uid'] = $this->messageData['to_uid'];
				return $sendData;
			} else if ($this->messageData['message_type'] == 'groupMessage' ) {
				//是否在群聊里 如果在返回 群信息
				$getData = $this->isInGroup();
				if ( $getData ) {

					//获得插入的id
					$insert_id = $this->messageInsert();
					//通知消息的插入 返回 一组发给客户端的数据
					$sendData = $this->noticeInsert( $insert_id );
					$sendData['group_name'] = $getData['group_name'];
                    $sendData['to_uid'] = $getData['to_uid_id'];
					return $sendData;
				}
			}
			return false;
			
		}
		// $this->model->sayUid();
		return false;

	}

	//选择人聊天
	public function mes_chat () 
	{
		return $this->model->selectManModel();
		return false;
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
	//验证是否在同一个房间
	public function isSameRooom()
	{
		return $this->model->isSameRooomModel();
	}

	//判断是不是好友
	public function isFriends () {

		return $resData = $this->model->isFriendsModel();

	}
	//消息内容的插入
	public function messageInsert () 
	{
		return $this->model->messageInsertModel();
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