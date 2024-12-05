<?php
// Start session and include database connection
session_start();
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'tolet_for_bachelor';

$con = new mysqli($server, $username, $password, $database); // Replace with your actual connection file

// Ensure only logged-in admin can access
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit;
}

// Search functionality
$search_location = '';
$search_room_type = '';
$properties_sql = "SELECT * FROM properties";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search_location = $con->real_escape_string($_POST['search_location']);
    $search_room_type = $con->real_escape_string($_POST['room_type']);

    $properties_sql = "SELECT * FROM properties WHERE 1";
    if (!empty($search_location)) {
        $properties_sql .= " AND (city LIKE '%$search_location%' OR thana LIKE '%$search_location%')";
    }
    if (!empty($search_room_type)) {
        $properties_sql .= " AND property_type = '$search_room_type'";
    }
}

$properties_result = $con->query($properties_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Properties</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        /* Header Styling */
        .main-header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .main-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .profile-menu .btn {
            background: white;
            color: #007bff;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .profile-menu .btn:hover {
            background: #0056b3;
            color: white;
        }
        .cards-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            gap: 20px;
            padding: 20px;
        }
        .property-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 300px;
            width: 100%;
        }
        .property-images {
            height: 200px;
            position: relative;
        }
        .property-images img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .property-images img:hover {
            transform: scale(1.05);
        }
        .property-details {
            padding: 15px;
        }
        .property-details h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #007bff;
        }
        .property-details p {
            font-size: 14px;
            margin: 5px 0;
            color: #333;
        }
        .property-icons {
            display: flex;
            gap: 10px;
            margin: 10px 0;
        }
        .property-icons span {
            display: flex;
            align-items: center;
            font-size: 14px;
        }
        .property-icons img {
            width: 20px;
            margin-right: 5px;
        }
        .star-rating {
            margin: 10px 0;
            color: gold;
        }
        .view-details-button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
        }
        .view-details-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Main Header -->
    <header class="main-header">
    <h1><a href="admin_dashboard.php" style="color: #fff; text-decoration: none;">Dashboard</a></h1>
        <div class="profile-menu">
            <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                Admin
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="admin_profile.php">View Profile</a></li>
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>

    <!-- Property Cards -->
    <div class="cards-container">
        <?php if ($properties_result->num_rows > 0): ?>
            <?php while ($property = $properties_result->fetch_assoc()): ?>
                <div class="property-card">
                    <div class="property-images">
                        <?php foreach (explode(',', $property['ppic']) as $image): ?>
                            <img src="<?= htmlspecialchars($image); ?>" alt="Property Image">
                        <?php endforeach; ?>
                    </div>
                    <div class="property-details">
                        <h3><?= htmlspecialchars($property['property_type']); ?></h3>
                        <p><strong>Location:</strong> <?= htmlspecialchars($property['city'] . ', ' . $property['thana']); ?></p>
                        <div class="property-icons">
                            <span>
                                <img src="pictures/bed.png" alt="Bedrooms">
                                <?= htmlspecialchars($property['number_of_bedrooms']); ?>
                            </span>
                            <span>
                                <img src="pictures/bath.png" alt="Bathrooms">
                                <?= htmlspecialchars($property['number_of_bathrooms']); ?>
                            </span>
                        </div>
                        <p><strong>Price: BDT</strong> <?= htmlspecialchars($property['price']); ?></p>
                        <div class="star-rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="star">&#9733;</span>
                            <?php endfor; ?>
                        </div>
                       
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No properties available at the moment.</p>
        <?php endif; ?>
    </div>

    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
