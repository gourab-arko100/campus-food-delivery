<?php
declare(strict_types=1);

session_start();

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'food_delivery');
define('DB_USER', 'root');
define('DB_PASS', '');

define('BASE_URL', '/food_delivery_php');

date_default_timezone_set('Asia/Dhaka');

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../includes/helpers.php';
