<?php
session_start();
require_once '../src/database.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: adminlogin.php");
    exit();
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: adminlogin.php");
    exit();
}

// Rest of your existing code...

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_booking'])) {
    $bookingId = $_POST['booking_id'];
    $deleteSql = "DELETE FROM bookings WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param('i', $bookingId);
    if ($deleteStmt->execute()) {
        $deleteMessage = "Booking deleted successfully!";
    } else {
        $deleteError = "Error deleting booking: " . $conn->error;
    }
    $deleteStmt->close();
}

// Fetch all bookings
$sql = "SELECT * FROM bookings ORDER BY id DESC";
$result = $conn->query($sql);
$bookings = $result->fetch_all(MYSQLI_ASSOC);

// Your existing functions...

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
        .admin-section {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../mainpage.php">POTATO AGENCY</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link">Welcome, <?php echo htmlspecialchars($_SESSION['user_email']); ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?logout=1">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Admin Panel</h1>

        <!-- Your existing admin panel content... -->

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>