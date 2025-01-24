<?php
include("db_connection.php");
// Collect form data
$doctorName = $_POST['doctorName'];
$patientName = $_POST['patientName'];
$patientAge = $_POST['patientAge'];
$patientGender = $_POST['patientGender'];
$bp = isset($_POST['bp']) ? $_POST['bp'] : null;
$pulseRate = isset($_POST['pulseRate']) ? $_POST['pulseRate'] : null;
$disease = isset($_POST['disease']) ? $_POST['disease'] : null;
$testList = isset($_POST['testList']) ? $_POST['testList'] : null;
$drugList = isset($_POST['drugList']) ? $_POST['drugList'] : null;
$drugStrength = isset($_POST['drugStrength']) ? $_POST['drugStrength'] : null;
$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : null;
$frequency = isset($_POST['frequency']) ? $_POST['frequency'] : null;
$remarks = isset($_POST['remarks']) ? $_POST['remarks'] : null;
$advice = isset($_POST['advice']) ? $_POST['advice'] : null;

// Insert data into the database
$sql = "INSERT INTO prescriptionID 
    (doctorName, patientName, patientAge, patientGender, bp, pulseRate, disease, testList, drugList, drugStrength, quantity, frequency, remarks, advice, completedDoses) 
    VALUES 
    ('$doctorName', '$patientName', $patientAge, '$patientGender', '$bp', $pulseRate, '$disease', '$testList', '$drugList', '$drugStrength', $quantity, '$frequency', '$remarks', '$advice', 0)";

if ($conn->query($sql) === TRUE) {
    echo '<script>alert("New prescription added successfully.")</script>';
} else {
    echo '<script>alert("Error: " . $sql . "<br>" . $conn->error")';
}

// Close the connection
$conn->close();
?>
