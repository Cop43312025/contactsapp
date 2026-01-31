<?php

set_error_handler(function ($severity, $message, $file, $line) {
  throw new ErrorException($message, 0, $severity, $file, $line);
});

require __DIR__ . "/api/service_helpers.php";
require __DIR__ . "/api/db.php";

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) ?? "/";
$method = $_SERVER["REQUEST_METHOD"];

if (strpos($path, "/api/") === 0 || strpos($path, "/users") === 0 || strpos($path, "/contacts") === 0) {

  $token = $_COOKIE["token"] ?? null;
  $owner_id = token_check($conn, $token);

  $segments = explode("/", trim($path, "/"));
  if (isset($segments[0]) && $segments[0] === "api") {
    $segments = array_slice($segments, 1);
  }

  $resource = $segments[0] ?? null;
  $body = json_decode(file_get_contents("php://input")) ?? (object)[];
  $params = (object) $_GET;

  try {
    switch ($resource) {
      case "users":
        require __DIR__ . "/api/auth_service.php";
        require __DIR__ . "/api/auth_controller.php";
        break;

      case "contacts":
        require __DIR__ . "/api/contacts_service.php";
        require __DIR__ . "/api/contacts_controller.php";
        break;

      default:
        send_response(404, false, [], "Endpoint not found");
    }
  } catch (Throwable $e) {
    send_response(500, false, [], $e->getMessage());
  }

  exit;
}

$page = $_GET["page"] ?? "login";

$allowed = [
  "login",
  "register",
  "contact_list",
  "contact_create_and_edit",
  "contact_info",
  "contact_edit",
  "contact_delete"
];

if (!in_array($page, $allowed)) {
  http_response_code(404);
  echo "Page not found";
  exit;
}

require __DIR__ . "/pages/$page.php";
