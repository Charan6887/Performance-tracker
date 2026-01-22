<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $conn = new mysqli("localhost", "root", "", "company_db");

    $employee_id = $_POST["employee_id"];
    $email = $_POST["email"];

    // Check employee exists
    $stmt = $conn->prepare("SELECT * FROM employees WHERE employee_id=? AND email=? LIMIT 1");
    $stmt->bind_param("ss", $employee_id, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // Store OTP in session
        $_SESSION["reset_employee"] = $employee_id;
        $_SESSION["reset_email"] = $email;
        $_SESSION["reset_otp"] = $otp;

        $msg = "Your OTP is: <b>$otp</b><br>Enter this OTP on the next page.";
        
    } else {
        $error = "Employee ID or Email is incorrect!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password - OTP</title>

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
    }

    body {
        background: linear-gradient(135deg, #1a237e, #0d47a1, #1976d2);
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .login-container {
        width: 380px;
        padding: 35px;
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.15);
        box-shadow: 0px 8px 30px rgba(0,0,0,0.2);
        backdrop-filter: blur(12px);
        animation: fadeIn 0.8s ease-in-out;
        color: white;
        text-align: center;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    h2 {
        font-weight: 600;
        margin-bottom: 20px;
        color: white;
    }

    label {
        text-align: left;
        display: block;
        margin-bottom: 5px;
        font-size: 14px;
        color: white;
        font-weight: 500;
    }

    input {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 8px;
        margin-bottom: 15px;
        font-size: 15px;
        background: rgba(255,255,255,0.8);
        color: #000;
        outline: none;
    }

    input::placeholder {
        color: #555;
    }

    button {
        width: 100%;
        padding: 12px;
        background: #ffca28;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        font-weight: 600;
        color: #000;
        transition: 0.2s;
    }

    button:hover {
        background: #ffb300;
        transform: scale(1.03);
    }

    a {
        color: #ffeb3b;
        font-weight: 500;
        text-decoration: none;
        margin-top: 15px;
        display: block;
    }

    a:hover {
        text-decoration: underline;
    }

    .msg, .error {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 6px;
        text-align: center;
    }

    .msg {
        background: rgba(0, 200, 83, 0.85);
        color: white;
    }

    .error {
        background: rgba(255, 51, 51, 0.85);
        color: white;
    }

</style>
</head>

<body>

<div class="login-container">

    <h2>Forgot Password</h2>

    <?php if (!empty($msg)) echo "<p class='msg'>$msg</p>"; ?>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <label>Employee ID</label>
        <input type="text" name="employee_id" placeholder="Enter your employee ID" required>

        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your registered email" required>

        <button type="submit">Send OTP</button>
    </form>

    <a href="verify.php">Already have OTP? Verify here</a>
</div>

</body>
</html>
