<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "book_ticket";

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    //echo "Connection created<br>";
}