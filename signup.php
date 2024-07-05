<?php
session_start(); // This should be the very first line
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('src/database.php');

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["signup"])) {
    // Verify CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token validation failed");
    }

    $fullname = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $phone = trim($_POST["phone"]);

    // Validation
    if (empty($fullname) || empty($email) || empty($password) || empty($phone)) {
        array_push($errors, "All fields are required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid");
    }
    if (strlen($password) < 8 || !preg_match("#[0-9]+#", $password) || !preg_match("#[a-zA-Z]+#", $password)) {
        array_push($errors, "Password must be at least 8 characters and contain both letters and numbers");
    }
    if (!preg_match("/^[0-9]{11}$/", $phone)) {
        array_push($errors, "Phone number must be 11 digits");
    }

    if (count($errors) == 0) {
        // Check if email exists
        $check_stmt = $conn->prepare("SELECT cus_username FROM customer WHERE cus_username = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            echo "<div class='alert alert-danger'>This email is already registered</div>";
        } else {
            // Insert new user
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $insert_stmt = $conn->prepare("INSERT INTO customer(cus_name, cus_username, cus_password, cus_phone) VALUES (?, ?, ?, ?)");
            $insert_stmt->bind_param("ssss", $fullname, $email, $passwordHash, $phone);

            if ($insert_stmt->execute()) {
                echo "<div class='alert alert-success'>Registered successfully!</div>";
            } else {
                echo "<div class='alert alert-danger'>Registration failed: " . $conn->error . "</div>";
            }
            $insert_stmt->close();
        }
        $check_stmt->close();
    } else {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/signup.css">
</head>

<body>
    <?php include('inc/header.php'); ?>

    <div class="container mt-4">
        <div class="form-container">
            <!-- HTML form -->
            <form method="post" action="signup.php">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="mb-3">
                    <label for="nameinput" class="form-label">Name</label>
                    <input type="text" class="form-control" id="nameinput" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="emailInput" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="emailInput" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="passwordInput" class="form-label">Password</label>
                    <input type="password" class="form-control" id="passwordInput" name="password" required pattern="(?=.*\d)(?=.*[a-zA-Z]).{8,}" title="Must be at least 8 characters long and contain both letters and numbers">
                </div>

                <div class="mb-3">
                    <label for="phoneInput" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phoneInput" name="phone" required pattern="[0-9]{11}" title="Phone number must be 11 digits">
                </div>

                <button type="submit" class="btn btn-primary" name="signup">Sign Up</button>
            </form>
        </div>

        <!-- Login Link -->
        <div class="signup-btn">
            <a href="http://localhost/5007CEM/login.php">Already have an account? Log in here.</a>
        </div>
    </div>

    <?php include('inc/footer.php'); ?>
</body>

</html>