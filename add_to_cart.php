<?php
session_start();
require 'src/database.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method.');
    }

    $location_id = isset($_POST['location_id']) ? intval($_POST['location_id']) : 0;
    $pax_count = isset($_POST['pax_count']) ? intval($_POST['pax_count']) : 0;
    $user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : null;

    if ($location_id <= 0) {
        throw new Exception('Invalid location ID: ' . $location_id);
    }
    if ($pax_count <= 0) {
        throw new Exception('Invalid pax count: ' . $pax_count);
    }
    if (!$user_email) {
        throw new Exception('User not logged in.');
    }

    // Check if the item is already in the cart
    $check_sql = "SELECT id, pax_count FROM cart WHERE cus_username = ? AND location_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param('si', $user_email, $location_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Item already in cart, update the pax_count
        $row = $result->fetch_assoc();
        $new_pax_count = $row['pax_count'] + $pax_count;
        $update_sql = "UPDATE cart SET pax_count = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param('ii', $new_pax_count, $row['id']);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        // Item not in cart, insert new row
        $insert_sql = "INSERT INTO cart (cus_username, location_id, pax_count) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param('sii', $user_email, $location_id, $pax_count);
        $insert_stmt->execute();
        $insert_stmt->close();
    }

    $check_stmt->close();

    echo json_encode(['success' => true, 'message' => 'Item added to cart successfully.']);

} catch (Exception $e) {
    error_log('Add to cart error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>