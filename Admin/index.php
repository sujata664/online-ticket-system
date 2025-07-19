<?php
session_name("admin_session");
session_start();
include("connection.php"); // Include database connection

$emErr = ""; 
$psErr = "";
$email = "";
$password = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Input validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emErr = 'Invalid email format';
    } elseif (strlen($password) < 6) {
        $psErr = 'Password must be at least 6 characters long';
    } else {
        // Query to fetch admin data
        $sql = "SELECT * FROM admin WHERE email = '$email'";
        $result = mysqli_query($connection, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);

            // Verify the password (assumes passwords are stored in plain text; use password_verify() if hashed)
            if ($user_data['password'] === $password) {
                $_SESSION['logged_in'] = true;
                $_SESSION['admin_id'] = $user_data['id'];
                $_SESSION['admin_name'] = $user_data['name'];
                header("Location: dashboard.php"); // Redirect to the admin dashboard
                exit();
            } else {
                $psErr = 'Incorrect email or password';
            }
        } else {
            $emErr = 'No account found with that email';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
  <center>
    <h1 class="my-4">Admin Login</h1>
  </center>

  <!-- Login Form -->
  <div class="card mb-4">
    <div class="card-body">
      <form id="loginForm" method="POST" action="">
        <div class="form-group">
          <label for="email">EMAIL</label>
          <input type="email" class="form-control" id="email" name="email" required>
          <?php if ($emErr): ?><small class="text-danger"><?php echo $emErr; ?></small><?php endif; ?>
        </div>
        <div class="form-group">
          <label for="password">PASSWORD</label>
          <input type="password" class="form-control" id="password" name="password" required>
          <?php if ($psErr): ?><small class="text-danger"><?php echo $psErr; ?></small><?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap JS and dependencies (jQuery, Popper.js) -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
