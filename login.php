<?php
session_start();
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($role == "doctor") {
        $emailCheckQuery = "SELECT * FROM doctorList WHERE email ='$email'";
        $result=mysqli_query($conn,$emailCheckQuery);
        $rows=mysqli_num_rows($result);
        if ($rows> 0) {
            $row = $result->fetch_assoc();
            $storedpass = $row['pass'];
            if ($password==$storedpass) {
                // Set session variables to keep the doctor logged in
                $_SESSION['doctor_id'] = $row['doctorID'];  // Store doctorID in session
                $_SESSION['doctor_email'] = $email;
                $_SESSION['doctor_category'] = $row['category'];
                $_SESSION['doctor_name'] = $row['name']; // Store the doctor's name
                header("Location: doctorDashboard.php");
                exit();
            } else {
                echo '<script>alert("Invalid password!"); window.location.href = "index.php";</script>';
                exit();
            }
        } else {
            echo '<script>alert("Email not found!"); window.location.href = "index.php";</script>';
            exit();
        }
    } else if ($role == "patient") {
        // Check if the email exists in patientList
        $emailCheckQuery = "SELECT * FROM patientList WHERE email = '$email'";
        $result = mysqli_query($conn, $emailCheckQuery);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Verify the password (plain text)
            if ($password == $row['password']) {
                // Login successful, redirect to patientDashboard.php
                $_SESSION['patient_email'] = $email;
                echo '<script>alert("Login successful! Redirecting to dashboard..."); window.location.href = "patientDashboard.php";</script>';
                exit();
            } else {
                // Incorrect password
                echo '<script>alert("Invalid password!"); window.location.href = "index.php";</script>';
                exit();
            }
        } else {
            // Email not found
            echo '<script>alert("Email not found!"); window.location.href = "index.php";</script>';
            exit();
        }
    } else if($role=="pharmacy") {
         // Check if the email exists in pharmacyList
         $emailCheckQuery = "SELECT * FROM pharmacyList WHERE email = '$email'";
         $result = mysqli_query($conn, $emailCheckQuery);
 
         if (mysqli_num_rows($result) > 0) {
             $row = mysqli_fetch_assoc($result);
 
             // Verify the password (plain text)
             if ($password == $row['password']) {
                 // Login successful, redirect to pharmacyDashboard.php
                 $_SESSION['pharmacy_email'] = $email;
                 echo '<script>alert("Login successful! Redirecting to dashboard..."); window.location.href = "pharmacyDashboard.php";</script>';
                 exit();
             } else {
                 // Incorrect password
                 echo '<script>alert("Invalid password!"); window.location.href = "index.php";</script>';
                 exit();
             }
         } else {
             // Email not found
             echo '<script>alert("Email not found!"); window.location.href = "index.php";</script>';
             exit();
         }
    }
    else{
    if($password=="8989" && $email=="admin@gmail.com"){
        $_SESSION['admin_email']=$email;
        header("Location: Admin_Dashboard.php");
        exit();
    }
    else{
        echo'<script>
    alert("SELECT YOUR ROLE");
    window.location.href="Index.php";
</script>';
    }
    }
}

$conn->close();

?>