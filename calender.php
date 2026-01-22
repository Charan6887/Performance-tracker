<?php include 'php-file.php'?>



<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">

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
                                <h4 class="page-title mb-0">Single button dropdowns</h4>
                            </div>
                            <p class="text-muted font-size-13 mb-2">
                                Any single <code
                                    class="highlighter-rouge">.btn</code> can be turned into a dropdown
                                toggle with some markup changes. Here’s how you can put them to work
                                with either <code class="highlighter-rouge">&lt;button&gt;</code>
                                elements:
                            </p>
                         <div class="row">
                            <div class="col-6">
                                <div class="dropdown mt-2">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Dropdown button <i class="mdi mdi-chevron-down"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="dropdown mt-2">
                                    <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Dropdown link <i class="mdi mdi-chevron-down"></i>
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
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
