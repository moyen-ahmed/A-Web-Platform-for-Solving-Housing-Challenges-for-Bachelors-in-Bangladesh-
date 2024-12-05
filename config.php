<?php
require_once 'vendor/autoload.php';

session_start();

// Google API credentials

$clientID = "ID";
$clientSecret = "getenv('GOOGLE_CLIENT_ID')";

$redirectUri = "http://localhost/demo/welcome.php";

// Create Google Client
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// Database connection
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'tolet_for_bachelor';

$conn = mysqli_connect($server, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
