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
        $sql = "INSERT INTO google (email, first_name, last_name, full_name, ppic, verified_email, token) 
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

    // Redirect to home.php after successful login
    header("Location: home.php");
    exit();
} else {
    // If there's no code, check if the user is already logged in
    if (!isset($_SESSION['user_token'])) {
        header("Location: index.php");
        exit();
    }
}
?>
