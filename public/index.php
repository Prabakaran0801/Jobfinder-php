<?php
require __DIR__ . "/../vendor/autoload.php";

use Framework\Router;
use Framework\Session;

Session::start();


require "../helper.php";

//Custom auto loader
//spl_autoload_register(function ($class){
//    $path = basePath("Framework/" . $class . ".php");
//   if(file_exists($path)){
//      require $path;
//    }
//});

//Itanciate the route
$router = new Router();

//Get routes
$routes = require basePath('routes.php');

//Get current URI and HTTP method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


//Route the request
$router->route($uri);
