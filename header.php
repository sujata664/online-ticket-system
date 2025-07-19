<?php
session_start(); // Start the session at the beginning of the script

include("connection.php");

$emErr = ""; 
$psErr = "";
$logem = ""; 
$password = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_login'])) {
    // Validate email
    if (isset($_POST['log_email']) && !empty($_POST['log_email']) && trim($_POST['log_email']) != '') {
        $logem = $_POST['log_email'];
        if (!filter_var($logem, FILTER_VALIDATE_EMAIL)) {
            $emErr = 'Invalid email pattern';
        }
    } else {
        $emErr = 'Enter email';
    }

    // Validate password
    if (isset($_POST['log_password']) && !empty($_POST['log_password']) && trim($_POST['log_password']) != '') {
        $password = $_POST['log_password'];
        if (strlen($password) < 6) {
            $psErr = 'Password must be greater than 6 characters';
        }
    } else {
        $psErr = 'Enter password';
    }

    // If no validation errors, proceed with login
    if (empty($emErr) && empty($psErr)) {
        $sql = "SELECT * FROM customer WHERE email = '$logem'";
        $result = mysqli_query($connection, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);

            // Verify the password
            if ($user_data['password'] == $password) { // Consider using password_verify if the password is hashed
                // Password is correct, set session variables and redirect to home.php
                $_SESSION['logged_in'] = true;
                $_SESSION['customer_id'] = $user_data['id'];
                $_SESSION['customer_name'] = $user_data['name'];
                header("Location: home.php");
                exit(); // Always exit after a header redirect
            } else {
                // Password is incorrect
                echo '<script>alert("Incorrect email or password");</script>';
            }
        } else {
            // No user found with the provided email
            echo '<script>alert("User not found");</script>';
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <title>BookBus</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="icon" href="img/uu-removebg-preview.png">
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-q8i/X+965DzO0rTDNEpUTHQoQUJMHLrErGJyHg89uy71MyuHH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
    * {box-sizing: border-box}

    /* Add padding to containers */
    .container {
    padding: 16px;
    }

    /* Full-width input fields */
    textarea,input[type=text], input[type=password],input[type=tel]{
    width: 100%;
    padding: 10px;
    margin: 5px 0 13px 0;
    display: inline-block;
    border: 1px solid black;
    background: #f1f1f1;
    }

    textarea,input[type=text]:focus, input[type=password]:focus {
    background-color: #ddd;
    outline: none;
    }

    /* Overwrite default styles of hr */
    hr {
    border: 1px solid #f1f1f1;
    margin-bottom: 25px;
    }

    /* Set a style for the submit/register button */
    .registerbtn {
    padding: 6px 8px;
    margin: 2px 0;
    border: none;
    cursor: pointer;
    width: 50%;
    opacity: 1;
    
    }

    .registerbtn:hover {
    opacity:1;
    }

    /* Add a blue text color to links */
    a {
    color: dodgerblue;
    }

    /* Set a grey background color and center the text of the "sign in" section */
    .signin {
    background-color: #f1f1f1;
    text-align: center;
    }
    .error{
        color:red;
    }

</style>

</head>
<body>
    
<nav class="navbar navbar-expand-md navbar-light" style="background-color:#87a6d8;">
    <a class="navbar-brand" href="home.php"><img src="img/uu-removebg-preview.png" style="Width: 50px;" /></a>
    <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#collapsibleNavId" aria-controls="collapsibleNavId"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavId">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="home.php">HOME</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="about.php">ABOUT</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="booking.php">BOOK TICKETS</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="contact.php">CONTACT</a>
            </li>
        </ul>

        <ul class="navbar-nav" style="background-color:#87a6d8;">
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) { ?>

                    <li class="nav-item dropdown" >
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"  aria-haspopup="true" aria-expanded="false">MANAGE TICKET</a>
                        <div class="dropdown-menu" style="background-color:#87a6d8;">
                            <a class="dropdown-item" href="print.php">Print Ticket</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="cancel.php">Cancel Ticket</a>
                        </div>
            </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">LOGOUT</a>
                </li>
            
            <?php } else { ?>
                <li class="nav-item">
                        <a class="nav-link" data-toggle="modal" data-target="#modelId1">LOGIN</a>
            </li>
            </li>
                </li>
                <li class="nav-item">
                    <!-- Button register trigger modal -->
                    <a class="nav-link" data-toggle="modal" data-target="#modelId">REGISTER</a>
                </li>
                <li class="nav-item">
                        <a class="nav-link" href="admin/index.php">ADMIN</a>
            </li>
            <?php } ?>
        </ul>
    </div>
</nav>

<!-- register Modal -->
<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
   
            <div class="modal-header"style="background-color:#87a6d8;color:Black">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <?php
            $nameErr = ""; $emailErr = ""; $phoneErr = ""; $passwordErr = ""; $confirmPasswordErr = "";
            $name = ""; $email = ""; $phone = ""; $password = ""; $confirm_password = "";

            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                if (isset($_POST['btn_register'])) {
                    if (isset($_POST['name']) && !empty($_POST['name']) && trim($_POST['name']) != '') {
                        $name = $_POST['name'];
                        $regex = '/^[a-zA-Z ]*$/';
                        if (!preg_match($regex, $name)) {
                            $nameErr = 'Only letters and space are allowed';
                        }
                    } else {
                        $nameErr = 'Enter name';
                    }

                    if (isset($_POST['phone']) && !empty($_POST['phone']) && trim($_POST['phone']) != '') {
                        $phone = $_POST['phone'];
                        $regex = '/^([0-9]{10})$/';
                        if (!preg_match($regex, $phone)) {
                            $phoneErr = 'Invalid phone pattern';
                        }
                    } else {
                        $phoneErr = 'Enter phone';
                    }

                    if (isset($_POST['email']) && !empty($_POST['email']) && trim($_POST['email']) != '') {
                        $email = $_POST['email'];
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $emailErr = 'Invalid email pattern';
                        }
                    } else {
                        $emailErr = 'Enter email';
                    }

                    if (isset($_POST['password']) && !empty($_POST['password']) && trim($_POST['password']) != '') {
                        $password = $_POST['password'];
                        if (strlen($password) < 6) {
                            $passwordErr = 'Password must be greater than 6 characters';
                        }
                    } else {
                        $passwordErr = 'Enter password';
                    }

                    if (isset($_POST['c_password']) && !empty($_POST['c_password']) && trim($_POST['c_password']) != '') {
                        $confirm_password = $_POST['c_password'];
                        if ($confirm_password !== $password) {
                            $confirmPasswordErr = 'Passwords do not match';
                        }
                    } else {
                        $confirmPasswordErr = 'Confirm your password';
                    }

                    if (empty($nameErr) && empty($emailErr) && empty($phoneErr) && empty($passwordErr) && empty($confirmPasswordErr)) {
                        $sql = "INSERT INTO customer (name, phone, email, password,c_password) VALUES ('$name', '$phone', '$email', '$password',$confirm_password)";

                        if (mysqli_query($connection, $sql)) {
                            echo '<script>alert("New record created successfully");</script>';
                        } else {
                            echo "Error: " . $sql . "<br>" . mysqli_error($connection);
                        }
                    }
                }
            }
            ?>

            <div class="modal-body">
                <form method="POST">
                    <center>
                        <h1>Register</h1>
                        <p>Please fill in this form to create an account.</p>
                    </center>
                    <hr>
                    <label>NAME</label>
                    <input type="text" style="border-radius:30px;" placeholder="Enter username" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>">
                    <span class="error"><?php echo $nameErr; ?></span><br><br>

                    <label>PHONE</label>
                    <input type="text" style="border-radius:30px;" placeholder="Enter Number" name="phone" id="phone" value="<?php echo htmlspecialchars($phone); ?>">
                    <span class="error"><?php echo $phoneErr; ?></span><br><br>

                    <label>EMAIL</label>
                    <input type="text" style="border-radius:30px;" placeholder="Enter Email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>">
                    <span class="error"><?php echo $emailErr; ?></span><br><br>

                    <label>PASSWORD</label>
                    <input type="password" style="border-radius:30px;" placeholder="Enter password" name="password" id="password" value="<?php echo htmlspecialchars($password); ?>">
                    <span class="error"><?php echo $passwordErr; ?></span><br><br>

                    <label>CONFIRM PASSWORD</label>
                    <input type="password" style="border-radius:30px;" placeholder="Confirm password" name="c_password" id="c_password" value="<?php echo htmlspecialchars($confirm_password); ?>">
                    <span class="error"><?php echo $confirmPasswordErr; ?></span><br><br>

                    <button type="submit" class="btn" name="btn_register" style="background-color:#87a6d8;color:Black">Register</button>
                    <hr>
                    <div>
                        <p>Already have an account? <a style="color:grey; cursor:pointer;" data-toggle="modal" data-target="#modelId1" data-dismiss="modal">Login</a>.</p>
                    </div>
                </form>
            </div>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btn_register']) && (!empty($nameErr) || !empty($phoneErr) || !empty($emailErr) || !empty($passwordErr) || !empty($confirmPasswordErr))) {
                echo '<script>$(document).ready(function() { $("#modelId").modal("show"); });</script>';
            }
            ?>
            <script>
    $(document).ready(function () {
        $('#modelId').on('shown.bs.modal', function () {
            $('body').addClass('modal-open');
        });

        $('#modelId').on('hidden.bs.modal', function () {
            $('body').removeClass('modal-open');
        });
    });
     </script>

        </div>
    </div>
</div>

<!--login  Modal -->
<div class="modal fade" id="modelId1" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
    
            <div class="modal-header" style="background-color:#87a6d8;color:Black">
                <h5 class="modal-title">LOGIN</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>

            <div class="modal-body">
                <form method="POST">
                    <label for="log_email">EMAIL</label>
                    <input type="text" style="border-radius: 30px;" placeholder="Enter Email" name="log_email" id="log_email" value="<?php echo htmlspecialchars($logem); ?>">
                    <span class="error"><?php echo $emErr; ?></span><br><br>

                    <label for="log_password">PASSWORD</label>
                    <input type="password" style="border-radius: 30px;" placeholder="Enter Password" name="log_password" id="log_password" value="<?php echo htmlspecialchars($password); ?>">
                    <span class="error"><?php echo $psErr; ?></span><br><br>

                    <button type="submit" class="btn" name="btn_login" style="background-color: #87a6d8; color: Black;">Login</button>
                    <hr>
                    <div>
                        <p>Don't have an account? <a style="color: grey; cursor: pointer;" data-toggle="modal" data-target="#modelId" data-dismiss="modal">Register</a>.</p>
                    </div>
                </form>
            </div>

            <?php
            if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btn_login']) && (!empty($emErr) || !empty($psErr))) {
                echo '<script>$(document).ready(function() { $("#modelId1").modal("show"); });</script>';
            }
            ?>

        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-q8i/X+965DzO0rTDNEpUTHQoQUJMHLrErGJyHg89uy71MyuHH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-pjaaA8dDz/40d9pgIaaK7tuKoM2p1u/sfdfwdf" crossorigin="anonymous"></script>

</body>
</html>
