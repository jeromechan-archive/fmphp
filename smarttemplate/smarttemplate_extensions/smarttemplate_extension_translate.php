<?php
/**
 * 转义函数
 * @example 
 * $template->assign('delivery_time',   array(array("code"=>"1","name"=>"周一至周五"),array("code"=>"2","name"=>"周六周日"),array("code"=>"3","name"=>"任何时间")) );
 * $template->assign('com_delivery_type', "3" );
 * {translate:delivery_time,com_delivery_type} or {translate:com_delivery_type,'CFL::deliver_time'} ('deliver_time'配置在静态参数表)
 * output：任何时间
 * @author xiehui 2012-2-15
 * @version 1.0
 */
function smarttemplate_extension_translate($data, $src, $src_key='code', $target_key='name', $glue=',') {
	//对于静态参数配置，由于模板解析的缺陷，通过倒置参数实现静态参数目的
	if(substr($src, 0, 5)=='CFL::') {
		$unit_name = substr($src, 5);
		$src = $data;
		$data = CFL::getCF($unit_name);
	}
	if (!is_array($src)) {
		$src = array($src);
	}
	$target = array();
	foreach ($src as $value) {
		foreach ($data as $key=>$item) {
			if(is_array($item)) {
				if($item[$src_key] == $value) {
					$target[] =$item[$target_key];
					break;
				}
			}else {
				if ($key==$value) {
					$target[] = $item;
					break;
				}
			}
		}
	}
	return implode($glue, $target);
}
?>