<?php
session_start();
require "db.php";  // your database connection file

$showSuccessModal = false; // flag to trigger modal

if (!isset($_SESSION['email'])) {
    echo "User not logged in";
    exit();
}

if (isset($_POST['submit'])) {

    $employee_id = $_SESSION['email'];
    $report_date = $_POST['report_date'];
    $work_description = $_POST['work_description'];

    $sql = "INSERT INTO reports (employee_id, report_date, work_description)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $employee_id, $report_date, $work_description);

    if ($stmt->execute()) {
        $showSuccessModal = true; // show modal
    } else {
        echo "<script>alert('Error submitting report');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">

<head>
    <?php include 'include/head.php' ?>
</head>

<body>

    <!-- Success Alert Modal -->
    <div id="success-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content modal-filled bg-success">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="bx bx-check-double h1 text-white"></i>
                        <h4 class="mt-2 text-white">Well Done!</h4>
                        <p class="mt-3 text-white">Your report has been submitted successfully.</p>
                        <button type="button" class="btn btn-light my-2" data-bs-dismiss="modal">Continue</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Success Modal -->

    <div id="danger-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content modal-filled bg-danger">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="bx bx-aperture h1 text-white"></i>
                        <h4 class="mt-2 text-white">Oh snap!</h4>
                        <p class="mt-3 text-white">Not successfully submitted, please check</p>
                        <button type="button" class="btn btn-light my-2" data-bs-dismiss="modal">Continue</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->

    

        
    </div><!-- /.modal -->

    <!-- Begin page -->
    <div class="layout-wrapper">

        <!-- ========== Left Sidebar ========== -->
        <?php include 'include\left-sidebar.php' ?>

        <!-- Start Page Content here -->
        <div class="page-content">

            <!-- ========== Topbar Start ========== -->
            <?php include 'include\top-bar.php' ?>
            <!-- ========== Topbar End ========== -->

            <div class="px-3">

                <!-- Start Content-->
                <div class="container-fluid">

                    <!-- Page Title -->
                    <div class="py-3 py-lg-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="page-title mb-0">Update Report</h4>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-none d-lg-block">
                                    <ol class="breadcrumb m-0 float-end">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Update Report</a></li>
                                        <li class="breadcrumb-item active">General</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Report Form -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-body">
                                <form class="form-horizontal" method="POST" action="">

                                    <label class="mb-2 text-primary">Reporting Date</label>
                                    <input class="form-control mb-3" type="date" name="report_date" required>

                                    <label class="mb-2 text-primary">Work Description</label>
                                    <textarea class="form-control mb-3" name="work_description" rows="10" placeholder="List your work done today"></textarea>

                                    <button type="submit" name="submit" class="btn btn-primary waves-effect waves-light">Submit Report</button>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- content -->

            <!-- Footer -->
            <?php include 'include/footer.php' ?>

        </div>
        <!-- End Page content -->

    </div>
    <!-- END wrapper -->

    <!-- App js -->
    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.js"></script>

    <?php if ($showSuccessModal): ?>
    <script>
        var myModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
        myModal.show();
    </script>
    <?php endif; ?>

</body>
</html>
