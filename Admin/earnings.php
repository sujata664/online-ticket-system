<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Earnings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            margin-top: 20px;
        }
        .table thead th {
            background-color: #343a40;
            color: #fff;
        }
        .table tbody tr:hover {
            background-color: #f1f1f1;
        }
        .no-data-message {
            text-align: center;
            margin-top: 20px;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <?php
    include("connection.php");

    // Initialize an array to store earnings by day
    $earnings_by_day = [];

    $query = "SELECT DATE(b.book_date) as booking_day, SUM(b.total_cost) as total_earnings 
              FROM bookings b 
              GROUP BY booking_day 
              ORDER BY booking_day DESC";

    $result = $connection->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $earnings_by_day[] = $row;
        }
    } else {
        $message = "<div class='no-data-message'><h2>No earnings data available</h2></div>";
    }

    $connection->close();
    ?>

    <section style="min-height:550px;">
        <div class="container mt-5">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Daily Earnings</h2>
                </div>
                <div class="card-body">
                    <?php if (!empty($earnings_by_day)): ?>
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Total Earnings</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($earnings_by_day as $earnings): ?>
                                    <tr>
                                        <td><?php echo $earnings['booking_day']; ?></td>
                                        <td><?php echo number_format($earnings['total_earnings'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <?php echo $message; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
