<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: index.php");
    exit();
}
include('db_connection.php');

// Handle delete request
if (isset($_GET['delete']) && isset($_GET['type']) && isset($_GET['id'])) {
    $type = $_GET['type'];
    $id = $_GET['id'];

    if ($type == 'patients') {
        $query = "DELETE FROM patientList WHERE patientID ='$id'";
        $query2="DROP TABLE `$id`";
        $result2query=mysqli_query($conn2,$query2);
        if(!$result2query){
            echo '<script>alert("patient table can not delete")</script>';
        }
    } elseif ($type == 'doctors') {
        $query = "DELETE FROM doctorList WHERE doctorID ='$id'";
    } elseif ($type == 'pharmacies') {
        $query = "DELETE FROM pharmacytList WHERE registrationNumber ='$id'";
    } else {
        echo "Invalid delete request.";
        exit;
    }
    $queryresult=mysqli_query($conn,$query);
    if ($queryresult) {
        $deleteMessage = "User deleted successfully.";
    } else {
        $deleteMessage = "Error deleting user: " . $conn->error;
    }
}

// Fetch data based on the selected view
$view = $_GET['view'] ?? null;
$data = [];

if ($view) {
    if ($view == 'patients') {
        $query = "SELECT patientID, name, age, gender, email, nid, timestamp FROM patientList";
    } elseif ($view == 'doctors') {
        $query = "SELECT doctorID, name, age, gender, email, registrationNumber, category, timeStamp FROM doctorList";
    } elseif ($view == 'pharmacies') {
        $query = "SELECT name, registrationNumber, address, email, timeStamp FROM pharmacyList";
    } else {
        $error = "Invalid view selected.";
    }

    if (!isset($error)) {

        $result = mysqli_query($conn,$query);
        if (mysqli_num_rows($result)>0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        } else {
            $error = "No records found for $view.";
        }
    }
}

?>
