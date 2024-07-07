<?php
session_start();
require_once "src/database.php";

// Ensure user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

// Handle comment submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_comment'])) {
    $comment = trim($_POST['comment']);
    $username = $_SESSION['user_email'];
    
    if (!empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO cus_share (cus_username, com) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $comment);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch existing comments
$comments = [];
$result = $conn->query("SELECT cs.cid, cs.cus_username, cs.com, cs.created_at FROM cus_share cs ORDER BY cs.created_at DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/share.css">
</head>
<body>
    <?php include('inc/header.php'); ?>

    <div class="container">
        <div class="comment-board">
            <h2>Comment Board</h2>
            
            <div class="comment-form">
                <form id="commentForm" method="post">
                    <div class="mb-3">
                        <label for="comment" class="form-label">Leave a comment:</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                    </div>
                    <button type="submit" name="submit_comment" class="btn btn-primary">Submit Comment</button>
                </form>
            </div>

            <div id="commentsList">
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <p><strong><?php echo htmlspecialchars($comment['cus_username']); ?></strong> 
                           <small class="text-muted"><?php echo $comment['created_at']; ?></small></p>
                        <p><?php echo htmlspecialchars($comment['com']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php include('inc/footer.php'); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#commentForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'submit_comment.php',
                data: $(this).serialize(),
                success: function(response) {
                    $('#comment').val('');
                    $('#commentsList').prepend(response);
                }
            });
        });
    });
    </script>
</body>
</html>