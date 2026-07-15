<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
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

<div class="dashboard">

    <h1>Welcome back, <?= htmlspecialchars($_SESSION['name']) ?> 👋</h1>
    <p>Your role: <strong><?= htmlspecialchars($_SESSION['role']) ?></strong></p>

    <div class="cards">

        <div class="card">
            <h2>My Profile</h2>

            <p><strong>Name:</strong> <?= htmlspecialchars($_SESSION['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['email']) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars($_SESSION['role']) ?></p>
            <p><strong>User ID:</strong> <?= htmlspecialchars($_SESSION['user_id']) ?></p>
        </div>

        <div class="card">
            <h2>Quick Actions</h2>

            <button>View Jobs</button>
            <button>Edit Profile</button>
            <button>Change Password</button>
        </div>

    </div>

</div>

</body>
</html>