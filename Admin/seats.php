<?php
include("connection.php"); 

$busNumFilter = isset($_GET['bus_num']) ? mysqli_real_escape_string($connection, $_GET['bus_num']) : '';

$bookedSeats = [];

if ($busNumFilter) {
    // Fetch booked seats for the searched bus
    $seatsSql = "SELECT bus_id, seat_no, s_booked FROM seats WHERE s_booked = 1 AND bus_id = (SELECT id FROM busses WHERE bus_num = '$busNumFilter')";
    $seatsResult = mysqli_query($connection, $seatsSql);

    if ($seatsResult && mysqli_num_rows($seatsResult) > 0) {
        while ($row = mysqli_fetch_assoc($seatsResult)) {
            $busId = $row['bus_id'];
            $seatNo = $row['seat_no'];
            if (!isset($bookedSeats[$busId])) {
                $bookedSeats[$busId] = [];
            }
            $bookedSeats[$busId][] = $seatNo;
        }
    }
}
?>
<style>
    textarea,input[type=text]{
    width: 30%;
    padding: 9px;
    border: 2px solid black;
    border-radius: 5px ;
    }
</style>

<center>
    <h2>View Seats</h2>
    <form method="GET" id="searchForm">
        <input type="text" name="bus_num" id="bus_num" placeholder="Enter Bus Number" value="<?php echo htmlspecialchars($busNumFilter); ?>">
        <button type="submit">Search</button>
    </form>
</center>

<?php
if ($busNumFilter) {
    $currentDateTime = date("Y-m-d H:i:s");

    $sql = "SELECT r.*, b.bus_num
            FROM route r
            LEFT JOIN busses b ON r.id = b.id
            WHERE CONCAT(r.d_date, ' ', r.d_time) > '$currentDateTime' AND b.bus_num LIKE '%$busNumFilter%'";

    $result = mysqli_query($connection, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<h3>Bus Number: " . $row["bus_num"] . "</h3>";
            echo "<div class='seat-container'>";
            for ($i = 1; $i <= 40; $i++) {
                if ($i == 11 || $i == 31) {
                    echo "<div class='seat-g'></div>";
                }
                if ($i == 21) {
                    echo "<div class='seat-gap'></div>";
                }
                if ($i % 10 == 1) {
                    echo "<div class='seat-row'>";
                }

                $seatClass = '';
                $disabled = '';

                // Check if the seat is booked
                if (isset($bookedSeats[$row["id"]]) && in_array($i, $bookedSeats[$row["id"]])) {
                    $seatClass = ' seat-booked';
                    $disabled = 'disabled';
                }

                echo "<div class='seat-wrapper'>";
                echo "<input type='checkbox' id='seat" . $i . "-bus-" . $row["id"] . "' class='seat' name='seat" . $row["id"] . "' value='" . $i . "' $disabled>";
                echo "<label for='seat" . $i . "-bus-" . $row["id"] . "' class='seat-label$seatClass'>" . $i . "</label>";
                echo "</div>";

                if ($i % 10 == 0) {
                    echo "</div>";
                }
            }
            echo "</div>";
        }
    } else {
        echo "<p>No buses found with the number '$busNumFilter'</p>";
    }
}
?>

<style>
    .seat-container {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        background-color: #f9f9f9;
        border-radius: 1px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .seat-row {
        display: flex;
        justify-content: center;
        margin-bottom: 1px;
        flex-wrap: wrap;
    }

    .seat-wrapper {
        margin: 1px;
        flex-basis: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .seat-gap {
        width: 100%;
        height: 50px;
    }

    .seat-g {
        width: 80%;
        height: 1px;
        margin-bottom: 10px;
    }

    .seat {
        display: none;
    }

    .seat-label {
        width:40px;
        height: 50px;
        text-align: center;
        line-height: 50px;
        border: 1px solid #ccc;
        background-color: #ddd;
        cursor: pointer;
        border-radius: 5px;
        font-size: 15px;
    }

    .seat-booked {
        border-color: red;
        background-color: red;
        cursor: not-allowed;
    }

    .seat:checked + .seat-label {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }

    .legend {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        margin-right: 15px;
    }

    .legend-color {
        display: inline-block;
        width: 20px;
        height: 20px;
        margin-right: 5px;
    }

    .available {
        background-color: #ddd;
    }

    .selected {
        background-color: #007bff;
    }

    .booked {
        background-color: red;
    }

    .legend-text {
        font-size: 14px;
    }

    button {
        display: block;
        margin: 10px auto;
        padding: 10px 20px;
        font-size: 16px;
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    button:hover {
        background-color: #0056b3;
    }
</style>


