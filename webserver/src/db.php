<?php

$servername = "db";
$username = getenv("DB_USERNAME");
$password = getenv("DB_PASSWORD");
$dbname = getenv("DB_DATABASE");

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error){
    send_response(500, false, [], 'Server failed to establish database connection');
    exit();
}