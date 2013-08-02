<?php
/**
 * Copyright © 2013 NEILSEN·CHAN. All rights reserved.
 * 
 * @author chenjinlong
 * @date 13-8-1
 * @time 下午6:02
 * @description mapping.php
 */
$_CONFIG['cache_lifetime'] = 0;
$_CONFIG['template_dir'] = WEBINF_DIR . 'html';
$_CONFIG['smarttemplate_compiled'] = PROJ_ROOT . 'template_c';
$_CONFIG['extension_dir'] = PROJ_ROOT . 'smarttemplate/smarttemplate_extensions';

$mapping = array(
    'test' => array(
        'action' => 'TestAction', // Full path
        'html' => 'test', // Full path
        'tpl' => 'test', // Full path
    ),
);