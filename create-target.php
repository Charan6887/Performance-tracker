<?php

include 'php-file.php';

/* ===============================
   AJAX: FETCH EMPLOYEE ID
================================ */
if (isset($_GET['fetch_employee_id'])) {

    $name = trim($_GET['fetch_employee_id']);

    $stmt = $conn->prepare("
        SELECT employee_id 
        FROM users 
        WHERE name = ? AND role = 'employee'
        LIMIT 1
    ");

    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->bind_result($employee_id);

    if ($stmt->fetch()) {
        echo $employee_id;
    }
    exit;
}

/* ===============================
   FORM SUBMISSION
================================ */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $stmt = $conn->prepare("
    INSERT INTO employee_targets
    (employee_name, employee_id, target_date, completion_date, details, target_sqft)
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "sisssi",
    $_POST['employee_name'],
    $_POST['employee_id'],
    $_POST['target_date'],
    $_POST['completion_date'],
    $_POST['details'],
    $_POST['target_sqft']
);

$stmt->execute();

}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <?php include 'include/head.php' ?>
</head>

<body>

<div class="layout-wrapper">
    <?php include 'include/left-sidebar.php' ?>

    <div class="page-content mt-4">

        <?php include 'include/top-bar.php' ?>

        <div class="px-3 pt-4">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-body">
                                <h4 class="header-title">Assign Employee Target</h4>
                            </div>

                            <div class="p-2">

                                <form class="form-horizontal" method="POST">

                                    <div class="mb-2 row">
                                        <label class="col-md-2 col-form-label">Employee Name</label>
                                        <div class="col-md-10">
                                            <input type="text"
                                                   name="employee_name"
                                                   id="employee_name"
                                                   class="form-control"
                                                   required>
                                        </div>
                                    </div>

                                    <div class="mb-2 row">
                                        <label class="col-md-2 col-form-label">Employee ID</label>
                                        <div class="col-md-10">
                                            <input type="number"
                                                   name="employee_id"
                                                   id="employee_id"
                                                   class="form-control"
                                                   readonly
                                                   required>
                                        </div>
                                    </div>
                                 
                                    

                                    

                                    <div class="mb-2 row">
                                        <label class="col-md-2 col-form-label">Target Description</label>
                                        <div class="col-md-10">
                                            <textarea name="details" class="form-control" rows="5" required></textarea>
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-md-2 col-form-label">Target Sqft</label>
                                        <div class="col-md-10">
                                            <input type="number"
                                                name="target_sqft"
                                                class="form-control"
                                                min="1"
                                                placeholder="Enter total target sqft"
                                                required>
                                        </div>
                                    </div>


                                    <div class="mb-2 row">
                                        <label class="col-md-2 col-form-label">Target Date</label>
                                        <div class="col-md-10">
                                            <input type="date" name="target_date" class="form-control" required>
                                        </div>
                                    </div>

                                    <button type="submit"
                                            class="btn btn-primary w-md">
                                        Submit
                                    </button>


                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <?php include 'include/footer.php' ?>
    </div>
</div>

<script src="assets/js/vendor.min.js"></script>
<script src="assets/js/app.js"></script>

<script>
document.getElementById('employee_name').addEventListener('blur', function () {
    const name = this.value.trim();
    if (!name) return;

    fetch(`?fetch_employee_id=${encodeURIComponent(name)}`)
        .then(res => res.text())
        .then(id => {
            document.getElementById('employee_id').value = id;
        });
});
</script>

</body>
</html>
