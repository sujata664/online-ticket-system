<?php
include("connection.php");
include("header.php");


$seatsSql = "SELECT bus_id, seat_no, s_booked FROM seats WHERE s_booked = 1";
$seatsResult = mysqli_query($connection, $seatsSql);

$bookedSeats = [];
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

$currentDateTime = date("Y-m-d H:i:s"); 

$sql = "SELECT r.*, b.bus_num
        FROM route r
        LEFT JOIN busses b ON r.id = b.id
        WHERE CONCAT(r.d_date, ' ', r.d_time) > '$currentDateTime'"; 

$result = mysqli_query($connection, $sql);
?>

<section style="min-height:550px;">
    <center>
        <h2>Available Buses</h2>
    </center>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Bus Number</th>
                <th>Origin</th>
                <th>Destination</th>
                <th>Date</th>
                <th>Time</th>
                <th>Cost</th>
                <th>Select Seats</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["bus_num"] . "</td>";
                    echo "<td>" . $row["origin"] . "</td>";
                    echo "<td>" . $row["destination"] . "</td>";
                    echo "<td>" . $row["d_date"] . "</td>";
                    echo "<td>" . $row["d_time"] . "</td>";
                    echo "<td>" . $row["cost"] . "</td>";
                    echo "<td>";
                    echo "<button class='view-seats-btn' type='button' style='background-color: #007bff; color: #fff; border-radius: 4px;' data-bus-id='" . $row["id"] . "' data-bus-num='" . $row["bus_num"] . "' data-origin='" . $row["origin"] . "' data-destination='" . $row["destination"] . "' data-date='" . $row["d_date"] . "' data-time='" . $row["d_time"] . "' data-cost='" . $row["cost"] . "'>View Seats</button>";
                    echo "</td>";
                    echo "</tr>";
                    echo "<tr class='seat-selection' id='seats-for-bus-" . $row["id"] . "' style='display: none;'>";
                    echo "<td colspan='8'>";
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
                    echo "<button onclick='bookNow(" . $row["id"] . ", `" . $row["bus_num"] . "`, `" . $row["origin"] . "`, `" . $row["destination"] . "`, `" . $row["d_date"] . "`, `" . $row["d_time"] . "`, `" . $row["cost"] . "`)' type='button' class='book-now-btn' style='background-color: #007bff; color: #fff; border-radius: 4px;'>Book Now</button>";
                    echo "<div class='legend'>";
                    echo "<div class='legend-item'><span class='legend-color booked'></span> Booked</div>";
                    echo "<div class='legend-item'><span class='legend-color selected'></span> Selected</div>";
                    echo "<div class='legend-item'><span class='legend-color available'></span> Available</div>";
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No buses found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <style>
        .seat-container {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 4px;
        }

        .seat-row {
            display: flex;
            justify-content: center;
            margin-bottom: 2px;
            flex-wrap: wrap;
        }

        .seat-wrapper {
            margin: 2px;
        }

        .seat-gap {
            width: 100%;
            height: 50px;
        }

        .seat-g {
            width: 80%;
            height: 1px;
        }

        .seat {
            display: none;
        }

        .seat-label {
            width: 40px;
            height: 40px;
            text-align: center;
            line-height: 40px;
            border: 1px solid #ccc;
            background-color: #ddd;
            cursor: pointer;
        }

        .seat-booked {
            border-color: red;
            background-color: red;
        }

        .seat:checked + .seat-label {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }

        .legend {
            display: flex;
            justify-content: right;
            margin-bottom: 20px;
        }

        .legend-item {
            display: flex;
            align-items: right;
            margin-right: 15px;
        }

        .legend-color {
            display: inline-block;
            width: 20px;
            height: 20px;
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
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>

    <script>
        function showSeats(busId) {
            var seatSelection = document.getElementById('seats-for-bus-' + busId);
            seatSelection.style.display = seatSelection.style.display === 'none' ? 'table-row' : 'none';
        }

        function bookNow(busId, busNum, origin, destination, date, time, cost) {
            var isLoggedIn = <?php echo isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ? 'true' : 'false'; ?>;

            if (isLoggedIn) {
                var selectedSeats = [];
                var checkboxes = document.querySelectorAll('#seats-for-bus-' + busId + ' .seat:checked');
                checkboxes.forEach(function (checkbox) {
                    selectedSeats.push(checkbox.value);
                });

                if (selectedSeats.length > 0) {
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'confirm_booking.php';

                    var busIdInput = document.createElement('input');
                    busIdInput.type = 'hidden';
                    busIdInput.name = 'bus_id';
                    busIdInput.value = busId;
                    form.appendChild(busIdInput);

                    var busNumInput = document.createElement('input');
                    busNumInput.type = 'hidden';
                    busNumInput.name = 'bus_num';
                    busNumInput.value = busNum;
                    form.appendChild(busNumInput);

                    var originInput = document.createElement('input');
                    originInput.type = 'hidden';
                    originInput.name = 'origin';
                    originInput.value = origin;
                    form.appendChild(originInput);

                    var destinationInput =

                    document.createElement('input');
                    destinationInput.type = 'hidden';
                    destinationInput.name = 'destination';
                    destinationInput.value = destination;
                    form.appendChild(destinationInput);

                    var dateInput = document.createElement('input');
                    dateInput.type = 'hidden';
                    dateInput.name = 'd_date';
                    dateInput.value = date;
                    form.appendChild(dateInput);

                    var timeInput = document.createElement('input');
                    timeInput.type = 'hidden';
                    timeInput.name = 'd_time';
                    timeInput.value = time;
                    form.appendChild(timeInput);

                    var seatsInput = document.createElement('input');
                    seatsInput.type = 'hidden';
                    seatsInput.name = 'selected_seats';
                    seatsInput.value = JSON.stringify(selectedSeats);
                    form.appendChild(seatsInput);

                    var costInput = document.createElement('input');
                    costInput.type = 'hidden';
                    costInput.name = 'total_cost';
                    costInput.value = selectedSeats.length * parseFloat(cost);
                    form.appendChild(costInput);

                    document.body.appendChild(form);
                    form.submit();
                } else {
                    alert('Please select at least one seat.');
                }
            } else {
                $('#modelId1').modal('show');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            var viewSeatsButtons = document.querySelectorAll('.view-seats-btn');
            viewSeatsButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var busId = button.getAttribute('data-bus-id');
                    showSeats(busId);
                });
            });
        });
    </script>
</section>

<!-- Bootstrap JS and dependencies (jQuery, Popper.js) -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<?php
include("footer.php");
?>
