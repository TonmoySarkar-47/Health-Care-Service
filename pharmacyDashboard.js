// Show/Hide Profile Modals
function showProfile() {
    document.getElementById("profileModal").style.display = "block";
    document.getElementById("overlay").style.display = "block";
}

function hideProfile() {
    document.getElementById("profileModal").style.display = "none";
    document.getElementById("overlay").style.display = "none";
}

function showUpdateProfile() {
    document.getElementById("updateProfileModal").style.display = "block";
    document.getElementById("overlay").style.display = "block";
}

function hideUpdateProfile() {
    document.getElementById("updateProfileModal").style.display = "none";
    document.getElementById("overlay").style.display = "none";
}

// Handle Update Profile form submission
document.getElementById("updateProfileForm").onsubmit = function (event) {
    event.preventDefault(); // Prevent form submission

    var name = document.getElementById("updateName").value;
    var address = document.getElementById("updateAddress").value;
    var email = document.getElementById("updateEmail").value;
    var password = document.getElementById("updatePassword").value;
    var confirmPassword = document.getElementById("confirmPassword").value;

    // Validate if passwords match
    if (password != confirmPassword) {
        alert("Passwords do not match.");
        return;
    }

    // Simplified data creation using FormData
    var formData = new FormData();
    formData.append("action", "saveChanges");
    formData.append("name", name);
    formData.append("address", address);
    formData.append("email", email);
    formData.append("password", password);
    formData.append("confirm_password", confirmPassword);

    // Create the AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "", true);
    xhr.onload = function () {
        var responseText = xhr.responseText;

        if (xhr.responseText == "Profile updated successfully.") {
            alert(xhr.responseText); // Display the response
            hideUpdateProfile();  // Close modal after successful update
            window.location.href = "pharmacyDashboard.php";
        }
        else {
            alert("Error: " + responseText);  // Show the response if not success
        }
    };
    xhr.send(formData); // Send the FormData
};

document.getElementById("submitSearchBtn").onclick = function (event) {
    document.getElementById("transactionResults").style.display = "none";
    document.getElementById("searchResults").style.display = "block";

    event.preventDefault(); // Prevent the default button behavior

    var prescriptionID = document.getElementById("prescriptionID").value;

    // Validate Prescription ID
    if (!prescriptionID || isNaN(prescriptionID)) {
        alert("Please enter a valid Prescription ID");
        return;
    }

    // Simplified data creation using FormData
    var formData = new FormData();
    formData.append("action", "search");
    formData.append("prescriptionID", prescriptionID);

    // Create the AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "", true);
    xhr.onload = function () {
        var response = xhr.responseText.trim();

        // Check if prescription was found
        if (response.startsWith("<form")) {
            document.getElementById("searchResults").innerHTML = response;

            // Handle the submit of the updatePrescription form
            document.getElementById("updatePrescriptionBtn").onclick = function (event) {
                event.preventDefault(); // Prevent the default button behavior

                // Prepare the form data using FormData
                var formData = new FormData(document.getElementById('updatePrescriptionForm'));
                formData.append("action", "updatePrescription");

                // Create the AJAX request
                var xhr2 = new XMLHttpRequest();
                xhr2.open("POST", "", true);
                xhr2.onload = function () {
                    alert(xhr2.responseText); // Display the response
                    window.location.href = 'pharmacyDashboard.php'; // Redirect to dashboard
                };
                xhr2.send(formData); // Send the FormData
            };
        } else {
            // Display an error message
            alert("Prescription not found.");
            window.location.href = 'pharmacyDashboard.php'; // Redirect to dashboard
        }
    };
    xhr.send(formData); // Send the FormData for the search
};

function showTransactionHistory() {
    document.getElementById("searchResults").style.display = "none";
    document.getElementById("transactionResults").style.display = "block";

    var xhr = new XMLHttpRequest();
    xhr.open("POST", " ", true);

    // Create a FormData object and append the action parameter
    var formData = new FormData();
    formData.append("action", "getTransactionHistory");

    xhr.onload = function () {
        if (xhr.status == 200) {
            document.getElementById("transactionResults").innerHTML = xhr.responseText;
        } else {
            console.error('Error fetching transaction history');
        }
    };

    // Send the FormData object
    xhr.send(formData);
}

