<?php

function signup($conn, $body){

    $username = $body->username;
    $password_hash = password_hash($body->password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    $data = execute_stmt($stmt);
    
    if(count($data)!=0){
        send_response(409,false,[],'Invalid credentials'); 
    }

    $token = bin2hex(random_bytes(32));

    $stmt = $conn->prepare("INSERT INTO users (username, password_hash, token) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password_hash, $token);
    $data = execute_stmt($stmt);
    $data = [['username'=>$username, 'id'=>$conn->insert_id, 'token'=>$token]];

    send_response(200,true,$data,null);

}

function login($conn, $body){

    $username = $body->username;
    $password = $body->password;

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    $data = execute_stmt($stmt);

    if(count($data)==0){
        send_response(401,false,[],'Invalid credentials');
    }

    if(count($data)!=1){
        send_response(500,false,[],'Database contains duplicate usernames. Please resolve'); 
    }

    $password_hash = $data[0]['password_hash'];

    if(password_verify($password, $password_hash)){
        $data = [['username'=>$data[0]['username'], 'id'=>$data[0]['id'], 'token'=>$data[0]['token']]];
        send_response(200,true,$data,null);
    }

    send_response(401,false,[],'Invalid credentials');

}

function logout($conn, $token){

    if($token){

        $stmt = $conn->prepare("UPDATE users SET token = NULL WHERE token = ?");
        $stmt->bind_param("s", $token);
        $data = execute_stmt($stmt);

        $changed = $conn->affected_rows;
        if ($changed === 1) {
            send_response(200, true, [], null); 
        } 
        else {
            send_response(401, false, [], "Invalid token"); 
        }
    }
    else{
        send_response(200, true, [], null);      
    }

}