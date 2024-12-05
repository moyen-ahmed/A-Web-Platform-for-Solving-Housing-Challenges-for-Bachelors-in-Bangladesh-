<?php
session_start();
require_once 'config.php'; // Import Google and DB config

// Fetch user information using the session token
if (!isset($_SESSION['user_token'])) {
    header("Location: index.php");
    exit();
}

$sql = "SELECT * FROM google WHERE token = '{$_SESSION['user_token']}'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $userinfo = mysqli_fetch_assoc($result);
} else {
    echo "User not found in session.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Profile</title>
    <style>
        /* Add some global styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        /* Navbar styling */
        .navbar {
            display: flex;
            justify-content: space-between;
            background-color: #333;
            padding: 10px;
            border-radius: 20px;
            margin: 20px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 14px 20px;
        }
        .navbar a:hover {
            background-color: #575757;
            border-radius: 10px;
        }
        /* Profile picture styling */
        .profile-picture {
            border-radius: 50%;
            width: 90px;
            height: 90px;
            border: 3px solid #333;
        }
        /* Centered container */
        .content {
            text-align: center;
            margin-top: 50px;
        }
        /* Styling for the name and email */
        .user-info {
            font-size: 1.2rem;
            margin: 20px 0;
        }
        .user-info .full-name {
            font-weight: bold;
            color: #0066cc;
        }
        .user-info .email {
            font-style: italic;
            color: #ff6600;
        }
        /* Button styling */
        .button {
            background-color: #0066cc;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 10px;
            text-decoration: none;
        }
        .button:hover {
            background-color: #004b99;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <a href="#" class="brand">BACHELOR HOBE</a>
        <a href="home.php" class="button">Home</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <!-- Display Profile Picture from the database -->
        <img src="<?= $userinfo['picture'] ?>" class="profile-picture" alt="Profile Picture" onerror="this.onerror=null; this.src='default-avatar.png';"/>
        
        <!-- User Information -->
        <div class="user-info">
            <p class="full-name">Full Name: <?= $userinfo['full_name'] ?></p>
            <p class="email">Email: <?= $userinfo['email'] ?></p>
        </div>
        
        <!-- Logout Button -->
        <a href="logout.php" class="button">Logout</a>
    </div>

</body>
</html>
