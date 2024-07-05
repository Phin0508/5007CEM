<?php
session_start();
require_once "src/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_email'])) {
    $comment = trim($_POST['comment']);
    $username = $_SESSION['user_email'];
    
    if (!empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO cus_share (cus_username, com) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $comment);
        $stmt->execute();
        $comment_id = $stmt->insert_id;
        $stmt->close();

        // Return the new comment HTML
        echo '<div class="comment">
                <p><strong>' . htmlspecialchars($username) . '</strong> 
                   <small class="text-muted">' . date('Y-m-d H:i:s') . '</small></p>
                <p>' . htmlspecialchars($comment) . '</p>
              </div>';
    }
}