<?php

switch ($segments[1] ?? null){

    case "signup":
        signup($conn, $body);
        break;

    case "login":
        login($conn, $body);
        break;

    case "logout":
        logout();
        break;

    default:
        error_response(400, "invalid request uri, or server request uri parsing error");

}