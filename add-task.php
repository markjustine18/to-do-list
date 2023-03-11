<html>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<?php
require 'config/config.php';
require 'config/db.php';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $duedate = mysqli_real_escape_string($conn, $_POST['duedate']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $query = "INSERT INTO tasks(task_name, task_description, task_due_date, task_status) VALUES('$name', '$description', '$duedate','$status')";
    echo $query;
    if (mysqli_query($conn, $query)) {
        header('Location: ' . 'view-task.php');
    } else {
        echo 'ERROR: ' . mysqli_error($conn);
    }
}
?>

<body>
    <div class="container">
        <br />
        <h2>Add New Task</h2>

        <form method="POST" action="<?php $_SERVER[
            'PHP_SELF'
        ]; ?>" class="container">
            <label>Name</label>
            <input type="text" placeholder="Name" name="name" required />

            <label>Description</label>
            <input type="text" placeholder="Description" name="description" required />

            <label>Due Date</label>
            <input type="date" name="duedate" required />

            <label>Status</label>
            <select type="text" name="status" required>
                <option value="select">Select .... </option>
                <option value="incomplete">Incomplete</option>
                <option value="in progress">In Progress</option>
                <option value="complete">Complete</option>
            </select>

            <button type="submit" value="Submit" name="submit">Save</button>


    </div>
    </form>
    </div>
</body>

</html>