<?php

// database
$host = '127.0.0.1';
$username = 'root';
$password = '';
$databaseName = 'LabExamDB';

$conn = new mysqli($host, $username, $password);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS $databaseName");
$conn->select_db($databaseName);

$createTableSQL = "CREATE TABLE IF NOT EXISTS Users (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    Gender ENUM('Male', 'Female') NOT NULL,
    Course VARCHAR(50) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    PhoneNumber VARCHAR(20) NOT NULL
)";
$conn->query($createTableSQL);

$allowedGender = ['Male', 'Female'];
$allowedCourse = ['BSIT', 'BSCS'];

// Delete record
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $deleteID = (int) $_GET['delete'];

    $deleteSQL = "DELETE FROM Users WHERE ID = ?";
    $stmt = $conn->prepare($deleteSQL);

    if ($stmt) {
        $stmt->bind_param('i', $deleteID);
        $stmt->execute();
        $stmt->close();
    }

    header('Location: index.php');
    exit;
}

// Add or update record
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

    if ($isValid && isset($_POST['submit'])) {
        $insertSQL = "INSERT INTO Users (FirstName, LastName, Gender, Course, Email, PhoneNumber)
                      VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertSQL);

        if ($stmt) {
            $stmt->bind_param('ssssss', $firstName, $lastName, $gender, $course, $email, $phoneNumber);
            $stmt->execute();
            $stmt->close();
        }

        header('Location: index.php');
        exit;
    }

    if ($isValid && isset($_POST['update']) && isset($_POST['id']) && is_numeric($_POST['id'])) {
        $updateID = (int) $_POST['id'];

        $updateSQL = "UPDATE Users
                      SET FirstName = ?, LastName = ?, Gender = ?, Course = ?, Email = ?, PhoneNumber = ?
                      WHERE ID = ?";
        $stmt = $conn->prepare($updateSQL);

        if ($stmt) {
            $stmt->bind_param('ssssssi', $firstName, $lastName, $gender, $course, $email, $phoneNumber, $updateID);
            $stmt->execute();
            $stmt->close();
        }

        header('Location: index.php');
        exit;
    }
}

// Get user to edit
$editUser = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $editID = (int) $_GET['edit'];
    $editSQL = "SELECT * FROM Users WHERE ID = ?";

    $stmt = $conn->prepare($editSQL);
    if ($stmt) {
        $stmt->bind_param('i', $editID);
        $stmt->execute();
        $result = $stmt->get_result();
        $editUser = $result->fetch_assoc();
        $stmt->close();
    }
}

// Get all users for display
$users = [];
$allUsersSQL = "SELECT ID, FirstName, LastName, Gender, Email, Course, PhoneNumber FROM Users ORDER BY ID DESC";
$result = $conn->query($allUsersSQL);
if ($result) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .custom-input {
            border-radius: 8px;
            padding: 10px 15px;
        }

        .form-label {
            font-size: 0.9rem;
            margin-bottom: 0.2rem;
        }

        .btn-submit {
            border-radius: 20px;
            padding: 8px 40px;
            background: transparent;
            font-weight: 500;
        }

        .table-custom th {
            border-bottom: 2px solid currentColor;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .table-custom td {
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .action-btn {
            border: none;
            border-radius: 4px;
            padding: 4px 8px;
            color: white;
            font-size: 0.85rem;
        }

        .btn-edit {
            background-color: #f1c40f;
        }

        .btn-delete {
            background-color: #e74c3c;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-5">
        <div class="row">

            <div class="col-md-4 pe-md-5">
                <h4 class="text-center mb-4 text-secondary fw-bold">
                    <?= $editUser ? 'Edit Information' : 'Add Information' ?>
                </h4>

                <form action="index.php" method="POST">
                    <?php if ($editUser): ?>
                        <input type="hidden" name="id" value="<?= htmlspecialchars($editUser['ID']) ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control custom-input" value="<?= $editUser ? htmlspecialchars($editUser['FirstName']) : '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control custom-input" value="<?= $editUser ? htmlspecialchars($editUser['LastName']) : '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block">Gender</label>
                        <div class="form-check form-check-inline mt-1">
                            <input class="form-check-input" type="radio" name="gender" id="male" value="Male" <?= ($editUser && $editUser['Gender'] === 'Male') ? 'checked' : (empty($editUser) ? 'required' : '') ?>>
                            <label class="form-check-label text-secondary" for="male">Male</label>
                        </div>
                        <div class="form-check form-check-inline mt-1 ms-4">
                            <input class="form-check-input" type="radio" name="gender" id="female" value="Female" <?= ($editUser && $editUser['Gender'] === 'Female') ? 'checked' : '' ?>>
                            <label class="form-check-label text-secondary" for="female">Female</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Course</label>
                        <select name="course" class="form-select custom-input" required>
                            <option value="" <?= !$editUser ? 'selected disabled' : '' ?>></option>
                            <option value="BSIT" <?= ($editUser && $editUser['Course'] === 'BSIT') ? 'selected' : '' ?>>BSIT</option>
                            <option value="BSCS" <?= ($editUser && $editUser['Course'] === 'BSCS') ? 'selected' : '' ?>>BSCS</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control custom-input" value="<?= $editUser ? htmlspecialchars($editUser['Email']) : '' ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone_number" class="form-control custom-input" value="<?= $editUser ? htmlspecialchars($editUser['PhoneNumber']) : '' ?>" required>
                    </div>

                    <div class="text-center">
                        <?php if ($editUser): ?>
                            <a href="index.php" class="btn btn-secondary rounded-pill px-4 me-2">Cancel</a>
                            <button type="submit" name="update" class="btn btn-submit">Update</button>
                        <?php else: ?>
                            <button type="submit" name="submit" class="btn btn-submit">Submit</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="col-md-8 ps-md-4 mt-5 mt-md-0">
                <h4 class="text-center mb-4 text-secondary fw-bold">Information</h4>
                <div class="table-responsive">
                    <table class="table table-borderless table-custom table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Gender</th>
                                <th>Email</th>
                                <th>Course</th>
                                <th>Phone Number</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="8" class="text-center">No data available.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['ID'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($user['FirstName'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($user['LastName'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($user['Gender'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($user['Email'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($user['Course'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($user['PhoneNumber'] ?? '') ?></td>
                                        <td>
                                            <a href="index.php?edit=<?= $user['ID'] ?>" class="action-btn btn-edit text-decoration-none">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="index.php?delete=<?= $user['ID'] ?>" onclick="return confirm('Are you sure you want to delete this record?');" class="action-btn btn-delete text-decoration-none">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</body>

</html>