<?php

function signup($conn, $body){

    $username = $body->username;
    $password_hash = password_hash($body->password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    $data = execute_stmt($stmt);
    $stmt->close();
    
    if(count($data)!=0){
        send_response(409,false,[],'Invalid credentials'); 
    }

    $token = bin2hex(random_bytes(32));

    $stmt = $conn->prepare("INSERT INTO users (username, password_hash, token) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password_hash, $token);
    $data = execute_stmt($stmt);
    $stmt->close();
    $data = [['username'=>$username, 'id'=>$conn->insert_id, 'token'=>$token]];

    send_response(200,true,$data,null);

}

function login($conn, $owner_id, $body){

    if($owner_id != null){
        send_response(200, true, [], null);
    }
    
    if($body->login_type=="token"){
        send_response(401, false, [], "Invalid token");
    }

    $username = $body->username;
    $password = $body->password;

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    $data = execute_stmt($stmt);
    $stmt->close();

    if(count($data)==0){
        send_response(401,false,[$owner_id],'Invalid credentials');
    }

    if(count($data)!=1){
        send_response(500,false,[],'Database contains duplicate usernames. Please resolve'); 
    }

    $password_hash = $data[0]['password_hash'];
    $id = $data[0]['id'];

    if(password_verify($password, $password_hash)){

        $token = bin2hex(random_bytes(32));
        $stmt = $conn->prepare("UPDATE users SET token = ? WHERE username = ?");
        $stmt->bind_param("ss", $token, $username);
        $data = execute_stmt($stmt);
        $stmt->close();

        $data = [['username'=>$username, 'id'=>$id, 'token'=>$token]];
        send_response(200,true,$data,null);
    }

    send_response(401,false,[],'Invalid credentials');

}

function logout($conn, $token){

    if($token){

        $stmt = $conn->prepare("UPDATE users SET token = NULL WHERE token = ?");
        $stmt->bind_param("s", $token);
        $data = execute_stmt($stmt);
        $changed = $stmt->affected_rows;

        $stmt->close();
        
        if ($changed === 1) {
            send_response(200, true, [], null); 
        } 
        else {
            send_response(401, false, [$changed], "Invalid token"); 
        }
    }
    else{
        send_response(200, true, [], null);      
    }

}