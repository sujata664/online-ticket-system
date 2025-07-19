<?php
include("connection.php");

$sql = "SELECT * FROM admin";
$result = mysqli_query($connection, $sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_admin'])) {
        // Code to add a new admin
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $password = ($_POST['password']);

        $insert_sql = "INSERT INTO admin (name, phone, email, password) VALUES ('$name', '$phone', '$email', '$password')";
        if (mysqli_query($connection, $insert_sql)) {
            echo "<script>alert('New admin added successfully');</script>";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error: " . $insert_sql . "<br>" . mysqli_error($connection);
        }
    } elseif (isset($_POST['update_admin'])) {
        // Code to update an existing admin
        $id = intval($_POST['id']);
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $password =($_POST['password']);

        $update_sql = "UPDATE admin SET name='$name', phone='$phone', email='$email', password='$password' WHERE id=$id";
        if (mysqli_query($connection, $update_sql)) {
            echo "<script>alert('Admin updated successfully');</script>";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error: " . $update_sql . "<br>" . mysqli_error($connection);
        }
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $delete_sql = "DELETE FROM admin WHERE id=$id";

    if (mysqli_query($connection, $delete_sql)) {
        echo "<script>alert('Record deleted successfully');</script>";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error: " . $delete_sql . "<br>" . mysqli_error($connection);
    }
}

$edit_admin = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $edit_result = mysqli_query($connection, "SELECT * FROM admin WHERE id=$id");
    $edit_admin = mysqli_fetch_assoc($edit_result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admin</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <center><br><h2>Manage Admin</h2><br></center>

    <!-- Form to add or update an admin -->
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $edit_admin['id'] ?? ''; ?>">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" class="form-control" value="<?php echo $edit_admin['name'] ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone Number:</label>
            <input type="text" name="phone" class="form-control" value="<?php echo $edit_admin['phone'] ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" value="<?php echo $edit_admin['email'] ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <?php if ($edit_admin): ?>
            <button type="submit" name="update_admin" class="btn btn-success">Update Admin</button>
        <?php else: ?>
            <button type="submit" name="add_admin" class="btn btn-primary">Add Admin</button>
        <?php endif; ?>
    </form>
    <br>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Password</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["phone"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["password"] . "</td>";
                    echo "<td>";
                    echo "<a href='?edit=" . $row["id"] . "' class='btn btn-sm btn-info mr-2'>Edit</a>";
                    echo "<a href='?delete=" . $row["id"] . "' class='btn btn-sm btn-danger'>Delete Admin</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No admins found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
mysqli_close($connection);
?>
