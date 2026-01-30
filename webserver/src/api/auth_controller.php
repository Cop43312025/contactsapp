<?php

$action = $segments[1] ?? null;

switch ($action){

    case "signup":
        signup($conn, $body);
        break;

    case "login":
        login($conn, $token, $body);
        break;

    case "logout":
        logout($conn, $token);
        break;

    default:
        send_response(404, false, [], "Endpoint not found");

}