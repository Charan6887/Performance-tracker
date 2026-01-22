<?php include 'backend.php'?>
<?php
/* ❌ Allow ONLY manager role */
if ($user["role"] !== "manager") {
    session_unset();
    session_destroy();
    header("Location: login_page.php?message=Access-denied");
    exit();
}

/* ✅ Authorized Manager */
$name = $user["name"];
$role = $user["role"];
?>


<?php include 'file.html' ?>