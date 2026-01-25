<?php

// (optional) keep db.php only if it doesn't print anything
require_once __DIR__ . '/api/db.php';

$page = $_GET['page'] ?? 'login';

$allowed = ['login', 'register', 'contact_list', 'contact_create_and_edit'];

if (!in_array($page, $allowed)) {
  http_response_code(404);
  echo "Page not found";
  exit;
}

require __DIR__ . "/pages/$page.php";
