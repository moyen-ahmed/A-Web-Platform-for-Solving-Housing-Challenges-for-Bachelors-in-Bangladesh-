<?php
// Start session and include database connection
session_start();
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'tolet_for_bachelor';

$con = new mysqli($server, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Ensure only logged-in admin can access
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch users from Google and registration tables
$google_users = mysqli_query($con, "SELECT id, full_name, ppic, address FROM google");
if (!$google_users) {
    die("Error fetching Google users: " . mysqli_error($con));
}

$registered_users = mysqli_query($con, "SELECT id, first_name,ppic, address FROM registration");
if (!$registered_users) {
    die("Error fetching registered users: " . mysqli_error($con));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
        .profile-menu {
            position: relative;
            display: inline-block;
        }
        .profile-menu .dropdown-menu {
            right: 0;
            left: auto;
        }
        .table-container {
            margin-top: 30px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .table img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <!-- Main Header -->
    <header class="main-header">
    <h1><a href="admin_dashboard.php" style="color: #fff; text-decoration: none;">Dashboard</a></h1>
        <div class="profile-menu">
            <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
               <?php echo $_SESSION['admin_email']; ?> 
            
            </button>
            <ul class="dropdown-menu">
              
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>

    <!-- Users Table -->
    <div class="container">
        <div class="table-container">
            <h2 class="mb-4">All Users</h2>
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Picture</th>
                        <th>Name</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display Google users
                    while ($google_user = mysqli_fetch_assoc($google_users)) {
                        echo "<tr>
                            <td><img src='uploads/{$google_user['ppic']}' alt='Profile Picture'></td>
                            <td>{$google_user['full_name']}</td>
                            <td>{$google_user['address']}</td>
                        </tr>";
                    }

                    // Display registered users
                    while ($registered_user = mysqli_fetch_assoc($registered_users)) {
                        echo "<tr>
                            <td><img src='image/{$registered_user['ppic']}' alt='Profile Picture'></td>
                            <td>{$registered_user['first_name']}</td>
                            <td>{$registered_user['address']}</td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
