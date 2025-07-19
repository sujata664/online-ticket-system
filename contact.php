<?php
include("header.php");
include("connection.php");


$nameErr = $emailErr = $phoneErr = "";
$name = $email = $phone = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['btn_submit'])) {
        if (empty($_POST["name"])) {
            $nameErr = "Name is required";
        } else {
            $name = test_input($_POST["name"]);
            if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
                $nameErr = "Only letters and white space allowed";
            }
        }

        
        if (empty($_POST["phone"])) {
            $phoneErr = "Phone is required";
        } else {
            $phone = test_input($_POST["phone"]);
            if (!preg_match("/^([0-9]{10})$/", $phone)) {
                $phoneErr = "Invalid phone format";
            }
        }

        if (empty($_POST["email"])) {
            $emailErr = "Email is required";
        } else {
            $email = test_input($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
            }
        }

        if (empty($nameErr) && empty($emailErr) && empty($phoneErr)) {
            $message = test_input($_POST["message"]);
            $sql = "INSERT INTO contact VALUES (0, '$name', '$phone', '$email', '$message', NOW())";

            if (mysqli_query($connection, $sql)) {
                echo '<script> alert("We will contact you soon on your email address.");</script>';
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($connection);
            }
        }
    }
}

mysqli_close($connection);

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<section style="min-height:500px;">
    <div class="container">
        <div class="col-md-12">
            <center>
                <h1>Contact Us</h1>
                <h5>GET IN TOUCH</h5>
                <p>We'd love to talk about how we can work together. Send us a message below and we will respond as soon as possible.</p>
            </center>
        </div>
        <div class="row">
            <div class="col-md-6 mt-5 mb-5" style="border-radius:30px; background-color:#87a6d8;">
                <h2 class="mt-5 pl-5">Contact Information</h2>
                <p class="mt-1 pl-5">Our team will get back to you within 24 hours.</p>
                <p class="mt-3 pl-5"><i class="fa fa-phone fa-2x mt-3"></i>&nbsp; +977 1234567</p>
                <p class="mt-3 pl-5"><i class="fa fa-envelope fa-2x mt-3"></i>&nbsp; BookBus@gmail.com</p>
                <p class="mt-3 pl-5"><i class="fa fa-map-marker fa-2x mt-3"></i>&nbsp; Sahidchowk, Chitwan</p>

                <h2 class="mt-5 pl-5">Join Us</h2>
                <div class="mb-3">
                    <a href="#" class="pl-5" style="color:black;"><i class="fa fa-facebook-square fa-2x mt-3"></i>&nbsp;</a>
                    <a href="#" class="ml-3" style="color:black;"><i class="fa fa-instagram fa-2x mt-3"></i>&nbsp;</a>
                    <a href="#" class="ml-3" style="color:black;"><i class="fa fa-twitter-square fa-2x mt-3"></i>&nbsp;</a>
                    <a href="#" class="ml-3" style="color:black;"><i class="fa fa-linkedin-square fa-2x mt-3"></i>&nbsp;</a>
                </div>
            </div>
            <div class="col-md-6">
                <form method="post">
                    <div class="container">
                        <label>Your Name </label>
                        <input type="text" style="border-radius:30px;" placeholder="Enter username" name="name" id="name">
                        <span class="error"><?php echo $nameErr; ?></span> <br><br>
                        <label>Phone </label>
                        <input type="text" style="border-radius:30px;" placeholder="Enter Number" name="phone" id="phone">
                        <span class="error"> <?php echo $phoneErr; ?></span><br><br>
                        <label>Email</label>
                        <input type="text" style="border-radius:30px;" placeholder="Enter Email" name="email" id="email">
                        <span class="error"><?php echo $emailErr; ?></span><br><br>
                        <label>Message</label>
                        <textarea name="message" id="message" rows="3" style="resize:none; width:100%; border-radius:30px;"required></textarea>
                        <button type="submit" class="btn" name="btn_submit" style="background-color:#87a6d8; color:black;">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
include("footer.php");
?>
