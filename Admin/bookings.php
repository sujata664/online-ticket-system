<?php
include("connection.php");

// Add new booking
if (isset($_POST['add_booking'])) {
    $cus_id = $_POST['cus_id'];
    $bus_id = $_POST['bus_id'];
    $seats_booked = $_POST['seats_booked'];
    $book_date = $_POST['book_date'];
    $total_cost = $_POST['total_cost'];
    $ticket_no = $_POST['ticket_no'];

    // Insert booking details into bookings table
    $booking_sql = "INSERT INTO bookings (cus_id, bus_id, seats_booked, book_date, total_cost,ticket_no) VALUES ('$cus_id', '$bus_id', '$seats_booked', '$book_date','$total_cost','$ticket_no')";
    if (mysqli_query($connection, $booking_sql)) {
        // Insert booked seats into seats table
        $seats = explode(", ", $seats_booked);
        foreach ($seats as $seat_no) {
            $seat_sql = "INSERT INTO seats (bus_id, seat_no, s_booked) VALUES ('$bus_id', '$seat_no', 1) ON DUPLICATE KEY UPDATE s_booked = 1";
            mysqli_query($connection, $seat_sql);
        }
        echo "<script>alert('Booking created successfully');</script>";
    } else {
        echo "Error: " . $booking_sql . "<br>" . mysqli_error($connection);
    }
}

// Delete booking
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete_sql = "DELETE FROM bookings WHERE id=$id";

    if (mysqli_query($connection, $delete_sql)) {
        echo "<script>alert('Booking deleted successfully');</script>";
    } else {
        echo "Error: " . $delete_sql . "<br>" . mysqli_error($connection);
    }
}

// Fetch all bookings
$sql = "SELECT * FROM bookings";
$result = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Add New Booking</h2>
        <form method="post">
            <div class="form-group">
                <label>Customer ID</label>
                <input type="text" class="form-control" placeholder="Enter Customer ID" name="cus_id" required>
                <label>Bus ID</label>
                <input type="text" class="form-control" placeholder="Enter Bus ID" name="bus_id" required>
                <label>Seat No</label>
                <input type="text" class="form-control" placeholder="Enter Seat no" name="seats_booked" required>
                <label>Booking Date</label>
                <input type="date" class="form-control" name="book_date" required>
                <label>Total cost</label>
                <input type="text" class="form-control" placeholder="Enter Cost" name="total_cost" required>
                <label>Ticket No</label>
                <input type="text" class="form-control" placeholder="Enter Ticket no" name="ticket_no" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add_booking">Add Booking</button>
        </form>

        <hr>
        <h2>Existing Bookings</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer ID</th>
                    <th>Bus ID</th>
                    <th>Seats Booked</th>
                    <th>Booking Date</th>
                    <th>Total cost</th>
                    <th>Ticket No</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["cus_id"] . "</td>";
                        echo "<td>" . $row["bus_id"] . "</td>";
                        echo "<td>" . $row["seats_booked"] . "</td>";
                        echo "<td>" . $row["book_date"] . "</td>";
                        echo "<td>" . $row["total_cost"] . "</td>";
                        echo "<td>" . $row["ticket_no"] . "</td>";
                        echo "<td>";
                        echo "<a href='update_booking.php?id=" . $row["id"] . "' class='btn btn-sm btn-info mr-2'>Update</a>";
                        echo "<a href='?delete=" . $row["id"] . "' class='btn btn-sm btn-danger'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No bookings found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div
