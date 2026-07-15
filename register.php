<?php
session_start();
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'employee';
    mysqli_Stmt_bind_param($tmt, "ssss", $_POST['name'], $_POST['email'], $hashed_password, $role);
    mysqli_stmt_execute($tmt);
    if (mysqli_stmt_affected_rows($tmt) > 0) {
        $_SESSION['user_id'] = mysqli_insert_id($conn);
        $_SESSION['user_name'] = $_POST['name'];
        $_SESSION['user_role'] = 'employee';
        header('Location: index.php');
        exit();
    } else {
        $error = "Registration failed. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card">
        <h1>Register</h1>
        <form method="POST">
            <input name="name" type="text" placeholder="Name" required>
            <input name="email" type="email" placeholder="Email" required>
            <input name="password" type="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>