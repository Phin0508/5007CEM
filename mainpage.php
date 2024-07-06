<?php
session_start();
require 'src/database.php'; // Assuming you have a database connection file

// Fetch all locations
$sql = "SELECT * FROM locations ORDER BY id DESC";
$result = $conn->query($sql);
$locations = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mainpage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/mainpage.css">
</head>

<body>
    <?php include('inc/header.php'); ?>

    <div class="header">
        <h1>POTATO AGENCY</h1>
        <p>Book your trip right now!</p>
    </div>

    <!-- Search Form -->
    <form class="example" action="/action_page.php">
        <input type="text" placeholder="Search.." name="search2">
        <button type="submit"><i class="fa fa-search"></i></button>
    </form>

    <!-- Location Cards -->
    <div class="row">
        <?php foreach ($locations as $location): ?>
            <div class="column">
                <img src="<?php echo htmlspecialchars($location['image_path']); ?>" alt="<?php echo htmlspecialchars($location['name']); ?>" style="width:100%">
                <h2><?php echo htmlspecialchars($location['name']); ?></h2>
                <a href="location.php?name=<?php echo urlencode($location['name']); ?>" class="btn btn-primary">View More</a>
            </div>
        <?php endforeach; ?>
    </div>

    <?php include('inc/footer.php'); ?>

</body>
</html>
