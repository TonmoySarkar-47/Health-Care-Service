<?php
session_start();
include('db_connection.php');

// Check if doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
    header("Location: index.php");
    exit();
}
$doctor_ID = $_SESSION['doctor_id'];
$details = "SELECT * FROM doctorList WHERE doctorID ='$doctor_ID'";
$result = mysqli_query($conn, $details);

if (mysqli_num_rows($result) > 0) {
    // Fetch the doctor data
    $doctor = mysqli_fetch_assoc($result);
    $_SESSION['doctor_name'] = $doctor['name'];
    $_SESSION['doctor_email'] = $doctor['email'];
} else {
    echo "Doctor not found.";
    exit();
}

$show_info = false; // At first showing no patient history

// Handle profile update and patient search when the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? null;

    // Profile update logic
    if ($action == 'saveChanges') {
        // Handling profile update
        $new_name = $_POST['name'] ?? null;
        $new_email = $_POST['email'] ?? null;
        $new_password = $_POST['password'] ?? null;
        $confirm_password = $_POST['confirm_password'] ?? null;

        $update_query = "UPDATE doctorList SET name='$new_name', email='$new_email' WHERE doctorID='$doctor_ID'";

        // Add the password update only if it's not empty
        if (!empty($new_password) && $new_password == $confirm_password) {
            $update_query .= ", password = '$new_password'";
        } elseif (!empty($new_password) && $new_password != $confirm_password) {
            echo "Passwords do not match.";
            exit();
        }

        // Execute the query
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['doctor_name'] = $new_name;  
            $_SESSION['doctor_email'] = $new_email;
            echo "Profile updated successfully.";
            exit();
        } else {
            echo "No changes made to your profile.";
            exit();
        }
    }

    // Patient search logic
    elseif (isset($_POST['searchbutton'])) {
        if (isset($_POST['patient_id']) && !empty($_POST['patient_id'])) {
            $patient_ID = $_POST['patient_id'];

            // Secure patient search query using prepared statements
            $searchquery = "SELECT * FROM patientList WHERE patientID='$patient_ID'";
            $result = mysqli_query($conn, $searchquery);

            if (mysqli_num_rows($result) > 0) {
                $patient_info = mysqli_fetch_assoc($result);
                $show_info = true;
                $_SESSION['doctor_search_patientID'] = $patient_ID;
                $_SESSION['doctor_search_patient_name'] = $patient_info['name'];
                $_SESSION['doctor_search_patient_age'] = $patient_info['age'];
                $_SESSION['doctor_search_patient_gender'] = $patient_info['gender'];
                echo '<button class="floating-button" onclick="window.location.href=\'createprescription.php\'">Write Prescription</button>';
            } else {
                echo "<p style='color: red;'>Patient not found.</p>";
            }
        } else {
            echo "<p style='color: red;'>Please enter a valid Patient ID.</p>";
        }
    }
}
?>
