<head>
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>

<?php
require 'config/config.php';
require 'config/db.php';

$query = 'SET @num := 0';
mysqli_query($conn, $query);
$query = 'UPDATE tasks SET id = @num := (@num+1)';
mysqli_query($conn, $query);
$query = 'ALTER TABLE tasks AUTO_INCREMENT = 1';
mysqli_query($conn, $query);

// Handle delete request
if (isset($_POST['delete'])) {
    $id = mysqli_real_escape_string($conn, $_POST['delete_id']);
    $sql = "DELETE FROM tasks WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Task deleted successfully</div>";
    } else {
        echo "<div class='alert alert-danger'>Error deleting task: " .
            mysqli_error($conn) .
            '</div>';
    }
}

// Handle edit form submission
if (isset($_POST['edit'])) {
    $id = mysqli_real_escape_string($conn, $_POST['edit_id']);
    $task_name = mysqli_real_escape_string($conn, $_POST['task_name']);
    $task_description = mysqli_real_escape_string(
        $conn,
        $_POST['task_description']
    );
    $task_due_date = mysqli_real_escape_string($conn, $_POST['task_due_date']);
    $task_status = mysqli_real_escape_string($conn, $_POST['task_status']);
    $sql = "UPDATE tasks SET task_name='$task_name', task_description='$task_description', task_due_date='$task_due_date', task_status='$task_status' WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Task updated successfully</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating task: " .
            mysqli_error($conn) .
            '</div>';
    }
}

// Handle filter request
$status = '';
if (isset($_GET['status'])) {
    $status = mysqli_real_escape_string($conn, $_GET['status']);
}

// Construct SQL query with optional WHERE clause
$sql =
    'SELECT id, task_name, task_description, task_due_date, task_status FROM tasks';
if (!empty($status)) {
    $sql .= " WHERE task_status = '$status'";
}
$result = mysqli_query($conn, $sql);
?>

<div class="container">
    <form action="view-task.php" method="GET" class="form-inline mb-3">
        <div class="form-group mr-2">
            <label for="status">Filter Task Status:</label>
            <select class="form-control ml-2" id="status" name="status">
                <option value="">All</option>
                <option value="incomplete" <?php if ($status == 'incomplete') {
                    echo 'selected';
                } ?>>Incomplete</option>
                <option value="in progress" <?php if (
                    $status == 'in progress'
                ) {
                    echo 'selected';
                } ?>>In Progress</option>
                <option value="complete" <?php if ($status == 'complete') {
                    echo 'selected';
                } ?>>Complete</option>
            </select>
            <button type="submit" class="btns">FILTER</button>
            <a href="add-task.php"><button type="button" class="button">ADD</button></a>
        </div>
    </form>
</div>


<table class="table">
    <thead class="table-header">
        <tr>
            <th scope="col">id</th>
            <th scope="col">task_name</th>
            <th scope="col">task_description</th>
            <th scope="col">task_due_date</th>
            <th scope="col">task_status</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($task = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $task['id']; ?></td>
            <td><?php echo $task['task_name']; ?></td>
            <td><?php echo $task['task_description']; ?></td>
            <td><?php echo $task['task_due_date']; ?></td>
            <td><?php echo $task['task_status']; ?></td>
            <td>
                <form method="post" action="edit-task.php" class="inline">
                    <input type="hidden" name="id" value="<?php echo $task[
                        'id'
                    ]; ?>">
                    <button type="submit" class="btn btn-primary">Edit</button>
                </form>
                <form method="post" action="delete-task.php" class="inline"
                    onsubmit="return confirm('Are you certain that you want to remove this task?')">
                    <input type="hidden" name="id" value="<?php echo $task[
                        'id'
                    ]; ?>">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>