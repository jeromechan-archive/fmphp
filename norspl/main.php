<?php
/**
 * Copyright © 2013 NEILSEN·CHAN. All rights reserved.
 * 
 * @date: 7/30/13
 * @description: main.php
 */
require_once './config/platform.php';
require_once './config/database.php';
require_once './config/mapping.php';

require_once './mvc/ActionConfig.php';
require_once './mvc/ActionServer.php';

$doPath = urldecode($_REQUEST['do']);
if(empty($doPath))
{
    $doPath = 'index';
}

// 1. do mapping job, produce a mapping array
$mappingConfig = $mapping[strval($doPath)];
$actionConfigObject = new ActionConfig($mappingConfig);

// 2. start action server
$actionServerObject = new ActionServer();
if($_SERVER['REQUEST_METHOD'] == 'GET')
{
    $actionServerObject->init($actionConfigObject, $_REQUEST);
}
elseif($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $actionServerObject->init($actionConfigObject, $_REQUEST, $_POST);
}
else
{
    exit('Norspl can only support GET and POST method');
}
$actionServerObject->process();