<?php
include("connection.php");
include("header.php");
?>
<section style="min-height:550px;">
<?php

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['ticket_no'])) {
    $ticket_no = $_GET['ticket_no'];

    // Fetch booking details including departure date
    $stmt = $connection->prepare("SELECT b.*, r.d_date FROM bookings b INNER JOIN route r ON b.bus_id = r.id WHERE b.ticket_no = ?");
    $stmt->bind_param("s", $ticket_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $booking = $result->fetch_assoc();

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

                echo "<center><h2>Booking Cancelled</h2></center>";
            } else {
                echo "<center><h2>Cancellation Failed</h2></center>";
            }

            $deleteStmt->close();
        } else {
            echo "<center><h2>Cancel Ticket</h2></center>";
            echo "<center><p>Sorry, this ticket cannot be canceled as departure date is less than 24 hours away.</p></center>";
        }
    } else {
        echo "<center><h2>No Ticket Found</h2></center>";
    }

    $stmt->close();
    $connection->close();
} else {
    echo "Invalid request.";
    exit;
}
?>
</section>
<?php
include("footer.php");
?>
