<?php

$action = $segments[1] ?? null;

switch ($action){

    case "signup":
        signup($conn, $body);
        break;

    case "login":
        login($conn, $body);
        break;

    case "logout":
        logout($conn, $token);
        break;

    default:
        send_response(400, false, [], "Invalid action");

}