<?php

$dsn = 'mysql:dbname=test;host=localhost';
$user = '';
$password = '';

try {

    $conn = new PDO($dsn, $user, $password);

} catch (PDOException $e) {

    echo 'Connection failed: ' . $e->getMessage();
}

session_start();

include("controller.php");

$controller = new Controller($conn);


?>