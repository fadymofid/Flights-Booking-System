<?php
// Include the necessary files
session_start();
include_once '../php/includes/db.php';
$conn = connectToDB();

// Assuming your DB class has the updateCompanyProfile method
include_once '../models/Flight.php';
// Initialize the flight object
$flight = new Flight();

// Initialize variables for feedback
$successMessage = '';
$errorMessage = '';
$companyId = $_SESSION['id'];
$stmt = $conn->prepare("SELECT name, bio, address, logo_img FROM company WHERE id = ?");
$stmt->execute([$companyId]);
$company = $stmt->fetch();

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $name = $_POST['name'];
    $source = $_POST['source'];
    $destination = $_POST['destination'];
    $transit = isset($_POST['transit']) ? $_POST['transit'] : []; // Transit is optional
    $fees = $_POST['fees'];
    $passengerLimit = $_POST['passenger_limit'];
    $start_datetime = $_POST['start_datetime'];
    $end_datetime = $_POST['end_datetime'];
// Logout logic
    if (!isset($_SESSION['company_id'])) {
        $errorMessage = "You must be logged in to add a flight.";
    } else {
        $companyId = $_SESSION['company_id']; // Get company_id from the session

        // Input validation
        if (empty($name) || empty($source) || empty($destination) || empty($fees) || empty($passengerLimit) || empty($start_datetime) || empty($end_datetime)) {
            $errorMessage = "All fields are required!";
        } else {
            // Call the addFlight method to insert the new flight
            try {
                $flightId = $flight->addFlight($name, $source, $destination, json_encode($transit), $fees, $passengerLimit, $start_datetime, $end_datetime, $companyId);
                $successMessage = "Flight added successfully! Flight ID: " . $flightId;
            } catch (Exception $e) {
                $errorMessage = "Error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Flight</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- For icons -->
    <link rel="stylesheet" href="../css/add_flight.css">
</head>
<body>

<div class="header">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <?php if ($company && isset($company['logo_img'])): ?>
                <img src="../images/<?= htmlspecialchars($company['logo_img']) ?>" alt="Company Logo" class="company-logo">
            <?php endif; ?>
            <h1><?= htmlspecialchars($company['name']) ?></h1>
        </div>
        <div>
            <a href="company_home.php" class="btn btn-info">Home <i class="fas fa-home"></i></a>
            <a href="?logout=true" class="btn-logout">Logout <i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>
</div>

<div class="container">
    <h1 class="my-4">Add New Flight</h1>

    <!-- Display success or error message -->
    <?php if ($successMessage): ?>
        <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
    <?php elseif ($errorMessage): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <!-- Flight Add Form -->
    <form action="add_flight.php" method="POST" class="form-container">
        <div class="mb-3">
            <label for="name" class="form-label">Flight Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="source" class="form-label">Source</label>
            <input type="text" class="form-control" id="source" name="source" required>
        </div>
        <div class="mb-3">
            <label for="destination" class="form-label">Destination</label>
            <input type="text" class="form-control" id="destination" name="destination" required>
        </div>
        <div class="mb-3">
            <label for="transit" class="form-label">Transit Cities</label>
            <div id="transit-container">
                <input type="text" class="form-control transit-input" name="transit[]" placeholder="Enter a city" required>
            </div>
            <button type="button" id="add-transit" class="btn btn-outline-secondary mt-2">+ Add Another Transit</button>
        </div>
        <div class="mb-3">
            <label for="fees" class="form-label">Fees</label>
            <input type="number" class="form-control" id="fees" name="fees" required>
        </div>
        <div class="mb-3">
            <label for="passenger_limit" class="form-label">Passenger Limit</label>
            <input type="number" class="form-control" id="passenger_limit" name="passenger_limit" required>
        </div>
        <div class="mb-3">
            <label for="start_datetime" class="form-label">Start Date & Time</label>
            <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime" required>
        </div>
        <div class="mb-3">
            <label for="end_datetime" class="form-label">End Date & Time</label>
            <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Flight</button>
    </form>
</div>

<script>
    // Add new transit city input field when the "+" button is clicked
    // Add new transit city input field when the "+" button is clicked
    document.getElementById('add-transit').addEventListener('click', function() {
        const transitContainer = document.getElementById('transit-container');
        const newInput = document.createElement('input');
        newInput.type = 'text';
        newInput.classList.add('form-control', 'transit-input');
        newInput.name = 'transit[]'; // name as an array
        newInput.placeholder = 'Enter a city';
        transitContainer.appendChild(newInput);
    });

    // Collect transit cities into an array before form submission
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const transitInputs = document.querySelectorAll('.transit-input');
        const transitCities = [];

        // Collect each transit city input value
        transitInputs.forEach(function(input) {
            const city = input.value.trim();
            if (city) {
                transitCities.push(city);
            }
        });

        // Make sure the transit cities are properly serialized into a JSON format
        const transitInputHidden = document.createElement('input');
        transitInputHidden.type = 'hidden';
        transitInputHidden.name = 'transit';
        transitInputHidden.value = JSON.stringify(transitCities);

        // Append the hidden input to the form to ensure it's sent
        form.appendChild(transitInputHidden);
    });

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
