<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Prescription</title>
    <link rel="stylesheet" href="createPrescription.css">
</head>

<body>
    <div class="container">
        <form action="savePrescription.php" method="post">

            <!-- Top Section -->
            <div class="top-section">
                <button type="button" onclick="window.location.href='doctorDashboard.php'">< Back</button>
                <h2 id="doctorName"><?php echo "Dr. " . $_SESSION['doctor_name'] ?></h2>
                <div class="timestamp" id="timestamp"></div>

                <input type="hidden" name="timestamp" id="hiddenTimestamp">
            </div>

            <!-- Patient Info -->
            <div class="patient-info">
                <div class="info-item">
                    <p><?php echo "Patient Name: " . $_SESSION['doctor_search_patient_name'] ?></p>
                </div>
                <div class="info-item">
                    <p><?php echo "Age: " . $_SESSION['doctor_search_patient_age'] ?></p>
                </div>
                <div class="info-item">
                    <p><?php echo "Gender: " . $_SESSION['doctor_search_patient_gender'] ?></p>
                </div>
            </div>

            <!-- Diagnosis Section -->
            <div class="row bp-pulse-container">
                <div class="bpcolumn">
                    <label for="bp">BP:</label>
                    <input type="text" id="bp" name="bp" placeholder="120/80 mm Hg" required>
                </div>
                <div class="column">
                    <label for="pulse">Pulse Rate:</label>
                    <input type="text" id="pulse" name="pulse" placeholder="73 Mnt" required>
                </div>
            </div>
            <div class="row">
                <label for="disease">Description of Disease:</label>
                <textarea id="disease" name="disease" rows="3" placeholder="Enter disease description"></textarea>
            </div>

            <!-- Tests Section -->
            <h3>Tests</h3>
            <div class="row">
                <input type="text" id="testInput" placeholder="Enter Test Name">
                <button type="button" onclick="addTest()">Add Test</button>
            </div>
            <ul id="testList"></ul>

            <!-- Drugs Section -->
            <h3>Rx</h3>
            <div class="drugs-row">
                <label for="drug">Drug:</label>
                <select id="drug">
                    <?php
                    include('medicines.php');
                    foreach ($medicines as $medicine) {
                        echo "<option value=\"$medicine\">$medicine</option>";
                    }
                    ?>
                </select>

                <label for="strength">Strength:</label>
                <select id="strength">
                    <option value="10mg">10mg</option>
                    <option value="20mg">20mg</option>
                    <option value="40mg">40mg</option>
                    <option value="75mg">75mg</option>
                    <option value="100mg">100mg</option>
                    <option value="120mg">120mg</option>
                    <option value="180mg">180mg</option>
                    <option value="200mg">200mg</option>
                    <option value="250mg">250mg</option>
                    <option value="300mg">300mg</option>
                    <option value="400mg">400mg</option>
                    <option value="500mg">500mg</option>
                    <option value="600mg">600mg</option>
                    <option value="650mg">650mg</option>
                    <option value="1g">1g</option>
                </select>
                <label for="quantity">Quantity:</label>
                <input type="text" id="quantity" placeholder="Enter quantity">

                <label for="frequency">Frequency:</label>
                <input type="text" id="frequency" placeholder="e.g., 1+1+1">

                <label for="remarks">Remarks:</label>
                <input type="text" id="remarks" placeholder="Enter remarks">

                <button type="button" class="add-button" onclick="addDrug()">Add</button>
            </div>

            <!-- Dynamic Prescription List -->
            <div class="box">
                <h4>Prescription List</h4>
                <table class="table" id="prescriptionTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Drug</th>
                            <th>Strength</th>
                            <th>Quantity</th>
                            <th>Frequency</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <!-- Advice Section -->
            <h3>Advice</h3>
            <textarea name="advice"></textarea>
            <input type="hidden" name="testList" id="hiddenTestList">
            <input type="hidden" name="prescriptionList" id="hiddenPrescriptionList">
            <!-- Submit Button -->
            <button type="submit">Submit</button>
        </form>
    </div>

    <script src="createprescription.js"> </script>
</body>

</html>