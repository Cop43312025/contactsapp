<?php

$dsn = 'mysql:host=db;dbname=spdb;charset=utf8mb4';
$user = 'root';
$pass = 'mysqlrootpassword21';

$pdo = new PDO(
    $dsn,
    $user,
    $pass,
    [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]
);

$stmt = $pdo->query('SELECT id FROM example');

foreach ($stmt as $row) {
    echo $row['id'] . PHP_EOL;
}
?>
