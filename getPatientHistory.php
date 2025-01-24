<?php
session_start();
include('db_connection.php');

// Assuming $show_info and $patient_ID are set correctly
if ($show_info && !empty($patient_ID)) {
    // Check if the table exists
    $query = "SHOW TABLES LIKE '$patient_ID'";
    $result = mysqli_query($conn2, $query);
    $table_exists = mysqli_num_rows($result) > 0;


    if ($table_exists) {
        // Fetch prescriptions based on doctor category
        $doc_category = $_SESSION['doctor_category'];
$query = "SELECT * FROM `$patient_ID` WHERE category = '$doc_category'";
$result = mysqli_query($conn2, $query);


        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='prescription-card'>";
                echo "<h3><strong>Doctor Name:</strong> <span style='color: #e74c3c;'>" . htmlspecialchars($row['doctorName']) . "</span></h3>";

                $prescid = $row['prescriptionID'];

                $prescription_query = "SELECT * FROM `$prescid`";
                $pres_result = mysqli_query($conn3, $prescription_query);                

                if ($pres_result && mysqli_num_rows($pres_result) > 0) {
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Drug</th>";
                    echo "<th>Strength</th>";
                    echo "<th>Quantity</th>";
                    echo "<th>Frequency</th>";
                    echo "<th>Remarks</th>";
                    echo "<th>Completed Dose</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";

                    while ($pres_row = $pres_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($pres_row['drug']) . "</td>";
                        echo "<td>" . htmlspecialchars($pres_row['strength']) . "</td>";
                        echo "<td>" . htmlspecialchars($pres_row['quantity']) . "</td>";
                        echo "<td>" . htmlspecialchars($pres_row['frequency']) . "</td>";
                        echo "<td>" . htmlspecialchars($pres_row['remarks']) . "</td>";
                        echo "<td>" . htmlspecialchars($pres_row['completedDose']) . "</td>";
                        echo "</tr>";
                    }

                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "<p>No details found for prescription ID: $prescid</p>";
                }

                echo "<p><strong>Disease:</strong> " . htmlspecialchars($row['disease']) . "</p>";
                echo "<p><strong>Advice:</strong> " . htmlspecialchars($row['advice']) . "</p>";
                echo "<p><strong>Timestamp:</strong> " . htmlspecialchars($row['timestamp']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No prescriptions found for the given category.</p>";
        }
    } else {
        echo "<p>Patient ID not found in the database.</p>";
    }
}
?>

<style>
    /* General Body Styling */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        color: #333;
        margin: 0;
        padding: 0;
    }

    /* Prescription Card Styling */
    .prescription-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin: 20px;
        padding: 20px;
        width: 80%;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }

    .prescription-card h3 {
        font-size: 24px;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .prescription-card p {
        font-size: 16px;
        line-height: 1.6;
        margin: 5px 0;
    }

    .prescription-card p strong {
        color: #2980b9;
    }

    /* Table Styling */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    th,
    td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #2980b9;
        color: #fff;
        font-size: 18px;
    }

    td {
        background-color: #f9f9f9;
    }

    tr:nth-child(even) td {
        background-color: #f1f1f1;
    }

    table tr:hover td {
        background-color: #ecf0f1;
    }

    /* No Details Message */
    p {
        font-size: 18px;
        color: #e74c3c;
        font-weight: bold;
    }

    /* Responsive Design */
    @media screen and (max-width: 768px) {
        .prescription-card {
            width: 90%;
            padding: 15px;
        }

        .prescription-card h3 {
            font-size: 22px;
        }

        .prescription-card p {
            font-size: 14px;
        }

        table th,
        table td {
            padding: 10px;
        }
    }
</style>