<?php
require './db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $deleteID = (int) $_GET['id'];
    $deleteSQL = $conn->prepare('DELETE FROM Students WHERE ID = ?');

    if ($deleteSQL) {
        $deleteSQL->bind_param('i', $deleteID);
        $deleteSQL->execute();
        $deleteSQL->close();
    }
}

$conn->close();
header('Location: ./index.php');
exit;
