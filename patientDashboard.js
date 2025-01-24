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
    var age = document.getElementById("updateAge").value;
    var email = document.getElementById("updateEmail").value;
    var password = document.getElementById("updatePassword").value;
    var confirmPassword = document.getElementById("confirmPassword").value;

    if (password != confirmPassword) {
        alert("Passwords do not match.");
        return;
    }

    // Simplified data creation using FormData
    var formData = new FormData();
    formData.append("action", "saveChanges");
    formData.append("name", name);
    formData.append("age", age);
    formData.append("email", email);
    formData.append("password", password);
    formData.append("confirm_password", confirmPassword);

    // Create the AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "", true);
    xhr.onload = function () {
        var responseText = xhr.responseText;

        if (responseText == "Profile updated successfully.") {
            alert(responseText);
            hideUpdateProfile();
            window.location.href = "patientDashboard.php";
        } else {
            alert("Error: " + responseText);
        }
    };
    xhr.onerror = function () {
        alert("An error occurred while updating the profile. Please try again.");
    };
    xhr.send(formData); // Send the FormData
};


function fetchMedicalHistory() {
    const historyResult = document.getElementById('historyResult');
    const medicalHistoryDiv = document.querySelector('.scrollable');

    // Toggle visibility of the medical history div
    medicalHistoryDiv.style.display = (medicalHistoryDiv.style.display == "none" || medicalHistoryDiv.style.display == "") ? "block" : "none";

    // Create FormData object
    const formData = new FormData();
    formData.append("action", "fetchMedicalHistory");

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "", true);

    xhr.onload = function () {
        var responseText = xhr.responseText;
        if (responseText.startsWith("No")) {
            alert(xhr.responseText);
        } else {
            historyResult.innerHTML = responseText;
        }
    };

    xhr.onerror = function () {
        alert("An error occurred while making the request.");
    };

    xhr.send(formData);
}

