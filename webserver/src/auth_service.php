<?php

function signup($conn, $body){

    $username = $body->username;
    $password_hash = password_hash($body->password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
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

    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    if(!$stmt->execute()) { 
        error_response(500, "problem adding user data to the database");
    }

    $result = $stmt->get_result();
    if($result->num_rows != 1){
        error_response(409, "username is not valid");
    }

    $user = $result->fetch_object();
    $password_hash = $user->password_hash;

    $stmt->close();
    
    if(password_verify($password, $password_hash)){
        success_response(200,"You are logged in");
    }

    error_response(401,"Invalid credentials");
}

function logout(){
    success_response(200, "Successful logout");
}