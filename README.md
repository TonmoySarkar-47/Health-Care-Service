# Health Care Service

## Overview

**Health Care Service** is a comprehensive healthcare management website built using PHP, HTML, CSS, and JavaScript on the XAMPP server. It tackles key challenges in Bangladesh’s healthcare system by connecting doctors, patients, and pharmacies on a centralized platform to ensure authorized medicine distribution, proper prescription management, and enhanced patient compliance.

## Problem Statement

- **Unauthorized Medicine Sales:** Unregulated pharmacies sell medicines without valid prescriptions.
- **Misuse of Antibiotics:** Patients often consume antibiotics without proper supervision, leading to antibiotic resistance.
- **Lack of Compliance:** Patients frequently fail to follow prescribed medications and dosages.
- **Incomplete Medical History:** Doctors lack access to complete patient records, hindering effective treatment.

## Proposed Solution

The system provides a centralized platform with real-time record keeping, offering the following:

- **Registration & Verification:**  
  - **Doctor:** Register using a unique doctor registration ID.  
  - **Patient:** Register using National ID (NID) or a birth certificate, with checks for authenticity and uniqueness.  
  - **Pharmacy:** Register with a valid pharmacy registration ID to ensure only authorized pharmacies participate.

- **Medical History Tracking:**  
  - Maintain comprehensive patient records accessible by doctors for accurate diagnosis and treatment.

- **Prescription Management:**  
  - Doctors can write and store prescriptions.
  - Pharmacies can retrieve and update prescription details, including dispensed dosages and billing.

- **Patient Monitoring:**  
  - Patients can view their medical histories.
  - Doctors can monitor patient compliance through pharmacy updates.

- **Unauthorized Pharmacy Prevention:**  
  - Only registered pharmacies have access, ensuring proper medicine distribution.

## Key Features

- **User Roles:**  
  - **Doctor:** Access profile management, search patients, and write prescriptions.
  - **Patient:** View personal medical history and prescription details.
  - **Pharmacy:** Retrieve prescriptions, record dispensed medicines, and view transaction history.
  - **Admin:** Manage (view and delete) records of doctors, patients, and pharmacies.

- **Dynamic Sign-Up:**  
  - Role-specific registration forms with real-time validation.
  
- **Dashboard Interfaces:**  
  - Customized dashboards for doctors, patients, and pharmacies to streamline their respective tasks.

## Technologies Used

- **Server Environment:** XAMPP (Apache, MySQL, PHP)
- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Databases:**  
  - `healthCareService`  
  - `patientDB`  
  - `prescriptionDB`

## Installation & Setup

1. **Prerequisites:**  
   - XAMPP installed on your machine.
   - Basic knowledge of PHP and MySQL.

2. **Installation Steps:**  
   - Clone the repository:
     ```bash
     git clone https://github.com/Kutubuddin-Rasel/Health-Care-Service.git
     ```
   - Place the project folder in the `htdocs` directory of your XAMPP installation.
   - Import the provided SQL scripts to set up the `healthCareService`, `patientDB`, and `prescriptionDB` databases.
   - Start Apache and MySQL via the XAMPP control panel.
   - Access the website at `http://localhost/health-care-service`.

## Contributions & Feedback

Contributions are welcome! Feel free to fork the repository and submit pull requests for improvements or bug fixes. For questions or suggestions, please use the issue tracker.
