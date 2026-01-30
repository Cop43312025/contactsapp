<?php

function read_contact($conn, $owner_id, $params){
    
    $first_name = $params->first_name ?? '';
    $last_name = $params->last_name ?? '';
    $email = $params->email ?? '';
    $phone = $params->phone ?? '';

    $stmt = $conn->prepare("SELECT * FROM contacts WHERE owner_id = ? AND first_name LIKE CONCAT(?, '%') AND last_name  LIKE CONCAT(?, '%') AND email  LIKE CONCAT(?, '%') AND phone  LIKE CONCAT(?, '%')");
    $stmt->bind_param("issss", $owner_id, $first_name, $last_name, $email, $phone);

    $data = execute_stmt($stmt);
    $stmt->close();
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
        $params[] = $body->phone;
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
    $changed = $conn->affected_rows;
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
    $changed = $conn->affected_rows;
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