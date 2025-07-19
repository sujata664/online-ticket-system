<?php
include("connection.php");
include("header.php");
?>
<section style="min-height: 550px;">
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $bus_id = $_POST['bus_id'];
    $bus_num = $_POST['bus_num'];
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $date = $_POST['d_date'];
    $time = $_POST['d_time'];
    $cost = $_POST['total_cost'];
    $selected_seats = json_decode($_POST['selected_seats'], true);

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
        $user_id = $_SESSION['customer_id'];
    } else {
        echo "User not logged in.";
        exit;
    }

    if (isset($_POST['finalize'])) {
        $ticket_no = 'TICKET-' . uniqid();
        // Convert selected seats array to comma-separated string
        $seats_booked = implode(", ", $selected_seats);

        // Prepare and execute SQL statement to insert booking details
        $stmt = $connection->prepare("INSERT INTO bookings (ticket_no, cus_id, bus_id, seats_booked, total_cost, book_date) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("siisi", $ticket_no, $user_id, $bus_id, $seats_booked, $cost);

        // Execute SQL statement
        if ($stmt->execute()) {
            // Insert new records into seats table for the selected seats
            foreach ($selected_seats as $seat_no) {
                $insertSeatStmt = $connection->prepare("INSERT INTO seats (bus_id, seat_no, s_booked) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE s_booked = 1");
                $insertSeatStmt->bind_param("ii", $bus_id, $seat_no);
                $insertSeatStmt->execute();
                $insertSeatStmt->close();
            }
           
            echo "<center><h2>Booking Confirmed</h2></center>";
            echo "<center><p>Thank you for booking with us.</p></center>";
            echo "<center><p>Your Ticket Number: <strong>$ticket_no</strong></p></center>";
            
            echo "<center>
                    <div class='button-container'>
                        <a href='print_ticket.php?ticket_no=$ticket_no' class='btn' style='background-color: #28a745; color: #fff; padding: 8px 20px; border-radius: 4px; text-decoration: none;'>Print Ticket</a>
                        <a href='cancel_ticket.php?ticket_no=$ticket_no' class='btn' style='background-color: #dc3545; color: #fff; padding: 8px 20px; border-radius: 4px; text-decoration: none;'>Cancel Ticket</a>
                    </div>
                    <div class='button-container'>
                        <a href='home.php' class='btn' style='background-color: #007bff; color: #fff; padding: 8px 20px; margin-top: 50px; border-radius: 4px; text-decoration: none;'>Back To Home</a>
                    </div>
                  </center>";
        } else {
            echo "<center><h2>Booking Failed</h2></center>";
            echo "<center><p>Sorry, there was an error processing your booking.</p></center>";
        }
    
        // Close statement and connection
        $stmt->close();
        $connection->close();
    } else {
        // Displaying booking summary for confirmation
        ?>
        <center>
            <h2>Booking Details</h2>
        </center>
        <div class="summary-container">
            <table class="table">
                <tr>
                    <th>Bus Number</th>
                    <td><?php echo htmlspecialchars($bus_num); ?></td>
                </tr>
                <tr>
                    <th>Origin</th>
                    <td><?php echo htmlspecialchars($origin); ?></td>
                </tr>
                <tr>
                    <th>Destination</th>
                    <td><?php echo htmlspecialchars($destination); ?></td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td><?php echo htmlspecialchars($date); ?></td>
                </tr>
                <tr>
                    <th>Time</th>
                    <td><?php echo htmlspecialchars($time); ?></td>
                </tr>
                <tr>
                    <th>Seats</th>
                    <td><?php echo implode(", ", $selected_seats); ?></td>
                </tr>
                <tr>
                    <th>Total Cost</th>
                    <td><?php echo htmlspecialchars($cost); ?></td>
                </tr>
            </table>
            <form method="POST" action="confirm_booking.php">
                <input type="hidden" name="bus_id" value="<?php echo htmlspecialchars($bus_id); ?>">
                <input type="hidden" name="bus_num" value="<?php echo htmlspecialchars($bus_num); ?>">
                <input type="hidden" name="origin" value="<?php echo htmlspecialchars($origin); ?>">
                <input type="hidden" name="destination" value="<?php echo htmlspecialchars($destination); ?>">
                <input type="hidden" name="d_date" value="<?php echo htmlspecialchars($date); ?>">
                <input type="hidden" name="d_time" value="<?php echo htmlspecialchars($time); ?>">
                <input type="hidden" name="total_cost" value="<?php echo htmlspecialchars($cost); ?>">
                <input type="hidden" name="selected_seats" value='<?php echo json_encode($selected_seats); ?>'>
                <input type="hidden" name="finalize" value="true">
                <button type="submit" class="finalize-btn" style="background-color: #007bff; color: #fff; padding: 10px 20px; border-radius: 4px;">Confirm Booking</button>
            </form>
        </div>
        </section>
        <style>
            
    .summary-container {
        margin: 0 auto;
        max-width: 600px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: #f9f9f9;
        text-align: center;
    }

    .table {
        width: 100%;
        margin-bottom: 20px;
        border-collapse: collapse;
    }

    .table th, .table td {
        padding: 8px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .finalize-btn {
        display: block;
        width: 100%;
        padding: 10px;
        font-size: 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-align: center;
    }

    .button-container {
        display: flex;
        justify-content: center;
        gap: 50px; /* Set gap between buttons */
        margin-top: 20px; /* Spacing above the button container */
    }

    .btn {
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        font-size: 16px;
        border-radius: 4px;
        color: #fff;
        transition: background-color 0.3s ease;
        margin: 20 10px; /* Add margin for additional spacing */
    }

    .print-btn {
        background-color: #28a745;
    }

    .print-btn:hover {
        background-color: #218838;
    }

    .cancel-btn {
        background-color: #dc3545;
    }

    .cancel-btn:hover {
        background-color: #c82333;
    }

</style>

        <?php
    }
} else {
    echo "Invalid request.";
    exit;
}
?>
</section>
<?php
include("footer.php");
?>
