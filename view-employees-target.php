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
   UPDATE TARGET (SECURE)
================================ */
$update_success = false;

if (isset($_POST['update_target'], $_POST['target_id'])) {

    $target_id = (int)$_POST['target_id'];
    $remarks   = trim($_POST['remarks']);
    $status    = $_POST['status'];

    $completion_date = ($status === 'Completed') ? date('Y-m-d') : NULL;

    $stmt = $conn->prepare("
        UPDATE employee_targets et
        INNER JOIN users e ON et.employee_id = e.employee_id
        INNER JOIN users m ON e.manager_id = m.id
        SET et.completion_date = ?, et.remarks = ?
        WHERE et.id = ?
          AND m.name = ?
    ");

    $stmt->bind_param("ssis", $completion_date, $remarks, $target_id, $manager_name);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $update_success = true;
    }
}

/* ===============================
   FETCH TARGETS (MANAGER ONLY)
================================ */
$stmt = $conn->prepare("
    SELECT 
        et.id,
        et.employee_name,
        et.employee_id,
        et.target_date,
        et.completion_date,
        et.details,
        (
            SELECT GROUP_CONCAT(
                CONCAT(
                    DATE_FORMAT(pl.created_at, '%d-%b-%Y %H:%i'),
                    ' → ',
                    pl.progress, '% (',
                    pl.booked_sqft, ' sqft): ',
                    pl.remarks
                )
                ORDER BY pl.created_at ASC
                SEPARATOR '<br>'
            )
            FROM employee_progress_logs pl
            WHERE pl.target_id = et.id
        ) AS progress_history
    FROM employee_targets et
    INNER JOIN users e ON et.employee_id = e.employee_id
    INNER JOIN users m ON e.manager_id = m.id
    WHERE m.name = ?
");


$stmt->bind_param("s", $manager_name);
$stmt->execute();
$result = $stmt->get_result();

/* ===============================
   GROUP BY EMPLOYEE
================================ */
$employees = [];
while ($row = $result->fetch_assoc()) {
    $employees[$row['employee_name']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
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

<?php if ($update_success): ?>
<div class="alert alert-success alert-dismissible fade show">
    Target updated successfully!
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card">
<div class="card-body">
<h4 class="header-title">Employee Targets</h4>
</div>

<div class="card-body">

<?php if (empty($employees)): ?>
    <p class="text-muted">No targets assigned under you yet.</p>
<?php else: ?>

<?php foreach ($employees as $employee_name => $targets): ?>

<h5 class="text-primary">
    <?= htmlspecialchars($employee_name) ?>
    <small class="text-muted">
        (ID: <?= htmlspecialchars($targets[0]['employee_id']) ?>)
    </small>
</h5>

<div class="table-responsive">
<table class="table table-bordered table-striped">
<thead>
<tr>
    <th>#</th>
    <th>Target Date</th>
    <th>Completion</th>
    <th>Description</th>
    <th>Action</th>
</tr>
</thead>

<tbody>
<?php foreach ($targets as $i => $target): ?>
<tr>
<td><?= $i + 1 ?></td>
<td><?= htmlspecialchars($target['target_date']) ?></td>
<td>
<?= $target['completion_date']
    ? '<span class="text-success">' . htmlspecialchars($target['completion_date']) . '</span>'
    : '<span class="text-warning">Pending</span>' ?>
</td>
<td><?= nl2br(htmlspecialchars($target['details'])) ?></td>

<td>
    <div>
        <strong>Status:</strong>
        <?= $target['completion_date']
            ? '<span class="text-success">Completed</span>'
            : '<span class="text-warning">Pending</span>' ?>
    </div>

    <div class="mt-1">
        <strong>Progress History:</strong><br>
        <?= $target['progress_history']
            ? $target['progress_history']
            : '<span class="text-muted">No updates yet</span>' ?>
    </div>


    <div class="mt-2">
        <a href="edit-employee-target.php?id=<?= $target['id'] ?>"
           class="btn btn-sm btn-success">
           Edit
        </a>
    </div>
</td>

</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<hr>

<?php endforeach; ?>
<?php endif; ?>

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
