<?php include 'backend.php' ?>
<?php
/* ❌ Role check */
if ($user["role"] !== "employee") {
    session_unset();
    session_destroy();
    header("Location: login_page.php?message=Access-denied");
    exit();
}

/* ✅ Authorized Employee */
$name = $user["name"];
$role = $user["role"];
?>


<?php  include 'file.html' ?>