<?php

$servername = "localhost";
$username = getenv("DB_USERNAME");
$password = getenv("DB_PASSWORD");
$dbname = getenv("DB_DATABASE");

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error){
    error_response(500, 'database connection failed to establish on server');
    exit();
}