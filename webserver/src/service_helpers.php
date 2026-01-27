<?php

function error_response($status_code, $err){
    http_response_code($status_code);
    echo json_encode(["success" => false, "data" => null, "error"=>$err]);
}

function success_response($status_code, $data){
    http_response_code($status_code);
    echo json_encode(["success" => true, "data" => $data, "error"=>null]);
}

function execute_stmt_and_respond($stmt) {

    if (!$stmt->execute()) {
        error_response(500, $stmt->error);
        $stmt->close();
        return;
    }

    $result = $stmt->get_result();

    if ($result !== false) {
        success_response(200, $result->fetch_all(MYSQLI_ASSOC));
        $stmt->close();
        return;
    }

    $stmt->affected_rows > 0 ? success_response(200, null) : error_response(400, "No rows affected");
    $stmt->close();
}