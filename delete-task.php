<?php
require 'config/config.php';
require 'config/db.php';

$query = 'SET @num := 0';
mysqli_query($conn, $query);
$query = 'UPDATE tasks SET id = @num := (@num+1)';
mysqli_query($conn, $query);
$query = 'ALTER TABLE tasks AUTO_INCREMENT = 1';
mysqli_query($conn, $query);

if (isset($_POST['id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $sql = "DELETE FROM tasks WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header('Location: view-task.php');
        exit();
    } else {
        echo 'Error deleting task: ' . mysqli_error($conn);
    }
}
?>
