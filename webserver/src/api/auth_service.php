<?php

function signup($conn, $body){
    $username = $body->username;
    $password = $body->password;

    // Validate input
    if (!$username || !$password) {
        send_response(500, false, [],"Username and password required");
    }

    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $data = execute_stmt($stmt);
    $stmt->close();
    if (count($data) > 0) {
        send_response(500, false, [],"User already exists");
    }

    // Generate password hash and token
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(32));

    // Insert new user into database
    $stmt = $conn->prepare("INSERT INTO users (username, password_hash, token) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password_hash, $token);
    execute_stmt($stmt);
    $stmt->close();

    // Set cookie and send response
    $data = [['username'=>$username, 'id'=>$conn->insert_id]];
    setcookie('token', $token, time() + (86400 * 30), "/");
    send_response(200,true,$data,null);

}

function login($conn, $token, $body){
    // Check if current token is still valid if exists
    if ($token) {
        if (token_check($conn, $token)) {
            $stmt = $conn->prepare("SELECT username, id FROM users WHERE token = ?");
            $stmt->bind_param("s", $token);
            $data = execute_stmt($stmt);
            $stmt->close();
            send_response(200, true, $data, null); 
        } else {
            // Invalid token, clear cookie
            setcookie('token', '', time() - 3600, "/");
            send_response(401, false, [], "Invalid token"); 
        }
    }

    $username = $body->username;
    $password = $body->password;

    // Fetch user by username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $data = execute_stmt($stmt);
    $stmt->close();

    $password_hash = $data[0]['password_hash'];
    $id = $data[0]['id'];

    // Verify password
    if(password_verify($password, $password_hash)){
        // Generate new token
        $token = bin2hex(random_bytes(32));
        $stmt = $conn->prepare("UPDATE users SET token = ? WHERE id = ?");
        $stmt->bind_param("si", $token, $id);
        $data = execute_stmt($stmt);
        $stmt->close();

        // Send response with new token
        $data = [['username'=>$username, 'id'=>$id]];
        setcookie('token', $token, time() + (86400 * 30), "/");
        send_response(200,true,$data,null);
    }

    send_response(401,false,[],'Invalid credentials');
}

function logout($conn, $token){
    if($token){
        // Invalidate token in database
        $stmt = $conn->prepare("UPDATE users SET token = NULL WHERE token = ?");
        $stmt->bind_param("s", $token);
        execute_stmt($stmt);
        $changed = $stmt->affected_rows;
        $stmt->close();

        // Clear cookie and send response
        setcookie('token', '', time() - 3600, "/");
        send_response(200, true, [$changed], null); 
    }
    else{
        send_response(401, true, [], "Cannot Logout. No login session.");      
    }
}