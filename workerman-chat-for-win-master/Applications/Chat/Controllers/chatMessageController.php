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
		if ($this->messageData['to_uid_id'] != 'all') {
			if ( $this->messageData['message_type'] == 'message' ) {
				//验证是不是在同一个房间
				$resRoom = $this->isSameRooom();
				if ( !$resRoom ) {
					$resIs = $this->isFriends();
					if ($resIs) {
						//获得插入的id
						$insert_id = $this->messageInsert();
						//通知消息的插入 返回 一组发给客户端的数据
						return $sendData = $tihs->noticeInsert( $insert_id );

					}
					return false;
				}
				//获得插入的id
				$insert_id = $this->messageInsert();
				//通知消息的插入 返回 一组发给客户端的数据
				$sendData = $this->noticeInsert( $insert_id );
				$sendData['to_uid_id'] = $this->selfInfo['to_uid_id'];
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
                    $sendData['to_uid_id'] = $getData['to_uid_id'];
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
		if (!empty($this->messageData['mes_para'])) {

			//调用数据模型
			return $this->model->selectManModel();
		}
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
				echo 'www';
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
		return $this->model->messageCloseModel();
	}

	//通知消息的关闭
	public function mes_notice_close() 
	{
		$this->model->messageNoticeCloseModel();
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
}

 ?>