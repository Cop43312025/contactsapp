<?php

header('Content-Type: application/json; charset=utf-8');

require "./src/service_helpers.php";
require "./src/db.php";

$path = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
$method = $_SERVER["REQUEST_METHOD"];
$segments = explode("/", trim($path,"/"));
$body = json_decode(file_get_contents("php://input")) ?? null;
$params = (object) $_GET;

switch($segments[0] ?? null){
    case 'users':
        require('./src/auth_service.php');
        require('./src/auth_controller.php');
        break;
    case 'contacts':
        require('./src/contacts_service.php');
        require('./src/contacts_controller.php');
        break;
    default:
        error_response(400, "invalid request uri, or server request uri parsing error");
}
