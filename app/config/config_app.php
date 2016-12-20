<?php

//=========================================== Ruta base para archivos ====================================//

$base = str_replace('/app/config', '', str_replace("\\", "/", dirname(__FILE__)));

define("BASE_PATH" , $base );
define("PATH_CONTROLLER" , $base . "/app/nomina/controllers/");
define("PATH_VIEWS" , $base . "/app/nomina/views/");
define("PATH_LAYOUTS" , $base . "/app/nomina/layout/");
define('URL', 'http://' . $_SERVER["SERVER_NAME"]);
define('EXTENSION_TEMPLATES', '.html');

//=========================================== Autoloader para namespace ==================================//
require BASE_PATH . "/vendor/autoload.php";