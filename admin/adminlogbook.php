<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in and is an admin
$isAdmin = isset($_SESSION['user_email']) && $_SESSION['user_role'] === 'admin';

// Redirect non-admin users
if (!$isAdmin) {
    header('Location: login.php');
    exit();
}

// Placeholder functions - you'll need to implement these
function updateLocation($newLocation) {
    // Update location in the database
}

function getParticipantList() {
    // Fetch participant list from the database
    return []; // Return an array of participants
}

function getShareComments() {
    // Fetch share comments from the database
    return []; // Return an array of comments
}

// Handle form submission for location update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_location'])) {
    $newLocation = $_POST['new_location'];
    updateLocation($newLocation);
    $updateMessage = "Location updated successfully!";
}

$participants = getParticipantList();
$comments = getShareComments();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - POTATO AGENCY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        /* Add your custom styles here */
        .admin-section {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="http://localhost/5007CEM/mainpage.php">POTATO AGENCY</a>
            <button class="navbar-toggler" type="button" onclick="toggleNavbar()">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost/5007CEM/mainpage.php">Share</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="aboutus.php">About</a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link">Welcome, Admin</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="confirmLogout()">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Admin Panel</h1>

        <div class="admin-section">
            <h2>Update Location</h2>
            <form method="POST">
                <div class="mb-3">
                    <input type="text" class="form-control" name="new_location" placeholder="Enter new location" required>
                </div>
                <button type="submit" name="update_location" class="btn btn-primary">Update Location</button>
            </form>
            <?php if (isset($updateMessage)) : ?>
                <div class="alert alert-success mt-3"><?php echo $updateMessage; ?></div>
            <?php endif; ?>
        </div>

        <div class="admin-section">
            <h2>Participant List</h2>
            <ul class="list-group">
                <?php foreach ($participants as $participant) : ?>
                    <li class="list-group-item"><?php echo htmlspecialchars($participant); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="admin-section">
            <h2>Share Comments</h2>
            <div class="list-group">
                <?php foreach ($comments as $comment) : ?>
                    <div class="list-group-item">
                        <h5 class="mb-1"><?php echo htmlspecialchars($comment['user']); ?></h5>
                        <p class="mb-1"><?php echo htmlspecialchars($comment['text']); ?></p>
                        <small><?php echo htmlspecialchars($comment['date']); ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to logout?")) {
                window.location.href = "logout.php";
            }
        }

        function toggleNavbar() {
            const navbar = document.getElementById("navbarNav");
            navbar.classList.toggle("show");
        }
    </script>
</body>
</html>