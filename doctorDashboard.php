<?php 
session_start();
include('doctorDashboardProcess.php');
if (!isset($_SESSION['doctor_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="doctorDashboard.css">
</head>
<body>
    <div class="header">
        <h1>Doctor Dashboard</h1>
        <!-- Profile Button -->
        <button class="profile-btn" onclick="showProfile();hideUpdateProfile()">My Profile</button>
    </div>
    <div id="overlay" onclick="hideProfile()"></div>

    <!-- Profile Modal -->
    <div id="profileModal">
        <h3>My Profile</h3>
        <p><strong>Name:</strong> <?php echo $_SESSION['doctor_name']; ?></p>
        <p><strong>Email:</strong> <?php echo $_SESSION['doctor_email']; ?></p>
        <button onclick="showUpdateProfile();hideProfile()">Update Profile</button>
        <button onclick="hideProfile();hideUpdateProfile()">Close</button>
        <button onclick="location.href='logout.php'">Log Out</button>
    </div>

    <!-- Update Profile Modal -->
    <div id="updateProfileModal">
        <h3>Update Profile</h3>
        <form id="updateProfileForm">
            <label for="updateName">Pharmacy Name</label>
            <input type="text" id="updateName" value="<?php echo $_SESSION['doctor_name']; ?>" placeholder="Name">
            <label for="updateEmail">Email</label>
            <input type="email" id="updateEmail" value="<?php echo $_SESSION['doctor_email']; ?>" placeholder="Email">
            <label for="updatePassword">New Password</label>
            <input type="password" id="updatePassword" placeholder="New Password">
            <label for="confirmPassword">Confirm Password</label>
            <input type="password" id="confirmPassword" placeholder="Confirm New Password">
            <button type="submit" id="saveChangesBtn">Save Changes</button>
            <button type="button" onclick="hideUpdateProfile()">Cancel</button>
        </form>
    </div>

    <!-- Search Form -->
    <div class="search-container">
        <form action="doctorDashboard.php" method="post">
            <input type="text" name="patient_id" placeholder="Enter Patient ID" required>
            <button type="submit" name="searchbutton">Search</button>
        </form>
    </div>

    <!-- Prescription History -->
    <div id="prescription-history">
        <?php
        if ($show_info) {
            include('getPatientHistory.php');
        }
        ?>
    </div>

    <script src="doctorDashboard.js" defer></script>
</body>
</html>
