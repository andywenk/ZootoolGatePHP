<?php
/**
 * ZootoolGatePHP example
 *
 * Andy Wenk <andy@nms.de>
 */
include_once('ZootoolGatePHP.php');

// these are the required parameter
$zoogate = new ZootoolGatePHP();
$zoogate->setApikey('your_api_key');

// username and password are optional. If you want to get your private 
// bookmarks, you hav to provide them
$zoogate->setUsername('your_user_name');
$zoogate->setPassword('your_password');

// optional - set a limit for the display 
$zoogate->setLimit(300);

// in which format do you want to get the result (json|object|array)
$zoogate->setResultFormat('json');

// examples for requests
// Items Popular
$result = $zoogate->get('items', 'popular', array('type' => 'all'));

// Items Info
//$result =  $zoogate->get('items', 'info', array('uid' => 'il4ik2'));

// Users Items
//$result =  $zoogate->get('users', 'items', array('username' => 'awenkhh'));

// Users Info
//$result =  $zoogate->get('users', 'info', array('username' => 'awenkhh'));

// Users Friends
//$result =  $zoogate->get('users', 'friends', array('username' => 'awenkhh'));
