<?php

require_once __DIR__ . '/db.php';
use function Src\Api\dbConnect;

$input = getRequestInfo();
$db = dbConnect();

if (!$db) {
    returnWithError("Database connection failed");
    exit;
}

switch ($input["login_type"] ?? null) {
    case "credential":
        $username = $input["username"] ?? null;
        $password = $input["password"] ?? null;

        if (!$username || !$password) {
            returnWithError("Username and password required");
            break;
        }

        $stmt = $db->prepare("SELECT id, username, password_hash, token FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password_hash'])) {
                returnWithInfo($row['username'], $row['id'], $row['token']);
            } else {
                returnWithError("Invalid credentials");
            }
        } else {
            returnWithError("No Records Found");
        }
        $stmt->close();
        break;
        
    case "token":
        $token = $input["token"] ?? null;

        if (!$token) {
            returnWithError("Token required");
            break;
        }

        $stmt = $db->prepare("SELECT id, username, token FROM users WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            returnWithInfo($row['username'], $row['id'], $row['token']);
        } else {
            returnWithError("Invalid Token");
        }
        $stmt->close();
        break;
        
    case "create":
        $username = $input["username"] ?? null;
        $password = $input["password"] ?? null;

        // Make sure username and password are provided
        if (!$username || !$password) {
            returnWithError("Username and password required");
            break;
        }

        // Check if username already exists
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            returnWithError("Username already exists");
            $stmt->close();
            break;
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(32));
        
        $stmt = $db->prepare("INSERT INTO users (username, password_hash, token) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password_hash, $token);
        
        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
            returnWithInfo($username, $user_id, $token);
        } else {
            returnWithError("User Creation Failed: " . $stmt->error);
        }
        $stmt->close();
        break;
        
    default:
        returnWithError("Invalid login type");
        break;
}

$db->close();

function getRequestInfo() {
    return json_decode(file_get_contents('php://input'), true);
}

function sendResultInfoAsJson($obj) {
    header('Content-type: application/json');
    echo $obj;
}

function returnWithInfo($username, $id, $token) {
    $retValue = json_encode(["username" => $username, "id" => $id, "token" => $token, "error" => ""]);
    sendResultInfoAsJson($retValue);
}

function returnWithError($err) {
    $retValue = json_encode(["id" => 0, "username" => "", "token" => "", "error" => $err]);
    sendResultInfoAsJson($retValue);
}