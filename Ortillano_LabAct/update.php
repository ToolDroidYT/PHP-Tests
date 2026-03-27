<?php
require './db.php';

$allowedGender = ['Male', 'Female'];
$allowedCourse = ['BSIT', 'BSCS'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && is_numeric($_POST['id'])) {
    $updateID = (int) $_POST['id'];
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
        $updateSQL = $conn->prepare('UPDATE Students SET FirstName = ?, LastName = ?, Gender = ?, Course = ?, Email = ?, PhoneNumber = ? WHERE ID = ?');

        if ($updateSQL) {
            $updateSQL->bind_param('ssssssi', $firstName, $lastName, $gender, $course, $email, $phoneNumber, $updateID);
            $updateSQL->execute();
            $updateSQL->close();
        }
    }
}

$conn->close();
header('Location: ./index.php');
exit;
