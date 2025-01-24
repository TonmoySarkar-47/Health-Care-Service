<?php
session_start();

include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['pName'];
    $age = $_POST['pAge'];
    $gender = $_POST['pGender'];
    $email = $_POST['pEmail'];
    $nid = $_POST['pNid'];
    $password = $_POST['pPass'];
    $timestamp = date("m/d/Y H:i:s");

    // Check if passwords match
    if ($_POST['pPass'] != $_POST['pConfirmPass']) {
        die('<script>alert("Passwords do not match."); window.history.back();</script>');
    }

    // Check if NID or email already exists in patientList
    $nidCheckQuery = "SELECT * FROM patientList WHERE nid = '$nid' OR email = '$email'";
    $result = mysqli_query($conn, $nidCheckQuery);

    if (!$result) {
        die('<script>alert("Database query failed while checking NID/Email."); window.history.back();</script>');
    }

    if (mysqli_num_rows($result) > 0) {
        // NID or email already exists
        die('<script>alert("NID or Email already exists. Please use a different NID or Email."); window.history.back();</script>');
    }

    // Check if NID exists in nidList
    $nidCheckQuery2 = "SELECT * FROM nidList WHERE nid = '$nid'";
    $result2 = mysqli_query($conn, $nidCheckQuery2);

    if (!$result2) {
        die('<script>alert("Database query failed while checking NID in nidList."); window.history.back();</script>');
    }

    if (mysqli_num_rows($result2) == 1) {
        // Generate the new table name using the NID
        $tableName = "Patient_" . $nid;
        $patientID = $tableName; // Use table name as patientID

        // Insert patient details into patientList with patientID
        $insertQuery = "INSERT INTO patientList (patientID, name, age, gender, email, nid, password, timeStamp) 
                        VALUES ('$patientID', '$name', '$age', '$gender', '$email', '$nid', '$password', '$timestamp')";
        $result3 = mysqli_query($conn, $insertQuery);

        if (!$result3) {
            die('<script>alert("Database query failed while inserting patient details."); window.history.back();</script>');
        }

        // Create the patient-specific table
        $createTableQuery = "
            CREATE TABLE $tableName (
                serial INT AUTO_INCREMENT PRIMARY KEY,
                doctorID VARCHAR(255) NOT NULL,
                doctorName VARCHAR(255) NOT NULL,
                prescriptionID VARCHAR(255) NOT NULL,
                testID VARCHAR(255) NOT NULL,
                bp VARCHAR(255) NOT NULL,
                pulse_rate INT(255) NOT NULL,
                disease VARCHAR(255) NOT NULL,
                advice VARCHAR(255) NOT NULL,
                category VARCHAR(255) NOT NULL,
                timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";

        if ($conn2->query($createTableQuery) == TRUE) {
            echo "<script>alert('Signup successful! Redirecting to login page...'); window.location.href='index.php';</script>";
        } else {
            die('<script>alert("Error creating table: ' . $conn2->error . '"); window.history.back();</script>');
        }
    } else {
        // NID not found in nidList
        die('<script>alert("NID not found. Please check your NID and try again."); window.history.back();</script>');
    }
}

$conn->close();
$conn2->close();
$conn3->close();
?>
