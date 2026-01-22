<div class="main-menu">
    <!-- Brand Logo -->
    <div class="logo-box">
        <a href="index.php" class="logo-light">
            <img src="assets/images/logo-light.png" alt="logo" class="logo-lg" height="18">
            <img src="assets/images/logo-sm.png" alt="small logo" class="logo-sm" height="24">
        </a>

        <a href="index.php" class="logo-dark">
            <img src="assets/images/logo-dark.png" alt="dark logo" class="logo-lg" height="18">
            <img src="assets/images/logo-sm.png" alt="small logo" class="logo-sm" height="24">
        </a>
    </div>

    <!-- Menu -->
    <div data-simplebar>
        <ul class="app-menu">

            <li class="menu-title">Menu</li>

            <!-- Common Home -->
            

            <?php if ($_SESSION["role"] === "manager"): ?>
                <!-- ================= MANAGER MENU ================= -->

                <li class="menu-item">
                    <a href="manager.php" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="home"></i></span>
                        <span class="menu-text">Home</span>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="create-target.php" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="navigation"></i></span>
                        <span class="menu-text">Create Targets</span>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="review.php" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="navigation"></i></span>
                        <span class="menu-text">Review Employee<br> Submissions</span>
                    </a>
                </li>

          <!--      <li class="menu-item">
                    <a href="update-report.php" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="navigation"></i></span>
                        <span class="menu-text">Give Rating & Feedback</span>
                    </a>
                </li>  -->
                <li class="menu-item">
                    <a href="view-employees-target.php" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="home"></i></span>
                        <span class="menu-text">Viewemployees-target</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="my-employees.php" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="navigation"></i></span>
                        <span class="menu-text">My Team</span>
                    </a>
                </li>

        <!--        <li class="menu-item">
                    <a href="update-report.php" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="navigation"></i></span>
                        <span class="menu-text">Recommend Next Step</span>
                    </a>
                </li>  -->

            <?php elseif ($_SESSION["role"] === "employee"): ?>
                <!-- ================= EMPLOYEE MENU ================= -->

                <li class="menu-item">
                    <a href="index.php" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="home"></i></span>
                        <span class="menu-text">Home</span>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="see-target.php" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="navigation"></i></span>
                        <span class="menu-text">See Targets</span>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="apply-incentive.php" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="navigation"></i></span>
                        <span class="menu-text">View Assigned Performance <br>Period</span>
                    </a>
                </li>

                

              <!--  <li class="menu-item">
                    <a href="update-report.php" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="navigation"></i></span>
                        <span class="menu-text">Update Progress Anytime</span>
                    </a>
                </li> 

                <li class="menu-item">
                    <a href="update-report.php" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="navigation"></i></span>
                        <span class="menu-text">Submit Final Performance</span>
                    </a>
                </li> 

                <li class="menu-item">
                    <a href="calender.php" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="calendar"></i></span>
                        <span class="menu-text">Calendar</span>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="my-report.php" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="navigation"></i></span>
                        <span class="menu-text">View Final Score</span>
                    </a>
                </li> -->

            <?php elseif ($_SESSION["role"] === "hr"): ?>
                <!-- ================= HR MENU ================= -->

                <li class="menu-item">
                    <a href="hr-dashboard.php" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="home"></i></span>
                        <span class="menu-text">Home</span>
                    </a>
                </li>

        <!--        <li class="menu-item">
                    <a href="manage-employees.php" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="users"></i></span>
                        <span class="menu-text">Creates performance <br>periods</span>
                    </a>
                </li> 

                <li class="menu-item">
                    <a href="cycles.php" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="user-check"></i></span>
                        <span class="menu-text">Locks/unlocks cycles</span>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="attendance.php" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="calendar-check"></i></span>
                        <span class="menu-text">Views final outcomes</span>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="payroll.php" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="wallet"></i></span>
                        <span class="menu-text">Payroll & Incentives</span>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="performance-overview.php" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="bar-chart-3"></i></span>
                        <span class="menu-text">Performance Overview</span>
                    </a>
                </li>  -->
                <li class="menu-item">
                    <a href="regitration-page.php" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="bar-chart-3"></i></span>
                        <span class="menu-text">Create Profile</span>
                    </a>
                </li>

            <?php endif; ?>

        </ul>
    </div>
</div>
