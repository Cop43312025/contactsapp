<?php

set_error_handler(function ($severity, $message, $file, $line) {
  throw new ErrorException($message, 0, $severity, $file, $line);
});

require "./api/service_helpers.php";
require "./api/db.php";

$token = $_COOKIE['token'] ?? null;
$owner_id = token_check($conn, $token);

$path = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
$method = $_SERVER["REQUEST_METHOD"];
$segments = array_slice(explode("/", trim($path,"/")),1);
$body = json_decode(file_get_contents("php://input")) ?? (object)[];
$params = (object) $_GET;
$resource = $segments[0] ?? null;

try {
    switch($resource){
        case 'users':
            require('./api/auth_service.php');
            require('./api/auth_controller.php');
            break;
        case 'contacts':
            require('./api/contacts_service.php');
            require('./api/contacts_controller.php');
            break;
        default:
            send_response(404, false, [], "404: Page not found");
    }
}catch (Throwable $e) {
    send_response(500, false, [], $e->getMessage());
}
