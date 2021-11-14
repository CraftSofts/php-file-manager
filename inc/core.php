<?php
define('COLOR', 'blue-grey');
define('HEX', '#e0f7fa'); // in some case
// turn of notices and warnings
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
session_start();
require_once(__DIR__.'/functions.php');
require_once(__DIR__.'/class.file.php');
// make sure if user is logged in
secureAccess();
