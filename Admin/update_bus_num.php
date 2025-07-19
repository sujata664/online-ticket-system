<?php
include("connection.php"); 

if(isset($_POST['update_bus'])) {
    
    $new_bus_number = $_POST['new_bus_number'];
    $bus_id = $_POST['bus_id'];

   
    $update_sql = "UPDATE busses SET bus_num='$new_bus_number' WHERE id=$bus_id";

    
    if (mysqli_query($connection, $update_sql)) {
        echo "<script>alert('Record updated successfully');</script>";
        header("Location: busses.php");
        exit;
    } else {
        echo "Error: " . $update_sql . "<br>" . mysqli_error($connection);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Bus Number</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Update Bus Number</h2>
        <form method="post" action="update_bus_num.php">
            <div class="form-group">
                <label for="new_bus_number">New Bus Number:</label>
                <input type="text" class="form-control" id="new_bus_number" name="new_bus_number" required>
            </div>
            <input type="hidden" name="bus_id" value="<?php echo $_GET['id']; ?>">
            <button type="submit" class="btn btn-primary" name="update_bus">Update Bus</button>
        </form>
    </div>
    <!-- Bootstrap JS and dependencies (jQuery, Popper.js) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
