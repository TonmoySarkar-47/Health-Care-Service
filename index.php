<?php
session_start();

if (isset($_SESSION["doctor_id"])) {
    header("Location: doctorDashboard.php");
    exit(); 
} elseif (isset($_SESSION["patient_email"])) {
    header("Location: patientDashboard.php");
    exit(); 
} elseif (isset($_SESSION["pharmacy_email"])) {
    header("Location: pharmacyDashboard.php");
    exit(); 
}
elseif (isset($_SESSION["admin_email"])) {
    header("Location: Admin_Dashboard.php");
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <script src="validation.js" defer></script>
</head>
<body>
    <header>
        <h1>Health Care Services</h1>
    </header>
    <div class="login-container">
        <h2>Login</h2>
        <form id="login-form" method="POST" action="login.php">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="role">Login as:</label>
            <select id="role" name="role" >
                <option value="">Select Role</option>
                <option value="doctor">Doctor</option>
                <option value="patient">Patient</option>
                <option value="pharmacy">Pharmacy</option>
            </select>

            <button type="submit">Login</button>
        </form>
        <p>New user? <a href="signup.php">Create an account</a></p>
    </div>
</body>
</html>