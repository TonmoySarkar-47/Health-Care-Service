const loginForm = document.getElementById('login-form');
loginForm.addEventListener('submit', (e) => {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
   // const role = document.getElementById('role').value;

    if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
        alert('Invalid email format');
        e.preventDefault();
    } 
    // else if (!role) {
    //     alert('Please select a role');
    //     e.preventDefault();
    // }
});

function showSignupFields() {
    const role = document.getElementById('role').value;
    document.getElementById('patient-fields').style.display = 'none';
    document.getElementById('doctor-fields').style.display = 'none';
    document.getElementById('pharmacy-fields').style.display = 'none';

    if (role == 'patient') {
        document.getElementById('patient-fields').style.display = 'block';
    } else if (role == 'doctor') {
        document.getElementById('doctor-fields').style.display = 'block';
    } else if (role == 'pharmacy') {
        document.getElementById('pharmacy-fields').style.display = 'block';
    }
}
