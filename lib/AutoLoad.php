<?php 
/**
* 自动加载类
*/
class AutoLoad
{
	public static function ByuseAutoLoad( $className )
	{
		$className = str_replace( '\\', '/', $className );
		require_once($className.'.php');
	}
	
}
spl_autoload_register('AutoLoad::ByuseAutoLoad');
 ?>