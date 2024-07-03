<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/signup.css">
    <?php include('src/database.php'); ?>
</head>

<body>
    <?php include('inc/header.php'); ?>
    
    <div class="container mt-4">
        <div class="form-container">
            <?php
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            $errors = array();

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["signup"])) {
                $fullname = $_POST["name"];
                $email = $_POST["email"];
                $password = $_POST["password"];

                $passwordhash = password_hash($password, PASSWORD_DEFAULT);
                $phone = $_POST["phone"];

                if (empty($fullname) || empty($email) || empty($password) || empty($phone)) {
                    array_push($errors, "All fields are required to be filled in");
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    array_push($errors, "Email is not valid");
                }
                if (strlen($password) < 8) {
                    array_push($errors, "Password must be more than 8 characters");
                }
                if (strlen($phone) < 10) {
                    array_push($errors, "Not a valid phone number");
                }

                if (count($errors) > 0) {
                    foreach ($errors as $error) {
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                } else {
                    require_once "src/database.php";
                    
                    // First, check if the email already exists
                    $check_sql = "SELECT cus_username FROM customer WHERE cus_username = ?";
                    $check_stmt = mysqli_stmt_init($conn);
                    if (mysqli_stmt_prepare($check_stmt, $check_sql)) {
                        mysqli_stmt_bind_param($check_stmt, "s", $email);
                        mysqli_stmt_execute($check_stmt);
                        mysqli_stmt_store_result($check_stmt);
                        if (mysqli_stmt_num_rows($check_stmt) > 0) {
                            echo "<div class='alert alert-danger'>This email is already registered. Please use a different email.</div>";
                        } else {
                            // If email doesn't exist, proceed with insertion
                            $insert_sql = "INSERT INTO customer(cus_name, cus_username, cus_password, cus_phone) VALUES (?, ?, ?, ?)";
                            $insert_stmt = mysqli_stmt_init($conn);
                            if (mysqli_stmt_prepare($insert_stmt, $insert_sql)) {
                                mysqli_stmt_bind_param($insert_stmt, "ssss", $fullname, $email, $passwordhash, $phone);
                                if (mysqli_stmt_execute($insert_stmt)) {
                                    echo "<div class='alert alert-success'>Registered successfully!</div>";
                                } else {
                                    echo "<div class='alert alert-danger'>Registration failed. Please try again.</div>";
                                }
                            } else {
                                echo "<div class='alert alert-danger'>Something went wrong. Please try again.</div>";
                            }
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Something went wrong. Please try again.</div>";
                    }
                }
                mysqli_stmt_close($check_stmt);
                mysqli_stmt_close($insert_stmt);
            }
            ?>
            <h3 class="text-center">Sign Up</h3>
            <form method="post" action="signup.php">
                <div class="mb-3">
                    <label for="nameinput" class="form-label form-group">Name</label>
                    <input type="text" class="form-control" id="nameinput" name="name" placeholder="Enter your name">
                    <div class="col-auto">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="emailInput" class="form-label form-group">Email address</label>
                    <input type="email" class="form-control" id="emailInput" name="email" placeholder="Enter your email, (will also be your username)">
                </div>
                <div class="mb-3">
                    <label for="passwordInput" class="form-label form-group">Password</label>
                    <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Enter your password">
                    <div class="col-auto">
                        <span class="form-text">
                            Must be 8-20 characters long.
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="phoneInput" class="form-label form-group">Phone Number</label>
                    <input type="tel" class="form-control" id="phoneInput" name="phone" placeholder="Enter your phone number">
                    <div class="col-auto">
                        <span class="form-text">
                            Please provide a phone number so we can contact you.
                        </span>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary btn-block" name="signup">Sign Up</button>
                </div>
            </form>
        </div>

        <!-- Login Link -->
        <div class="signup-btn">
            <a href="http://localhost/TourAgency/MainPage.html">Already have an account? Log in here.</a>
        </div>
    </div>

    <?php include('inc/footer.php'); ?>

</body>

</html>