<?php
require_once 'config.php'; // Import Google and DB config

if (isset($_GET['code'])) {
    // Authenticate and fetch the access token using the authorization code
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    // Set the access token in the client
    $client->setAccessToken($token['access_token']);
    
    // Get the user's Google profile information
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    
    // Store user information
    $userinfo = [
        'email' => $google_account_info->email,
        'first_name' => $google_account_info->givenName,
        'last_name' => $google_account_info->familyName,
        'full_name' => $google_account_info->name,
        'picture' => $google_account_info->picture,
        'verified_email' => $google_account_info->verifiedEmail,
        'token' => $google_account_info->id
    ];
    
    // Download the profile picture and save it to the server
    $image_url = $google_account_info->picture;
    $image_path = 'uploads/' . $userinfo['email'] . '.jpg';
    file_put_contents($image_path, file_get_contents($image_url));

    // Check if the user already exists in the database
    $sql = "SELECT * FROM google WHERE email = '{$userinfo['email']}'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // Fetch user info if they exist
        $userinfo = mysqli_fetch_assoc($result);
        $token = $userinfo['token'];
    } else {
        // Insert the new user into the database, including the saved image path
        $sql = "INSERT INTO google (email, first_name, last_name, full_name, picture, verified_email, token) 
                VALUES ('{$userinfo['email']}', '{$userinfo['first_name']}', '{$userinfo['last_name']}', '{$userinfo['full_name']}', '$image_path', '{$userinfo['verified_email']}', '{$userinfo['token']}')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $token = $userinfo['token'];
        } else {
            echo "Error inserting user: " . mysqli_error($conn);
            die();
        }
    }

    // Store user token in the session
    $_SESSION['user_token'] = $token;
} else {
    // If there's no code, check if the user is already logged in
    if (!isset($_SESSION['user_token'])) {
        header("Location: index.php");
        die();
    }

    // Fetch user information using the session token
    $sql = "SELECT * FROM google WHERE token = '{$_SESSION['user_token']}'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $userinfo = mysqli_fetch_assoc($result);
    } else {
        echo "User not found in session.";
        die();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
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

    <!-- First Navbar -->
    <div class="navbar">
        <a href="#" class="brand">BACHELOR HOBE</a>
        <a href="home.php" class="button">Home</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <!-- Display Profile Picture from the server -->
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
