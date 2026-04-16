<?php
define('BASE_PATH', dirname(__DIR__)); 
define('BASE_URL',  '/profit/public'); 

require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/app/core/model.php';
require_once BASE_PATH . '/app/core/controller.php';
require_once BASE_PATH . '/app/core/app.php';

new App();