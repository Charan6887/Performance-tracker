<?php
require "db.php";
$message = "";

/* ==============================
   FETCH MANAGERS FOR DROPDOWN
================================ */
$managers = [];
$mgrResult = $conn->query("SELECT id, name FROM users WHERE role = 'manager'");
if ($mgrResult) {
    while ($row = $mgrResult->fetch_assoc()) {
        $managers[] = $row;
    }
}
/* ==============================
   FORM SUBMISSION
================================ */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"];
    $employee_id = $_POST["employee_id"];
    $email = $_POST["emailaddress"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $role = $_POST["role"];

    $manager_id = null;
    $manager_name = null;

    // If employee, fetch manager name
    if ($role === 'employee' && !empty($_POST['manager_id'])) {
        $manager_id = (int)$_POST['manager_id'];

        $mgrStmt = $conn->prepare("SELECT name FROM users WHERE id = ? AND role = 'manager'");
        $mgrStmt->bind_param("i", $manager_id);
        $mgrStmt->execute();
        $mgrStmt->bind_result($manager_name);
        $mgrStmt->fetch();
        $mgrStmt->close();
    }

    // Check duplicate email
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $message = "Email already exists!";
    } else {

        $sql = "INSERT INTO users 
                (name, employee_id, email, password, role, manager_id, manager_name)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sisssis",
            $name,
            $employee_id,
            $email,
            $password,
            $role,
            $manager_id,
            $manager_name
        );

        if ($stmt->execute()) {
            header("Location: login_page.php?registered=1");
            exit();
        } else {
            $message = "Registration failed!";
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">

<head>
    <meta charset="utf-8" />
    <title>Register & Signup</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="assets/css/style.min.css" rel="stylesheet">
    <link href="assets/css/icons.min.css" rel="stylesheet">
    <script src="assets/js/config.js"></script>
</head>

<body>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center min-vh-100">
                <div class="w-100 card shadow-lg rounded my-5 overflow-hidden">
                    <div class="row">

                        <div class="col-lg-5 d-none d-lg-block bg-register"></div>

                        <div class="col-lg-7">
                            <div class="p-5">

                                <h1 class="h5 mb-1">Create an Account!</h1>
                                <p class="text-muted mb-4">It takes less than a minute</p>

                                <?php if ($message): ?>
                                    <div class="alert alert-danger text-center">
                                        <?= htmlspecialchars($message) ?>
                                    </div>
                                <?php endif; ?>

                                <form method="POST">

                                    <div class="form-group mb-3">
                                        <label>Name</label>
                                        <input class="form-control" type="text" name="name" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Employee ID</label>
                                        <input class="form-control" type="number" name="employee_id" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Email address</label>
                                        <input class="form-control" type="email" name="emailaddress" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Role</label>
                                        <select class="form-control" name="role" id="roleSelect" required>
                                            <option value="">Select role</option>
                                            <option value="manager">Manager</option>
                                            <option value="employee">Employee</option>
                                        </select>
                                    </div>

                                    <!-- =============================
                                         SELECT MANAGER (HIDDEN INITIALLY)
                                    ============================== -->
                                    <div class="form-group mb-3" id="managerField" style="display:none;">
                                        <label>Select Manager</label>
                                        <select class="form-control" name="manager_id">
                                            <option value="">Select Manager</option>
                                            <?php foreach ($managers as $manager): ?>
                                                <option value="<?= $manager['id'] ?>">
                                                    <?= htmlspecialchars($manager['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Password</label>
                                        <input class="form-control" type="password" name="password" required>
                                    </div>

                                    <button class="btn btn-primary w-100" type="submit">
                                        Sign Up
                                    </button>

                                </form>

                                <div class="text-center mt-4">
                                    <p>Already have an account?
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

<script src="assets/js/vendor.min.js"></script>
<script src="assets/js/app.js"></script>

<!-- =============================
     TOGGLE MANAGER FIELD
============================== -->
<script>
document.getElementById('roleSelect').addEventListener('change', function () {
    const managerField = document.getElementById('managerField');
    managerField.style.display = (this.value === 'employee') ? 'block' : 'none';
});
</script>

</body>
</html>
