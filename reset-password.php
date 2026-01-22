


<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login_page.php");
    exit();
}
require "db.php";
$message = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $old_password = $_POST["old_password"];
    $new_password = $_POST["new_password"];
    $user_id = $_SESSION['user_id'];

    // Fetch current password
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();


    if (!password_verify($old_password, $row['password'])) {
        $message = "Old password is incorrect!";
    } else {

        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update->bind_param("si", $hashed_password, $user_id);


        if ($update->execute()) {
            $success = "Password reset successfully! You can login now.";
        } else {
            $message = "Something went wrong. Try again!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">

<head>
    <meta charset="utf-8" />
    <title>Reset Password</title>
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

                            <!-- Right Form -->
                            <div class="col-lg-7">
                                <div class="p-5">

                                    <h1 class="h5 mb-1">Reset Password</h1>
                                    <p class="text-muted mb-4">Enter your email and new password</p>

                                    <?php if ($message != ""): ?>
                                        <div class="alert alert-danger text-center"><?= $message ?></div>
                                    <?php endif; ?>

                                    <?php if ($success != ""): ?>
                                        <div class="alert alert-success text-center"><?= $success ?></div>
                                    <?php endif; ?>

                                    <form method="POST" action="">

                                        <div class="form-group mb-3">
                                            <label class="form-label">Old Password</label>
                                            <input class="form-control" type="password" name="old_password" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">New Password</label>
                                            <input class="form-control" type="password" name="new_password" required>
                                        </div>

                                        

                                        <button class="btn btn-primary w-100" type="submit">
                                            Reset Password
                                        </button>

                                    </form>

                                    <div class="text-center mt-4">
                                        <p>
                                            Remembered your password?
                                            <a href="login_page.php"><b>Sign In</b></a>
                                        </p>
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
