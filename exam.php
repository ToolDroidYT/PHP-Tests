<?php

$db_config = [
    'host' => '127.0.0.1',
    'user' => 'root',
    'pass' => '',
    'db' => 'CampusExchangeDB'
];

// Suppress errors for clean UI if DB fails, or handle properly in production.
$conn = @new mysqli($db_config['host'], $db_config['user'], $db_config['pass'], $db_config['db']);

$users = [];
if (!$conn->connect_error) {
    // Assuming table columns match the required output based on the image
    $query = "SELECT ID, FirstName, LastName, Gender, Email, Course, PhoneNumber FROM Users";
    $result = $conn->query($query);
    if ($result) {
        $users = $result->fetch_all(MYSQLI_ASSOC);
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .custom-input {
            background-color: #eef2f7;
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
        }
        .form-label {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 0.2rem;
        }
        .btn-submit {
            border: 1px solid #ccc;
            border-radius: 20px;
            padding: 8px 40px;
            background: transparent;
            font-weight: 500;
        }
        .btn-submit:hover {
            background-color: #e2e6ea;
        }
        .table-custom th {
            border-bottom: 2px solid #dee2e6;
            color: #555;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .table-custom td {
            vertical-align: middle;
            font-size: 0.9rem;
            color: #444;
        }
        .action-btn {
            border: none;
            border-radius: 4px;
            padding: 4px 8px;
            color: white;
            font-size: 0.85rem;
        }
        .btn-edit { background-color: #f1c40f; }
        .btn-delete { background-color: #e74c3c; }
    </style>
</head>

<body>

    <div class="container-fluid p-5">
        <div class="row">
            
            <div class="col-md-4 pe-md-5">
                <h4 class="text-center mb-4 text-secondary fw-bold">Add Information</h4>
                <form action="index.php" method="POST">
                    
                    <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control custom-input" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control custom-input" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block">Gender</label>
                        <div class="form-check form-check-inline mt-1">
                            <input class="form-check-input" type="radio" name="gender" id="male" value="Male" required>
                            <label class="form-check-label text-secondary" for="male">Male</label>
                        </div>
                        <div class="form-check form-check-inline mt-1 ms-4">
                            <input class="form-check-input" type="radio" name="gender" id="female" value="Female">
                            <label class="form-check-label text-secondary" for="female">Female</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Course</label>
                        <select name="course" class="form-select custom-input" required>
                            <option value="" disabled selected></option>
                            <option value="BSIT">BSIT</option>
                            <option value="BSCS">BSCS</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control custom-input" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone_number" class="form-control custom-input" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-submit">Submit</button>
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
                                    <td colspan="8" class="text-center">No data available or database connection failed.</td>
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
                                            <a href="edit.php?id=<?= $user['ID'] ?>" class="action-btn btn-edit text-decoration-none">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="delete.php?id=<?= $user['ID'] ?>" class="action-btn btn-delete text-decoration-none">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>