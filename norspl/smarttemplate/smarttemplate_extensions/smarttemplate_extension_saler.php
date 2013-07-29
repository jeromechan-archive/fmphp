<?php
require_once 'service/saler/SalerService.cls.php';
/**
 * @copyright (C) 2006-2012 Tuniu All rights reserved
 * @author: xiehui 2012-4-9
 * @version: 1.0
 * @desc: 用户ID取信息
 * @example {saler:3119} => 谢辉
 */
function smarttemplate_extension_saler($crm_id, $field='nickname') {
	$ssvc = new SalerService();
	echo $ssvc->getSalerFieldByIdWithCache($crm_id, $field);
}
?>