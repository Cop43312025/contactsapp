<?php

function read_contact($conn, $owner_id, $body){

    $search_query = preg_replace('/(?<=\d)-/', '', $body->search_query ?? "");
    $query_exprs = array_filter(explode(' ',$search_query));
    $db_query = "SELECT * FROM contacts WHERE owner_id = ? "; 
    $types = "i";
    $params = [];
    foreach($query_exprs as $expr){
        $db_query .= "AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone LIKE ?) ";
        $types .= "ssss";
        $expr = "$expr%";
        array_push($params, $expr, $expr, $expr, $expr);
    }   
    $db_query .= "ORDER BY first_name ASC, last_name ASC, email ASC, phone ASC";
    $stmt = $conn->prepare($db_query);
    $stmt->bind_param($types,$owner_id,...$params);
    $data = execute_stmt($stmt);
    $stmt->close();
    send_response(200, true, $data, null);
}

function create_contact($conn, $owner_id, $body){

    $first_name = $body->first_name ?? null;
    $last_name = $body->last_name  ?? null;
    $email = $body->email ?? null;
    $phone = preg_replace('/(?<=\d)-/', '', $body->phone ?? null);
    
    $stmt = $conn->prepare("INSERT INTO contacts (owner_id, first_name, last_name, email, phone) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss",$owner_id,$first_name,$last_name,$email,$phone);
    
    $data = execute_stmt($stmt);
    $stmt->close();
    send_response(200, true, ["id"=>$conn->insert_id], null);

}

function update_contact($conn, $owner_id, $id, $body) {
    $contact_id = $id ?? null;
    
    $fields = [];
    $params = [];
    
    if (isset($body->first_name)) {
        $fields[] = "first_name=?";
        $params[] = $body->first_name;
    }
    if (isset($body->last_name)) {
        $fields[] = "last_name=?";
        $params[] = $body->last_name;
    }
    if (isset($body->email)) {
        $fields[] = "email=?";
        $params[] = $body->email;
    }
    if (isset($body->phone)) {
        $fields[] = "phone=?";
        $params[] = preg_replace('/(?<=\d)-/', '', $body->phone);
    }

    if (empty($fields)) {
        send_response(400, false, [], "No fields to update");
        return;
    }

    $sql = "UPDATE contacts SET " . implode(", ", $fields) . " WHERE id=? AND owner_id=?";
    $params[] = $contact_id;
    $params[] = $owner_id;

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat("s", count($params) - 2) . "ii", ...$params);

    execute_stmt($stmt);
    $changed = $stmt->affected_rows;
    $stmt->close();
    
    if ($changed === 0) {
        // Handle unauthorized change
        $stmt = $conn->prepare("SELECT owner_id FROM contacts WHERE id = ?");
        $stmt->bind_param("i", $contact_id);
        $data = execute_stmt($stmt);
        $stmt->close();
        if ($owner_id !== $data[0]['owner_id']) {
            send_response(403, false, [], "Unauthorized to update this contact");
            return;
        } else {
            // Otherwise just give generic no change response
            send_response(400, false, ["changed"=>$changed], "No change made");
        }
    }
    send_response(200, true, ["id"=>$contact_id], null);
}

function delete_contact($conn, $owner_id, $id) {

    $contact_id = $id ?? null;
    
    $stmt = $conn->prepare("DELETE FROM contacts WHERE id=? AND owner_id=?");
    $stmt->bind_param("ii", $contact_id, $owner_id);
    $data = execute_stmt($stmt);
    $changed = $stmt->affected_rows;
    $stmt->close();

    if ($changed === 0) {
        // Handle unauthorized change
        $stmt = $conn->prepare("SELECT owner_id FROM contacts WHERE id = ?");
        $stmt->bind_param("i", $contact_id);
        $data = execute_stmt($stmt);
        $stmt->close();
        if ($owner_id !== $data[0]['owner_id']) {
            send_response(403, false, [], "Unauthorized to delete this contact");
            return;
        } else {
            // Otherwise just give generic no change response
            send_response(400, false, ["changed"=>$changed], "No change made");
        }
    }
    send_response(200, true, ["id"=>$contact_id], null);
}