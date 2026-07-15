<?php
session_start();
$_SESSION['id'];
require 'db.php';

// at first i thought about using $_GET['id'] but then i realized that it would be too 
// easy to exploit so i decided to use $_SESSION['id'] instead

$id = $_SESSION['id'] ?? null;
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user || $user['role'] !== 'employer') {
    die("Access denied");
}

// vulnerability: the server does NOT check if the user has permission to add or delete other accouts
// this allows an attacker to delete any employee account by sending a POST request with the employee id

if (isset($_POST['add'])) {
    $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'employee')");
    mysqli_stmt_bind_param($stmt, "sss", $_POST['name'], $_POST['email'], $hash);
    mysqli_stmt_execute($stmt);
}

if (isset($_POST['delete'])) {
    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ? AND role = 'employee'");
    mysqli_stmt_bind_param($stmt, "i", $_POST['delete']);
    mysqli_stmt_execute($stmt);
}

$employees = mysqli_query($conn, "SELECT * FROM users WHERE role = 'employee'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar">
    <h2>Job Portal</h2>

    <div class="nav-right">
        <span><?= htmlspecialchars($_SESSION['name']) ?></span>
        <a href="logout.php">Logout</a>
    </div>
    </nav>
    <div class="container">
        <div class="card">
            <h1>Welcome, <?= htmlspecialchars($user['name']) ?></h1>

            <h2>Add Employee</h2>
            <form method="POST">
                <input name="name" placeholder="Name" required>
                <input name="email" placeholder="Email" required>
                <input name="password" placeholder="Password" required>
                <button name="add">Add Employee</button>
            </form>

            <h2>Employees</h2>
            <?php while ($e = mysqli_fetch_assoc($employees)): ?>
                <div class="employee-row">
                    <span><?= htmlspecialchars($e['name']) ?> — <?= htmlspecialchars($e['email']) ?></span>
                    <form method="POST">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                        <button name="delete" value="<?= $e['id'] ?>">Delete</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>