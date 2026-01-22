<?php
session_start();
require "db.php";

// Protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: login_page.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $conn->prepare("SELECT name, employee_id, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">

<head>
    <meta charset="utf-8" />
    <title>User Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="assets/css/style.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <script src="assets/js/config.js"></script>
</head>

<body>
<div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex align-items-center min-vh-100">
                    <div class="w-100 card shadow-lg rounded my-5 overflow-hidden">
                        <div class="row">

                            <!-- Left UI panel -->
                            <div class="col-lg-5 d-none d-lg-block bg-register rounded-left"></div>

                            <!-- Right Content -->
                            <div class="col-lg-7">
                                <div class="p-5">

                                    <h1 class="h5 mb-1">My Profile</h1>
                                    <p class="text-muted mb-4">View your account details</p>

                                    <div class="form-group mb-3">
                                        <label class="form-label">Name</label>
                                        <input class="form-control" type="text"
                                               value="<?= htmlspecialchars($user['name']) ?>" readonly>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="form-label">Employee ID</label>
                                        <input class="form-control" type="text"
                                               value="<?= htmlspecialchars($user['employee_id']) ?>" readonly>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="form-label">Email Address</label>
                                        <input class="form-control" type="email"
                                               value="<?= htmlspecialchars($user['email']) ?>" readonly>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="form-label">Role</label>
                                        <input class="form-control" type="text"
                                               value="<?= htmlspecialchars(ucfirst($user['role'])) ?>" readonly>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <a href="reset-password.php" class="btn btn-outline-primary">
                                            Change Password
                                        </a>
                                        <a href="dashboard.php" class="btn btn-primary">
                                            Back to Dashboard
                                        </a>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/vendor.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
