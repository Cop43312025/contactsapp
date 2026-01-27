<?php

function error_response($status_code, $err){
    http_response_code($status_code);
    echo json_encode(["success" => false, "data" => null, "error"=>$err]);
    exit;
}

function success_response($status_code, $data){
    http_response_code($status_code);
    echo json_encode(["success" => true, "data" => $data, "error"=>null]);
    exit;
}

function execute_stmt_and_respond($stmt) {

    if (!$stmt->execute()) {
        error_response(500, $stmt->error);
        $stmt->close();
        return;
    }

    $result = $stmt->get_result();

    if ($result !== false) {
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        if (count($rows) === 0) {
            error_response(401, "Invalid credentials");
        } else {
            success_response(200, $rows);
        }
        $stmt->close();
        return;
    }

    $stmt->affected_rows > 0 ? success_response(200, null) : error_response(400, "No rows affected");
    $stmt->close();
}