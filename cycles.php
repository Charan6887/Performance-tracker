<!DOCTYPE html>

<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <?php include 'php-file.php' ?>
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

                    <!-- Start Content-->
                    <div class="container-fluid">
                        
                         <div class="row">
                            <div class="col-lg-6">
                                <div class="card" dir="ltr">
                                    <div class="card-body">
                                        <h4 class="header-title mb-3">Area Chart with Point</h4>
                                        <div class="text-center">
                                            <p class="text-muted font-size-15 mb-0">
                                                <span class="mx-2"><i class="mdi mdi-checkbox-blank-circle text-primary"></i> Bitcoin</span>
                                                <span class="mx-2"><i class="mdi mdi-checkbox-blank-circle text-light"></i> Ethereum</span>
                                            </p>
                                        </div>
                                        <div id="morris-area-with-dotted" style="height: 350px;" class="morris-chart" data-colors="#e3eaef,#00acc1"></div>
                                    </div>
                                </div> <!-- end card-->
                            </div> <!-- end col-->

                            <div class="col-lg-6">
                                <div class="card" dir="ltr">
                                    <div class="card-body">
                                        <h4 class="header-title mb-3">Donut Chart</h4>
                                        <div id="morris-donut-example" style="height: 350px;" class="morris-chart" data-colors="#4fc6e1,#00acc1,#ebeff2"></div>
                                        <div class="text-center">
                                            <p class="text-muted font-size-15 mb-0">
                                                <span class="mx-2"><i class="mdi mdi-checkbox-blank-circle text-primary"></i> Bitcoin</span>
                                                <span class="mx-2"><i class="mdi mdi-checkbox-blank-circle text-info"></i> Ethereum</span>
                                                <span class="mx-2"><i class="mdi mdi-checkbox-blank-circle text-light"></i> Litecoin</span>
                                            </p>
                                        </div>
                                    </div>
                                </div> <!-- end card-->
                            </div> <!-- end col-->
                        </div>
                        <!-- end row -->
                       
                
                                                
                                

</div>

</div> <!-- content -->

<!-- Footer -->
<?php include 'include/footer.php' ?>

    </div>
</div>

<script src="assets/js/vendor.min.js"></script>
<script src="assets/js/app.js"></script>

</body>
</html>
