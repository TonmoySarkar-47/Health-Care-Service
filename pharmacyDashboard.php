<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['pharmacy_email'])) {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['pharmacy_email'];
$flag = false;

// Fetch pharmacy details
$sql = "SELECT * FROM pharmacyList WHERE email = '$email'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $pharma = mysqli_fetch_assoc($result);
    $registration_number = $pharma['registrationNumber'];
    $name = $pharma['name'];
    $address = $pharma['address'];
    $password = $pharma['password'];
} else {
    echo "<script>alert('No pharmacy found with the given email.');</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? null;

    if ($action == 'saveChanges') {
        // Handling profile update
        $new_name = $_POST['name'] ?? null;
        $new_address = $_POST['address'] ?? null;
        $new_email = $_POST['email'] ?? null;
        $new_password = $_POST['password'] ?? null;
        $confirm_password = $_POST['confirm_password'] ?? null;

        // Check if the new email already exists (excluding the current session email)
        $email_check_query = "SELECT email FROM pharmacyList WHERE email = '$new_email' AND email != '$email'";
        $email_check_result = mysqli_query($conn, $email_check_query);

        if ($email_check_result && mysqli_num_rows($email_check_result) > 0) {
            echo "The email already exists. Please choose a different email.";
            exit();
        }

        // Prepare the update query
        $update_query = "UPDATE pharmacyList SET 
            name = '$new_name', 
            address = '$new_address', 
            email = '$new_email'
            WHERE email = '$email'";

        // Add the password update only if it's not empty
        if (!empty($new_password) && $new_password == $confirm_password) {
            $update_query .= ", password = '$new_password'";
        } elseif (!empty($new_password) && $new_password != $confirm_password) {
            echo "Passwords do not match.";
            exit();
        }

        // Execute the query
        if (mysqli_query($conn, $update_query)) {
            if ($email != $new_email) {
                $_SESSION['email'] = $new_email;
            }
            echo "Profile updated successfully.";
            exit();
        } else {
            echo "No changes made to your profile.";
            exit();
        }
    }

    if ($action == 'search') {
        $prescriptionID = $_POST['prescriptionID'];
        $sql = "SELECT Serial, drug, strength, quantity, frequency, completedDose FROM `$prescriptionID`";
        $result = mysqli_query($conn3, $sql);
    
        if ($result && mysqli_num_rows($result) > 0) {
            echo "<form id='updatePrescriptionForm'><table class='result-table'>";
            echo "<thead><tr><th>Serial</th><th>Medicine</th><th>Strength</th><th>Quantity</th><th>Frequency</th><th>Completed Doses</th><th>Add Quantity</th></tr></thead>";
            echo "<tbody>";
    
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td><input type='hidden' name='serial[]' value='" . htmlspecialchars($row['Serial']) . "'>" . htmlspecialchars($row['Serial']) . "</td>";
                echo "<td>" . htmlspecialchars($row['drug']) . "</td>";
                echo "<td>" . htmlspecialchars($row['strength']) . "</td>";
                echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                echo "<td>" . htmlspecialchars($row['frequency']) . "</td>";
                echo "<td>" . htmlspecialchars($row['completedDose']) . "</td>";
                echo "<td><input type='number' name='add_quantity[]' value='0' min='0' required></td>";
                echo "</tr>";
            }
    
            echo "</tbody></table>";
            echo "<input type='hidden' name='prescriptionID' value='" . htmlspecialchars($prescriptionID) . "'>";
    
            // Add total bill input field
            echo "<div style='text-align: center; margin-top: 20px;'>";
            echo "<label for='total_bill' style='font-size: 20px; margin-right: 10px;'>Total Bill:</label>";
            echo "<input type='number' id='total_bill' name='total_bill' placeholder='Enter Total Bill Amount' style='padding: 10px; width: 50%; border-radius: 5px; border: 1px solid #ccc; font-size: 14px; margin-top: 5px;' required>";
            echo "</div>";
    
            echo "<button type='button' id='updatePrescriptionBtn' style='background-color: #4CAF50; color: white; padding: 13px 30px; font-size: 18px; border: none; border-radius: 5px; cursor: pointer; display: block; margin: 20px auto;'>Submit</button>";
            echo "</form>";
        } else {
            echo "<p>Prescription not found.</p>";
        }
        exit();
    }
    

    if ($action == 'updatePrescription') {
        $prescriptionID = $_POST['prescriptionID'] ?? null;
        $serials = $_POST['serial'] ?? [];
        $addQuantities = $_POST['add_quantity'] ?? [];
        $totalBill = $_POST['total_bill'] ?? 0;

        if ($prescriptionID && !empty($serials) && !empty($addQuantities)) {
            // Get first 10 digits of the prescriptionID
            $nid = "Patient_" . substr($prescriptionID, 0, 10);

            // Fetch patient details from patientList table
            $sql_patient = "SELECT name, age, gender, email FROM patientList WHERE patientID = '$nid'";
            $patient_result = mysqli_query($conn, $sql_patient);
            $patient = mysqli_fetch_assoc($patient_result);

            if ($patient) {
                // Patient data
                $patient_name = $patient['name'];
                $patient_age = $patient['age'];
                $patient_gender = $patient['gender'];
                $patient_email = $patient['email'];

                // Validate all serials before updating
                $flag = false; // To track if any invalid data is found
                foreach ($serials as $index => $serial) {
                    $addQuantity = $addQuantities[$index];
                    if (is_numeric($serial) && is_numeric($addQuantity)) {
                        $sql = "SELECT completedDose, quantity FROM `$prescriptionID` WHERE Serial = '$serial'";
                        $result = mysqli_query($conn3, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $completedDose = $row['completedDose'] ?? 0;
                        $quantity = $row['quantity'] ?? 0;

                        $newCompletedDose = $completedDose + $addQuantity;

                        if ($newCompletedDose > $quantity) {
                            $flag = true;
                            break; // Stop validation on finding invalid data
                        }
                    }
                }

                if ($flag) {
                    // If validation fails, show an error and exit
                    echo "One of the selected doses is already completed. Please provide valid quantities.";
                    exit();
                }

                // If validation passes, proceed with updates
                foreach ($serials as $index => $serial) {
                    $addQuantity = $addQuantities[$index];
                    if (is_numeric($serial) && is_numeric($addQuantity)) {
                        $sql = "SELECT completedDose FROM `$prescriptionID` WHERE Serial = '$serial'";
                        $result = mysqli_query($conn3, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $completedDose = $row['completedDose'] ?? 0;

                        $newCompletedDose = $completedDose + $addQuantity;

                        $updateSql = "UPDATE `$prescriptionID` SET completedDose = '$newCompletedDose' WHERE Serial = '$serial'";
                        if (!mysqli_query($conn3, $updateSql)) {
                            echo "Error updating record.";
                            exit();
                        }
                    }
                }

                // Insert transaction into pharmacyTransactions table
                $timestamp = date('m/d/Y H:i:s');
                $insert_sql = "INSERT INTO pharmacyTransactions (patientName, patientAge, patientGender, email, amount, pharmacyRegistrationNumber, date) 
                    VALUES ('$patient_name', '$patient_age', '$patient_gender', '$patient_email', '$totalBill', '$registration_number', '$timestamp')";
                if (mysqli_query($conn, $insert_sql)) {
                    echo "Prescription Updated successfully.";
                } else {
                    echo "Error recording transaction.";
                }
                exit();
            } else {
                echo "Patient not found.";
                exit();
            }
        } else {
            echo "Invalid input.";
            exit();
        }
    }


    // Fetch transaction history for pharmacy
    if ($action == 'getTransactionHistory') {
        $sql = "SELECT serial, pharmacyRegistrationNumber, patientName, patientAge, patientGender, email, amount, date FROM pharmacyTransactions WHERE pharmacyRegistrationNumber = '$registration_number'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            echo "<table class='result-table'>
                <tr>
                    <th>Serial</th>
                    <th>Patient Name</th>
                    <th>Patient Age</th>
                    <th>Patient Gender</th>
                    <th>Email</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>" . $row['serial'] . "</td>
                    <td>" . $row['patientName'] . "</td>
                    <td>" . $row['patientAge'] . "</td>
                    <td>" . $row['patientGender'] . "</td>
                    <td>" . $row['email'] . "</td>
                    <td>" . $row['amount'] . "</td>
                    <td>" . $row['date'] . "</td>
                </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No transactions found.</p>";
        }
        exit();
    }

}
mysqli_close($conn);
mysqli_close($conn2);
mysqli_close($conn3);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Dashboard</title>
</head>

<body>
    <header>
        <link rel="stylesheet" href="pharmacyDashboard.css">
        Welcome To <?php echo htmlspecialchars($name); ?>
        <button class="profile-btn" onclick="showProfile();hideUpdateProfile()">My Profile</button>
    </header>
    <div class="container">
        <form id="searchForm" class="form-group">
            <label for="prescriptionID">Search For Prescriptions:</label>
            <input type="text" id="prescriptionID" name="prescriptionID" placeholder="Enter Prescription ID"
                required><br><br>
            <button type="button" id="submitSearchBtn">Search</button>
        </form>
        <button id="transactionHistoryBtn" onclick="showTransactionHistory()">Transaction History</button>
    </div>
    <div id="searchResults"></div>
    <!-- Hidden transaction results container -->
    <div id="transactionResults" style="display:none;"></div>
    <div id="overlay" onclick="hideProfile()"></div>

    <!-- Profile Modal -->
    <div id="profileModal">
        <h3>My Pharmacy Profile</h3>
        <p><strong>Registration Number:</strong> <?php echo htmlspecialchars($registration_number); ?></p>
        <p><strong>Pharmacy Name:</strong> <?php echo htmlspecialchars($name); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p><br>
        <button onclick="showUpdateProfile();hideProfile()">Update Profile</button>
        <button onclick="hideProfile();hideUpdateProfile()">Close</button>
        <button onclick="location.href='logout.php'">Log Out</button>
    </div>

    <!-- Update Profile Modal -->
    <div id="updateProfileModal">
        <h3>Update Profile</h3>
        <form id="updateProfileForm">
            <label for="updateName">Pharmacy Name</label>
            <input type="text" id="updateName" value="<?php echo htmlspecialchars($name); ?>" placeholder="Name">
            <label for="updateAddress">Address</label>
            <input type="text" id="updateAddress" value="<?php echo htmlspecialchars($address); ?>"
                placeholder="Address">
            <label for="updateEmail">Email</label>
            <input type="email" id="updateEmail" value="<?php echo htmlspecialchars($email); ?>" placeholder="Email">
            <label for="updatePassword">New Password</label>
            <input type="password" id="updatePassword" placeholder="New Password">
            <label for="confirmPassword">Confirm Password</label>
            <input type="password" id="confirmPassword" placeholder="Confirm New Password">
            <button type="submit" id="saveChangesBtn">Save Changes</button>
            <button type="button" onclick="hideUpdateProfile()">Cancel</button>
        </form>
    </div>

    <script src="pharmacyDashboard.js" defer></script>

</body>

</html>