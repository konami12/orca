<?php

//=========================================== Ruta base para archivos ====================================//

$base = str_replace('/app/config', '', str_replace("\\", "/", dirname(__FILE__)));

define("BASE_PATH" , $base );
define("PATH_CONTROLLER" , $base . "/app/application/controllers/");
define("PATH_VIEWS" , $base . "/app/application/views/");
define("PATH_LAYOUTS" , $base . "/app/application/layout/");
define('URL', 'http://' . $_SERVER["SERVER_NAME"] . '/');
define('EXTENSION_TEMPLATES', '.html');

//=========================================== Variables de configuracion ==================================//

define("CONNECTION_TYPE", "mys");
define("BD_USER", getenv("USER"));
define("BD_PASS", getenv("PASS"));
define("BD_BASE", getenv("BASE"));
define("KEY", "SeTiC-537IC");

//=========================================== Autoloader para namespace ==================================//
require BASE_PATH . "/vendor/autoload.php";