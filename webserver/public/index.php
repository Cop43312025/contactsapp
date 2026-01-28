<?php

require "./src/service_helpers.php";
require "./src/db.php";

$token = $_COOKIE['token'] ?? null;
$owner_id = token_check($conn, $token);

$path = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
$method = $_SERVER["REQUEST_METHOD"];
$segments = explode("/", trim($path,"/"));
$body = json_decode(file_get_contents("php://input")) ?? (object)[];
$params = (object) $_GET;
$resource = $segments[0] ?? null;

try {
    switch($resource){
        case 'users':
            require('./src/auth_service.php');
            require('./src/auth_controller.php');
            break;
        case 'contacts':
            require('./src/contacts_service.php');
            require('./src/contacts_controller.php');
            break;
        default:
            send_response(400, false, [], "Invalid request uri, or uri parsing error");
    }
}catch (Throwable $e) {
    send_response(500, false, [], $e->getMessage());
}
