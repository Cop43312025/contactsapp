<?php

switch($method ?? null){ 

    case "GET":
        read_contact($conn,$params);
        break;

    case "POST":
        create_contact($conn,$body);
        break;

    case "PUT":
        update_contact($conn,$segments[1],$body);
        break;

    case "DELETE":
        delete_contact($conn,$segments[1],$body);
        break;

    default:
        error_response(405,"invalid method");     
         
}