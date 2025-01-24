<?php
session_start();
include('db_connection.php');

// // Enable error reporting for debugging
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);



// Ensure session variables exist
if (!isset($_SESSION['doctor_id']) || !isset($_SESSION['doctor_search_patientID'])) {
    die("Doctor or patient ID not found in session.");
}

// Retrieve session data
$doctor__ID = $_SESSION['doctor_id'];
$patient__ID = $_SESSION['doctor_search_patientID'];
// Extract the numeric part of both patient_id and doctor_id
$patient_numeric = preg_replace('/\D/', '', $patient__ID);  // Remove non-digits
$doctor_numeric = preg_replace('/\D/', '', $doctor__ID);
// Table names
$prescriptionTableName = $patient_numeric . $doctor_numeric;
$testTableName = $prescriptionTableName . "_testList";

// Check and create tables
try {
    // Create prescription table
    $createPrescriptionTable = "
        CREATE TABLE IF NOT EXISTS `$prescriptionTableName` (
            serial INT AUTO_INCREMENT PRIMARY KEY,
            drug VARCHAR(255),
            strength VARCHAR(255),
            quantity VARCHAR(255),
            frequency VARCHAR(255),
            remarks VARCHAR(255),
            completedDose INT DEFAULT 0
        );
    ";
    $querypres = mysqli_query($conn3, $createPrescriptionTable);

    // Create test table
    $createTestTable = "
        CREATE TABLE IF NOT EXISTS `$testTableName` (
            serial INT AUTO_INCREMENT PRIMARY KEY,
            test VARCHAR(255)
        );
    ";
    $querytest = mysqli_query($conn3, $createTestTable);
} catch (Exception $e) {
    die("Error creating tables: " . $e->getMessage());
}

// Process POST data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $doctorName = $_SESSION['doctor_name'] ?? 'Unknown Doctor';
    $doctorcategory = $_SESSION['doctor_category'];
    $timestamp = $_POST['timestamp'] ?? '';
    $bp = $_POST['bp'] ?? '';
    $pulseRate = $_POST['pulse'] ?? '';
    $disease = $_POST['disease'] ?? '';
    $testList = $_POST['testList'] ?? '[]';
    $prescriptionList = $_POST['prescriptionList'] ?? '[]';
    $advice = $_POST['advice'] ?? '';

    // Decode JSON data
    $testListDecoded = json_decode($testList, true);
    $prescriptionListDecoded = json_decode($prescriptionList, true);


    // Insert test data into test table
    try {
        $testInsertQuery = "";
        foreach ($testListDecoded as $test) {
            $testInsertQuery = "INSERT INTO `$testTableName` (test) VALUES ('$test')";
            mysqli_query($conn3, $testInsertQuery);
        }


    } catch (Exception $e) {
        die("Error inserting tests: " . $e->getMessage());
    }

    // Insert prescription data into prescription table
    try {
        $prescriptionInsertQuery = "
            INSERT INTO `$prescriptionTableName` 
            (drug, strength, quantity, frequency, remarks, completedDose) 
            VALUES (?, ?, ?, ?, ?, ?)
        ";
        foreach ($prescriptionListDecoded as $item) {
            $drug = $item['drug'] ?? '';
            $strength = $item['strength'] ?? '';
            $quantity = $item['quantity'] ?? '';
            $frequency = $item['frequency'] ?? '';
            $remarks = $item['remarks'] ?? '';
            $completedDose = 0;
        
            // Create the query string
            $prescriptionInsertQuery = "
                INSERT INTO `$prescriptionTableName` 
                (drug, strength, quantity, frequency, remarks, completedDose) 
                VALUES ('$drug', '$strength', '$quantity', '$frequency', '$remarks', $completedDose)
            ";
        
            // Execute the query
            mysqli_query($conn3, $prescriptionInsertQuery);
        }
        
    } catch (Exception $e) {
        die("Error inserting prescriptions: " . $e->getMessage());
    }

    // Update patient table in patientDB
    try {
        // Check if the doctorID exists in the $patient__ID table
       // Check if the doctorID exists in the $patient__ID table
$checkQuery = "SELECT * FROM `$patient__ID` WHERE doctorID = '$doctor__ID'";
$result = mysqli_query($conn2, $checkQuery);

if (mysqli_num_rows($result) > 0) {
    // Row does not exist, insert new data
    $insertQuery = "
        INSERT INTO `$patient__ID` (
            doctorID, doctorName, prescriptionID, testID, bp, pulse_rate, 
            disease, advice, category, timestamp
        ) VALUES ('$doctor__ID', '$doctorName', '$prescriptionTableName', '$testTableName', 
            '$bp', '$pulseRate', '$disease', '$advice', '$doctorcategory', '$timestamp')
    ";

    mysqli_query($conn2, $insertQuery);
} else {
    // Row exists, update the data if new values are provided
    $updateQuery = "
        UPDATE `$patient__ID` 
        SET 
            doctorID = '$doctor__ID', 
            doctorName = '$doctorName', 
            prescriptionID = '$prescriptionTableName', 
            testID = '$testTableName', 
            bp = '$bp', 
            pulse_rate = '$pulseRate', 
            disease = '$disease', 
            advice = '$advice', 
            category = '$doctorcategory', 
            timestamp = '$timestamp'
        WHERE doctorID = '$doctor__ID'
    ";

    mysqli_query($conn2, $updateQuery);
}

    } catch (Exception $e) {
        die("Error updating patient table: " . $e->getMessage());
    }

    echo '<script>alert("Prescription and tests saved successfully!");window.location.href="doctorDashboard.php"</script>';
} else {
    echo "Invalid request method.";
}
?>