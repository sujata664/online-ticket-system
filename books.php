<?php
include("connection.php");
include("header.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_now'])) {
    $cusId = 1; 
    $busId = $_POST['bus_id'];
    $routeId = $_POST['route_id'];
    $seats = $_POST['seats']; 
    $bookDate = date("Y-m-d"); 

    foreach ($seats as $seatNo) {
        // Insert into bookings table
        $sql = "INSERT INTO bookings (cus_id, bus_id, seat_id, route_id, book_date) VALUES ('$cusId', '$busId', '$seatNo', '$routeId', '$bookDate')";

        if (mysqli_query($connection, $sql)) {
            // Mark seat as booked
            $updateSeatSql = "UPDATE seats SET s_booked=1 WHERE bus_id='$busId' AND seat_no='$seatNo'";
            mysqli_query($connection, $updateSeatSql);
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    }

    echo "<script>alert('Booking successful!');</script>";
  

$sql = "SELECT r.*, b.bus_num, s.seat_no, s.s_booked
        FROM route r
        LEFT JOIN busses b ON r.id = b.route_id
        LEFT JOIN seats s ON b.id = s.bus_id";
$result = mysqli_query($connection, $sql);
}
include("footer.php");
?> 