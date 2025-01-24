<?php
session_start();
include("db_connection.php");

// Assuming the patient is logged in and their email is stored in the session
if (!isset($_SESSION['patient_email'])) {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['patient_email'];
// Fetch patient details once
$sql = "SELECT * FROM patientList WHERE email = '$email'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $patient = mysqli_fetch_assoc($result);
    $patientID = $patient['patientID'];
    $patientName = $patient['name'];
    $age = $patient['age'];
    $gender = $patient['gender'];
    $nid = $patient['nid'];
} else {
    echo "No patient found with email: $email";
    exit();
}


// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'fetchMedicalHistory') {

        // Assuming $show_info and $patient_ID are set correctly
        if (!empty($patientID)) {

            $query = "SELECT * FROM `$patientID`";
            $result = mysqli_query($conn2, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $output = "";
                while ($row = mysqli_fetch_assoc($result)) {
                    $output .= "<div class='prescription-card'>
                <h4>Doctor Name: <span>" . htmlspecialchars($row['doctorName']) . "</span></h4>
                <p><strong>Date:</strong> " . htmlspecialchars($row['timestamp']) . "</p>
                <p><strong>Disease:</strong> " . htmlspecialchars($row['disease']) . "</p>";

                    $prescid = $row['prescriptionID'];
                    // Fetch prescription details for the current prescription ID
                    $prescription_query = "SELECT * FROM `$prescid`";
                    $pres_result = mysqli_query($conn3, $prescription_query);

                    if ($pres_result && mysqli_num_rows($pres_result) > 0) {
                        $output .= "<table>
                                <thead>
                                    <tr>
                                        <th>Drug</th>
                                        <th>Strength</th>
                                        <th>Quantity</th>
                                        <th>Frequency</th>
                                        <th>Remarks</th>
                                        <th>Completed Dose</th>
                                    </tr>
                                </thead>
                                <tbody>";

                        while ($pres_row = mysqli_fetch_assoc($pres_result)) {
                            $output .= "<tr>
                                    <td>" . htmlspecialchars($pres_row['drug']) . "</td>
                                    <td>" . htmlspecialchars($pres_row['strength']) . "</td>
                                    <td>" . htmlspecialchars($pres_row['quantity']) . "</td>
                                    <td>" . htmlspecialchars($pres_row['frequency']) . "</td>
                                    <td>" . htmlspecialchars($pres_row['remarks']) . "</td>
                                    <td>" . htmlspecialchars($pres_row['completedDose']) . "</td>
                                </tr>";
                        }

                        $output .= "</tbody>
                            </table>";
                    } else {
                        $output .= "No details found for prescription ID: $prescid";
                    }

                    $output .= "</div>";
                }

                echo $output;
            } else {
                echo "No prescriptions found for the given category.";
            }
        }
        exit();
    }


    if ($action == 'saveChanges') {
        // Handling profile update
        $new_name = $_POST['name'] ?? null;
        $new_age = $_POST['age'] ?? null;
        $new_email = $_POST['email'] ?? null;
        $new_password = $_POST['password'] ?? null;
        $confirm_password = $_POST['confirm_password'] ?? null;

        // Check if the new email already exists (excluding the current session email)
        $email_check_query = "SELECT email FROM patientList WHERE email = '$new_email' AND email != '$email'";
        $email_check_result = mysqli_query($conn, $email_check_query);

        if ($email_check_result && mysqli_num_rows($email_check_result) > 0) {
            echo "The email already exists. Please choose a different email.";
            exit();
        }

        // Prepare the update query
        $update_query = "UPDATE patientList SET 
            name = '$new_name', 
            age = '$new_age', 
            email = '$new_email'
            WHERE email = '$email'";

        // Add the password update only if it's not empty
        if (!empty($new_password) && $new_password == $confirm_password) {
            $update_query .= ", password = '$new_password'";
        } elseif (!empty($new_password) && $new_password != $confirm_password) {
            echo "Passwords do not match.";
            exit();
        }

        // Execute the query
        if (mysqli_query($conn, $update_query)) {
            if ($email != $new_email) {
                $_SESSION['email'] = $new_email;
            }
            echo "Profile updated successfully.";
            exit();
        } else {
            echo "No changes made to your profile.";
            exit();
        }
    }

}
$conn->close();
$conn2->close();
$conn3->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
</head>

<body>
    <header>
        <link rel="stylesheet" href="patientDashboard.css">
        <link rel="stylesheet" href="patientDashboardSearch.css">
        <h1>Patient Dashboard</h1>
        <button class="profile-btn" onclick="showProfile();hideUpdateProfile()">My Profile</button>
        <div class="welcome">
            <h2>Welcome, <?php echo htmlspecialchars($patientName); ?></h2>
        </div>
    </header>
    <main>
        <div class="buttons">
            <button onclick="fetchMedicalHistory()">Check Medical History</button>

            <div class="scrollable">
                <table id="historyTable">

                    <tbody id="historyResult">
                        <!-- Medical history rows will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="overlay" onclick="hideProfile()"></div>

    <!-- Profile Modal -->
    <div id="profileModal">
        <h3>My Profile</h3>
        <p><strong>Patient ID:</strong> <?php echo htmlspecialchars($patientID); ?></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($patientName); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Age:</strong> <?php echo htmlspecialchars($age); ?></p>
        <p><strong>NID:</strong> <?php echo htmlspecialchars($nid); ?></p><br>
        <button onclick="showUpdateProfile();hideProfile()">Update Profile</button>
        <button onclick="hideProfile();hideUpdateProfile()">Close</button>
        <button onclick="location.href='logout.php'">Log Out</button>
    </div>

    <!-- Update Profile Modal -->
    <div id="updateProfileModal">
        <h3>Update Profile</h3>
        <form id="updateProfileForm">
            <label for="updateName">Name</label>
            <input type="text" id="updateName" value="<?php echo htmlspecialchars($patientName); ?>" placeholder="Name">

            <label for="updateEmail">Email</label>
            <input type="email" id="updateEmail" value="<?php echo htmlspecialchars($email); ?>" placeholder="Email">

            <label for="updateAge">Age</label>
            <input type="number" id="updateAge" value="<?php echo htmlspecialchars($age); ?>" placeholder="Age">

            <label for="updatePassword">New Password</label>
            <input type="password" id="updatePassword" placeholder="New Password">

            <label for="confirmPassword">Confirm Password</label>
            <input type="password" id="confirmPassword" placeholder="Confirm New Password">

            <button type="submit" id="saveChangesBtn">Save Changes</button>
            <button type="button" onclick="hideUpdateProfile()">Cancel</button>
        </form>
    </div>

    <script src="patientDashboard.js"></script>
</body>

</html>