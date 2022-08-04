<?php


$path = __DIR__ . "\\..\\";
//echo wp_normalize_path($path);
define("PROJECT_ROOT_PATH", wp_normalize_path($path));

// include main configuration file
require_once PROJECT_ROOT_PATH . "/config/config.php";
 
// include the base controller file
require_once PROJECT_ROOT_PATH . "/api/BaseController.php";
 
// include the use model file
require_once PROJECT_ROOT_PATH . "/api/Model/UserModel.php";

// blacklist
// include the base controller file
require_once PROJECT_ROOT_PATH . "/api/BlacklistController.php";
 
// include the use model file
require_once PROJECT_ROOT_PATH . "/api/Model/BlacklistModel.php";
 

?>