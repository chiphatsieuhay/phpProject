<?php
require "../bootstrap.php";
use Src\Controllers\ContactController;
use Src\Controllers\AppointmentController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// all of our endpoints start with /person

// everything else results in a 404 Not Found
if ($uri[1] !== 'contact' && $uri[1]!== 'appointment' ) {
    header("HTTP/1.1 404 Not Found");
    exit();
}


$requestMethod = $_SERVER["REQUEST_METHOD"];
// $controller = new 
$controller = $uri[1]=='contact' ?new ContactController($dbConnection, $requestMethod):new AppointmentController($dbConnection,$requestMethod);
$controller->processRequest();