<?php
/*
 +----------------------------------------------------------
 * 数据收集
 +----------------------------------------------------------
 * 文件名称 Model.class.php
 +----------------------------------------------------------
 * 文件描述 mysql操作
 +----------------------------------------------------------
 * 作    者 z+<wtxing@gmail.com>
 +----------------------------------------------------------
 * 时    间 2011-05-09
 +----------------------------------------------------------
 */
 
class database
{
	//数据库连接
	private $db = '';
	
	//当前操作表
	public $table = '';
	
	//SQL语句
	public $sql = '';
	
	//返回结果
	private $result = '';
	
	//id
	public $id = '';
	
	//添加字段
	public $keys = '';
	
	//添加值
	public $values = '';
	
	//更新字段、值
	public $value = '';
	
	
	
	/*
	 * 初始化
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->id = intval($_GET['id']);
		
		//组合数据
		if($_POST)
		{
			$length = count($_POST);
			$keys = array_keys($_POST);
			
			//默认添加数据
			$this->keys = join(',', $keys);
			$this->values = '\''. join('\',\'', $_POST) .'\'';
			
			//默认更新数据
			for($i = 0; $i < $length; $i++)
			{			
				$value[] = $keys[$i] .'=\''. $_POST[$keys[$i]] .'\'';
			}
			
			$this->value = join(',', $value);
		}
	}
	
	
	
	
	/*
	 * 执行SQL语句
	 *
	 * @access public
	 * @return true
	 */
	public function query($sql = NULL)
	{
		try
		{
			$this->db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.'', DB_USER, DB_PASS);
			$this->db->exec("SET NAMES utf8");
			$this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
		}
		catch(PDOException $e)
		{
			echo 'Connection failed: ' . $e->getMessage();
		}
		
		$this->result = $this->db->query($sql);
				
		if($this->db->errorCode() == '00000')
		{
			return true;
			//return $this->result->rowCount();
		}
		else
		{
			print_r($this->db->errorInfo());
			exit();
		}
	}
	
	
	/*
	 * 获得一条记录
	 *
	 * @access public
	 * @return $row
	 */
	public function find($sql = NULL)
	{
		$this->query($sql);
		$this->result->setFetchMode(PDO::FETCH_ASSOC);
		$row = $this->result->fetch();
		return $row;
	}
	
	
	
	/*
	 * 获得全部记录
	 *
	 * @access public
	 * @return $rows
	 */
	public function findAll($sql = NULL)
	{
		$this->query($sql);
		$this->result->setFetchMode(PDO::FETCH_ASSOC);
		$rows = $this->result->fetchAll();
		return $rows;	
	}
	
	
	
	/*
	 * 获得记录总数
	 *
	 * @access public
	 * @return $amount
	 */
	public function records($sql = NULL)
	{		
		$this->query($sql);
		$amount = $this->result->fetchColumn();
		return $amount;
	}
	
	
		
	/*
	 * 数据录入
	 *
	 * @access public
	 * @return true
	 */
	public function create($table = NULL)
	{
		$now = time();	
		$sql = "INSERT INTO `{$table}`($this->keys, create_time, update_time) VALUES($this->values, $now, $now)";
//        echo $sql;
		return $this->query($sql);
	}
	
	
	
	/*
	 * 数据更新
	 *
	 * @access public
	 * @return true
	 */
	public function update($table = NULL, $id = 'id')
	{
		$now = time();
		$sql = "UPDATE `{$table}` SET $this->value, update_time = $now WHERE `oms_id` = {$_SESSION['oms_id']} AND $id = $this->id";
		return $this->query($sql);
	}
	
	
	
	/*
	 * 数据删除
	 *
	 * @access public
	 * @return true
	 */
	public function delete($table = NULL)
	{
		$sql = "DELETE FROM `{$table}` WHERE id = $this->id";
		return $this->query($sql);
	}
	
	
	/*
	 * 获取id
	 *
	 * @access public
	 * @return true
	 */
	public function lastInsertId()
	{
		return $this->db->lastInsertId();
	}
}
?>