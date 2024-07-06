<?php
session_start();
require 'src/database.php';

if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}

$user_email = $_SESSION['user_email'];

// Update pax count if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_pax'])) {
    $cart_id = $_POST['cart_id'];
    $new_pax_count = $_POST['pax_count'];

    $update_sql = "UPDATE cart SET pax_count = ? WHERE id = ? AND cus_username = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('iis', $new_pax_count, $cart_id, $user_email);
    $update_stmt->execute();
    $update_stmt->close();
}

// Fetch cart items
$sql = "SELECT c.id, c.pax_count, l.id as location_id, l.name, l.trip_price, l.image_path 
        FROM cart c 
        JOIN locations l ON c.location_id = l.id 
        WHERE c.cus_username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $user_email);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Calculate total
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['trip_price'] * $item['pax_count'];
}

// Process the checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_purchase'])) {
    // Here you would typically process the payment
    // For this example, we'll just simulate a successful payment

    // Create a booking for each cart item
    $booking_sql = "INSERT INTO bookings (cus_username, location_id, pax_count, total_price, status) VALUES (?, ?, ?, ?, 'Paid')";
    $booking_stmt = $conn->prepare($booking_sql);

    foreach ($cart_items as $item) {
        $item_total = $item['trip_price'] * $item['pax_count'];
        $booking_stmt->bind_param('siid', $user_email, $item['location_id'], $item['pax_count'], $item_total);
        $booking_stmt->execute();
    }

    $booking_stmt->close();

    // Clear the cart
    $clear_cart_sql = "DELETE FROM cart WHERE cus_username = ?";
    $clear_cart_stmt = $conn->prepare($clear_cart_sql);
    $clear_cart_stmt->bind_param('s', $user_email);
    $clear_cart_stmt->execute();
    $clear_cart_stmt->close();

    // Set a session variable to indicate the purchase was completed
    $_SESSION['purchase_completed'] = true;

    // Redirect to the thank you page
    header('Location: thankyou.php');
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/checkout.css">
</head>

<body>
    <?php include('inc/header.php'); ?>

    <main>
        <div class="container">
            <h1 class="mb-4">Checkout</h1>
            <?php if (empty($cart_items)) : ?>
                <p>Your cart is empty. Nothing to checkout.</p>
            <?php else : ?>
                <div class="table-responsive mb-4">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Location</th>
                                <th>Price</th>
                                <th>Pax</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items as $item) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td>$<?php echo number_format($item['trip_price'], 2); ?></td>
                                    <td>
                                        <form action="checkout.php" method="POST" class="d-inline">
                                            <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                            <input type="number" name="pax_count" value="<?php echo $item['pax_count']; ?>" min="1" class="form-control form-control-sm" style="width: 70px;">
                                    </td>
                                    <td>$<?php echo number_format($item['trip_price'] * $item['pax_count'], 2); ?></td>
                                    <td>
                                        <button type="submit" name="update_pax" class="btn btn-sm btn-primary">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <form action="checkout.php" method="POST" class="checkout-form">
    <h2 class="mb-3">Billing Information</h2>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="fullName" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="fullName" name="fullName" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
    </div>
    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <input type="text" class="form-control" id="address" name="address" required>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="city" class="form-label">City</label>
            <input type="text" class="form-control" id="city" name="city" required>
        </div>
        <div class="col-md-4 mb-3">
            <label for="country" class="form-label">Country</label>
            <input type="text" class="form-control" id="country" name="country" required>
        </div>
        <div class="col-md-4 mb-3">
            <label for="zipCode" class="form-label">Zip Code</label>
            <input type="text" class="form-control" id="zipCode" name="zipCode" required>
        </div>
    </div>

    <h2 class="mb-3 mt-4">Payment Information</h2>
    <div class="mb-3">
        <label for="cardName" class="form-label">Name on Card</label>
        <input type="text" class="form-control" id="cardName" name="cardName" required>
    </div>
    <div class="mb-3">
        <label for="cardNumber" class="form-label">Card Number</label>
        <input type="text" class="form-control" id="cardNumber" name="cardNumber" required>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="expirationDate" class="form-label">Expiration Date</label>
            <input type="text" class="form-control" id="expirationDate" name="expirationDate" placeholder="MM/YY" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="cvv" class="form-label">CVV</label>
            <input type="text" class="form-control" id="cvv" name="cvv" required>
        </div>
    </div>

    <div class="d-grid gap-2 mt-4">
        <button type="submit" name="complete_purchase" class="btn btn-primary btn-lg">Complete Purchase</button>
    </div>
</form>
            <?php endif; ?>
        </div>
    </main>

    <?php include('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>