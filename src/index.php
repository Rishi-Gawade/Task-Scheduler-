<?php
require 'functions.php';

// Handle adding a task
if (isset($_POST['add'])) {
    $task_name = $_POST['task-name'];
    $added = addTask($task_name);
    if (!$added) {
        $error = "Task already exists or is invalid.";
    }
}

// Handle toggle completion
if (isset($_POST['toggle'])) {
    $id = $_POST['toggle'];
    $tasks = getAllTasks();
    foreach ($tasks as $task) {
        if ($task['id'] == $id) {
            $new_status = $task['completed'] ? false : true;
            markTaskAsCompleted($id, $new_status);
        }
    }
}

// Handle delete
if (isset($_POST['delete'])) {
    deleteTask($_POST['delete']);
}

$tasks = getAllTasks();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Task Manager</title>
    <style>
       body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #e3f2fd;
    margin: 0;
    padding: 0;
}

nav {
    background-color: #0d47a1;
    padding: 15px 0;
    text-align: center;
}

nav a {
    color: #ffffff;
    text-decoration: none;
    margin: 0 20px;
    font-size: 18px;
    font-weight: bold;
}

nav a:hover {
    color: #90caf9;
}

.container {
    max-width: 650px;
    margin: 40px auto;
    background: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

h1, h2 {
    color: #0d47a1;
    text-align: center;
    margin-bottom: 25px;
}

.section {
    margin-bottom: 40px;
}

form {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-bottom: 20px;
}

input[type="text"], input[type="email"] {
    padding: 10px;
    width: 70%;
    border: 2px solid #90caf9;
    border-radius: 8px;
    font-size: 16px;
}

input:focus {
    border-color: #1e88e5;
    outline: none;
}

button {
    padding: 10px 18px;
    background-color: #1e88e5;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
}

button:hover {
    background-color: #1565c0;
}

.task {
    background: #f5faff;
    padding: 15px 20px;
    border-left: 5px solid #2196f3;
    margin-bottom: 15px;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.task:hover {
    background: #e1f5fe;
}

.task-name {
    font-size: 18px;
    color: #0d47a1;
}

.task-name.completed {
    text-decoration: line-through;
    color: #90a4ae;
}

.actions {
    display: flex;
    gap: 10px;
}

.btn {
    padding: 6px 12px;
    font-size: 14px;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
}

.btn.toggle {
    background-color: #42a5f5;
    color: white;
}

.btn.toggle:hover {
    background-color: #1e88e5;
}

.btn.delete {
    background-color: #ef5350;
    color: white;
}

.btn.delete:hover {
    background-color: #d32f2f;
}

.error {
    color: red;
    text-align: center;
}

.subscribe-msg {
    text-align: center;
    color: green;
}

.subscribe-box {
    text-align: center;
}

    </style>
</head>
<body>

<!-- Navigation -->
<nav>
    <a href="index.php">🏠 Home</a>
    <a href="about.php">📖 About</a>
    <a href="contact.php">📞 Contact</a>
</nav>

<!-- Subscribe Section -->
<div class="section subscribe-box">
    <h2>📩 Subscribe for Task Reminders</h2>
    <form method="POST">
        <input type="email" name="email" required />
        <button type="submit" id="submit-email" name="subscribe">Submit</button>
    </form>
    <?php
    if (isset($_POST['subscribe'])) {
        $email = $_POST['email'];
        $msg = subscribeEmail($email);
        echo "<p class='subscribe-msg'>$msg</p>";
    }
    ?>
</div>

<!-- Main Container -->
<div class="container">

    <h1>📝 Task Manager</h1>

    <!-- Add Task Section -->
    <div class="section">
        <h2>📌 Add a Task</h2>
        <form method="POST">
            <input type="text" name="task-name" id="task-name" placeholder="Enter new task" required autofocus />
            <button type="submit" id="add-task" name="add">Add Task</button>
        </form>
        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
    </div>

    <!-- Task List -->
    <?php if (!empty($tasks)): ?>
        <div class="section">
            <h2>✅ Task List</h2>
            <ul class="task-list">
                <?php foreach ($tasks as $task): ?>
                    <li class="task-item<?= $task['completed'] ? ' completed' : '' ?>">
                        <form method="POST" style="display: flex; justify-content: space-between; align-items: center;">
                            <input type="checkbox" class="task-status" name="toggle" value="<?= $task['id'] ?>" onchange="this.form.submit()" <?= $task['completed'] ? 'checked' : '' ?>>
                            <span class="task-name"><?= htmlspecialchars($task['name']) ?></span>
                            <button class="delete-task" name="delete" value="<?= $task['id'] ?>">Delete</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
