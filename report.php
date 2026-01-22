<?php
session_start();

// If user not logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: sign.php");
    exit();
}

// Database connection
$host = "localhost";
$dbname = "company_db";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Ensure we have employee_id in session (you already check this earlier)
$emp_id = $_SESSION['employee_id'];

// If department or role not present in session, fetch from employees table and set them
if (!isset($_SESSION['department']) || !isset($_SESSION['role'])) {
    $stmt = $conn->prepare("SELECT name, role, department FROM employees WHERE employee_id = ? LIMIT 1");
    // use string param type because employee_id in your code is treated as string
    $stmt->bind_param("s", $emp_id);
    $stmt->execute();
    $stmt->bind_result($db_name, $db_role, $db_department);
    if ($stmt->fetch()) {
        // set values into session so subsequent pages don't need DB lookup
        if (!isset($_SESSION['name']) || empty($_SESSION['name'])) {
            $_SESSION['name'] = $db_name;
        }
        $_SESSION['role'] = $db_role;
        $_SESSION['department'] = $db_department;
    } else {
        // if employee not found in employees table, set safe defaults
        if (!isset($_SESSION['role'])) $_SESSION['role'] = 'Employee';
        if (!isset($_SESSION['department'])) $_SESSION['department'] = '';
    }
    $stmt->close();
}


// Now safe to read these
$role = $_SESSION['role'];
$department = $_SESSION['department'];
// and $emp_id is already set above

// -----------------------------------------
// ADD NEW REPORT (INSERT WITH DEPARTMENT)
// -----------------------------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_report'])) {

    $name = $_SESSION['name'];
    $employee_id = $_SESSION['employee_id'];
    $date = $_POST['date'];
    $worked_on = $_POST['worked_on'];
    $department = $_SESSION['department'];   // NEW

    $stmt = $conn->prepare("
        INSERT INTO daily_reports (name, employee_id, report_date, worked_on, department)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("sssss", $name, $employee_id, $date, $worked_on, $department);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Daily Report Submitted Successfully!";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }

    $stmt->close();

    header("Location: report.php");
    exit();
}


// -----------------------------------------
// UPDATE REPORT (ONLY worked_on is editable)
// -----------------------------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_report'])) {

    $report_id = $_POST['report_id'];
    $worked_on = $_POST['worked_on'];
    $employee_id = $_SESSION['employee_id'];

    $stmt = $conn->prepare("
        UPDATE daily_reports 
        SET worked_on = ?
        WHERE id = ? AND employee_id = ?
    ");

    $stmt->bind_param("sis", $worked_on, $report_id, $employee_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Report Updated Successfully!";
    } else {
        $_SESSION['message'] = "Update Error: " . $stmt->error;
    }

    $stmt->close();

    header("Location: report.php");
    exit();
}


// -----------------------------------------
// FETCH REPORTS BASED ON ROLE
// -----------------------------------------
$role = $_SESSION['role'];          
$department = $_SESSION['department'];
$emp_id = $_SESSION['employee_id'];

if ($role === 'Admin') {
    // Admin sees all reports
    $query = "SELECT * FROM daily_reports ORDER BY report_date DESC";

} elseif ($role === 'TeamLeader') {
    // Team Leader sees all reports from their department
    $query = "
        SELECT * FROM daily_reports 
        WHERE department = '$department'
        ORDER BY report_date DESC
    ";

} else {
    // Normal employee sees only their own reports
    $query = "
        SELECT * FROM daily_reports 
        WHERE employee_id = '$emp_id'
        ORDER BY report_date DESC
    ";
}

$result = $conn->query($query);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Daily Report Panel</title>

    <style>
        body {
            font-family: "Segoe UI", Roboto, Arial, sans-serif;
            background: linear-gradient(135deg, #eef2f3, #7dbcd7ff);
            padding: 30px;
            color: #333;
        }

        .container {
            width: 900px;
            margin: auto;
        }

        .box {
            background: #fcfcfccc;
            padding: 25px;
            border-radius: 14px;
            margin-bottom: 25px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            backdrop-filter: blur(5px);
        }

        h2, h3 {
            color: #2c3e50;
            margin-bottom: 12px;
        }

        label {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
            color: #555;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #dcdde1;
            background: #d8dadcff;
            border-radius: 10px;
            margin-bottom: 15px;
            font-size: 15px;
            transition: 0.2s;
        }

        input:focus, textarea:focus {
            border-color: #4b7bec;
            background: white;
            box-shadow: 0 0 5px rgba(75,123,236,0.3);
            outline: none;
        }

        button {
            background: #4b7bec;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            transition: 0.2s;
        }

        button:hover {
            background: #3867d6;
        }

        .msg {
            color: #2ecc71;
            background: #69786fff;
            padding: 12px;
            border-left: 5px solid #2ecc71;
            margin-bottom: 20px;
            border-radius: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        table th {
            background: #4b7bec;
            color: white;
            padding: 12px;
            font-size: 15px;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        table tr:hover td {
            background: #f6f6f6ff;
        }

        .edit-box {
            background: #f5f5f5ff;
            padding: 20px;
            border-left: 6px solid #4b7bec;
            border-radius: 10px;
        }

        .logout {
            display: inline-block;
            padding: 10px 18px;
            background: #eb4d4b;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
            transition: 0.2s;
        }

        .logout:hover {
            background: #c0392b;
        }

    </style>
</head>

<body>

<div class="container">

    <h2>Welcome, <?php echo $_SESSION['name']; ?> (ID: <?php echo $_SESSION['employee_id']; ?>)</h2>

    <?php
    if (isset($_SESSION['message'])) {
        echo "<p class='msg'>{$_SESSION['message']}</p>";
        unset($_SESSION['message']);
    }
    ?>

    <!-- New Report Form -->
    <div class="box">
        <h3>Submit Daily Report</h3>

        <form method="POST">
            <input type="hidden" name="add_report" value="1">

            <label>Date:</label>
            <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" readonly required>

            <label>Worked on:</label>
            <textarea name="worked_on" rows="4" required></textarea>

            <button type="submit">Submit</button>
        </form>
    </div>

    <!-- Edit Form -->
    <?php
    if (isset($_GET['edit'])) {
        $edit_id = $_GET['edit'];

        $edit_query = $conn->query("SELECT * FROM daily_reports WHERE id='$edit_id' AND employee_id='$emp_id' LIMIT 1");

        if ($edit_query->num_rows > 0) {
            $edit_row = $edit_query->fetch_assoc();
    ?>

    <div class="box edit-box">
        <h3>Edit Report</h3>

        <form method="POST">
            <input type="hidden" name="update_report" value="1">
            <input type="hidden" name="report_id" value="<?php echo $edit_row['id']; ?>">

            <label>Date:</label>
            <input type="date" name="date" value="<?php echo $edit_row['report_date']; ?>" required>

            <label>Worked on:</label>
            <textarea name="worked_on" rows="4" required><?php echo $edit_row['worked_on']; ?></textarea>

            <button type="submit">Update</button>
        </form>
    </div>

    <?php }} ?>

    <!-- Report History -->
    <div class="box">
        <h3>
            <?php 
                if ($role === 'TeamLeader') echo "Department Reports ({$department})";
                else if ($role === 'Admin') echo "All Department Reports";
                else echo "Your Submitted Reports";
            ?>
        </h3>

        <table>
            <tr>
                <th>Date</th>
                <th>Employee</th>
                <th>Department</th>
                <th>Worked On</th>
                <th>Edit</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['report_date']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['department']; ?></td>
                    <td><?php echo nl2br($row['worked_on']); ?></td>
                    <td>
                        <?php if ($row['employee_id'] == $emp_id) { ?>
                            <a href="?edit=<?php echo $row['id']; ?>">
                                <button>Edit</button>
                            </a>
                        <?php } else { echo "—"; } ?>
                    </td>
                </tr>
            <?php } ?>

        </table>
    </div>

    <center>
        <a class="logout" href="signout.php">Logout</a>
    </center>

</div>

</body>
</html>
