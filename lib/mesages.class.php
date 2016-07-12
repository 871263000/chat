<?php 
// require_once('config.inc.php');
/**
* 作者 zdl 联系: 871263000@qq.com
* 聊天的内容
* 消息的列表
*/
class messageList
{
	//发送人id
	public $uid = 0;
	//注册id
	public $oms_id = 1;
	//聊天对方名字
	public $accname = '请选择聊天对象';// 选择人的名字
	public $name = '匿名';//自己的名字
	//每页显示的消息数
	public $pageload = 10;// 每页显示的消息个数
	//通知消息的个数
	public $mesNum = 0;
	//聊天对方的id
	public $staffid = 0;
	//会话id
	public $session_no = 0;
	//单聊的消息内容
	public $mesContent = [];
	//数据库操作
	public $d = '';
	//消息类型
	public $mes_type = 'message';
	//析构函数
	/*
	* $uid 发送人的id
	* $staffid 聊天对方的id
	*/
	function __construct($uid =0, $oms_id = 0)
	{
		$this->uid = $uid;
		$this->oms_id = $oms_id;
		$this->d = new database();

	}
	//单聊聊天的内容
	public function MesContent(){
		if (isset($_GET['staffid'])) {
		      //对话消息列表
		    $this->session_no = $this->uid <= $_GET['staffid'] ? $this->uid."-".$_GET['staffid'] : $_GET['staffid']."-".$this->uid;
		    $sql = "SELECT * FROM `oms_string_message` WHERE `delState` = 0 AND session_no="."'{$this->session_no}' ORDER BY create_time desc limit 0,".$this->pageload;
		    $arrRes = $this->d->findAll($sql);//消息的data
		    //接收人名字
		    $sql = 'SELECT `name` FROM `oms_hr` WHERE staffid='.$_GET['staffid'];
		    $arrAccname = $this->d->find($sql);
		    $this->accname = $arrAccname['name'];//接收人名字

		  }
		  $this->mes_type = 'message';
		  return $arrRes;
	}
	//群聊的聊天内容
	public function groupMesContent(){
		if (isset($_GET['session_no'])) {
			$this->session_no = $_GET['session_no'];
			$sql = "SELECT * FROM `oms_string_message` WHERE `delState`= 0 AND `session_no`='".$_GET['session_no']."' ORDER BY create_time desc limit 0,".$this->pageload;
			$arrContent = $this->d->findAll($sql);
			//群聊名字
			$sql = 'SELECT `group_name` FROM `oms_group_chat` WHERE `id`='.$_GET['session_no'];
		    $arrGroupAccname = $this->d->find($sql);
		    $this->accname = $arrGroupAccname['group_name'];
			$this->mes_type = 'groupMessage';
		}
		return $arrContent;
	}
	//自己的信息
	public function userinfo()
	{
		$sql = 'SELECT `name`,`card_image`, `general_admin` FROM `oms_hr` WHERE staffid='.$this->uid;
		$arrName = $this->d->find($sql);
		return $arrName;
	}
	// 查询所有管理员
	public function getAdmin(){
		$arrAdmin = [];
		$sql = 'SELECT `staffid`,`card_image`, `name` FROM `oms_hr` WHERE `general_admin`= 1';
		$arrAdmin = $this->d->findAll($sql);
		if ($arrAdmin) {
			return $arrAdmin;
		}
		return $arrAdmin;
	}
	//消息列表
	public function mesAlertList( $isAdmin ){
		 //右边的消息根据$_SESSION获取名字

		//单聊消息
		$sql = 'SELECT a.`mes_num`, a.`mes_type`, a.`session_no`,a.`pid`, a.`chat_header_img`, b.`sender_id`, b.`sender_name`, b.`accept_id`, b.`accept_name`, b.`message_type`, b.`mesages_types` , b.`message_content`, b.`groupId` FROM `oms_chat_message_ist` a LEFT JOIN `oms_string_message` b ON a.`mes_id` = b.`id` WHERE a.`pid`="'.$this->uid.'"';
		$arrMes = $this->d->findALL($sql);
		//群聊消息
		$sql = "SELECT a.`mes_num`, a.`id`, b.`sender_id`, b.`sender_name`, b.`accept_id`, b.`accept_name`, b.`message_type`, b.`mesages_types` , b.`message_content`, b.`session_no`, b.`groupId` FROM `oms_groups_people` a LEFT JOIN `oms_string_message` b ON a.`mes_id` = b.`id` WHERE a.`mes_state`=1 AND  a.`staffid`=$this->uid";
  		$arrGroupMes = $this->d->findAll($sql);
  		if ( $isAdmin == 1 ) {
	  		//管理员群聊消息
			$sql = "SELECT a.`mes_num`, a.`staffid`, b.`sender_id`, b.`sender_name`, b.`accept_id`, b.`accept_name`, b.`message_type`, b.`mesages_types` , b.`message_content`, b.`session_no`, b.`groupId` FROM `oms_hr` a inner JOIN `oms_string_message` b ON a.`mes_id` = b.`id` WHERE  a.`staffid`=$this->uid";
	  		$arrAdminMes = $this->d->findAll($sql);
	  		foreach ($arrAdminMes as $key => $value) {
	  			$arrMes[] = $value;
	  		}
  		}
  		foreach ($arrGroupMes as $key => $value) {
  			$arrMes[] = $value;
  		}

  		return $arrMes;
	}
	//群列表
	public function groupChatList(){
		//自己有多少个群聊
		$sql = "SELECT a.*,b.group_founder FROM `oms_groups_people` a LEFT JOIN  `oms_group_chat` b ON a.pid = b.id  WHERE a.`state`= 0 AND a.`staffid`=".$this->uid;
		//群聊参加人
		$arr_group_num = $this->d->findAll($sql);
		// $sql = "SELECT a.*, b.name,b.card_image FROM `oms_groups_people` a LEFT JOIN `oms_hr` b ON a.`staffid` = b.staffid WHERE a.`state` = 0 AND a.`pid` in(SELECT `pid` FROM `oms_groups_people` WHERE `staffid`=".$this->uid.")";
		// $arr_group_man = $this->d->findAll($sql);
		// foreach ($arr_group_num as $key => $value) {
		// 	foreach ($arr_group_man as $k => $val) {
		// 		if ($value['pid'] == $val['pid']) {
		// 			$arr_group_num[$key]['group_people'][] =$val;
		// 		}
		// 	}
		// }
		return $arr_group_num;
	}
	//最近联系人
	public function recentContact()
	{
		// $sql = "SELECT a.*, b.`card_image` FROM `oms_nearest_contact` a LEFT JOIN  `oms_hr` b ON a.`mes_id` = b.`staffid` WHERE a. `pid`=".$this->uid." or `session_no` in (SELECT `pid` FROM `oms_groups_people` WHERE `contacts_id`=0 AND `staffid` =".$this->uid.")  ORDER BY timeStamp desc";
		$sql = "SELECT a.*, b.`card_image` FROM `oms_nearest_contact` a LEFT JOIN  `oms_hr` b ON a.`mes_id` = b.`staffid` WHERE a. `pid`=".$this->uid." ORDER BY timeStamp desc";
		$recentContact = $this->d->findAll($sql);
		return $recentContact;
	}
	// 还有列表
	public function friendsList() 
	{
		$sql = "SELECT a.*, b.`card_image`,b.`name` FROM `oms_friend_list` a LEFT JOIN `oms_hr` b ON a.staffid = b.staffid WHERE  a.`state` = 2 AND a.`pid`=".$this->uid;
		$arrFriendList = $this->d->findAll($sql);
		return $arrFriendList;
	}
}
 ?>