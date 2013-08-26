<?php
require_once 'service/saler/SalerService.cls.php';
/**
 * @example {hasrole:saler_id,role_id}
 * @author xiehui 2012-3-30
 * @version 1.0
 */
function smarttemplate_extension_hasrole($crm_id, $role_id) {
	$ssvc = new SalerService();
	return $ssvc->hasRole($crm_id, $role_id);
}
?>