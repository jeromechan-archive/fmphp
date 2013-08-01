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

$doPath = urldecode($_REQUEST['do']);
if(!empty($doPath))
{
    $doPath = 'index';
}

// 1. do mapping job, produce a mapping array

// 2. run action server
