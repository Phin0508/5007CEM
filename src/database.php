<?php
$host = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "potatoagency";
$conn = mysqli_connect($host, $dbUser, $dbPassword, $dbName);

if(!$conn) {
    die("Something went wrong");
}
?>