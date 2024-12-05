<?php 
session_start();

// Database connection
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'tolet_for_bachelor';

$con = new mysqli($server, $username, $password, $database);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Check if Google login or regular login
if (isset($_SESSION['user_token'])) {
    // Google login
    $token = $_SESSION['user_token'];
    $sql = "SELECT * FROM google WHERE token = '$token'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $user_name = $user['full_name'];
        $user_email = $user['email'];
        $user_picture = $user['ppic'];
        $user_phone = $user['phone'] ?? 'Not provided'; // Handle optional phone field
    } else {
        echo "<p class='text-center'>User not found.</p>";
        exit();
    }
} else if (isset($_SESSION['email'])) {
    // Regular email login
    $email = $_SESSION['email'];
    $sql = "SELECT * FROM registration WHERE email = '$email'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $user_name = $user['first_name'];
        $user_email = $user['email'];
        $user_picture = $user['ppic'] ?? 'default-avatar.png';
        $user_phone = $user['phone'] ?? 'Not provided'; // Handle optional phone field
    } else {
        echo "<p class='text-center'>User not found.</p>";
        exit();
    }
} else {
    echo "<p class='text-center'>You are not logged in.</p>";
    exit();
}

// Close the database connection
$con->close();
?>

<!doctype html>
<html lang="en">
<head>
    <title>View Profile</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            background: #000;
        }
        .profile-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .profile-content img {
            border-radius: 50%;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">BACHELOR HOBA</a>
    <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?= (isset($_SESSION['user_token'])) ? "uploads/$user_picture" : "image/$user_picture" ?>" alt="Profile" class="rounded-circle" width="30" height="30"> <?= $user_name ?>
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="home.php">Profile</a></li>
                    <li><a class="dropdown-item" href="mngpersonal.php">Edit Profile</a></li>
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="profile-content">
                <!-- Profile Picture -->
                <img src="<?= (isset($_SESSION['user_token'])) ? "uploads/$user_picture" : "image/$user_picture" ?>" alt="Profile Picture" width="150" height="150">
                
                <!-- User Info -->
                <h3><?= $user_name ?></h3>
                <p><strong>Email:</strong> <?= $user_email ?></p>
                <p><strong>Phone:</strong> <?= $user_phone ?></p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
