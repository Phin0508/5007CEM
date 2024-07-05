<?php
session_start();
require_once "src/database.php";

if(isset($_POST["login"])) {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if(empty($email) || empty($password)) {
        echo "<div class='alert alert-danger'>Please fill in all fields</div>";
    } else {
        // Check customer table
        $stmt = $conn->prepare("SELECT * FROM customer WHERE cus_username = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if(password_verify($password, $user["cus_password"])) {
                $_SESSION['user_id'] = $user['id']; // Make sure 'id' is the correct column name
                $_SESSION['user_email'] = $user['cus_username'];
                header("Location: mainpage.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Invalid password</div>";
            }
        } else {
            // Check admin table
            $stmt = $conn->prepare("SELECT * FROM admin WHERE Admin_user = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if($result->num_rows == 1) {
                $admin = $result->fetch_assoc();
                if($password === $admin["Admin_pass"]) { // Assuming admin password is not hashed
                    $_SESSION['admin_id'] = $admin['Admin_id'];
                    $_SESSION['admin_email'] = $admin['Admin_user'];
                    header("Location: adminlogbook.php");
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Invalid admin password</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Email does not exist</div>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/login.css">
    <?php include('src/database.php'); ?>
</head>

<body>
    <?php include('inc/header.php'); ?>

    <div class="container mt-4">
        <div class="form-container">
            <h3 class="text-center">Login</h3>
            <form action="login.php" method="post">
                <div class="mb-3">
                    <label for="emailInput" class="form-label">Email address</label>
                    <input type="email" class="form-control" name="email" id="emailInput" required>
                </div>
                <div class="mb-3">
                    <label for="passwordInput" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="passwordInput" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
            </form>
        </div>
        <div class="signup-btn text-center">
            <a href="signup.php">Don't have an account? Sign up here.</a>
        </div>
    </div>
    
    <a href="admin/adminlogin.php" class="btn btn-secondary admin-login-btn">Admin Login</a>

    <?php include('inc/footer.php'); ?>
</body>

</html>