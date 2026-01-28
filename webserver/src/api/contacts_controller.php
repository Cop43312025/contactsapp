<?php

if($owner_id === null){
    send_response(401, false, [], "Unauthorized request");
} 

$contact_id = $segments[1] ?? null;

switch($method ?? null){ 

    case "GET":
        read_contact($conn,$owner_id,$params);
        break;

    case "POST":
        create_contact($conn,$owner_id,$body);
        break;

    case "PUT":
        update_contact($conn,$owner_id,$contact_id,$body);
        break;

    case "DELETE":
        delete_contact($conn,$owner_id,$contact_id);
        break;

    default:
        send_response(405, false, [], "Invalid method");
}