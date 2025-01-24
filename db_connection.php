<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";

// Database names
$dbname1 = "healthCareService";
$dbname2 = "patientDB";
$dbname3 = "prescriptionDB";

// Connect to healthCareService
$conn = new mysqli($servername, $username, $password, $dbname1);
if ($conn->connect_error) {
    die("Connection to healthCareService failed: " . $conn->connect_error);
}

// Connect to patientDB
$conn2 = new mysqli($servername, $username, $password, $dbname2);
if ($conn2->connect_error) {
    die("Connection to patientDB failed: " . $conn2->connect_error);
}

// Connect to prescriptionDB
$conn3 = new mysqli($servername, $username, $password, $dbname3);
if ($conn3->connect_error) {
    die("Connection to prescriptionDB failed: " . $conn3->connect_error);
}

?>
