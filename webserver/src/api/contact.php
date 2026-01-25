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

$input = json_decode(file_get_contents('php://input'), true);
$db = dbConnect();
if (!$db) returnWithError("Database connection failed");

switch ($input["action"] ?? null) {
    case "create":
        createContact($db, $input);
        break;
    case "edit":
        editContact($db, $input);
        break;
    case "delete":
        deleteContact($db, $input);
        break;
    case "get":
        getContacts($db, $input);
        break;
    default:
        returnWithError("Invalid action");
}

$db->close();

function deleteContact($db, $input) {
    $contactId = $input["contact_id"] ?? null;
    $ownerId = $input["owner_id"] ?? null;

    if (!$contactId || !$ownerId) returnWithError("Missing required fields");

    $stmt = $db->prepare("DELETE FROM contacts WHERE id=? AND owner_id=?");
    $stmt->bind_param("ii", $contactId, $ownerId);
    if ($stmt->execute() && $stmt->affected_rows > 0) returnWithSuccess(["contact_id" => $contactId]);
    else returnWithError("Failed to delete contact or not found");
}



function editContact($db, $input) {
    $contactId = $input["contact_id"] ?? null;
    $ownerId = $input["owner_id"] ?? null;
    $firstName = $input["first_name"] ?? null;
    $lastName = $input["last_name"] ?? null;
    $email = $input["email"] ?? null;
    $phone = $input["phone"] ?? null;

    if (!$contactId || !$ownerId) returnWithError("Missing required fields");

    $stmt = $db->prepare("UPDATE contacts SET first_name=?, last_name=?, email=?, phone=? WHERE id=? AND owner_id=?");
    $stmt->bind_param("ssssii", $firstName, $lastName, $email, $phone, $contactId, $ownerId);
    if ($stmt->execute()) returnWithSuccess(["contact_id" => $contactId]);
    else returnWithError("Failed to update contact");
}


// ---- Response Helpers ----
function sendResultInfoAsJson($obj) {
    header('Content-type: application/json');
    echo $obj;
}

function returnWithSuccess($data) {
    sendResultInfoAsJson(json_encode(["success" => true, "data" => $data, "error" => ""]));
}

function returnWithError($err) {
    sendResultInfoAsJson(json_encode(["success" => false, "data" => null, "error" => $err]));
}