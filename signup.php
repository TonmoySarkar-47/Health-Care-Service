<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="signupStyle.css">
    <title>Signup</title>
</head>

<body>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <form id="role-form">
            <label for="role">Sign up as:</label>
            <select id="role" name="role" onchange="showSignupFields()" required>
                <option value="">Select Role</option>
                <option value="doctor">Doctor</option>
                <option value="patient">Patient</option>
                <option value="pharmacy">Pharmacy</option>
            </select>
        </form> <!-- Close the role-form here -->

        <!-- Hidden fields for different roles -->
        <div id="patient-fields" style="display: none;">
            <form action="patientSignUp.php" method="post" name="patient">
                <label for="pName">Name:</label>
                <input type="text" name="pName" id="pName" required>

                <label for="pAge">Age:</label>
                <input type="number" name="pAge" min="0" id="pAge" required>

                <label for="pGender">Gender:</label>
                <select name="pGender" id="pGender" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>

                <label for="pNid">NID/Birth Certificate:</label>
                <input type="text" name="pNid" id="pNid" required>

                <label for="pEmail">Email:</label>
                <input type="email" name="pEmail" id="pEmail" required>

                <label for="pPass">Password:</label>
                <input type="password" name="pPass" id="pPass" required>

                <label for="pConfirmPass">Confirm Password:</label>
                <input type="password" name="pConfirmPass" id="pConfirmPass" required>

                <button type="submit">Sign Up</button>
            </form>
            <!-- Back to Login Button -->
            <form action="index.php" method="get" class="back-to-login-form">
                <button type="submit" class="back-to-login">Back to Login</button>
            </form>
        </div>

        <div id="doctor-fields" style="display: none;">
            <form action="doctorSignUp.php" method="post">
                <label for="name">Name:</label>
                <input type="text" name="name" id="dName" required>

                <label for="age">Age:</label>
                <input type="number" name="age" id="dAge" min="0" required>

                <label for="gender">Gender:</label>
                <select name="gender" id="dGender" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>

                <label for="reg">Registration Number:</label>
                <input type="text" name="reg" id="dRegNumber" required>

                <label for="field">Field of Specialization:</label>
                <select name="field" id="dCategory" required>
                    <option value="Cardiology">Cardiology</option>
                    <option value="Dermatology">Dermatology</option>
                    <option value="Neurology">Neurology</option>
                    <option value="Pediatrics">Pediatrics</option>
                    <option value="Psychiatry">Psychiatry</option>
                    <option value="Radiology">Radiology</option>
                    <option value="Surgery">Surgery</option>
                    <option value="Orthopedics">Orthopedics</option>
                    <option value="Gynecology">Gynecology</option>
                    <option value="Ophthalmology">Ophthalmology</option>
                </select>

                <label for="email">Email:</label>
                <input type="email" name="email" id="dEmail" required>

                <label for="password">Password:</label>
                <input type="password" name="password" id="dPass" required>

                <label for="confirm-password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="dConfirmPass" required>

                <button type="submit">Sign Up</button>
            </form>

            <!-- Back to Login Button -->
            <form action="index.php" method="get" class="back-to-login-form">
                <button type="submit" class="back-to-login">Back to Login</button>
            </form>
        </div>

        <div id="pharmacy-fields" style="display: none;">
            <form action="pharmacySignUp.php" method="post">
                <label for="pharmaName">Pharmacy Name:</label>
                <input type="text" name="pharmaName" id="pharmaName" required>

                <label for="pharmaRegNum">Pharmacy Registration Number:</label>
                <input type="text" name="pharmaRegNum" id="pharmaRegNum" required>

                <label for="pharmEmail">Email:</label>
                <input type="email" name="pharmEmail" id="pharmEmail" required>

                <label for="paddress">Address:</label>
                <input type="text" name="paddress" id="paddress" required>

                <label for="pharmaPass">Password:</label>
                <input type="password" name="pharmaPass" id="pharmaPass" required>

                <label for="pharmaConfirmPass">Confirm Password:</label>
                <input type="password" name="pharmaConfirmPass" id="pharmaConfirmPass" required>

                <button type="submit">Sign Up</button>
            </form>
            <!-- Back to Login Button -->
            <form action="index.php" method="get" class="back-to-login-form">
                <button type="submit" class="back-to-login">Back to Login</button>
            </form>
        </div>
    </div>

    <script src="validation.js" defer></script>
    <!-- deffer ensures html parsing before js -->
</body>

</html>
