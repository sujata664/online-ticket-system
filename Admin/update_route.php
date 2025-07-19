<?php
include("connection.php"); 


if(isset($_POST['update_route'])) {
    $new_origin = $_POST['ori'];
    $n_dest = $_POST['dest'];
    $n_date = $_POST['dd'];
    $n_time = $_POST['tt'];
    $n_cost = $_POST['price'];

    if(isset($_GET['id'])) {
        $route_id = $_GET['id'];

        
        $update_sql = "UPDATE route SET origin='$new_origin', destination='$n_dest', d_date='$n_date', d_time='$n_time', cost='$n_cost' WHERE id=$route_id";
        if (mysqli_query($connection, $update_sql)) {
            echo "<script>alert('Record updated successfully');</script>";
            header("Location: routes.php");
            exit;
        } else {
            echo "Error: " . $update_sql . "<br>" . mysqli_error($connection);
        }
    } 
}


if(isset($_GET['id'])) {
    $route_id = $_GET['id'];

    
    $select_sql = "SELECT * FROM route WHERE id=$route_id";
    $result = mysqli_query($connection, $select_sql);
    if($result && mysqli_num_rows($result) > 0) {
        $route_data = mysqli_fetch_assoc($result);
        $current_origin = $route_data['origin'];
        $current_dest = $route_data['destination'];
        $current_date = $route_data['d_date'];
        $current_time = $route_data['d_time'];
        $current_cost = $route_data['cost'];
    } else {
        echo "Route not found";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Route</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Update Route</h2>
        <form method="post" action="update_route.php?id=<?php if(isset($_GET['id'])) echo $_GET['id']; ?>">
            <div class="form-group">
                <label>Origin</label>
                <input type="text" placeholder="Enter origin" name="ori" value="<?php if(isset($current_origin)) echo $current_origin; ?>" required>
                <label>Destination</label>
                <input type="text" placeholder="Enter destination" name="dest" value="<?php if(isset($current_dest)) echo $current_dest; ?>" required>
                <label>Date</label>
                <input type="date" name="dd" value="<?php if(isset($current_date)) echo $current_date; ?>" required>
                <label>Time</label>
                <input type="time" name="tt" value="<?php if(isset($current_time)) echo $current_time; ?>" required>
                <label>Cost</label>
                <input type="text" placeholder="Enter price" name="price" value="<?php if(isset($current_cost)) echo $current_cost; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" name="update_route">Update</button>
        </form>
    </div>
    <!-- Bootstrap JS and dependencies (jQuery, Popper.js) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
