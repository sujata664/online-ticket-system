<?php
include("header.php");
?>
 
<section style="min-height:580px;" >

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Datepicker CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <!-- Font Awesome CSS (Version 4) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<title>Stylish Form</title>
<style>
    body {
        font-family: Arial;
        background-image: url('img/n.jpg');
        background-size: cover;
        background-position: center;
        height: 100vh;
        justify-content: center;
        align-items: center;
    } 
    
    
    .search_box {
        width: 70%;
        margin: 45px auto;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.7);
    }
    .search_box form {
        display: flex;
        flex-direction:row;
    }
    .search_box label {
        display: block;
    padding: 5px;
    font-weight: bold;
    color: #fff; 
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }
    .search_box input[type="text"]{
        padding: 6px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        width: 30%;
    }
    .search_box input,[type="date"] {
        padding: 6px;
        margin-top: 3px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        width: 20%;
    }
    .search_box input[type="submit"] {
        margin-top: 5px;
     margin-left: 5px; 
     padding: 5px;
    background-color: #87a6d8;
    color: black;
    border-radius: 5px;
    border-color: black;
    cursor: pointer;
    transition: 0.3s;
    width: 80%; 
    max-width: 100px; 
    text-align: center;
    font-size: 14px; 
    height:100%
    
        
    }
    .search_box input[type="submit"]:hover {
        background-color:  #007bff;
    }
    .navigation {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    
    }
    .overlay {
    position: fixed; 
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.1);
    z-index: -1; 
}
    

</style>
</head>
<body>
<h1 style="text-align: center;margin-top: 50px;font-size: 36px;color: #fff; letter-spacing: 1px;text-transform: uppercase;font-family: Arial Black;text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);"
    >Plan Your Journey</h1>

<div class="navigation"></div>
<div class="overlay"></div>
<div class="search_box">

<form method="POST" action="book.php">
    <label for="origin">From</label>
    <input type="text" id="origin" placeholder="Enter origin" name="fro" required>

    <label for="destination">To</label>
    <input type="text" id="destination" placeholder="Enter Destination" name="too" required>

    <label for="date">Date</label>
    <input type="date" name="dd" placeholder="Select date" required>

    <input type="submit" value="Search" name="search">
</form>

            
        </body>
        </html>
        
</section>


<?php
include("footer.php");
?>


