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

    $stmt = $connection->prepare("
        SELECT b.*, bs.bus_num, r.d_date, r.d_time 
        FROM bookings b 
        JOIN busses bs ON b.bus_id = bs.id 
        JOIN route r ON bs.id = r.id 
        WHERE b.ticket_no = ?");
    $stmt->bind_param("s", $ticket_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $booking = $result->fetch_assoc();
        ?>
        <div class="ticket-container">
            <div class="ticket-header">
                <h1>Bus Ticket</h1>
                <p><strong>Ticket Number:</strong> <?php echo htmlspecialchars($booking['ticket_no']); ?></p>
            </div>
            <div class="ticket-details">
            <p><strong>Bus Number:</strong> <?php echo htmlspecialchars($booking['bus_num']); ?></p>
                <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($_SESSION['customer_name']); ?></p>
                <p><strong>Seats Booked:</strong> <?php echo htmlspecialchars($booking['seats_booked']); ?></p>
                <p><strong>Departure Date:</strong><?php echo htmlspecialchars($booking['d_date']); ?></p>
                 <p><strong>Departure Time:</strong> <?php echo htmlspecialchars($booking['d_time']); ?></p>
                <p><strong>Total Cost:</strong> <?php echo htmlspecialchars($booking['total_cost']); ?></p>
                <p><strong>Booking Date:</strong> <?php echo htmlspecialchars($booking['book_date']); ?></p>

                <!-- Add more fields as needed -->
            </div>
            <div class="ticket-footer">
                <p>Thank you for booking with us. Have a safe journey!</p>
            </div>
            <button class="print-btn" onclick="window.print()">Print Ticket</button>
        </div>

        <style>
            .ticket-container {
                width: 80%;
                max-width: 600px;
                margin: 20px auto;
                padding: 20px;
                border: 2px solid #000;
                border-radius: 10px;
                background-color: #fff;
                box-shadow: 0 0 15px rgba(0,0,0,0.1);
                font-family: 'Arial, sans-serif';
            }

            .ticket-header {
                text-align: center;
                border-bottom: 1px solid #000;
                padding-bottom: 10px;
                margin-bottom: 10px;
            }

            .ticket-header h1 {
                margin: 0;
                font-size: 24px;
            }

            .ticket-details {
                text-align: left;
            }

            .ticket-details p {
                margin: 5px 0;
                font-size: 16px;
            }

            .ticket-footer {
                text-align: center;
                border-top: 1px solid #000;
                padding-top: 10px;
                margin-top: 10px;
            }

            .print-btn {
                display: block;
                width: 100%;
                margin: 10px 0;
                padding: 10px;
                font-size: 16px;
                background-color: #007bff;
                color: white;
                border: none;
                cursor: pointer;
                border-radius: 4px;
                text-align: center;
            }

            .print-btn:hover {
                background-color: #0056b3;
            }
        </style>
        <?php
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

