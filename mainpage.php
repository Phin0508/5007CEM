<?php
session_start();
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
        <div class="column">
            <img src="malaysia/Sempurna.jpg" alt="Sabah" style="width:100%"> <!-- Add your image here -->
            <h2>Sempurna</h2>
            <p>0 itineraries</p>
            <a href="location.php?name=Sempurna" class="btn btn-primary">View More</a> <!-- Updated link -->
        </div>
        <div class="column">
            <img src="malaysia/Penang.jpg" alt="Penang" style="width:100%"> <!-- Add your image here -->
            <h2>Penang</h2>
            <p>0 itineraries</p>
            <a href="location.php?name=Penang" class="btn btn-primary">View More</a> <!-- Updated link -->
        </div>
        <div class="column">
            <img src="malaysia/Kundasang.webp" alt="Kundasang" style="width:100%"> <!-- Add your image here -->
            <h2>Kundasang</h2>
            <p>0 itineraries</p>
            <a href="location.php?name=Kundasang" class="btn btn-primary">View More</a> <!-- Updated link -->
        </div>
    </div>

    <?php include('inc/footer.php'); ?>

</body>
</html>
