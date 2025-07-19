<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="../img/pn.png">
  <title>Admin Dashboard</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f0f0f0;
    }
    .sidebar {
      background-color: #3e5569;
      color: #fff;
      height: 100vh;
    }
    .sidebar a {
      color: #fff;
      padding: 10px 20px;
      display: block;
      text-decoration: none;
    }
    .sidebar a:hover {
      background-color: #546d82;
    }
    .content {
      padding: 20px;
    }
    .widget {
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin-bottom: 20px;
      background-color: #fff;
      height: 200px; 
    }
    .widget h2 {
      color: #333;
    }
    .widget p {
      color: #666;
    }
    .widget .btn {
      width: 100%;
      padding: 15px 0;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light"style="background-color:#87a6d8;">
  <a class="navbar-brand" href="#"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
      <a class="nav-link" href="../home.php">Log Out</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">
    <nav class="col-md-2 sidebar" style="background-color:#003366;">
      <div class="sidebar-sticky">
      <img src="../img/nwp.png" style="width: 100px;" />
        <h4 class=" pb-4 pl-3">Admin Dashboard</h4>
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link active" href="busses.php">Buses</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="routes.php">Routes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="seats.php">Seats</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="customer.php">Customers</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="bookings.php">Bookings</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="messages.php">Messages</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="earnings.php">Earnings</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login.php">Admin</a>
          </li>
        </ul>
      </div>
    </nav>

    <main role="main" class="col-md-10 content">
      <div class="row">
        <div class="col-md-3">
          <div class="widget">
            <h2>Buses</h2>
            <button onclick="window.location.href ='busses.php'" class="btn btn-primary">Manage Buses</button>
          </div>
        </div>
        <div class="col-md-3">
          <div class="widget">
            <h2>Routes</h2>
            <button onclick="window.location.href='routes.php'" class="btn btn-primary">Update Routes</button>
          </div>
        </div>
        <div class="col-md-3">
          <div class="widget">
            <h2>Seats</h2>
            <button onclick="window.location.href='seats.php'" class="btn btn-primary">Maintain Seats</button>
          </div>
        </div>
        <div class="col-md-3">
          <div class="widget">
            <h2>Customers</h2>
            <button onclick="window.location.href='customer.php'" class="btn btn-primary">Customers</button>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <div class="widget">
            <h2>Bookings</h2>
            <button onclick="window.location.href='bookings.php'" class="btn btn-primary">Bookings</button>
          </div>
        </div>
        <div class="col-md-3">
          <div class="widget">
            <h2>Messages</h2>
            <button onclick="window.location.href='messages.php'" class="btn btn-primary">Customer Messages</button>
          </div>
          </div>
          <div class="col-md-3">
          <div class="widget">
            <h2>Earnings</h2>
            <button onclick="window.location.href='earnings.php'" class="btn btn-primary">Earnings</button>
          </div>
        </div>
        <div class="col-md-3">
          <div class="widget">
            <h2>Admin</h2>
            <button onclick="window.location.href='login.php'" class="btn btn-primary">Manage Admin</button>
          </div>
      </div>
    </main>
  </div>
</div>


<!-- Bootstrap JS and dependencies (jQuery, Popper.js) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
