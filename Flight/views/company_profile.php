<?php
session_start();
include_once '../php/includes/db.php';
$conn = connectToDB();

// Assuming your DB class has the updateCompanyProfile method
include_once '../models/CompanyModel.php';
$companyModel = new CompanyModel($conn);

// Check if the user is logged in and is a company admin
if (!isset($_SESSION['id']) || $_SESSION['type'] != 'company') {
    header("Location: login.php");
    exit();
}

// Fetch company data
$companyId = $_SESSION['id'];
$stmt = $conn->prepare("SELECT name, bio, address, logo_img FROM company WHERE id = ?");
$stmt->execute([$companyId]);
$company = $stmt->fetch();

// Fetch flights for the company
$stmt = $conn->prepare("SELECT id, name, source, destination, start_datetime, end_datetime FROM flights WHERE company_id = ?");
$stmt->execute([$companyId]);
$flights = $stmt->fetchAll();

// Logout logic
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Update company info logic
if (isset($_FILES['logo_img']) && $_FILES['logo_img']['error'] == 0) {
    // Get the original filename
    $logoPath = basename($_FILES['logo_img']['name']);

    // Optionally sanitize the filename to prevent any issues
    $logoPath = preg_replace("/[^a-zA-Z0-9\-_\.]/", "", $logoPath); // Allow only safe characters

    $targetDirectory = "../images/"; // Assuming the images are stored in this folder

    // Ensure the images directory exists and is writable
    if (move_uploaded_file($_FILES['logo_img']['tmp_name'], $targetDirectory . $logoPath)) {
        // Image uploaded successfully, save the filename in the database
    } else {
        $error = "Failed to upload logo image.";
    }
} else {
    $logoPath = $company['logo_img']; // Keep the current logo if no new one is uploaded
}

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $bio = $_POST['bio'];
    $address = $_POST['address'];

    // Save only the filename in the database, not the full path
    $companyModel->updateCompanyProfile($companyId, $name, $bio, $address, $logoPath);

    // Redirect to the same page to refresh
    header("Location: company_profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/company_profile.css"> <!-- Add a separate CSS file -->

</head>

<body>

<!-- Include the navbar -->
<div class="header">
    <div class="container d-flex justify-content-between">
        <div class="d-flex align-items-center">
            <?php if ($company && isset($company['logo_img'])): ?>
                <img src="../images/<?= htmlspecialchars($company['logo_img']) ?>" alt="Company Logo" class="company-logo mr-3">
            <?php endif; ?>
            <h1><?= htmlspecialchars($company['name']) ?></h1>
        </div>
        <div>
            <a href="company_home.php" class="btn btn-info">Home <i class="fas fa-home"></i></a>
            <a href="?logout=true" class="btn-logout">Logout <i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <h2>Company Bio:</h2>
            <p><?= htmlspecialchars($company['bio']) ?></p>
        </div>
        <div class="col-md-6">
            <h2>Company Address:</h2>
            <p><?= htmlspecialchars($company['address']) ?></p>
        </div>
    </div>

<div class="container mt-5">
    <h2>Update Company Information</h2>
    <div class="form-container">
        <form action="company_profile.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Company Name</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($company['name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="bio">Company Bio</label>
                <textarea id="bio" name="bio" rows="4" required><?= htmlspecialchars($company['bio']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="address">Company Address</label>
                <input type="text" id="address" name="address" value="<?= htmlspecialchars($company['address']) ?>" required>
            </div>
            <div class="form-group">
                <label for="logo_img">Company Logo</label>
                <input type="file" id="logo_img" name="logo_img">
            </div>
            <button type="submit" name="update" class="btn-update">Update Information</button>
        </form>
        <?php if (isset($error)): ?>
            <p class="text-danger"><?= $error ?></p>
        <?php endif; ?>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
