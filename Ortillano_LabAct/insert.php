<?php
require './db.php';

$allowedGender = ['Male', 'Female'];
$allowedCourse = ['BSIT', 'BSCS'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $course = $_POST['course'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $phoneNumber = trim($_POST['phone_number'] ?? '');

    $isValid = $firstName !== ''
        && $lastName !== ''
        && in_array($gender, $allowedGender, true)
        && in_array($course, $allowedCourse, true)
        && $email !== ''
        && $phoneNumber !== '';

    if ($isValid) {
        $insertSQL = $conn->prepare('INSERT INTO Students (FirstName, LastName, Gender, Course, Email, PhoneNumber) VALUES (?, ?, ?, ?, ?, ?)');

        if ($insertSQL) {
            $insertSQL->bind_param('ssssss', $firstName, $lastName, $gender, $course, $email, $phoneNumber);
            $insertSQL->execute();
            $insertSQL->close();
        }
    }
}

$conn->close();
header('Location: ./index.php');
exit;
