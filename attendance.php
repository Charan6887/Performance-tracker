<?php
include 'php-file.php';

/* ===============================
   LOGIN CHECK (MANAGER / HR)
================================ */
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['manager','hr'])) {
    header("Location: login_page.php");
    exit;
}

/* ===============================
   CALCULATE FINAL SCORE
================================ */
if (isset($_POST['calculate_score'])) {

    $target_id = (int)$_POST['target_id'];
    $remarks_rating = $_POST['remarks_rating'];

    // Fetch target details
    $stmt = $conn->prepare("
        SELECT progress, target_date, completion_date
        FROM employee_targets
        WHERE id = ?
    ");
    $stmt->bind_param("i", $target_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if (!$row) {
        die("Invalid Target");
    }

    /* ===============================
       SCORE COMPONENTS
    ================================ */
    $progress_score = $row['progress'] * 0.6;

    // On-time score
    if ($row['completion_date'] && $row['completion_date'] <= $row['target_date']) {
        $time_score = 25;
    } else {
        $time_score = 0;
    }

    // Remarks score
    $remarks_map = [
        'Excellent' => 1,
        'Good'      => 0.7,
        'Average'   => 0.4,
        'Poor'      => 0
    ];

    $remarks_score = $remarks_map[$remarks_rating] * 15;

    // Final score
    $final_score = round($progress_score + $time_score + $remarks_score);

    // Save score
    $stmt = $conn->prepare("
        UPDATE employee_targets
        SET final_score = ?, remarks_rating = ?
        WHERE id = ?
    ");
    $stmt->bind_param("isi", $final_score, $remarks_rating, $target_id);
    $stmt->execute();

    $_SESSION['success_message'] = "Final score calculated successfully.";
    header("Location: " . basename(__FILE__));
    exit;
}

/* ===============================
   FETCH COMPLETED TARGETS
================================ */
$result = $conn->query("
    SELECT id, employee_name, progress, final_score
    FROM employee_targets
    WHERE progress >= 100
    ORDER BY employee_name ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Final Score Calculation</title>
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
    <?= $_SESSION['success_message'] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<div class="card">
<div class="card-body">
<h4 class="header-title">Final Score Evaluation</h4>
</div>

<div class="card-body">

<table class="table table-bordered">
<thead class="table-light">
<tr>
    <th>Employee</th>
    <th>Progress</th>
    <th>Final Score</th>
    <th>Action</th>
</tr>
</thead>

<tbody>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($row['employee_name']) ?></td>
<td><?= $row['progress'] ?>%</td>
<td>
    <?= $row['final_score'] !== null
        ? '<span class="badge bg-success">'.$row['final_score'].'</span>'
        : '<span class="badge bg-warning">Pending</span>' ?>
</td>

<td>
<form method="POST" class="d-flex gap-2">
<input type="hidden" name="target_id" value="<?= $row['id'] ?>">

<select name="remarks_rating" class="form-select form-select-sm" required>
    <option value="">Select Remarks</option>
    <option value="Excellent">Excellent</option>
    <option value="Good">Good</option>
    <option value="Average">Average</option>
    <option value="Poor">Poor</option>
</select>

<button type="submit"
        name="calculate_score"
        class="btn btn-sm btn-primary">
    Calculate
</button>
</form>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

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
