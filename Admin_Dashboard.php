<?php 
session_start();
include('adminprocess.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admindashboard.css">
</head>
<body>
<header>
    <h1>Admin Dashboard</h1>
    <button type="button" onclick="window.location.href='logout.php'">LOG OUT</button>
</header>

<div class="dashboard">
    <div class="dashboard-box" onclick="window.location.href='?view=patients'">
        <h2>Patient List</h2>
    </div>
    <div class="dashboard-box" onclick="window.location.href='?view=doctors'">
        <h2>Doctor List</h2>
    </div>
    <div class="dashboard-box" onclick="window.location.href='?view=pharmacies'">
        <h2>Pharmacy List</h2>
    </div>
</div>

<?php if (isset($deleteMessage)): ?>
    <p><?php echo htmlspecialchars($deleteMessage); ?></p>
<?php endif; ?>

<?php if ($view): ?>
    <?php if (isset($error)): ?>
        <p><?php echo htmlspecialchars($error); ?></p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <?php if ($view == 'patients'): ?>
                        <th>Patient ID</th><th>Name</th><th>Age</th><th>Gender</th><th>Email</th><th>NID</th><th>Timestamp</th><th>Delete</th>
                    <?php elseif ($view == 'doctors'): ?>
                        <th>Doctor ID</th><th>Name</th><th>Age</th><th>Gender</th><th>Email</th><th>Registration Number</th><th>Category</th><th>Timestamp</th><th>Delete</th>
                    <?php elseif ($view == 'pharmacies'): ?>
                        <th>Name</th><th>Registration Number</th><th>Address</th><th>Email</th><th>Timestamp</th><th>Delete</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <?php foreach ($row as $key => $value): ?>
                            <td><?php echo htmlspecialchars($value); ?></td>
                        <?php endforeach; ?>
                        <td>
                            <button class="delete-btn" onclick="if(confirm('Are you sure you want to delete this user?')) { 
                                window.location.href='?delete=1&type=<?php echo $view; ?>&id=<?php echo htmlspecialchars($row[$view == 'patients' ? 'patientID' : ($view == 'doctors' ? 'doctorID' : 'registrationNumber')]); ?>'; 
                            }">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
<?php endif; ?>
</body>
</html>
