<?php

if($owner_id === null){
    send_response(401, false, [], "Unauthorized request");
} 

$action = $segments[1] ?? null;
$contact_id = $segments[2] ?? null;

switch($action ?? null){ 

    case "view":
        read_contact($conn,$owner_id,$body);
        break;

    case "create":
        create_contact($conn,$owner_id,$body);
        break;

    case "update":
        update_contact($conn,$owner_id,$contact_id,$body);
        break;

    case "delete":
        delete_contact($conn,$owner_id,$contact_id);
        break;

    default:
        send_response(404, false, [], "Endpoint not found");
}