<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_email']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        /* Top Navigation Bar */
        .topnav {
            background-color: #222529;
            overflow: hidden;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }

        .topnav a {
            color: #ffffff;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }

        .topnav a.active {
            background-color: #222529;
            /* Darker background for active link */
        }

        .topnav .icon {
            display: none;
        }

        /* Responsive Navigation */
        @media screen and (max-width: 600px) {
            .topnav a:not(:first-child) {
                display: none;
            }

            .topnav a.icon {
                float: right;
                display: block;
            }
        }

        @media screen and (max-width: 600px) {
            .topnav.responsive {
                position: relative;
            }

            .topnav.responsive .icon {
                position: absolute;
                right: 0;
                top: 0;
            }

            .topnav.responsive a {
                float: none;
                display: block;
                text-align: left;
            }
        }

        /* Header */
        .header {
            text-align: center;
            padding: 80px 20px;
            /* Adjusted padding */
            background-color: #cdfff7;
            /* Grey background */
            color: #333;
            /* Text color */
            margin-bottom: 20px;
            /* Added margin */
        }

        .header h1 {
            margin-bottom: 20px;
            font-size: 36px;
            /* Increased font size */
        }

        .header p {
            font-size: 18px;
            /* Adjusted font size */
        }

        /* Flexbox adjustment for navbar */
        .nav-left {
            flex: 1;
        }

        .nav-right {
            display: flex;
            gap: 10px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="http://localhost/5007CEM/mainpage.php">POTATO AGENCY</a>
            <button class="navbar-toggler" type="button" onclick="toggleNavbar()">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost/5007CEM/share.php">Share</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="aboutus.php">About</a>
                    </li>
                    <?php if ($isLoggedIn) : ?>
                        <li class="nav-item">
                            <span class="nav-link">Welcome, <?php echo htmlspecialchars($_SESSION['user_email']); ?></span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="confirmLogout()">Logout</a>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="http://localhost/5007CEM/login.php">Login/Signup</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href=""><i class="fa fa-shopping-cart" style="font-size:22px"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to logout?")) {
                window.location.href = "logout.php";
            }
        }

        function toggleNavbar() {
            const navbar = document.getElementById("navbarNav");
            navbar.classList.toggle("show");
        }
    </script>
</body>

</html>