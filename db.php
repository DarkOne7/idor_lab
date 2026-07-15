<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "idor_lab");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}