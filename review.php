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
if (empty($_SESSION['name'])) {
    die("Manager name not found in session.");
}

$manager_name = trim($_SESSION['name']);

/* ===============================
   GET MANAGER ID USING NAME
================================ */
$stmt = $conn->prepare("
    SELECT id 
    FROM users 
    WHERE name = ? AND role = 'manager'
    LIMIT 1
");
$stmt->bind_param("s", $manager_name);
$stmt->execute();
$mgrResult = $stmt->get_result();

if ($mgrResult->num_rows === 0) {
    die("Manager record not found.");
}

$manager = $mgrResult->fetch_assoc();
$manager_id = $manager['id'];

/* ===============================
   FETCH EMPLOYEE SUBMISSIONS
   (ONLY EMPLOYEES UNDER THIS MANAGER)
================================ */
$stmt = $conn->prepare("
    SELECT 
        et.id,
        u.name AS employee_name,
        u.employee_id,
        et.target_date,
        et.completion_date,
        et.details,
        et.remarks
    FROM employee_targets et
    INNER JOIN users u ON et.employee_id = u.employee_id
    WHERE u.manager_id = ?
    ORDER BY u.name ASC, et.target_date ASC
");
$stmt->bind_param("i", $manager_id);
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
                                <h4 class="header-title">Review Employee Submissions</h4>
                            </div>

                            <div class="card-body">

                                <?php if ($result->num_rows > 0): ?>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Employee Name</th>
                                                    <th>Employee ID</th>
                                                    <th>Target Date</th>
                                                    <th>Completion Date</th>
                                                    <th>Details</th>
                                                    <th>Remarks</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php while ($row = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($row['employee_name']) ?></td>
                                                        <td><?= htmlspecialchars($row['employee_id']) ?></td>
                                                        <td><?= htmlspecialchars($row['target_date']) ?></td>

                                                        <td>
                                                            <?= $row['completion_date']
                                                                ? htmlspecialchars($row['completion_date'])
                                                                : '<span class="text-warning">Pending</span>' ?>
                                                        </td>

                                                        <td><?= nl2br(htmlspecialchars($row['details'])) ?></td>

                                                        <td>
                                                            <?= !empty($row['remarks'])
                                                                ? htmlspecialchars($row['remarks'])
                                                                : '<span class="text-muted">—</span>' ?>
                                                        </td>

                                                        <td>
                                                            <?php if ($row['completion_date']): ?>
                                                                <span class="badge bg-success">Completed</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary">Yet to Complete</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>

                                            </tbody>
                                        </table>
                                    </div>

                                <?php else: ?>

                                    <div class="alert alert-info">
                                        No submissions found for your employees.
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
