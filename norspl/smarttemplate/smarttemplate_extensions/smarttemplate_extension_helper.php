<?php
/**
 * 助手工具函数集
 * @author xiehui 2012-2-13
 * @version 1.0
 */

/**
 * 解释表达式
 * @param string $expression
 * @param array $data
 * @example $expression: 'orderid=id' $data: array(id=>123)  ===> orderid="123"
 */
function getAttrValue($expression, $data) {
	$array = explode('=', $expression);
	if(count($array)>=2) {
		if(strpos($array[1], '"')===0) {
			return $expression;
		}else {
			return $array[0] . '="' . $data[$array[1]] . '"';
		}
	}
	return $expression;
}

/**
 * 判断一个表达式是否需要使用getAttrValue解释计算
 * @param unknown_type $expression
 */
function needExplain($expression) {
	return strpos($expression, '="')===false;
}

function assertEquals($para1, $para2) {
	if(is_null($para1) || is_null($para2)) {
		return $para1 === $para2;
	}
	return (String)$para1 === (String)$para2;
}
?>