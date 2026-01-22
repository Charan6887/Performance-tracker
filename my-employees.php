<?php
include 'php-file.php';

/* ===============================
   LOGIN + ROLE CHECK
================================ */
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'manager') {
    header("Location: login_page.php");
    exit;
}

/* ===============================
   GET MANAGER NAME FROM SESSION
================================ */
if (!isset($_SESSION['name'])) {
    die("Manager name not found in session.");
}

$manager_name = $_SESSION['name'];

/* ===============================
   FETCH EMPLOYEES UNDER MANAGER NAME
   (USING SELF JOIN)
================================ */
$stmt = $conn->prepare("
    SELECT e.id, e.name, e.employee_id, e.email, e.created_at
    FROM users e
    INNER JOIN users m ON e.manager_id = m.id
    WHERE e.role = 'employee'
      AND m.name = ?
    ORDER BY e.name ASC
");
$stmt->bind_param("s", $manager_name);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <?php include 'include/head.php'; ?>
</head>

<body>

<div class="layout-wrapper">

    <?php include 'include/left-sidebar.php'; ?>

    <div class="page-content">

        <?php include 'include/top-bar.php'; ?>

        <div class="px-3">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">

                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">My Employees</h4>
                            </div>

                            <div class="card-body">

                                <?php if ($result->num_rows > 0): ?>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Employee Name</th>
                                                    <th>Employee ID</th>
                                                    <th>Email</th>
                                                    <th>Joined On</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php while ($row = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                                        <td><?= htmlspecialchars($row['employee_id']) ?></td>
                                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                                                    </tr>
                                                <?php endwhile; ?>

                                            </tbody>
                                        </table>
                                    </div>

                                <?php else: ?>

                                    <div class="alert alert-info">
                                        No employees registered under you yet.
                                    </div>

                                <?php endif; ?>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <?php include 'include/footer.php'; ?>

    </div>
</div>

<script src="assets/js/vendor.min.js"></script>
<script src="assets/js/app.js"></script>

</body>
</html>
