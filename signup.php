<?php
// Registration backend code
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Database connection
    $host = "localhost";
    $dbname = "company_db";
    $username = "root";
    $password = "";

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    }

    // Form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $employee_id = $_POST['employee_id'];
    $role = $_POST['role'];
    $reporting_to = $_POST['reporting_to']; // new field
    $password_input = $_POST['password'];

    // Check if employee already exists
    $check = $conn->query("SELECT * FROM employees WHERE employee_id = '$employee_id'");

    if ($check->num_rows > 0) {
        $message = "Employee ID already exists!";
        $msg_color = "red";
    } else {

        // Hash password
        $hashed_password = password_hash($password_input, PASSWORD_DEFAULT);

        // Insert into DB
        $sql = "INSERT INTO employees (name, email, employee_id, role, reporting_to, password)
                VALUES ('$name', '$email', '$employee_id', '$role', '$reporting_to', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            header("Location: sign.php?registered=1");
            exit();
        } else {
            $message = "Error: " . $conn->error;
            $msg_color = "red";
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Registration</title>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1a237e, #0d47a1, #1976d2);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .register-container {
            width: 420px;
            padding: 35px;
            border-radius: 15px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity:0; transform: translateY(20px); }
            to { opacity:1; transform: translateY(0); }
        }

        h2 {
            text-align:center;
            color:white;
            margin-bottom:20px;
            font-weight:600;
        }

        label {
            color:white;
            font-size:14px;
            font-weight:500;
        }

        input, select {
            width:100%;
            padding:12px;
            margin:8px 0 15px 0;
            border:none;
            border-radius:8px;
            background: rgba(255,255,255,0.85);
            font-size:15px;
        }

        button {
            width:100%;
            padding:12px;
            background:#ffca28;
            border:none;
            border-radius:8px;
            font-size:16px;
            font-weight:600;
            cursor:pointer;
            transition:0.2s;
        }

        button:hover {
            background:#ffb300;
            transform: scale(1.03);
        }

        .msg {
            padding: 10px;
            text-align: center;
            background: rgba(255, 51, 51, 0.85);
            color:white;
            border-radius: 6px;
            margin-bottom:15px;
        }

        .bottom-text {
            text-align:center;
            margin-top:10px;
            color:white;
        }

        .bottom-text a {
            color:#ffeb3b;
            text-decoration:none;
            font-weight:500;
        }

        .bottom-text a:hover {
            text-decoration:underline;
        }

    </style>
</head>
<body>

<div class="register-container">
    <h2>Employee Registration</h2>

    <?php if (!empty($message)) { ?>
        <p class="msg"><?php echo $message; ?></p>
    <?php } ?>

    <form method="POST">

        <label>Full Name:</label>
        <input type="text" name="name" placeholder="Enter full name" required>

        <label>Email:</label>
        <input type="email" name="email" placeholder="Enter email" required>

        <label>Employee ID:</label>
        <input type="text" name="employee_id" placeholder="Enter employee ID" required>

        <label>Select Role:</label>
        <select name="role" required>
            <option value="">-- Select Role --</option>
            <option value="Website Developer">Website Developer</option>
            <option value="Tester">Tester</option>
            <option value="Designer">Designer</option>
            <option value="SEO">SEO</option>
            <option value="Lead Generation">Lead Generation</option>
            <option value="Team Lead">Team Lead</option> <!-- new role -->
        </select>

        <label>Reporting To:</label>
        <input type="text" name="reporting_to" placeholder="Enter team lead name" required> <!-- new field -->

        <label>Password:</label>
        <input type="password" name="password" placeholder="Create password" required>

        <button type="submit">Register</button>
    </form>

    <p class="bottom-text">
        Already registered? <a href="sign.php">Login here</a>
    </p>
</div>

</body>
</html>
