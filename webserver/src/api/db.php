<?php

namespace Src\Api;
use mysqli;

function dbConnect() 
{
    $host = getenv('DB_HOST');
    $port = getenv('DB_PORT');
    $database = getenv('DB_DATABASE');
    $user = getenv('DB_USERNAME');
    $pass = getenv('DB_PASSWORD');
    
    $connection = new mysqli($host, $user, $pass, $database, $port);
    
    if ($connection->connect_error) {
        return null;
    }
    
    return $connection;
}

