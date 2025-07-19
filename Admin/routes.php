<?php
include("connection.php");


if (isset($_POST['add_route'])) {
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $d_date = $_POST['d_date'];
    $d_time = $_POST['d_time'];
    $cost= $_POST['cost'];

    $sql = "INSERT INTO route (origin,destination,d_date,d_time,cost) VALUES ('$origin','$destination' ,'$d_date','$d_time','$cost')";

    if (mysqli_query($connection, $sql)) {
       // echo "<script>alert('New record created successfully');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($connection);
    }
}

// Delete bus
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $delete_sql = "DELETE FROM route WHERE id=$id";

    if (mysqli_query($connection, $delete_sql)) {
       // echo "<script>alert('Record deleted successfully');</script>";
    } else {
        echo "Error: " . $delete_sql . "<br>" . mysqli_error($connection);
    }
}



// Fetch all buses
$sql = "SELECT * FROM route";
$result = mysqli_query($connection, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update route</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        
        <h2>Add New Route</h2>
        <form method="post">
            <div class="form-group">
                <label>Origin</label>
                <input type="text"  placeholder="Enter origin" name="origin" required>
                <label>Destination</label>
                <input type="text"  placeholder="Enter destination " name="destination" required>
                <label>Date</label>
                <input type="date" name="d_date" required>
                <label>Time</label>
                <input type="time"  name="d_time" required>
                <label>Cost</label>
                <input type="text"  placeholder="Enter price" name="cost" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add_route">Add Route</button>
        </form>

        <hr>
        <h2>Existing Routes</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Origin</th>
                    <th>Destination</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Cost</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["origin"] . "</td>";
                        echo "<td>" . $row["destination"] . "</td>";
                        echo "<td>" . $row["d_date"] . "</td>";
                        echo "<td>" . $row["d_time"] . "</td>";
                        echo "<td>" . $row["cost"] . "</td>";
                        echo "<td>";
                        echo "<a href='update_route.php?id=" . $row["id"] . "' class='btn btn-sm btn-info mr-2'>Update</a>";
                        echo "<a href='?delete=" . $row["id"] . "' class='btn btn-sm btn-danger'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No buses found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS and dependencies (jQuery, Popper.js) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close database connection
mysqli_close($connection);
?>
