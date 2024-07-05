<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kota Kinabalu Tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/location.css">
</head>

<body>
    <?php include('inc/header.php'); ?>
    <div class="main-content">
        <div class="page">Kota Kinabalu Tour</div>
        <section class="product-container">
    <div class="img-card">
        <img src="malaysia/Kotakinabalu.jpg" alt="Kota Kinabalu" id="mainImage">
        <div class="small-card">
            <img src="malaysia/kkcity1.jpg" alt="Kota Kinabalu 1" onclick="changeImage('malaysia/kkcity1.jpg')">
            <img src="malaysia/kkcity2.jpg" alt="Kota Kinabalu 2" onclick="changeImage('malaysia/kkcity2.jpg')">
            <img src="malaysia/kkcity3.jpg" alt="Kota Kinabalu 3" onclick="changeImage('malaysia/kkcity3.jpg')">
        </div>
    </div>
    <div class="product-info">
        <!-- Product info content remains the same -->
    </div>


        <div class="product-info">
            <h2>Kota Kinabalu Sabah</h2>
            <p class="price">RM3000 per person</p>
            <p class="description">Experience the beauty of Kota Kinabalu with our guided city tour. Visit iconic landmarks, enjoy local cuisine, and immerse yourself in the rich culture of Sabah.</p>
            <ul>
                <li>Duration: 3Days2Night</li>
                <li>Includes: Transportation, Guide, Food</li>
                <li>Highlights: City Mosque, Signal Hill Observatory, Sabah Museum</li>
            </ul>
            <div class="pax-selection">
                <label for="pax">Number of Pax:</label>
                <select id="pax" name="pax">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
            <button id="addToCart" class="btn btn-primary">Add to Cart</button>
        </div>
    </section>

    <?php include('inc/footer.php'); ?>
    <script src="js/location.js"></script>
</body>

</html>