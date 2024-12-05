<?php
// Start session and include database connection
session_start();
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'tolet_for_bachelor';

$con = new mysqli($server, $username, $password, $database);// Replace with your actual connection file

// Ensure only logged-in admin can access
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch admin details
$admin_email = $_SESSION['admin_email'];
$sql = "SELECT * FROM admin WHERE email = '$admin_email'";
$result = mysqli_query($con, $sql);
$admin_data = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .main-header {
            background-color: #343a40;
            color: #fff;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .main-header .profile-menu {
            position: relative;
            display: inline-block;
        }
        .profile-menu .dropdown-menu {
            right: 0;
            left: auto;
        }
        .second-navbar {
            background-color: #007bff;
            padding: 10px;
        }
        .second-navbar a {
            color: #fff;
            margin-right: 15px;
            text-decoration: none;
        }
        .card {
            margin: 20px 0;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card i {
            font-size: 3rem;
            color: #007bff;
        }
        .card-body {
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Main Header -->
    <header class="main-header">
        <h1>Admin Dashboard</h1>
        <div class="profile-menu">
            <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
              
                <?php echo $admin_data['name']; ?>
            </button>
            <ul class="dropdown-menu">
                <!-- <li><a class="dropdown-item" href="admin_profile.php">View Profile</a></li> -->
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>

    <!-- Second Navbar -->
    <nav class="second-navbar">
        <a href="inserthousekeeper.php">Add Housekeeper</a>
        <a href="insert_driver.php">Add Driver</a>

        <a href="host.php">Insert Properties</a>
    </nav>

    <!-- Dashboard Content -->
    <div class="container mt-4">
        <div class="row">
            <!-- Card 1: View All Users -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <i class="fas fa-users"></i>
                        <h5 class="card-title mt-3">View All Users</h5>
                        <p class="card-text">See all registered users in the system.</p>
                        <a href="view_users.php" class="btn btn-primary">View Users</a>
                    </div>
                </div>
            </div>

            <!-- Card 2: View All Properties -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <i class="fas fa-home"></i>
                        <h5 class="card-title mt-3">View All Properties</h5>
                        <p class="card-text">See all listed properties in the system.</p>
                        <a href="view_properties.php" class="btn btn-primary">View Properties</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add more cards as needed -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <i class="fas fa-truck"></i>
                        <h5 class="card-title mt-3">Manage Drivers</h5>
                        <p class="card-text">Add, update, or remove drivers for transport services.</p>
                        <a href="admindiver_view.php" class="btn btn-primary">Manage Drivers</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <i class="fas fa-broom"></i>
                        <h5 class="card-title mt-3">Manage Housekeepers</h5>
                        <p class="card-text">Add, update, or remove housekeeping staff.</p>
                        <a href="adminmangehosuekepper.php" class="btn btn-primary">Manage Housekeepers</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
