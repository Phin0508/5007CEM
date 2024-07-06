<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Check if the user has just completed a purchase
if (!isset($_SESSION['purchase_completed'])) {
    header('Location: http://localhost/5007CEM/mainpage.php');
    exit();
}

// Clear the purchase_completed flag
unset($_SESSION['purchase_completed']);

// Set a timer to redirect after 5 seconds
header("refresh:5;url=http://localhost/5007CEM/mainpage.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .thank-you-container {
            text-align: center;
            padding: 2rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="thank-you-container">
        <h1 class="mb-4">Thank You for Planning Your Trip with Us!</h1>
        <p class="lead">Your purchase has been completed successfully.</p>
        <p>You will be redirected to the main page in 5 seconds...</p>
        <a href="http://localhost/5007CEM/mainpage.php" class="btn btn-primary mt-3">Return to Main Page Now</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>