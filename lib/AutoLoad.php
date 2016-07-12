<?php 
/**
* 自动加载类
*/
namespace lib\chatAutoload;

class chatAutoload
{
	
	public  static  function autoLoadClass( $className )
	{
		echo $className;
	}
}
spl_autoload_register('lib\chatAutoload\chatAutoload::autoLoadClass');
 ?>