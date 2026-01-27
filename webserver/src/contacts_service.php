<?php

function read_contact($conn, $params){
    
        $owner_id = $params -> owner_id ?? '';
        $first_name = $params->first_name ?? '';
        $last_name = $params->last_name ?? '';
        $email = $params->email ?? '';
        $phone = $params->phone ?? '';

        $stmt = $conn->prepare("SELECT * FROM contacts WHERE owner_id = ? AND first_name LIKE CONCAT(?, '%') AND last_name  LIKE CONCAT(?, '%') AND email  LIKE CONCAT(?, '%') AND phone  LIKE CONCAT(?, '%')");
        $stmt->bind_param("issss", $owner_id, $first_name, $last_name, $email, $phone);

        execute_stmt_and_respond($stmt);
}

function create_contact($conn, $body){
    
        $owner_id = $body->owner_id ?? null;
        $first_name = $body->first_name ?? null;
        $last_name = $body->last_name  ?? null;
        $email = $body->email ?? null;
        $phone = $body->phone ?? null;
        
        $stmt = $conn->prepare("INSERT INTO contacts (owner_id, first_name, last_name, email, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss",$owner_id,$first_name,$last_name,$email,$phone);
        
        execute_stmt_and_respond($stmt);

}

function update_contact($conn, $id, $body) {
    
    $contact_id = $id ?? null;
    $owner_id = $body->owner_id ?? null;
    $first_name = $body->first_name ?? null;
    $last_name = $body->last_name ?? null;
    $email = $body->email ?? null;
    $phone = $body->phone ?? null;

    $stmt = $conn->prepare("UPDATE contacts SET first_name=?, last_name=?, email=?, phone=? WHERE id=? AND owner_id=?");
    $stmt->bind_param("ssssii", $first_name, $last_name, $email, $phone, $contact_id, $owner_id);

    execute_stmt_and_respond($stmt);
}

function delete_contact($conn, $id, $body) {

    $contact_id = $id ?? null;
    $owner_id = $body->owner_id ?? null;
    
    $stmt = $conn->prepare("DELETE FROM contacts WHERE id=? AND owner_id=?");
    $stmt->bind_param("ii", $contact_id, $owner_id);

    execute_stmt_and_respond($stmt);
}