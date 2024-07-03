<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>About Us</title>
    <link rel="stylesheet" href="css/aboutus.css">
</head>

<body>
    <?php include('inc/header.php'); ?>

    <div class="about-section">
        <h1>About Us Page</h1>
        <p>Some text about who we are and what we do.</p>
        <p>Resize the browser window to see that this page is responsive by the way.</p>
    </div>

    <h2 style="text-align:center">Our Team</h2>
    <div class="row">
        <div class="column">
            <div class="card">
                <div class="card-flex">
                    <img src="potatopic/Potato.JPG" alt="Phin" class="card-image">
                    <div class="container">
                        <h2>Phin</h2>
                        <p class="title">CEO & Founder</p>
                        <p>Welcome to potato agencies and thank you for using it.</p>
                        <p>phin@example.com</p>
                        <p><button class="button">Contact</button></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Repeat the above block for other team members -->
    </div>

    <?php include('inc/footer.php'); ?>
</body>

</html>
