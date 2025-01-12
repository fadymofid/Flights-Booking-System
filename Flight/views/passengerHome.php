<?php
session_start();
include_once '../php/includes/db.php';
$conn = connectToDB();

// Check if user is logged in and is a passenger
if (!isset($_SESSION['id']) || $_SESSION['type'] != 'passenger') {
    header("Location: login.php");
    exit();
}

// Fetch passenger data
$userId = $_SESSION['id'];
$stmt = $conn->prepare("SELECT name, email, account_number, photo, passport_img, tel FROM passenger WHERE id = ?");
$stmt->execute([$userId]);
$profile = $stmt->fetch();

// Fetch completed flights
$stmt = $conn->prepare("SELECT flights.name, flights.source, flights.destination, flight_id 
                        FROM passengers_flights 
                        JOIN flights ON flight_id = flights.id 
                        WHERE passenger_id = ? AND flights.end_datetime < CURDATE()");
$stmt->execute([$userId]);
$completedFlights = $stmt->fetchAll();

// Fetch current flights
$stmt = $conn->prepare("SELECT flights.name, flights.source, flights.destination, flight_id 
                        FROM passengers_flights 
                        JOIN flights ON flight_id = flights.id 
                        WHERE passenger_id = ? AND flights.end_datetime >= CURDATE()");
$stmt->execute([$userId]);
$currentFlights = $stmt->fetchAll();

// Search flights functionality
$searchResults = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['from'], $_GET['to'])) {
    $from = $_GET['from'];
    $to = $_GET['to'];
    $stmt = $conn->prepare("SELECT id AS flight_id, name, company_id, source, destination 
                            FROM flights 
                            WHERE source LIKE ? AND destination LIKE ? AND is_completed = 0 AND end_datetime >= CURDATE()");
    $stmt->execute(["%$from%", "%$to%"]);
    $searchResults = $stmt->fetchAll();
}

// Logout logic
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/passenger_home.css">
</head>

<body>
<!-- Navbar -->
<div class="navbar">
    <a href="profile.php" class="profile-btn">Profile</a>
    <a href="?logout=true" class="btn-logout">Logout <i class="fas fa-sign-out-alt"></i></a>
    <a href="notifications.php" class="btn btn-warning"><i class="fas fa-bell"></i></a>

</div>

<div class="container">
    <div class="header">
        <h1>Welcome to your Passenger Dashboard, <?php echo htmlspecialchars($profile['name']); ?>!</h1>
    </div>

    <!-- Profile Section (Row) -->
    <div class="row profile-section">
        <div class="col-md-4">
            <img src="<?php echo '../images/' . htmlspecialchars($profile['photo']); ?>" alt="Profile Image"
                 class="profile-img img-fluid">
        </div>
        <div class="col-md-8">
            <h2><?php echo htmlspecialchars($profile['name']); ?></h2>
            <p>Email: <?php echo htmlspecialchars($profile['email']); ?></p>
            <p>Phone: <?php echo htmlspecialchars($profile['tel']); ?></p>
            <p>Balance <?php echo htmlspecialchars($profile['account_number']);?>   </p>
        </div>
    </div>

    <!-- Completed Flights Section (Row) -->
    <div class="row completed-flights">
        <h3 class="section-title">Completed Flights</h3>
        <?php if (count($completedFlights) > 0): ?>
            <table class="flights-table table table-bordered">
                <thead>
                <tr>
                    <th>Flight No.</th>
                    <th>Source</th>
                    <th>Destination</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($completedFlights as $flight): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($flight['name']); ?></td>
                        <td><?php echo htmlspecialchars($flight['source']); ?></td>
                        <td><?php echo htmlspecialchars($flight['destination']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="placeholder-text">No completed flights found.</p>
        <?php endif; ?>
    </div>

    <!-- Current Flights Section (Row) -->
    <div class="row completed-flights">
        <h3 class="section-title">Current Flights</h3>
        <?php if (count($currentFlights) > 0): ?>
            <table class="flights-table table table-bordered">
                <thead>
                <tr>
                    <th>Flight No.</th>
                    <th>Source</th>
                    <th>Destination</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($currentFlights as $flight): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($flight['name']); ?></td>
                        <td><?php echo htmlspecialchars($flight['source']); ?></td>
                        <td><?php echo htmlspecialchars($flight['destination']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="placeholder-text">No current flights found.</p>
        <?php endif; ?>
    </div>

    <!-- Search Section (Row) -->
    <div class="row search-section">
        <h3 class="section-title">Search Flights</h3>
        <form method="GET">
            <div class="col-md-4">
                <input type="text" name="from" placeholder="From"
                       value="<?php echo isset($_GET['from']) ? htmlspecialchars($_GET['from']) : ''; ?>" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="to" placeholder="To"
                       value="<?php echo isset($_GET['to']) ? htmlspecialchars($_GET['to']) : ''; ?>" required>
            </div>
            <button type="submit" class="btn btn-success col-md-2">Search</button>
        </form>

        <?php if (!empty($searchResults)): ?>
            <h4 class="section-title mt-4">Search Results</h4>
            <table class="flights-table table table-bordered">
                <thead>
                <tr>
                    <th>Flight No.</th>
                    <th>Source</th>
                    <th>Destination</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($searchResults as $flight): ?>
                    <tr onclick="window.location.href='flight_details.php?id=<?php echo $flight['flight_id']; ?>'">
                        <td><?php echo htmlspecialchars($flight['name']); ?></td>
                        <td><?php echo htmlspecialchars($flight['source']); ?></td>
                        <td><?php echo htmlspecialchars($flight['destination']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'GET'): ?>
            <p class="placeholder-text">No flights found for your search criteria.</p>
        <?php endif; ?>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>