<?php
require './db.php';

$students = [];
$editStudent = null;
$allowedGender = ['Male', 'Female'];
$allowedCourse = ['BSIT', 'BSCS'];

if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $editID = (int) $_GET['edit'];
    $editSQL = $conn->prepare('SELECT * FROM Students WHERE ID = ?');

    if ($editSQL) {
        $editSQL->bind_param('i', $editID);
        $editSQL->execute();
        $result = $editSQL->get_result();
        $editStudent = $result->fetch_assoc();
        $editSQL->close();
    }
}

$listSQL = $conn->query('SELECT ID, FirstName, LastName, Gender, Course, Email, PhoneNumber FROM Students ORDER BY ID DESC');

if ($listSQL) {
    $students = $listSQL->fetch_all(MYSQLI_ASSOC);
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
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-5">
        <div class="row">

            <div class="col-md-4 pe-md-5">
                <h4 class="text-center mb-4 text-secondary fw-bold">
                    <?= $editStudent ? 'Edit Information' : 'Add Information' ?>
                </h4>

                <form action="<?= $editStudent ? './update.php' : './insert.php' ?>" method="POST">
                    <?php if ($editStudent): ?>
                        <input type="hidden" name="id" value="<?= htmlspecialchars($editStudent['ID']) ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control"
                            value="<?= $editStudent ? htmlspecialchars($editStudent['FirstName']) : '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control"
                            value="<?= $editStudent ? htmlspecialchars($editStudent['LastName']) : '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block">Gender</label>
                        <div class="form-check form-check-inline mt-1">
                            <input class="form-check-input" type="radio" name="gender" id="male" value="Male"
                                <?= ($editStudent && $editStudent['Gender'] === 'Male') ? 'checked' : (empty($editStudent) ? 'required' : '') ?>>
                            <label class="form-check-label text-secondary" for="male">Male</label>
                        </div>
                        <div class="form-check form-check-inline mt-1 ms-4">
                            <input class="form-check-input" type="radio" name="gender" id="female" value="Female"
                                <?= ($editStudent && $editStudent['Gender'] === 'Female') ? 'checked' : '' ?>>
                            <label class="form-check-label text-secondary" for="female">Female</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Course</label>
                        <select name="course" class="form-select" required>
                            <option value="" <?= !$editStudent ? 'selected disabled' : '' ?>></option>
                            <?php foreach ($allowedCourse as $courseOption): ?>
                                <option value="<?= $courseOption ?>"
                                    <?= ($editStudent && $editStudent['Course'] === $courseOption) ? 'selected' : '' ?>>
                                    <?= $courseOption ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                            value="<?= $editStudent ? htmlspecialchars($editStudent['Email']) : '' ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone_number" class="form-control"
                            value="<?= $editStudent ? htmlspecialchars($editStudent['PhoneNumber']) : '' ?>" required>
                    </div>

                    <div class="text-center">
                        <?php if ($editStudent): ?>
                            <a href="./index.php" class="btn btn-secondary rounded-pill px-4 me-2">Cancel</a>
                            <button type="submit" name="update" class="btn btn-primary">Update</button>
                        <?php else: ?>
                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="col-md-8 ps-md-4 mt-5 mt-md-0">
                <h4 class="text-center mb-4 text-secondary fw-bold">Information</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
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
                            <?php if (empty($students)): ?>
                                <tr>
                                    <td colspan="8" class="text-center">No data available.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($student['ID'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($student['FirstName'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($student['LastName'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($student['Gender'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($student['Email'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($student['Course'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($student['PhoneNumber'] ?? '') ?></td>
                                        <td>
                                            <a href="./index.php?edit=<?= $student['ID'] ?>" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="./delete.php?id=<?= $student['ID'] ?>"
                                                onclick="return confirm('Are you sure you want to delete this record?');"
                                                class="btn btn-danger btn-sm">
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
