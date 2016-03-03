<?php
mysql_connect('localhost', 'root', '');
mysql_select_db('oms');
mysql_query('set names utf8');
?>
<?php
	class gl{
		private $fname;	//节点名字
		private $lastid;	//最后一个id
		private $lastinid;	//最后一次插入数据时候的id
		public $dep=array();
		private $resData=array();
		private $table='';
        private $person=array();

		function __construct($table)
		{
			//获得最后一个id
			$this->table=$table;
			$this->lastid=mysql_query("select max(id) from $table");
			while ($row=mysql_fetch_assoc($this->lastid)) {
				$data[]=$row;
			}
			$this->lastid=$data[0]['max(id)'];
		}
		function select($action,$con)
		{
			switch ($action) {
				case 'alldata':
					$res=mysql_query('select * from '.$this->table);
					while ($row=mysql_fetch_assoc($res)) {
						$data[]=$row;
					}
					return $data;
					break;
				case 'search_node':
					$sql='select * from '.$this->table.' where zwname like "%'.$con.'%"';
					$res=mysql_query($sql);
					while ($row=mysql_fetch_assoc($res)) {
						$data[]=$row;
					}
					return @$data=json_encode($data);
					break;
				default:
					return '参数错误';
					break;
			}
		}
		function getdata($id=0)
		{
			global $str;
			$res=mysql_query('select id,zwname,pid,path,`desc` from '.$this->table.' where pid='.$id.' and state=0 ');

			if($res && mysql_affected_rows()){
				$str.='<ul class="lu" >';
				while ($row=mysql_fetch_array($res)) {
                    if($row['pid']==0 && $row['path']==0){
                        $this->person[$row['zwname']][]=$this->getOT(@$row['zwname']);
                    }
                    $sql='select * from '.$this->table.' where path="'.($row['path'].','.$row['id'].'"');
                    if(mysql_num_rows(mysql_query($sql))){
                        $this->dep[]=$row;
                        $str.=<<<cd
					<li class='afl' onclick="listu(this)" ondblclick="chat({$row['zwname']})" path="{$row['path']}" desc="{$row['desc']}" myinner="{$row['zwname']}" sid="{$row['id']}" onmousedown="fun(this)"><input class="ltclasscheckbox" type="checkbox">{$row['zwname']} <span style="display:none" class="btns"><button class="select_daily_list" depname="{$row['zwname']}">查看报表</button><button class="input_daily_job" depname="{$row['zwname']}">日常工作</button></span></li>
cd;
                        $this->getdata($row['id']);
                    }else{
                        $str.=<<<cd
					<li class='afl' onclick="listu(this)" ondblclick="chat({$row['zwname']})" path="{$row['path']}" desc="{$row['desc']}" myinner="{$row['zwname']}" sid="{$row['id']}" onmousedown="fun(this)"><input class="ltclasscheckbox" type="checkbox">{$row['zwname']} </li>
cd;
                    }
				}
				$str.='</ul>';
			}
            $result=array();$result['tree']=$str;$result['dep']=$this->dep;$result['person']=$this->person;
			return $result;
		}
		function doadd1($fname)
		{
			$res=mysql_query('insert into '.$this->table.'(zwname,pid,path) values("'.$fname.'","0","0")');
            mysql_query('insert into oms_department(`Dep`,`dep1`,`pid`,`path`,`state`) values("'.$fname.'","",0,0,0)');
			$max_id=mysql_insert_id();
			$res=mysql_query('update '.$this->table.' set `desc`='.$max_id.' where id='.$max_id) ;
			return $res;
		}
		function doadd2($fname,$sid,$spath)
		{
                $sql='insert into '.$this->table.'(zwname,pid,path) values("'.$fname.'","'.$sid.'","'.$spath.'")';
			$res=mysql_query($sql);

            if(count(explode(',',$spath))==2){
                mysql_query('insert into oms_department(`Dep`,`dep1`,`pid`,`path`,`state`) values("","'.$fname.'","'.$sid.'","'.$spath.'",0)');
            }
			$max_id=mysql_insert_id();
			$res=mysql_query('update '.$this->table.' set `desc`='.$max_id.' where id='.$max_id) ;
			return $res;
		}
		function rename($id,$name)
		{
			$sql='update '.$this->table.' set zwname="'.$name.'" where id="'.$id.'"';
            $s='select path from oms_department where id="'.$id.'"';
            $path=mysql_fetch_assoc(mysql_query($s));
            $arrlength=count(explode(',',$path['path']));
//            echo $arrlength;
            $nam=$arrlength==1?'Dep':'dep1';
            $sql2='update oms_department set '.$nam.'="'.$name.'" where id="'.$id.'"';
			$res=mysql_query($sql) && mysql_query($sql2);
			return $res;
		}
		function acjobs($json,$id,$action,$content='')
		{
			switch ($action) {
				case 'setjobs':
				$res=mysql_fetch_array(mysql_query('select nodeid from joblist where nodeid='.$id));
				$res=$res['nodeid'];
					if(!$res){
						$sql="insert into joblist(`nodeid`,`jobcontent`) values('".$id."','".$json."')";
					}else{
						$sql='select jobcontent from joblist where nodeid='.$id;
						$lastdata=mysql_fetch_array(mysql_query($sql));
						$lastdata=$json.$lastdata['jobcontent'];
						$sql="update joblist set jobcontent='".$lastdata."' where nodeid=".$id;
					}
					return mysql_query($sql)?'保存成功':'保存失败';
					break;
				case 'read':
				
					$staffid = $_SESSION['staffid'];
					if($staffid)//权限
					{
						$sql='select jobcontent from joblist where nodeid='.$id.' and (staffid = '.$staffid.' or adminid = '.$staffid.')';
						$arr=mysql_fetch_array(mysql_query($sql));
						$arr = $arr['jobcontent'];
						return $arr;
					}
					else
					{
						return false;
					}
					
					break;
				case 'del':
					$sql='select jobcontent from joblist where nodeid='.$id;
					$j=mysql_query($sql);
					$jo=mysql_fetch_array($j);
					$job=$jo['jobcontent'];
					preg_match("/<a .*?>".$content."<\/a>/", $job, $m);
					$job=str_replace(',', '',$job);
					$new=str_replace($m[0],'', $job);
					$sql="update joblist set jobcontent='".$new."' where nodeid=".$id;
					return mysql_query($sql);
					break;
				default:
					return '未定义的操作';
					break;
			}
		}
		function moves($id,$sid)
		{
			if($id>$sid){
				mysql_query('update '.$this->table.' set `desc`='.$sid.' where id='.$id);
				//找出之间的序号
				$c='select * from '.$this->table.' where id>'.$sid;
				$res=mysql_query($c);
				while ($row=mysql_fetch_array($res)) {
					for ($i=0; $i < count($row); $i++) { 
						$sql='update '.$this->table.' set `desc`='.($row['desc']+1).' where id='.$row['id'];
					}
				}
			}else{
				return'移动失败';
			}
		}
		function del($path,$id){
	            if($path==0){
	                $sql='update '.$this->table.' set state=1 where path like "'.$path.'%" and id='.$id;
                    $sql2='update oms_department set state=1 where path like "'.$path.'%" and id='.$id;
	            }else{
	                $sql='update '.$this->table.' set state=1 where path like "'.$path.'%"';
                    $sql2='update oms_department set state=1 where path like "'.$path.'%"';
	            }
	            $res=mysql_query($sql) && mysql_query($sql2);
	            return $res;
		}
		function alldel(){
			$a=mysql_query('truncate table '.$this->table);
			$b=mysql_query('truncate table joblist');
            mysql_query('truncate table oms_department');
			return($a && $b);
		}
        //查询一级和二级节点
        function getOT($onedep){
            $data=mysql_query('select * from oms_department where Dep="'.$onedep.'"');
            $hr_data=mysql_fetch_array($data);

            $hr_fpath=$hr_data['pid'].','.$hr_data['id'];
            $hr_sql='select * from oms_department where  path="'.$hr_fpath.'"';
            $hr_result=mysql_query($hr_sql);
            while ($hr_res=mysql_fetch_assoc($hr_result)){
                //查二级节点
                $hr_data=mysql_query('select * from oms_hr where new_department="'.$onedep.'" and new_department_two="'.$hr_res['dep1'].'"');

                while ($row=mysql_fetch_assoc($hr_data)) {
                    $resData[$hr_res['dep1']][]=$row['new_position'].':'.$row['name'];
                }
            }
            //查一级节点
            $hr_datat=mysql_query('select * from oms_hr where new_department="'.$onedep.'" and new_department_two =""');
            while ($row=mysql_fetch_assoc($hr_datat)) {
                $resData[]=str_replace(' ','',$row['new_position'].':'.$row['name']);
            }
            return $resData;
        }


		//4
        function getNode($cmd='getall',$onedep=''){
            $allowArray=array('getall','getone','gettwo');
            //判断
        	if(!$onedep){
        		 $res=in_array($cmd,$allowArray)?$this->getdata():'参数错误';
        	}else{
        		$data=mysql_query('select * from oms_department where Dep="'.$onedep.'"');
                $data=mysql_fetch_array($data);
                $fpath=$data['pid'].','.$data['id'];
                $sql='select * from oms_department where  path="'.$fpath.'"';
                $result=mysql_query($sql);
                while ($row=mysql_fetch_assoc($result)) {
                    $resData[]=$row;
                }
        	}


        	//Switch print
            switch($cmd){
                case $allowArray[0]:
                    $resData=$res['dep'];
                break;
                case $allowArray[1]:
                    $resData=array();
                    $sql='select * from oms_department where pid=0';
                    $result=mysql_query($sql);
                    while ($row=mysql_fetch_assoc($result)) {
                        $resData[]=$row;
                    }
                break;
                case $allowArray[2]:
                    foreach ($res['dep'] as $key => $value) {
                        if($value['path']==0 && $value['pid']==0) continue;
                        $dmt=explode(',', $value['path']);
                        if(count($dmt)==2) $resu[]=$value;
                    }
                    $resData=$resu;
                break;
            }
            //Return
          		  return $resData;
        }
	}

	$r=new gl('presonfl');
	if(@$_POST['Dep']){
		$json=$r->getNode('',$_POST['Dep']);
		die(json_encode($json));
	}

	if ($_POST) {
		switch ($_POST['model']) {
			case 1:
				die($r->doadd1($_POST['fname'])?'添加成功':'添加失败');
			case 2: 
				die($r->doadd2($_POST['fname'],$_POST['sid'],$_POST['spath'])?'添加成功':'添加失败');
			case 3:
				die($r->rename($_POST['id'],$_POST['newname'])?'命名成功':'命名失败');
			case 4:
				die($r->del($_POST['spath'],$_POST['id'])?'删除成功':'删除失败');
			case 5:
				die($r->alldel('alldel')?'删除完毕！':'删除失败...');
			case 6:
				die($r->select($_POST['cmd'],$_POST['con']));
			case 7:
				die($r->moves($_POST['id'],$_POST['sid']));
				break;
			case 8:
				die($r->acjobs($_POST['arr'],$_POST['id'],$_POST['ac']));
				break;
			case 9:
				die($r->acjobs('',$_POST['nodeid'],$_POST['action']));
				break;
			case 10:
				die($r->acjobs('',$_POST['nodeid'],$_POST['action'],$_POST['val'])?'删除成功':'删除失败');
			default:
				die($_POST['model']."模式错误！");
		}
	}

 ?>