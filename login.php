<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <?php include('inc/header.php'); ?>

    <div class="container mt-4">
        <div class="form-container">
            <h3 class="text-center">Login</h3>
            <form action="login.php" method="post">
                <div class="mb-3">
                    <label for="emailInput" class="form-label">Email address</label>
                    <input type="email" class="form-control" name="email" id="emailInput" placeholder="Enter your email">
                </div>

                <div class="mb-3">
                    <label for="passwordInput" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="passwordInput" placeholder="Enter your password">
                </div>

                <button type="submit" value="login" name="login" class="btn btn-primary btn-block">Login</button>
            </form>
        </div>

        <div class="signup-btn text-center">
            <a href="http://localhost/5007CEM/login.php">Don't have an account? Sign up here.</a>
        </div>
    </div>

    <?php include('inc/footer.php'); ?>
</body>

</html>