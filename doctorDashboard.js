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
            window.location.href = "doctorDashboard.php";
        }
        else {
            alert("Error: " + responseText);  // Show the response if not success
        }
    };
    xhr.send(formData); // Send the FormData
};