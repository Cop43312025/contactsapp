<?php


require_once __DIR__ . '/db.php';
use function Src\Api\dbConnect;

header('Content-Type: application/json');

#read input json
$input = json_decode(file_get_contents("php://input"), true);

#connect to database
$db = dbConnect();
if (!$db) {
    returnWithError("Database connection failed");
}


// TEMP for testing
$ownerId = $input["owner_id"] ?? null;
$contactId = $input["contact_id"] ?? null;

$method=$_SERVER['REQUEST_METHOD'];

if ($method == 'POST') {
    delete_contact($db, $ownerId, $contactId);
} elseif ($method == 'PUT') {
    $first_name = $input["first_name"] ?? null;
    $last_name = $input["last_name"] ?? null;
    $email = $input["email"] ?? null;
    $phone = $input["phone"] ?? null;
    edit_contact($db, $ownerId, $contactId, $first_name, $last_name, $email, $phone);
} else {
    returnWithError("Invalid request method");
}

function delete_contact($db, $ownerId, $contactId) {
    $stmt = $db->prepare("DELETE FROM contacts WHERE id = ? AND owner_id = ?");
    $stmt->bind_param("ii", $contactId, $ownerId);
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            returnSuccess("Contact deleted successfully");
        } else {
            returnWithError("No Records Found");
        }
    } else {
        returnWithError("Failed to delete contact");
    }
    $stmt->close();
}

function returnSuccess($message) {
    echo json_encode([
        "success" => true,
        "message" => $message,
        "error" => ""
    ]);
    exit;
}


function edit_contact($db, $ownerId, $contactId, $first_name, $last_name,$email, $phone) {
    $stmt = $db->prepare("
        UPDATE contacts
        SET first_name = ?, last_name = ?, email = ?, phone = ?
        WHERE id = ? AND owner_id = ?
    ");
    $stmt->bind_param("ssssii", $first_name, $last_name,$email, $phone, $contactId, $ownerId);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            returnSuccess("Contact updated successfully");
        } else {
            returnWithError("No Records Found");
        }
    } else {
        returnWithError("Failed to update contact");
    }
    $stmt->close();
}
