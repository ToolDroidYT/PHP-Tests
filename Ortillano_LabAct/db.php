<?php

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$databaseName = 'Ortillano_DB';

$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS $databaseName");
$conn->select_db($databaseName);

$createTableSQL = "CREATE TABLE IF NOT EXISTS Students (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    Gender ENUM('Male', 'Female') NOT NULL,
    Course VARCHAR(50) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    PhoneNumber VARCHAR(20) NOT NULL
)";

$conn->query($createTableSQL);
?>
