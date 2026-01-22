<?php
session_start();
require "db.php";

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$employee_id = $_SESSION['email'];

if (!isset($_GET['id'])) {
    die("Invalid Request");
}


$report_id = $_GET['id'];


// Fetch existing report
$sql = "SELECT * FROM reports WHERE id = ? AND employee_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $report_id, $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Report not found or you don't have permission.");
}

$report = $result->fetch_assoc();

// Update report on submit
if (isset($_POST['update'])) {
    $report_date = $_POST['report_date'];
    $work_description = $_POST['work_description'];

    $update_sql = "UPDATE reports SET report_date = ?, work_description = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssi", $report_date, $work_description, $report_id);

    if ($update_stmt->execute()) {
        $_SESSION['update_success'] = true;
        header("Location: edit-report.php?id=" . $report_id);
        exit();
    } else {
        echo "<script>alert('Failed to update report');</script>";
    }
}
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
                            <h4 class="page-title mb-0">Edit Report</h4>
                        </div>
                        <div class="col-lg-6">
                            <div class="d-none d-lg-block">
                                <ol class="breadcrumb m-0 float-end">
                                    <li class="breadcrumb-item"><a href="my-report.php">My Report</a></li>
                                    <li class="breadcrumb-item active">Edit</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-body">

                            <form method="POST">
                                <label class="mb-2 text-primary">Reporting Date</label>
                                <input type="date" class="form-control mb-3" name="report_date"
                                       value="<?php echo $report['report_date']; ?>" required>

                                <label class="mb-2 text-primary">Work Description</label>
                                <textarea class="form-control mb-3" rows="10" name="work_description"
                                          required><?php echo $report['work_description']; ?></textarea>

                                <button type="submit" name="update" class="btn btn-primary">Update Report</button>
                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        <?php include 'include/footer.php' ?>

    </div>
</div>

<?php if (isset($_SESSION['update_success'])): ?>
<script>
    window.onload = function () {
        var myModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
        myModal.show();
    }
</script>
<?php unset($_SESSION['update_success']); endif; ?>

<!-- Success Alert Modal -->
<div id="success-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content modal-filled bg-success">
            <div class="modal-body p-4">
                <div class="text-center">
                    <i class="bx bx-check-double h1 text-white"></i>
                    <h4 class="mt-2 text-white">Well Done!</h4>
                    <p class="mt-3 text-white">Your report has been submitted successfully.</p>
                    <a href="my-report.php" class="btn btn-light my-2">Continue</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Success Modal -->

<script src="assets/js/vendor.min.js"></script>
<script src="assets/js/app.js"></script>

</body>
</html>
