<?php
session_start();
require 'src/database.php'; // Assuming you have a database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user_email'];

// First, let's check the structure of the bookings table
$table_info_sql = "DESCRIBE bookings";
$table_info_result = $conn->query($table_info_sql);

if ($table_info_result) {
    $columns = $table_info_result->fetch_all(MYSQLI_ASSOC);
    $user_column = '';
    foreach ($columns as $column) {
        if (strpos(strtolower($column['Field']), 'user') !== false || 
            strpos(strtolower($column['Field']), 'email') !== false) {
            $user_column = $column['Field'];
            break;
        }
    }
} else {
    die("Error fetching table structure: " . $conn->error);
}

if (!$user_column) {
    die("Could not find a suitable user identifier column in the bookings table.");
}

// Fetch bookings for the logged-in user
$sql = "SELECT * FROM bookings WHERE $user_column = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $user_email);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bookings - POTATO AGENCY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php include('inc/header.php'); ?>

    <div class="container mt-5">
        <h2>Your Bookings</h2>
        
        <?php if (empty($bookings)): ?>
            <p>You have no bookings at the moment.</p>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <?php
                        // Dynamically generate table headers based on available columns
                        foreach ($bookings[0] as $key => $value) {
                            if ($key != 'id' && $key != $user_column) {
                                echo "<th>" . ucfirst(str_replace('_', ' ', $key)) . "</th>";
                            }
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['id']); ?></td>
                            <?php
                            foreach ($booking as $key => $value) {
                                if ($key != 'id' && $key != $user_column) {
                                    echo "<td>" . htmlspecialchars($value) . "</td>";
                                }
                            }
                            ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <?php include('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>