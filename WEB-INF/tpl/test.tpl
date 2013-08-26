<?php
require_once('smarttemplate/class.smarttemplate.php');
$tpl = new SmartTemplate('test.html');
$tpl->assign($response['form']);
$tpl->assign($response['data']);
$tpl->output();
