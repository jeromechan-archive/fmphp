<?php
/**
 * 调用静态方法
 * example: {call:arg1,arg2,'utils/Tools.cls.php','getStaticName'}
 * 		==> Tools->getStaticName(arg1,arg2)
 * @author xiehui 2012-3-2
 * @version 1.0
 */
function smarttemplate_extension_call() {
	
	$args = func_get_args();
	if(count($args>2)) {
		$callArgs = array_slice($args, 0, -2);
		$classPath = array_slice($args, -2, 1);
		$method = array_slice($args, -1);
	}else {
		$callArgs = array();
		$classPath = $args[0];
		$method = $args[1];
	}
	include_once $classPath;
	$class = array_slice(explode('.', array_slice(explode('/', $classPath),-1)), 0, 1);
	$obj = new $class();
	echo call_user_func(array($obj, $method), $callArgs);
	
}
?>