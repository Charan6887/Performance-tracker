<?php
session_start();
require "db.php";


$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["emailaddress"];
    $password = $_POST["password"];

    // SECURE QUERY
    $sql = "SELECT * FROM users WHERE email=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $row = $result->fetch_assoc();

        if (password_verify($password, $row["password"])) {

            // 🔐 Prevent session hijacking
            session_regenerate_id(true);

            // Common session values
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["user_name"] = $row["name"];
            $_SESSION["email"] = $row["email"];
            $_SESSION["role"] = strtolower($row["role"]); // ✅ normalize role
            $_SESSION["last_activity"] = time();
            $_SESSION['employee_id'] = $row['employee_id'];
            $_SESSION['name']        = $row['name'];

          


            

            /* ---------------- ROLE BASED REDIRECT ---------------- */
            if ($_SESSION["role"] === "manager") {

                header("Location: manager.php");
                exit();

            } elseif ($_SESSION["role"] === "employee") {

                header("Location: index.php");
                exit();

            } elseif ($_SESSION["role"] === "hr") {

                header("Location: hr-dashboard.php");
                exit();

            } else {
                // ❌ Unknown / unauthorized role
                session_unset();
                session_destroy();
                $error = "Access denied";
            }

        } else {
            $error = "Incorrect Email or Password!";
        }

    } else {
        $error = "Incorrect Email or Password!";
    }
}
?>



<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">

<head>
    <meta charset="utf-8" />
    <title>Log In | Drezoc - Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="assets/images/favicon.ico">
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
                        <div class="w-100 d-block card shadow-lg rounded my-5 overflow-hidden">
                            <div class="row">

                                <div class="col-lg-5 d-none d-lg-block bg-login rounded-left"></div>

                                <div class="col-lg-7">
                                    <div class="p-5">

                                        <div class="text-center w-75 mx-auto auth-logo mb-4">
                                            <a href="login_page.php" class="logo-dark">
                                                <span><img src="assets/images/logo-dark.png" height="32"></span>
                                            </a>
                                        </div>
                                        

                                        <!-- ⭐ Greeting Text -->
                                        <h4 id="greeting" class="text-primary mb-1"></h4>

                                        
                                        <p class="text-muted mb-4">Login to your Account</p>

                                        <?php if ($error != ""): ?>
                                            <div class="alert alert-danger text-center"><?= $error ?></div>
                                        <?php endif; ?>

                                        <form method="POST" action="">
                                            <div class="form-group mb-3">
                                                <label>Email Address</label><br>
                                                <input class="form-control" type="email" name="emailaddress" required>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label>Password</label>
                                                <input class="form-control" type="password" name="password" required>
                                            </div>

                                            <button class="btn btn-primary w-100" type="submit">Log In</button>
                                        </form>

                                        <div class="row mt-4 text-center">
                                            <!--<p class="text-muted mb-2"><a href="recover_pw.php" class="text-muted">Forgot password?</a></p>
                                            <p class="text-muted">Don't have an account? <a href="regitration-page.php"><b>Sign Up</b></a></p>-->
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

    <!-- ⭐ Greeting Script -->
    <script>
        function getGreeting() {
            const hour = new Date().getHours();

            if (hour >= 5 && hour < 12) return "🌅 Good Morning";
            if (hour >= 12 && hour < 17) return "☀️ Good Afternoon";
            if (hour >= 17 && hour < 21) return "🌇 Good Evening";
            return "🌙 Good Night";
        }

        document.getElementById("greeting").textContent = getGreeting();
    </script>

</body>
</html>
