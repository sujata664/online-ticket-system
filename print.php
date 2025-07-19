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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['print_ticket_submit'])) {
    $ticket_no = $_POST['print_ticket_no'];

    // Fetch booking details
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

        // Check if the booking belongs to the logged-in user
        if ($booking['cus_id'] == $cus_id) {
            // Display ticket details
            $message = "<div class='ticket-container'>
                            <div class='ticket-header'>
                                <h1>Bus Ticket</h1>
                                <p><strong>Ticket Number:</strong> " . htmlspecialchars($booking['ticket_no']) . "</p>
                            </div>
                            <div class='ticket-details'>
                                <p><strong>Bus Number:</strong> " . htmlspecialchars($booking['bus_num']) . "</p>
                                <p><strong>Customer Name:</strong> " . htmlspecialchars($_SESSION['customer_name']) . "</p>
                                <p><strong>Seats Booked:</strong> " . htmlspecialchars($booking['seats_booked']) . "</p>
                                <p><strong>Departure Date:</strong> " . htmlspecialchars($booking['d_date']) . "</p>
                                <p><strong>Departure Time:</strong> " . htmlspecialchars($booking['d_time']) . "</p>
                                <p><strong>Booking Date:</strong> " . htmlspecialchars($booking['book_date']) . "</p>
                                <p><strong>Total Cost:</strong> " . htmlspecialchars($booking['total_cost']) . "</p>
                                <!-- Add other booking details as required -->
                            </div>
                            <div class='ticket-footer'>
                                <p>Thank you for booking with us. Have a safe journey!</p>
                            </div>
                            <button class='print-btn' onclick='window.print()'>Print Ticket</button>
                        </div>";
        } else {
            $message = "<center><h2>Permission Denied</h2></center>";
            $message .= "<center><p>Error: You do not have permission to view this ticket.</p></center>";
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
        <?php if (empty($message)) { ?>
            <h2>Print Ticket</h2>
            <form id="print-ticket-form" method="POST" action="">
                <div class="form-group">
                    <label for="print_ticket_no">Ticket Number</label>
                    <input type="text" class="form-control" id="print_ticket_no" name="print_ticket_no" placeholder="Enter ticket number" required>
                </div>
                <button type="submit" class="btn btn-primary" name="print_ticket_submit">Print Ticket</button>
            </form>
        <?php } ?>
        <div id="ticket-message">
            <?php
            // Display the message below the form
            if ($message) {
                echo $message;
            }
            ?>
        </div>
    </div>
</section>


