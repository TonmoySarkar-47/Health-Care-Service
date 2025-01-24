let drugCount = 0;

// Add Test Function
function addTest() {
    const testInput = document.getElementById('testInput');
    const testName = testInput.value.trim();
    if (testName) {
        const li = document.createElement('li');
        li.textContent = testName;
        document.getElementById('testList').appendChild(li);
        testInput.value = '';
    } else {
        alert('Please enter a test name');
    }
}

// Add Drug Function
function addDrug() {
    const drug = document.getElementById('drug').value;
    const strength = document.getElementById('strength').value;
    const quantity = document.getElementById('quantity').value.trim();
    const frequency = document.getElementById('frequency').value.trim();
    const remarks = document.getElementById('remarks').value.trim();

    if (drug && strength && quantity && frequency) {
        drugCount++;
        const table = document.getElementById('prescriptionTable').querySelector('tbody');// querySelector use for select a single element given CSS selector (tbody)
        const row = table.insertRow();
        row.innerHTML = `
                    <td>${drugCount}</td>
                    <td>${drug}</td>
                    <td>${strength}</td>
                    <td>${quantity}</td>
                    <td>${frequency}</td>
                    <td>${remarks}</td>
                `;
    } else {
        alert('Please fill all fields before adding a drug');
    }
}

function updateTimestamp() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    // MySQL DATETIME format: YYYY-MM-DD HH:MM:SS
    const timestamp = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    document.getElementById('timestamp').textContent = timestamp;
    document.getElementById('hiddenTimestamp').value = timestamp; // Update hidden input
}

// Update Timestamp Every Second
setInterval(updateTimestamp, 1000);
document.querySelector('form').addEventListener('submit', function () {
    const tests = Array.from(document.getElementById('testList').children).map(li => li.textContent);// array.form convert html collection to javascript array 
    document.getElementById('hiddenTestList').value = JSON.stringify(tests);// Stringify converts the tests array into a JSON string.

    const prescriptions = [];
    const rows = document.getElementById('prescriptionTable').querySelector('tbody').rows;
    for (const row of rows) {
        const cells = row.cells;
        prescriptions.push({ // adds an object to the prescriptions array
            drug: cells[1].textContent,
            strength: cells[2].textContent,
            quantity: cells[3].textContent,
            frequency: cells[4].textContent,
            remarks: cells[5].textContent
        });
    }
    document.getElementById('hiddenPrescriptionList').value = JSON.stringify(prescriptions);
});