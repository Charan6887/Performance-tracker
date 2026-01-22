<?php
include 'php-file.php';

// ---------------------- GET TARGET ID ----------------------
if (!isset($_GET['id'])) {
    header("Location: Viewemployees-target.php");
    exit;
}

$id = (int)$_GET['id'];

// ---------------------- FETCH TARGET DETAILS ----------------------
$stmt = $conn->prepare("
    SELECT employee_name, employee_id, target_date, completion_date, details
    FROM employee_targets
    WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Target not found!";
    exit;
}

$target = $result->fetch_assoc();

// ---------------------- UPDATE TARGET ----------------------
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $stmt = $conn->prepare("
        UPDATE employee_targets
        SET target_date = ?, completion_date = ?, details = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "sssi",
        $_POST['target_date'],
        $_POST['completion_date'],
        $_POST['details'],
        $id
    );

    $stmt->execute();

    header("Location: Viewemployees-target.php");
    exit;
}
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
                                <h4 class="header-title">Edit Employee Target</h4>
                            </div>

                            <div class="card-body">

                                <form method="POST">

                                    <div class="mb-3">
                                        <label class="form-label">Employee Name</label>
                                        <input type="text" class="form-control"
                                               value="<?= htmlspecialchars($target['employee_name']) ?>" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Employee ID</label>
                                        <input type="text" class="form-control"
                                               value="<?= htmlspecialchars($target['employee_id']) ?>" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Target Date</label>
                                        <input type="date" name="target_date" class="form-control"
                                               value="<?= $target['target_date'] ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Completion Date</label>
                                        <input type="date" name="completion_date" class="form-control"
                                               value="<?= $target['completion_date'] ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Target Description</label>
                                        <textarea name="details" class="form-control" rows="5" required><?= htmlspecialchars($target['details']) ?></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-success">Update Target</button>
                                    <a href="View-employees-target.php" class="btn btn-secondary">Cancel</a>

                                </form>

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
