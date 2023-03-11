<html>
<link rel="stylesheet" type="text/css" href="css/edit-style.css" />
<?php
require 'config/config.php';
require 'config/db.php';

$query = 'SET @num := 0';
mysqli_query($conn, $query);
$query = 'UPDATE tasks SET id = @num := (@num+1)';
mysqli_query($conn, $query);
$query = 'ALTER TABLE tasks AUTO_INCREMENT = 1';
mysqli_query($conn, $query);

// Check for submit
if (isset($_POST['submit'])) {
    // Get form data
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $task_name = mysqli_real_escape_string($conn, $_POST['task_name']);
    $task_description = mysqli_real_escape_string(
        $conn,
        $_POST['task_description']
    );
    $task_due_date = mysqli_real_escape_string($conn, $_POST['task_due_date']);
    $task_status = mysqli_real_escape_string($conn, $_POST['task_status']);

    // Update task in database
    $sql = "UPDATE tasks SET task_name='$task_name', task_description='$task_description', task_due_date='$task_due_date', task_status='$task_status' WHERE id = {$id}";

    if (mysqli_query($conn, $sql)) {
        // Success
        header('Location: view-task.php');
    } else {
        // Error
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Get task information
$id = mysqli_real_escape_string($conn, $_POST['id']);
$sql = "SELECT * FROM tasks WHERE id = {$id}";
$result = mysqli_query($conn, $sql);
$task = mysqli_fetch_assoc($result);

mysqli_free_result($result);
mysqli_close($conn);
?>

<body>
    <div class="container">
        <h1>Edit Task</h1>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
            <div class="form-group">
                <label for="task_name">Name</label>
                <input type="text" name="task_name" class="form-control" value="<?php echo $task[
                    'task_name'
                ]; ?>">
            </div>
            <div class="form-group">
                <label for="task_description">Description</label>
                <textarea name="task_description" class="form-control"><?php echo $task[
                    'task_description'
                ]; ?></textarea>
            </div>
            <div class="form-group">
                <label for="task_due_date">Due Date</label>
                <input type="date" name="task_due_date" class="form-controls" value="<?php echo $task[
                    'task_due_date'
                ]; ?>">
            </div>
            <div class="form-group">
                <label for="task_status">Status</label>
                <select name="task_status" class="form-controls">
                    <option value="incomplete" <?php if (
                        $task['task_status'] == 'incomplete'
                    ) {
                        echo 'selected';
                    } ?>>Incomplete
                    </option>
                    <option value="in progress" <?php if (
                        $task['task_status'] == 'in progress'
                    ) {
                        echo 'selected';
                    } ?>>In
                        Progress</option>
                    <option value="complete" <?php if (
                        $task['task_status'] == 'complete'
                    ) {
                        echo 'selected';
                    } ?>>Complete
                    </option>
                </select>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>

</html>