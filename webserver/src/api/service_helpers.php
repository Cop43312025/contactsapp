<?php

function execute_stmt($stmt) {

    try {
        $stmt->execute();
    }catch( mysqli_sql_exception $e){
        $stmt->close();
        send_response(500, false, [], $e->getMessage());
    }

    $result=$stmt->get_result();
    $data = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

    return $data;
}

function send_response($status, $success, $data, $error){
    header('Content-Type: application/json');
    http_response_code($status);
    $response = ['success' => $success, 'data' => $data, 'error' => $error];
    echo json_encode($response);
    exit;
}

function token_check($conn, $token){
    if(!$token){
        return null;
    }    
    $stmt = $conn->prepare("SELECT id FROM users WHERE token = ?");
    $stmt->bind_param("s", $token);
    $data = execute_stmt($stmt);
    $stmt->close();
    if(count($data)==0){
        return null;   
    }
    return $data[0]['id'];
}