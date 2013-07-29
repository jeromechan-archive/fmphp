<?php
require_once 'smarttemplate/smarttemplate_extensions/smarttemplate_extension_helper.php';
   /**
	* SmartTemplate Extension options
	* Creates HTML DropDown Option list from an array
	*
	* Usage Example :
	* Content:  $template->assign('delivery_time',   array(array("code"=>"1","name"=>"周一至周五"),array("code"=>"2","name"=>"周六周日"),array("code"=>"3","name"=>"任何时间")) );
	*           $template->assign('com_delivery_type', "3" );
	* Template: <select name="com_delivery_type">{option:delivery_time,com_delivery_type,'code','name'}</select> OR <select name="com_delivery_type">{option:delivery_time,com_delivery_type}</select>
	* Result:   <select id="com_delivery_type" name="com_delivery_type"><option value="1">周一至周五</option><option value="2">周六周日</option><option selected="" value="3">任何时间</option></select>
	*
	* @author xiehui 2012-2-10
	*/
	function smarttemplate_extension_option ( $param,  $default = '_DEFAULT_', $value_key = 'code', $name_key = 'name' ) {
		//对于静态参数配置，由于模板解析的缺陷，通过倒置参数实现静态参数目的
		if(substr($default, 0, 5)=='CFL::') {
			$unit_name = substr($default, 5);
			$default = $param;
			$param = CFL::getCF($unit_name);
		}
		$args_num = func_num_args();
		$attrStr = '';
		$needExplain = array();
		if($args_num>4) {
			for($i=4; $i<$args_num; $i++) {
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
					$output .= '<option value="' . $item[$value_key] . '"' . (assertEquals($item[$value_key], $default) ? '  selected' : '')
					 . $attrStr . ' ' . $extraAttr . '>' . $item[$name_key] . '</option>';
				}else {
					$output  .=  '<option value="' . $key . '"' . (assertEquals($key, $default) ? '  selected' : '') . $attrStr . '>' . $item . '</option>';
				}
					
			}
		}
		return $output;
	}
?>