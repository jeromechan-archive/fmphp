<?php
/**
 * 简单断言比较输出函数，扩展函数函数拿不到上下文，这种写法是无奈之举。
 * @author xiehui 2012-2-15
 * @version 1.0
 */
function smarttemplate_extension_assert($param1, $param2, $expression, $trueOutput, $falseOutput='') {
	$expression = str_replace('&1', '$param1', $expression);
	$expression = str_replace('&2', '$param2', $expression);
	return eval("return $expression;") ? $trueOutput : $falseOutput;
}
?>