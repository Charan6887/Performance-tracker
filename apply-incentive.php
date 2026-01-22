<?php
include 'php-file.php';

/* ===============================
   LOGIN CHECK (EMPLOYEE)
================================ */
if (!isset($_SESSION['employee_id'])) {
    header("Location: login_page.php");
    exit;
}

$employee_id = (int)$_SESSION['employee_id'];

/* ===============================
   UPDATE PROGRESS USING SQFT
================================ */
if (isset($_POST['update_progress'])) {

    $target_id   = (int)$_POST['target_id'];
    $booked_sqft = (int)$_POST['booked_sqft'];
    $remarks     = trim($_POST['remarks']);

    // Fetch target sqft
    $stmt = $conn->prepare("
        SELECT target_sqft
        FROM employee_targets
        WHERE id = ? AND employee_id = ?
    ");
    $stmt->bind_param("ii", $target_id, $employee_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if (!$res) {
        die("Invalid target.");
    }

    $target_sqft = (int)$res['target_sqft'];

    // Calculate progress
    $progress = ($target_sqft > 0)
        ? min(100, round(($booked_sqft / $target_sqft) * 100))
        : 0;

    // Completion date
    $completion_date = ($progress >= 100) ? date('Y-m-d') : NULL;

    // Update target
    // INSERT progress log (append)
$stmt = $conn->prepare("
    INSERT INTO employee_progress_logs
    (target_id, employee_id, booked_sqft, progress, remarks)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param(
    "iiiis",
    $target_id,
    $employee_id,
    $booked_sqft,
    $progress,
    $remarks
);
$stmt->execute();

// Update completion date ONLY if completed
if ($progress >= 100) {
    $stmt = $conn->prepare("
        UPDATE employee_targets
        SET completion_date = ?
        WHERE id = ? AND employee_id = ?
    ");
    $stmt->bind_param("sii", $completion_date, $target_id, $employee_id);
    $stmt->execute();
}


    $_SESSION['success_message'] = "Progress updated successfully.";
    header("Location: " . basename(__FILE__));
    exit;
}

/* ===============================
   FETCH EMPLOYEE TARGETS
================================ */
$stmt = $conn->prepare("
    SELECT 
        et.*,
        (
            SELECT pl.booked_sqft
            FROM employee_progress_logs pl
            WHERE pl.target_id = et.id
            ORDER BY pl.created_at DESC
            LIMIT 1
        ) AS latest_booked_sqft,
        (
            SELECT pl.progress
            FROM employee_progress_logs pl
            WHERE pl.target_id = et.id
            ORDER BY pl.created_at DESC
            LIMIT 1
        ) AS latest_progress
    FROM employee_targets et
    WHERE et.employee_id = ?
    ORDER BY et.target_date ASC
");
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Employee Progress Update</title>
    <?php include 'include/head.php'; ?>
</head>

<body>

<div class="layout-wrapper">
<?php include 'include/left-sidebar.php'; ?>

<div class="page-content">
<?php include 'include/top-bar.php'; ?>

<div class="px-3">
<div class="container-fluid">

<?php if (!empty($_SESSION['success_message'])): ?>
<div class="alert alert-success alert-dismissible fade show">
    <?= htmlspecialchars($_SESSION['success_message']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<div class="card">
<div class="card-body">
<h4 class="header-title">My Target Progress</h4>
</div>

<div class="card-body">

<?php if ($result->num_rows === 0): ?>
    <p class="text-muted">No targets assigned yet.</p>
<?php endif; ?>

<?php while ($row = $result->fetch_assoc()):
    $logStmt = $conn->prepare("
    SELECT booked_sqft, progress, remarks, created_at
    FROM employee_progress_logs
    WHERE target_id = ?
    ORDER BY created_at DESC
");
$logStmt->bind_param("i", $row['id']);
$logStmt->execute();
$logs = $logStmt->get_result();
 ?>

<div class="card mb-3">
<div class="card-body">

<p><strong>Target Date:</strong> <?= htmlspecialchars($row['target_date']) ?></p>

<p><strong>Description:</strong><br>
<?= nl2br(htmlspecialchars($row['details'])) ?></p>

<p><strong>Target Sqft:</strong> <?= (int)$row['target_sqft'] ?> sqft</p>
<p><strong>Sqft Booked:</strong>
    <?= (int)($row['latest_booked_sqft'] ?? 0) ?> sqft
</p>

<p><strong>Progress:</strong>
    <?= (int)($row['latest_progress'] ?? 0) ?>%
</p>




<div class="progress mb-4">
    <div class="progress-bar bg-success"
         style="width: <?= (int)$row['progress'] ?>%">
        <?= (int)$row['progress'] ?>%
    </div>
</div>

<form method="POST" class="row g-2 align-items-center">
    <input type="hidden" name="target_id" value="<?= $row['id'] ?>">

    <div class="col-md-3">
        <input type="number"
               name="booked_sqft"
               class="form-control"
               min="0"
               max="<?= (int)$row['target_sqft'] ?>"
               value="<?= (int)$row['booked_sqft'] ?>"
               required>
    </div>

    <div class="col-md-6">
        <input type="text"
               name="remarks"
               class="form-control"
               value=""
               placeholder="Update remarks">
    </div>

    <div class="col-md-3">
        <button type="submit"
                name="update_progress"
                class="btn btn-primary w-100">
            Update Progress
        </button>
    </div>
</form>

<?php if ($logs->num_rows > 0): ?>
<hr>
<h6 class="text-muted">Progress History</h6>

<ul class="list-group list-group-flush">
<?php while ($log = $logs->fetch_assoc()): ?>
    <li class="list-group-item">
     
        <strong><?= date('d M Y H:i', strtotime($log['created_at'])) ?></strong><br>
Booked: <?= (int)$log['booked_sqft'] ?> sqft<br>
<?= htmlspecialchars($log['remarks']) ?>

    </li>
<?php endwhile; ?>
</ul>
<?php endif; ?>


<?php if ($row['completion_date']): ?>
<p class="text-success mt-2">
    ✔ Completed on <?= htmlspecialchars($row['completion_date']) ?>
</p>
<?php endif; ?>

</div>
</div>

<?php endwhile; ?>

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
