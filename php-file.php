<?php
session_start();
require "db.php";

// Check login
if (!isset($_SESSION['email'])) {
    header("Location: login_page.php");
    exit();
}

$employee_id = $_SESSION['email'];                


// Fetch all reports of this user
$sql = "SELECT * FROM reports WHERE employee_id = ? ORDER BY report_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
?>