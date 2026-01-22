


<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <?php include 'php-file.php' ?>
<head>
<head>
    <?php include 'include/head.php' ?>
</head>


<body>

    <div class="layout-wrapper">

        <!-- Sidebar -->
        <?php include 'include/left-sidebar.php' ?>

        <div class="page-content">

            <!-- Topbar -->
            <?php include 'include/top-bar.php' ?>

            <div class="px-3">
                <div class="container-fluid">

                    <!-- Page Title -->
                    <div class="py-3 py-lg-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="page-title mb-0">My Report</h4>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-none d-lg-block">
                                    <ol class="breadcrumb m-0 float-end">
                                        <li class="breadcrumb-item"><a href="#">Update Report</a></li>
                                        <li class="breadcrumb-item active">My Report</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Report Table -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-body">

                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="15%">Date</th>
                                            <th>Work Description</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>


                                    <tbody>
                                        <?php if ($result->num_rows > 0): ?>
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo $row['report_date']; ?></td>
                                                    <td><?php echo nl2br($row['work_description']); ?></td>
                                                    <td width="10%">
                                                        <a href="edit-report.php?id=<?php echo $row['id']; ?>" 
                                                        class="btn btn-sm btn-primary">Edit</a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-danger">No reports found.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>


                                </table>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Footer -->
            <?php include 'include/footer.php' ?>

        </div>
    </div>

    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.js"></script>

</body>
</html>
