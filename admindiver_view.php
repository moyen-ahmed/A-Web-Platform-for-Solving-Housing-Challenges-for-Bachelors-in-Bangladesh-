<?php
// Database connection
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'tolet_for_bachelor';

$con = new mysqli($server, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Fetch driver data
$drivers_sql = "SELECT * FROM van_truck_driver";
$drivers_result = $con->query($drivers_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Drivers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Global Styles */
        .rating {
    display: flex; /* Arrange stars horizontally */
    gap: 2px; /* Add spacing between stars */
    font-size: 1.2rem; /* Adjust size of the stars */
    color: #FFD700; /* Gold color for filled stars */
}

.rating span {
    color: #d3d3d3; /* Light gray color for unfilled stars */
}

        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: rgba(0, 0, 0, 0.4); /* Transparent header */
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 1.5rem;
            margin: 0;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons a {
            text-decoration: none;
            color: white;
            background-color: #28a745; /* Green for "Add Driver" */
            padding: 8px 12px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .action-buttons a:hover {
            background-color: #218838;
        }

        .driver-container {
            margin-top: 80px; /* Adjust for header space */
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .driver-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 300px;
            width: 100%;
            transition: transform 0.3s ease;
        }

        .driver-card:hover {
            transform: translateY(-5px);
        }

        .driver-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .driver-details {
            padding: 15px;
        }

        .driver-details h3 {
            margin: 0;
            font-size: 1.2rem;
            color: #333;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }

        .driver-details p {
            margin: 5px 0;
            font-size: 0.9rem;
            color: #555;
        }

        .rating {
            color: #FFD700;
        }

        .contact-button {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        .contact-button a {
            text-decoration: none;
            font-size: 0.9rem;
            background-color: #34B717FF;
            color: white;
            padding: 8px 10px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .contact-button a:hover {
            background-color: #0077b6;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .driver-card {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
<header>
<h1><a href="admin_dashboard.php" style="color: #fff; text-decoration: none;">Dashboard</a></h1>
    <!-- <div class="action-buttons">
        <a href="insert_driver.php">Add Driver</a>
    </div> -->
</header>
<div class="driver-container">
    <?php if ($drivers_result->num_rows > 0): ?>
        <?php while ($driver = $drivers_result->fetch_assoc()): ?>
            <div class="driver-card">
                <img src="<?= htmlspecialchars($driver['ppic']); ?>" alt="Driver Picture">
                <div class="driver-details">
                    <h3><?= htmlspecialchars($driver['name']); ?></h3>
                    <p><strong>Vehicle Type:</strong> <?= htmlspecialchars($driver['vehicle_type']); ?></p>
                    <p><strong>Hourly Rate:</strong> BDT <?= htmlspecialchars($driver['hourly_rate']); ?></p>
                    <p><strong>Experience:</strong> <?= htmlspecialchars($driver['experience_years']); ?> years</p>
                    <p class="rating">
    <?php
    $total_ratings = $driver['stars_1'] + $driver['stars_2'] + $driver['stars_3'] + $driver['stars_4'] + $driver['stars_5'];
    if ($total_ratings > 0) {
        $average_stars = (
            (1 * $driver['stars_1']) +
            (2 * $driver['stars_2']) +
            (3 * $driver['stars_3']) +
            (4 * $driver['stars_4']) +
            (5 * $driver['stars_5'])
        ) / $total_ratings;
        $average_stars = round($average_stars);
    } else {
        $average_stars = 0;
    }

    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $average_stars) {
            echo '<span>&#9733;</span>'; // Filled star
        } else {
            echo '<span class="unfilled">&#9734;</span>'; // Unfilled star
        }
    }
    ?>
</p>


                    <div class="contact-button">
                        <a href="tel:<?= htmlspecialchars($driver['phone_number']); ?>">Call</a>
                        <a href="https://wa.me/<?= htmlspecialchars($driver['phone_number']); ?>" target="_blank">
                            WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No drivers available at the moment.</p>
    <?php endif; ?>
</div>
</body>
</html>
