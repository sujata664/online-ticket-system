<?php
include("connection.php");

// Check if the update booking form is submitted
if (isset($_POST['update_booking'])) {
    $booking_id = $_GET['id']; // Get the booking ID from the URL parameter
    $new_cus_id = $_POST['cus_id'];
    $new_bus_id = $_POST['bus_id'];
    $new_seats_booked = $_POST['seats_booked'];
    $new_book_date = $_POST['book_date'];
    $new_total_cost = $_POST['total_cost'];
    $new_ticket_no = $_POST['ticket_no']; // Added name attribute to the input field
    // Update the booking details in the database
    $update_sql = "UPDATE bookings SET cus_id='$new_cus_id', bus_id='$new_bus_id', seats_booked='$new_seats_booked', book_date='$new_book_date', total_cost='$new_total_cost', ticket_no='$new_ticket_no' WHERE id=$booking_id"; // Added space before WHERE
    if (mysqli_query($connection, $update_sql)) {
        echo "<script>alert('Booking updated successfully');</script>";
        header("Location: bookings.php"); // Redirect to the bookings page after updating
        exit;
    } else {
        echo "Error: " . $update_sql . "<br>" . mysqli_error($connection);
    }
}

// Fetch the existing booking data
if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    // Retrieve booking details from the database
    $select_sql = "SELECT * FROM bookings WHERE id=$booking_id";
    $result = mysqli_query($connection, $select_sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $booking_data = mysqli_fetch_assoc($result);
        $current_cus_id = $booking_data['cus_id'];
        $current_bus_id = $booking_data['bus_id'];
        $current_seats_booked = $booking_data['seats_booked'];
        $current_book_date = $booking_data['book_date'];
        $current_total_cost = $booking_data['total_cost'];
        $current_ticket_no = $booking_data['ticket_no'];
    } else {
        echo "Booking not found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Booking</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Update Booking</h2>
        <form method="post" action="update_booking.php?id=<?php if (isset($_GET['id'])) echo $_GET['id']; ?>">
            <div class="form-group">
                <label>Customer ID</label>
                <input type="text" placeholder="Enter Customer ID" name="cus_id" value="<?php if (isset($current_cus_id)) echo $current_cus_id; ?>" required>
                <label>Bus ID</label>
                <input type="text" placeholder="Enter Bus ID" name="bus_id" value="<?php if (isset($current_bus_id)) echo $current_bus_id; ?>" required>
                <label>Seats Booked</label>
                <input type="text" placeholder="Enter Seats Booked" name="seats_booked" value="<?php if (isset($current_seats_booked)) echo $current_seats_booked; ?>" required>
                <label>Booking Date</label>
                <input type="date" name="book_date" value="<?php if (isset($current_book_date)) echo $current_book_date; ?>" required>
                <label>Total Cost</label>
                <input type="text" placeholder="Enter Total Cost" name="total_cost" value="<?php if (isset($current_total_cost)) echo $current_total_cost; ?>" required>
                <label>Ticket No</label> <!-- Added label for ticket number -->
                <input type="text" placeholder="Enter Ticket no" name="ticket_no" value="<?php if (isset($current_ticket_no)) echo $current_ticket_no; ?>" required> <!-- Added name attribute -->
            </div>
            <button type="submit" class="btn btn-primary" name="update_booking">Update Booking</button>
        </form>
    </div>
    <!-- Bootstrap JS and dependencies (jQuery, Popper.js) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
