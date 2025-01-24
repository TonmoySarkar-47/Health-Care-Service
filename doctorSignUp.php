<?php
include('db_connection.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $registration_number = $_POST['reg'];
    $field = $_POST['field'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $timestamp = date("m/d/Y H:i:s");

    // Check if passwords match
    if ($password != $confirm_password) {
        die('<script>alert("Passwords do not match."); window.history.back();</script>');
    } else {
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die('<script>alert("Invalid email format."); window.history.back();</script>');
        } else {
            // Check if the email is already registered
            $email_check_query = "SELECT * FROM doctorList WHERE email='$email' OR registrationNumber='$registration_number'";
            $result = mysqli_query($conn, $email_check_query);

            if (!$result) {
                die('<script>alert("Database query failed while checking email or registration number."); window.history.back();</script>');
            }

            if (mysqli_num_rows($result) > 0) {
                die('<script>alert("Already registered."); window.history.back();</script>');
            } else {
                // Validate registration number
                $reg_check_query = "SELECT * FROM doctorRegistrationList WHERE registrationNumber='$registration_number'";
                $result = mysqli_query($conn, $reg_check_query);

                if (!$result) {
                    die('<script>alert("Database query failed while checking registration number."); window.history.back();</script>');
                }

                if (mysqli_num_rows($result) > 0) {
                    // Insert into doctorList
                    $doctorid = "Doctorid_" . $registration_number;
                    $insert_query = "INSERT INTO doctorList (doctorID,name, age, gender, email, registrationNumber, pass, timeStamp, category) VALUES ('$doctorid','$name', '$age', '$gender', '$email', '$registration_number', '$password', '$timestamp', '$field')";

                    if (mysqli_query($conn, $insert_query)) {
                        die('<script>alert("Signup successful! Redirecting to login page..."); window.location.href="index.php";</script>');
                    } else {
                        die('<script>alert("Error during insertion: ' . mysqli_error($conn) . '"); window.history.back();</script>');
                    }
                } else {
                    die('<script>alert("Registration number not found."); window.history.back();</script>');
                }
            }
        }
    }
}
?>
