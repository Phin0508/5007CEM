<?php
session_start();
require 'src/database.php'; // Assuming you have a database connection file

// Get the location name from the query parameter
$location_name = isset($_GET['name']) ? $_GET['name'] : '';

if ($location_name) {
    // Fetch the location data from the database
    $sql = "SELECT * FROM locations WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $location_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $location = $result->fetch_assoc();
    $stmt->close();
}

// Check if location data is fetched
if (!isset($location)) {
    // Handle the case where the location is not found
    $location = [
        'name' => 'Unknown Location',
        'image_path' => 'path/to/default/image.jpg', // Default image path
        'description' => 'Description not available.',
        'itineraries_count' => 0,
        'trip_price' => 'N/A',
        'view_more_link' => '#',
        'id' => 0 // Default id
    ];
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($location['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/location.css">
</head>

<body>
    <?php include('inc/header.php'); ?>

    <div class="container">
        <h1><?php echo htmlspecialchars($location['name']); ?></h1>
        <img src="<?php echo htmlspecialchars($location['image_path']); ?>" alt="<?php echo htmlspecialchars($location['name']); ?>" class="img-fluid">
        <p><?php echo htmlspecialchars($location['description']); ?></p>
        <p>Number of itineraries: <?php echo htmlspecialchars($location['itineraries_count']); ?></p>
        <p>Trip price: <?php echo htmlspecialchars($location['trip_price']); ?></p>
        <a href="<?php echo htmlspecialchars($location['view_more_link']); ?>" class="btn btn-primary">View More</a>

        <!-- Pax Count and Add to Cart Button -->
        <form id="add-to-cart-form" class="mt-3">
            <div class="mb-3">
                <label for="pax_count" class="form-label">Number of Passengers (Pax):</label>
                <input type="number" class="form-control" id="pax_count" name="pax_count" value="1" min="1" required>
            </div>
            <input type="hidden" name="location_id" value="<?php echo htmlspecialchars($location['id']); ?>">
            <button type="submit" class="btn btn-success">Add to Cart</button>
        </form>

        <div id="message" class="mt-3"></div>
    </div>

    <?php include('inc/footer.php'); ?>

    <script>
      document.getElementById('add-to-cart-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    fetch('add_to_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        const messageDiv = document.getElementById('message');
        messageDiv.textContent = data.message;
        if (data.success) {
            messageDiv.classList.add('alert', 'alert-success');
            messageDiv.classList.remove('alert-danger');
        } else {
            messageDiv.classList.add('alert', 'alert-danger');
            messageDiv.classList.remove('alert-success');
        }
        console.log('Server response:', data);
    })
    .catch(error => {
        console.error('Error:', error);
        const messageDiv = document.getElementById('message');
        messageDiv.textContent = 'An error occurred while adding the item to the cart: ' + error.message;
        messageDiv.classList.add('alert', 'alert-danger');
        messageDiv.classList.remove('alert-success');
    });
});
    </script>
</body>

</html>