<?php 
session_start(); 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database logic for adding properties...
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listing</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 20%;
            background-color: #ffffff;
            border-right: 1px solid #ddd;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 15px;
            margin-bottom: 10px;
            color: #333;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .sidebar ul li.active,
        .sidebar ul li:hover {
            background-color: #f0f0f0;
            font-weight: bold;
            color: #ff6600;
        }

        .main-content {
            width: 80%;
            padding: 40px;
            background-color: #f9f9f9;
        }

        h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .property {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .property:hover {
            transform: scale(1.02);
        }

        .property img {
            width: 100%;
            height: auto;
            max-height: 300px;
            margin-bottom: 15px;
            border-radius: 8px;
            object-fit: cover;
        }

        .property h3 {
            font-size: 20px;
            color: #ff6600;
            margin-bottom: 10px;
        }

        .property p {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
        }

        .no-properties {
            color: #d9534f;
            font-weight: bold;
            font-size: 18px;
            text-align: center;
        }
    </style>
</head>

<body>

<?php
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'tolet_for_bachelor';

// Database connection
$con = new mysqli($server, $username, $password, $database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Check if Google login or regular login
$user_name = '';
$user_email = '';
$user_picture = '';
$user_phone = 'Not provided';

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
        $user_phone = $user['phone'] ?? 'Not provided';
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
        $user_phone = $user['phone'] ?? 'Not provided';
    } else {
        echo "<p class='text-center'>User not found.</p>";
        exit();
    }
}

// Fetch properties associated with the user email
$properties_sql = "SELECT * FROM properties WHERE pro_email = '$user_email'";
$properties_result = mysqli_query($con, $properties_sql);
?>

<div class="container">
    <div class="sidebar">
        <ul>
            <li class="active">My Properties</li>
            <li>Other Options</li>
        </ul>
    </div>
    <div class="main-content">
        <h2>My Property Listings</h2>

        <?php if (mysqli_num_rows($properties_result) > 0): ?>
            <?php while ($property = mysqli_fetch_assoc($properties_result)): ?>
                <div class="property">
                    <h3><?php echo htmlspecialchars($property['property_type']); ?></h3>
                    <?php 
                    $images = explode(',', $property['ppic']);
                    foreach ($images as $image): ?>
                        <img src="<?php echo htmlspecialchars($image); ?>" alt="Property Image">
                    <?php endforeach; ?>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($property['city'] . ', ' . $property['thana']); ?></p>
                    <p><strong>Guests:</strong> <?php echo htmlspecialchars($property['number_of_guests']); ?></p>
                    <p><strong>Bedrooms:</strong> <?php echo htmlspecialchars($property['number_of_bedrooms']); ?></p>
                    <p><strong>Bathrooms:</strong> <?php echo htmlspecialchars($property['number_of_bathrooms']); ?></p>
                    <p><strong>Amenities:</strong> <?php echo htmlspecialchars($property['amenities']); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-properties">You don't have any properties listed.</p>
        <?php endif; ?>
    </div>
</div>

<?php
$con->close();
?>
</body>

</html>
