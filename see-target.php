<?php
include 'php-file.php';

/* ===============================
   LOGIN CHECK
================================ */
if (!isset($_SESSION['employee_id']) || empty($_SESSION['employee_id'])) {
    header("Location: login_page.php");
    exit;
}

$employee_id = (int)$_SESSION['employee_id'];

/* ===============================
   UPDATE STATUS & REMARKS
================================ */
if (isset($_POST['update_target'], $_POST['target_id'])) {

    $target_id = (int)$_POST['target_id'];
    $status    = $_POST['status'];
    $remarks   = trim($_POST['remarks']);

    $completion_date = ($status === 'Completed') ? date('Y-m-d') : NULL;

    $stmt = $conn->prepare("
        UPDATE employee_targets
        SET completion_date = ?, remarks = ?
        WHERE id = ? AND employee_id = ?
    ");
    $stmt->bind_param("ssii", $completion_date, $remarks, $target_id, $employee_id);
    $stmt->execute();

    $_SESSION['success_message'] =
        ($stmt->affected_rows > 0)
        ? "Target updated successfully."
        : "No changes were made.";

    header("Location: " . basename(__FILE__));
    exit;
}

/* ===============================
   FETCH ASSIGNED TARGETS
================================ */
$stmt = $conn->prepare("
    SELECT 
        employee_name,
        employee_id,
        target_date,
        completion_date,
        details,
        target_sqft,
        remarks,
        created_at,
        id
    FROM employee_targets
    WHERE employee_id = ?
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
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

<?php if (!empty($_SESSION['success_message'])): ?>
<div class="alert alert-success alert-dismissible fade show">
    <?= htmlspecialchars($_SESSION['success_message']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if ($result->num_rows === 0): ?>
    <div class="alert alert-info">No targets assigned yet.</div>
<?php endif; ?>

<?php while ($row = $result->fetch_assoc()): ?>

<div class="card mb-3">
<div class="card-body">

<p><strong>Employee Name:</strong> <?= htmlspecialchars($row['employee_name']) ?></p>
<p><strong>Employee ID:</strong> <?= (int)$row['employee_id'] ?></p>

<p><strong>Target Date:</strong> <?= htmlspecialchars($row['target_date']) ?></p>

<p><strong>Target Sqft:</strong>
    <span class="badge bg-info">
        <?= (int)$row['target_sqft'] ?> sqft
    </span>
</p>

<p><strong>Completion Date:</strong>
<?= $row['completion_date']
    ? htmlspecialchars($row['completion_date'])
    : '<span class="text-warning">Pending</span>' ?>
</p>

<p><strong>Description:</strong><br>
<?= nl2br(htmlspecialchars($row['details'])) ?></p>

<form method="POST" class="d-flex gap-2 mt-2">
<input type="hidden" name="target_id" value="<?= $row['id'] ?>">

<select name="status" class="form-select form-select-sm w-auto">
    <option value="Yet to Complete" <?= empty($row['completion_date']) ? 'selected' : '' ?>>
        Yet to Complete
    </option>
    <option value="Completed" <?= !empty($row['completion_date']) ? 'selected' : '' ?>>
        Completed
    </option>
</select>

<input type="text"
       name="remarks"
       class="form-control form-control-sm"
       value="<?= htmlspecialchars($row['remarks']) ?>"
       placeholder="Add remark">

<button type="submit"
        name="update_target"
        class="btn btn-sm btn-primary">
    Update
</button>
</form>

<small class="text-muted d-block mt-2">
    Assigned on <?= htmlspecialchars($row['created_at']) ?>
</small>

</div>
</div>

<?php endwhile; ?>

</div>
</div>

<?php include 'include/footer.php'; ?>
</div>
</div>

<script src="assets/js/vendor.min.js"></script>
<script src="assets/js/app.js"></script>

</body>
</html>
