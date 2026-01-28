<?php

function read_contact($conn, $owner_id, $params){
    
        $first_name = $params->first_name ?? '';
        $last_name = $params->last_name ?? '';
        $email = $params->email ?? '';
        $phone = $params->phone ?? '';

        $stmt = $conn->prepare("SELECT * FROM contacts WHERE owner_id = ? AND first_name LIKE CONCAT(?, '%') AND last_name  LIKE CONCAT(?, '%') AND email  LIKE CONCAT(?, '%') AND phone  LIKE CONCAT(?, '%')");
        $stmt->bind_param("issss", $owner_id, $first_name, $last_name, $email, $phone);

       $data = execute_stmt($stmt);
       send_response(200, true, $data, null);
}

function create_contact($conn, $owner_id, $body){
    
        $first_name = $body->first_name ?? null;
        $last_name = $body->last_name  ?? null;
        $email = $body->email ?? null;
        $phone = $body->phone ?? null;
        
        $stmt = $conn->prepare("INSERT INTO contacts (owner_id, first_name, last_name, email, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss",$owner_id,$first_name,$last_name,$email,$phone);
        
       $data = execute_stmt($stmt);
       send_response(200, true, $data, null);

}

function update_contact($conn, $owner_id, $id, $body) {
    
    $contact_id = $id ?? null;
    $first_name = $body->first_name ?? null;
    $last_name = $body->last_name ?? null;
    $email = $body->email ?? null;
    $phone = $body->phone ?? null;

    $stmt = $conn->prepare("UPDATE contacts SET first_name=?, last_name=?, email=?, phone=? WHERE id=? AND owner_id=?");
    $stmt->bind_param("ssssii", $first_name, $last_name, $email, $phone, $contact_id, $owner_id);

    $data = execute_stmt($stmt);
    
    $changed = $conn->affected_rows;
    if ($changed === 0) {
        send_response(400, false, [], "No change");
    }
    send_response(200, true, $data, null);
}

function delete_contact($conn, $owner_id, $id) {

    $contact_id = $id ?? null;
    
    $stmt = $conn->prepare("DELETE FROM contacts WHERE id=? AND owner_id=?");
    $stmt->bind_param("ii", $contact_id, $owner_id);

    $data = execute_stmt($stmt);

    $changed = $conn->affected_rows;
    if ($changed === 0) {
        send_response(400, false, [], "No change");
    }
    send_response(200, true, $data, null);
}