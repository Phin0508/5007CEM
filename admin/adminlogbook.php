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

// Handle comment deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment'])) {
    $comment_id = $_POST['comment_id'];
    $deleteSql = "DELETE FROM cus_share WHERE cid = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param('i', $comment_id);
    if ($deleteStmt->execute()) {
        $deleteMessage = "Comment deleted successfully!";
    } else {
        $deleteError = "Error deleting comment: " . $conn->error;
    }
    $deleteStmt->close();
}

// Handle location addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_location'])) {
    $name = $_POST['name'];
    $image_path = $_POST['image_path'];
    $description = $_POST['description'];
    $trip_price = $_POST['trip_price'];
    $view_more_link = $_POST['view_more_link'];
    $created_at = date('Y-m-d H:i:s');
    $updated_at = date('Y-m-d H:i:s');

    $insertSql = "INSERT INTO locations (name, image_path, description, itineraries_count, trip_price, view_more_link, created_at, updated_at)
                  VALUES (?, ?, ?, 0, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param('sssssss', $name, $image_path, $description, $trip_price, $view_more_link, $created_at, $updated_at);
    if ($insertStmt->execute()) {
        $addMessage = "Location added successfully!";
    } else {
        $addError = "Error adding location: " . $conn->error;
    }
    $insertStmt->close();
}
// Handle location deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_location'])) {
    $location_id = $_POST['location_id'];
    $deleteLocationSql = "DELETE FROM locations WHERE id = ?";
    $deleteLocationStmt = $conn->prepare($deleteLocationSql);
    $deleteLocationStmt->bind_param('i', $location_id);
    if ($deleteLocationStmt->execute()) {
        $locationDeleteMessage = "Location deleted successfully!";
    } else {
        $locationDeleteError = "Error deleting location: " . $conn->error;
    }
    $deleteLocationStmt->close();
}
// Handle booking deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_booking'])) {
    $booking_id = $_POST['booking_id'];
    $deleteBookingSql = "DELETE FROM bookings WHERE id = ?";
    $deleteBookingStmt = $conn->prepare($deleteBookingSql);
    $deleteBookingStmt->bind_param('i', $booking_id);
    if ($deleteBookingStmt->execute()) {
        $bookingDeleteMessage = "Booking deleted successfully!";
    } else {
        $bookingDeleteError = "Error deleting booking: " . $conn->error;
    }
    $deleteBookingStmt->close();
}


// Fetch all locations
$locationSql = "SELECT * FROM locations ORDER BY id DESC";
$locationResult = $conn->query($locationSql);
$locations = $locationResult->fetch_all(MYSQLI_ASSOC);


// Fetch all bookings
$sql = "SELECT * FROM bookings ORDER BY id DESC";
$result = $conn->query($sql);
$bookings = $result->fetch_all(MYSQLI_ASSOC);

// Fetch all comments
$commentSql = "SELECT cs.cid, cs.cus_username, cs.com, cs.created_at FROM cus_share cs ORDER BY cs.created_at DESC";
$commentResult = $conn->query($commentSql);
$comments = $commentResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - POTATO AGENCY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="admin/adminlogbook.css">
    <style>
        .admin-section {
            margin-bottom: 30px;
        }

        /* admin-style.css */

        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
        }

        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .1);
            padding: 30px;
            margin-top: 30px;
        }

        h1 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .admin-section {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .admin-section h2 {
            color: #007bff;
            margin-bottom: 20px;
        }

        .table {
            background-color: #ffffff;
        }

        .table thead {
            background-color: #007bff;
            color: #ffffff;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        .alert {
            border-radius: 8px;
        }

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

        <?php if (isset($addMessage)) : ?>
            <div class="alert alert-success"><?php echo $addMessage; ?></div>
        <?php endif; ?>
        <?php if (isset($addError)) : ?>
            <div class="alert alert-danger"><?php echo $addError; ?></div>
        <?php endif; ?>
        <?php if (isset($deleteMessage)) : ?>
            <div class="alert alert-success"><?php echo $deleteMessage; ?></div>
        <?php endif; ?>
        <?php if (isset($deleteError)) : ?>
            <div class="alert alert-danger"><?php echo $deleteError; ?></div>
        <?php endif; ?>
        <?php if (isset($locationDeleteMessage)) : ?>
            <div class="alert alert-success"><?php echo $locationDeleteMessage; ?></div>
        <?php endif; ?>
        <?php if (isset($locationDeleteError)) : ?>
            <div class="alert alert-danger"><?php echo $locationDeleteError; ?></div>
        <?php endif; ?>
        <?php if (isset($bookingDeleteMessage)) : ?>
            <div class="alert alert-success"><?php echo $bookingDeleteMessage; ?></div>
        <?php endif; ?>
        <?php if (isset($bookingDeleteError)) : ?>
            <div class="alert alert-danger"><?php echo $bookingDeleteError; ?></div>
        <?php endif; ?>

        <!-- Form to Add New Location -->
        <div class="admin-section">
            <h2>Add New Location</h2>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="name" class="form-label">Location Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="image_path" class="form-label">Image Path</label>
                    <input type="text" class="form-control" id="image_path" name="image_path" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="trip_price" class="form-label">Trip Price</label>
                    <input type="number" class="form-control" id="trip_price" name="trip_price" required>
                </div>
                <div class="mb-3">
                    <label for="view_more_link" class="form-label">View More Link</label>
                    <input type="text" class="form-control" id="view_more_link" name="view_more_link" required>
                </div>
                <button type="submit" class="btn btn-primary" name="add_location">Add Location</button>
            </form>
        </div>
        <!-- Locations Management -->
        <div class="admin-section">
            <h2>Manage Locations</h2>
            <?php if (empty($locations)) : ?>
                <p>No locations found.</p>
            <?php else : ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Location ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Image Path</th>
                            <th>Trip Price</th>
                            <th>View More Link</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($locations as $location) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($location['id']); ?></td>
                                <td><?php echo htmlspecialchars($location['name']); ?></td>
                                <td><?php echo htmlspecialchars($location['description']); ?></td>
                                <td><?php echo htmlspecialchars($location['image_path']); ?></td>
                                <td><?php echo htmlspecialchars($location['trip_price']); ?></td>
                                <td><?php echo htmlspecialchars($location['view_more_link']); ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="location_id" value="<?php echo $location['id']; ?>">
                                        <button type="submit" name="delete_location" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this location?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>


        <!-- Bookings Table -->
        <div class="admin-section">
            <h2>Bookings</h2>
            <?php if (empty($bookings)) : ?>
                <p>No bookings found.</p>
            <?php else : ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <?php
                            foreach ($bookings[0] as $key => $value) {
                                if ($key != 'id') {
                                    echo "<th>" . ucfirst(str_replace('_', ' ', $key)) . "</th>";
                                }
                            }
                            ?>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['id']); ?></td>
                                <?php
                                foreach ($booking as $key => $value) {
                                    if ($key != 'id') {
                                        echo "<td>" . htmlspecialchars($value) . "</td>";
                                    }
                                }
                                ?>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                        <button type="submit" name="delete_booking" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this booking?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Comments Management -->
        <div class="admin-section">
            <h2>User Comments</h2>
            <?php if (empty($comments)) : ?>
                <p>No comments found.</p>
            <?php else : ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Comment ID</th>
                            <th>Username</th>
                            <th>Comment</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comments as $comment) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($comment['cid']); ?></td>
                                <td><?php echo htmlspecialchars($comment['cus_username']); ?></td>
                                <td><?php echo htmlspecialchars($comment['com']); ?></td>
                                <td><?php echo htmlspecialchars($comment['created_at']); ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="comment_id" value="<?php echo $comment['cid']; ?>">
                                        <button type="submit" name="delete_comment" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this comment?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>