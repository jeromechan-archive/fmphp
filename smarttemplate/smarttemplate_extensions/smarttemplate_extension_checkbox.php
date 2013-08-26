<?php
require_once 'smarttemplate/smarttemplate_extensions/smarttemplate_extension_helper.php';
/**
	* SmartTemplate Extension options
	* Creates HTML DropDown checkbox list from an array
	*
	* Usage Example :
	* Content:  $template->assign('delivery_time',   array(array("code"=>"1","name"=>"周一至周五"),array("code"=>"2","name"=>"周六周日"),array("code"=>"3","name"=>"任何时间")) );
	*           $template->assign('com_delivery_type', "3" );
	* Template: <lable>{checkbox:delivery_time,com_delivery_type,'delivery_time','code','name'}</lable> OR <lable>{checkbox:delivery_time,com_delivery_type,'delivery_time'}</lable>
	* Result:   <lable>
	* 				<input type="checkbox" value="1" id="delivery_time_1" name="delivery_time[]"/>周一至周五
	* 				<input type="checkbox" value="2" id="delivery_time_2" name="delivery_time[]"/>周六周日
	* 				<input type="checkbox" value="3" id="delivery_time_3" name="delivery_time[]" checked/>任何时间
	* 			</lable>
	*
	* @author  xiehui 2012-2-10
	*/
function smarttemplate_extension_checkbox( $param,  $default = '_DEFAULT_', $name,
	$value_key = 'code', $name_key = 'name' ) {
		//对于静态参数配置，由于模板解析的缺陷，通过倒置参数实现静态参数目的
		if(substr($default, 0, 5)=='CFL::') {
			$unit_name = substr($default, 5);
			$default = $param;
			$param = CFL::getCF($unit_name);
		}
		$args_num = func_num_args();
		$attrStr = '';
		$needExplain = array();
		if($args_num>5) {
			for($i=5; $i<$args_num; $i++) {
				$expression = func_get_arg($i);
				if(needExplain($expression)) {
					$needExplain[] = $expression;
				}else {
					$attrStr .= ' ' . $expression;
				}
			}
		}
		$output  =  "";
		if (is_array($param)) {
			foreach ($param as $key => $item) {
				if(is_array($item)) {
					$extraAttr = '';
					foreach ($needExplain as $expression) {
						$attr = getAttrValue($expression, $item);
						$extraAttr .= ' ' . $attr;
					}
					$output .= '<input type="checkbox" value="' . $item[$value_key] . '" id="' . $name .'_' . $item[$value_key]
						.'" name="' . $name . '[]"' . (((is_array($default) && in_array($item[$value_key], $default)) || assertEquals($item[$value_key], $default)) ? '  checked' : '') . $attrStr . $extraAttr . '/>' . $item[$name_key];
				}else {
					$output .= '<input type="checkbox" value="' . $key . '" id="' . $name . '_' . $key . '" name="' . $name . '[]"' 
						. (((is_array($default) && in_array($key, $default)) || assertEquals($key, $default)) ? ' checked' : '') . $attrStr . '/>'.$item;
				}
			}
		}
		return $output;	
}

?>