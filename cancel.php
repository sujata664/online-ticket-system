<?php

include("connection.php");
include("header.php");

// Initialize variables
$message = '';

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Get the current logged-in user ID
$cus_id = $_SESSION['customer_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_ticket_submit'])) {
    $ticket_no = $_POST['cancel_ticket_no'];

    // Fetch booking details including departure date and validate cus_id
    $stmt = $connection->prepare("
        SELECT b.*, r.d_date 
        FROM bookings b 
        INNER JOIN route r ON b.bus_id = r.id 
        WHERE b.ticket_no = ?");
    $stmt->bind_param("s", $ticket_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $booking = $result->fetch_assoc();

        // Check if the logged-in user is the one who booked the ticket
        if ($booking['cus_id'] == $cus_id) {
            // Calculate time difference between departure date and current date
            $departure_date = strtotime($booking['d_date']);
            $current_date = time();
            $time_difference = $departure_date - $current_date;

            // Convert seconds to hours
            $hours_difference = floor($time_difference / (60 * 60));

            // Check if cancellation is allowed based on departure date
            if ($hours_difference >= 24) {
                // Delete booking
                $deleteStmt = $connection->prepare("DELETE FROM bookings WHERE ticket_no = ?");
                $deleteStmt->bind_param("s", $ticket_no);
                if ($deleteStmt->execute()) {
                    // Also, free the booked seats
                    $seats = explode(", ", $booking['seats_booked']);
                    foreach ($seats as $seat_no) {
                        $freeSeatStmt = $connection->prepare("UPDATE seats SET s_booked = 0 WHERE bus_id = ? AND seat_no = ?");
                        $freeSeatStmt->bind_param("ii", $booking['bus_id'], $seat_no);
                        $freeSeatStmt->execute();
                        $freeSeatStmt->close();
                    }

                    $message = "<center><h2>Booking Cancelled</h2></center>";
                } else {
                    $message = "<center><h2>Cancellation Failed</h2></center>";
                }

                $deleteStmt->close();
            } else {
                $message = "<center><h2>Cancel Ticket</h2></center>";
                $message .= "<center><p>Sorry, this ticket cannot be canceled as departure date is less than 24 hours away.</p></center>";
            }
        } else {
            $message = "<center><h2>Permission Denied</h2></center>";
            $message .= "<center><p>You do not have permission to cancel this ticket.</p></center>";
        }
    } else {
        $message = "<center><h2>No Ticket Found</h2></center>";
        $message .= "<center><p>Error: No booking found for this ticket number.</p></center>";
    }

    $stmt->close();
    $connection->close();
}
?>

<section style="min-height:550px;">
    <div class="container mt-5">
        <h2>Cancel Ticket</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="cancel_ticket_no">Ticket Number</label>
                <input type="text" class="form-control" id="cancel_ticket_no" name="cancel_ticket_no" placeholder="Enter ticket number" required>
            </div>
            <button type="submit" class="btn btn-danger" name="cancel_ticket_submit">Cancel Ticket</button>
        </form>
        <?php
        // Display the message below the form
        if ($message) {
            echo $message;
        }
        ?>
    </div>
</section>

<?php
include("footer.php");
?>
