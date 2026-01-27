<?php

function signup($conn, $body){

    $username = $body->username;
    $password_hash = password_hash($body->password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("SELECT id, username, email FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    if(!$stmt->execute()) { 
        error_response(500, "problem adding user data to the database");
    }

    $result = $stmt->get_result();
    if($result->num_rows != 0){
        error_response(409, "username is not valid");
    }

    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password_hash);

    execute_stmt_and_respond($stmt);

}

function login($conn, $body){

    $username = $body->username;
    $password = $body->password;

    $stmt= $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    execute_stmt_and_respond($stmt);
}

function logout(){
    success_response(200, "Successful logout");
}