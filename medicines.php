<?php
session_start();
include('db_connection.php');

$query = "SELECT * FROM madicineList";
$result = mysqli_query($conn, $query);
$rows = mysqli_num_rows($result);

$medicines = []; 

if ($rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $medicines[] = $row['medicines'];
    }
}