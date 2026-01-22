<?php
session_start();
require "db.php";

// ONLY ADMIN CAN ACCESS
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'Admin') {
    header("Location: admin_panel.php");
    exit();
}

// Fetch departments for filter dropdown
$dept_sql = "SELECT DISTINCT department FROM users ORDER BY department ASC";
$dept_result = $conn->query($dept_sql);

$filter_department = "";
$filter_condition = "";


// If filter applied
if (isset($_GET['department']) && $_GET['department'] !== "") {
    $filter_department = $_GET['department'];
    $filter_condition = "WHERE u.department = '" . $conn->real_escape_string($filter_department) . "'";
}

// Fetch reports with employee details
$sql = "
    SELECT r.*, u.name, u.department 
    FROM reports r
    JOIN users u ON r.employee_id = u.id
    $filter_condition
    ORDER BY r.report_date DESC
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'include/head.php' ?>
</head>

<body>

<div class="layout-wrapper">

    <?php include 'include/left-sidebar.php' ?>

    <div class="page-content">
        
        <?php include 'include/top-bar.php' ?>

        <div class="px-3">
            <div class="container-fluid">

                <div class="py-3 py-lg-4">
                    <div class="row">
                        <div class="col-lg-6">
                            <h4 class="page-title mb-0">Team Wise Report Panel</h4>
                        </div>
                    </div>
                </div>

                <!-- FILTER SECTION -->
                <div class="card card-body mb-3">
                    <form method="GET" class="row">
                        <div class="col-md-4">
                            <label class="text-primary mb-2">Filter by Department</label>
                            <select name="department" class="form-control">
                                <option value="">All Departments</option>

                                <?php while ($dep = $dept_result->fetch_assoc()) { ?>
                                    <option value="<?php echo $dep['department']; ?>"
                                        <?php if ($filter_department == $dep['department']) echo "selected"; ?>>
                                        <?php echo $dep['department']; ?>
                                    </option>
                                <?php } ?>

                            </select>
                        </div>

                        <div class="col-md-2 align-self-end">
                            <button class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>

                <!-- REPORT TABLE -->
                <div class="card card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Employee Name</th>
                                    <th>Department</th>
                                    <th>Report Date</th>
                                    <th>Work Description</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    $i = 1;
                                    while ($row = $result->fetch_assoc()) {
                                    ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><?php echo $row['department']; ?></td>
                                            <td><?php echo $row['report_date']; ?></td>
                                            <td><?php echo nl2br($row['work_description']); ?></td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center text-danger'>No reports found</td></tr>";
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
                </div>

            </div>
        </div>

        <?php include 'include/footer.php' ?>

    </div>
</div>

<script src="assets/js/vendor.min.js"></script>
<script src="assets/js/app.js"></script>

</body>
</html>
