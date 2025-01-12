<?php
session_start();
require_once '../php/includes/db.php'; // Include your DB connection
require_once '../models/Flight.php'; // Assuming you have a Flight model file

$conn = connectToDB();
$flightModel = new Flight($conn);

// Check if the user is logged in and is of type 'company'
if (!isset($_SESSION['id']) || $_SESSION['type'] !== 'company') {
    header("Location: login.php");
    exit();
}

// Check if the flight ID is provided
if (!isset($_GET['flight_id'])) {
    echo "<script>alert('No flight selected.'); window.location.href = 'company_home.php';</script>";
    exit();
}

$flightId = $_GET['flight_id'];

// Fetch flight details
$flight = $flightModel->getFlightDetails($flightId);

// Fetch pending passengers
$pendingPassengers = $flightModel->getPendingPassengers($flightId);

// Fetch registered passengers
$registeredPassengers = $flightModel->getRegisteredPassengers($flightId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Details</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/flight_details_comp.css">
</head>
<body>
<?php
// Assuming $flight contains the selected flight details as fetched earlier
if ($flight):
    // Handle empty or null transit value
    $transit = isset($flight['transit']) && !empty($flight['transit']) ? json_decode($flight['transit'], true) : [];
    $transitCities = is_array($transit) && !empty($transit) ? implode(', ', $transit) : 'No Transit';
    ?>
    <div class="container mt-4">
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Flight: <?= htmlspecialchars($flight['name']) ?></h5>
                <p class="card-text"><strong>ID:</strong> <?= htmlspecialchars($flight['id']) ?></p>
                <p class="card-text"><strong>Source:</strong> <?= htmlspecialchars($flight['source']) ?></p>
                <p class="card-text"><strong>Destination:</strong> <?= htmlspecialchars($flight['destination']) ?></p>
                <strong>Transit Cities:</strong>
                <p class="card-text transit-column" data-raw="<?= htmlspecialchars($flight['transit']) ?>">
                    <?= htmlspecialchars($transitCities) ?>
                </p>
                <p class="card-text"><strong>Destination:</strong> <?= htmlspecialchars($flight['destination']) ?></p>
                <p class="card-text"><strong>Start Time:</strong> <?= htmlspecialchars($flight['start_datetime']) ?></p>
                <p class="card-text"><strong>End Time:</strong> <?= htmlspecialchars($flight['end_datetime']) ?></p>

                <!-- Pending Passengers List -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Pending Passengers</h4>
                    </div>
                    <div class="card-body">
                        <?php if (empty($pendingPassengers)): ?>
                            <p>No pending passengers.</p>
                        <?php else: ?>
                            <ul class="list-group">
                                <?php foreach ($pendingPassengers as $passenger): ?>
                                    <li class="list-group-item">
                                        <?= htmlspecialchars($passenger['name']) ?> (ID: <?= htmlspecialchars($passenger['id']) ?>)
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Registered Passengers List -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Registered Passengers</h4>
                    </div>
                    <div class="card-body">
                        <?php if (empty($registeredPassengers)): ?>
                            <p>No registered passengers.</p>
                        <?php else: ?>
                            <ul class="list-group">
                                <?php foreach ($registeredPassengers as $passenger): ?>
                                    <li class="list-group-item">
                                        <?= htmlspecialchars($passenger['name']) ?> (ID: <?= htmlspecialchars($passenger['id']) ?>)
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <a href="company_home.php" class="btn btn-secondary">Back to Home</a>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="container mt-4">
        <p>No flight details available.</p>
        <a href="company_home.php" class="btn btn-secondary">Back to Home</a>
    </div>
<?php endif; ?>

<script>
    // Function to sanitize transit values
    function sanitizeTransit(transit) {
        if (!transit) return 'No Transit'; // Handle empty or null values

        // Remove unwanted characters and return the cleaned string
        return transit.replace(/[\\"[\]]/g, '').trim();
    }

    // Apply the function to sanitize the transit column dynamically
    document.addEventListener('DOMContentLoaded', () => {
        const transitElement = document.querySelector('.transit-column');
        if (transitElement) {
            const rawValue = transitElement.getAttribute('data-raw');
            transitElement.textContent = sanitizeTransit(rawValue);
        }
    });
</script>
</body>
</html>
