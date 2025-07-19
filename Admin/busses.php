<?php
include("connection.php");

// Add new bus
if (isset($_POST['add_bus'])) {
    $bus_number = $_POST['bus_number'];

    $sql = "INSERT INTO busses (bus_num) VALUES ('$bus_number')";

    if (mysqli_query($connection, $sql)) {
       // echo "<script>alert('New record created successfully');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($connection);
    }
}

// Delete bus
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $delete_sql = "DELETE FROM busses WHERE id=$id";

    if (mysqli_query($connection, $delete_sql)) {
       // echo "<script>alert('Record deleted successfully');</script>";
    } else {
        echo "Error: " . $delete_sql . "<br>" . mysqli_error($connection);
    }
}



// Fetch all buses
$sql = "SELECT * FROM busses";
$result = mysqli_query($connection, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Buses</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        
        <h2>Add New Bus</h2>
        <form method="post">
            <div class="form-group">
                <label for="bus_number"></label>
                <input type="text" class="form-control" placeholder="Enter Bus Number"id="bus_number" name="bus_number" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add_bus">Add Bus</button>
        </form>

        <hr>

        <h2>Existing Buses</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Bus Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["bus_num"] . "</td>";
                        echo "<td>";
                        echo "<a href='update_bus_num.php?id=" . $row["id"] . "' class='btn btn-sm btn-info mr-2'>Update</a>";
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
