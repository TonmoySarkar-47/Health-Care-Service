<?php
include('db_connection.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['pharmaName'];
    $registration_number = $_POST['pharmaRegNum'];
    $address = $_POST['paddress'];
    $email = $_POST['pharmEmail'];
    $password = $_POST['pharmaPass'];
    $confirm_password = $_POST['pharmaConfirmPass'];
    $timestamp = date("m/d/Y H:i:s");
    
    // Check if passwords match
    if ($password !== $confirm_password) {
        die('<script>alert("Passwords do not match."); window.history.back();</script>');//used die and hisory.back() karon html nai,blank page redirect hoy
    } else {
        // Check if the registration number and email are already in pharmacyList
        $check_query = "SELECT * FROM pharmacyList WHERE registrationNumber = '$registration_number' OR email = '$email'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            // Registration number or email already exists
            die('<script>alert("Registration number or email already exists. Cannot register again."); window.history.back();</script>');
        } else {
            // Validate registration number exists in pharmacyRegistrationList
            $reg_check_query = "SELECT * FROM pharmacyRegistrationList WHERE registrationNumber = '$registration_number'";
            $reg_check_result = mysqli_query($conn, $reg_check_query);

            if (mysqli_num_rows($reg_check_result) > 0) {

                // Insert into pharmacyList
                $insert_query = "INSERT INTO pharmacyList (name, registrationNumber, address, email, password, timeStamp) 
                 VALUES ('$name', '$registration_number', '$address', '$email', '$password', '$timestamp')";
                ;
                if (mysqli_query($conn, $insert_query)) {
                    echo '<script>
                        alert("Registration successful! Redirecting to login page...");
                        window.location.href = "index.php";
                    </script>';
                    exit();
                } else {
                    die('<script>alert("Error: Unable to register. Please try again."); window.history.back();</script>');
                }
            } else {
                die('<script>alert("Registration number not found in the authorized list."); window.history.back();</script>');

            }
        }
    }
}
$conn->close();
$conn2->close();
$conn3->close();
?>