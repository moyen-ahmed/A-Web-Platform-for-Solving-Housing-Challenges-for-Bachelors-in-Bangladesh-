<?php
// Start session and include database connection
session_start();
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'tolet_for_bachelor';

$con = new mysqli($server, $username, $password, $database);

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Ensure only logged-in admin can access
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch housekeeper data
$sql = "SELECT * FROM housekeeper";
$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Housekeepers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to bottom, #f3f4f6, #ffffff);
            font-family: 'Arial', sans-serif;
        }

        header {
            background-color: rgba(0, 0, 0, 0.4);
            padding: 10px 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: none;
            backdrop-filter: blur(5px);
        }

        header h1 {
            color: white;
            margin: 0;
        }

        header .profile-menu {
            position: relative;
        }

        header .btn {
            color: white;
        }

        .container {
            padding-top: 100px;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .card {
            width: 18rem;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card-body {
            padding: 15px;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
        }

        .card-text {
            color: #555;
        }

        .stars {
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .stars i {
            color: #FFD700;
        }

        .contact-btn {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .contact-btn a {
            flex: 1;
            margin-right: 5px;
            text-align: center;
            text-decoration: none;
            color: white;
            background-color: #25D366; /* WhatsApp green */
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .contact-btn a:last-child {
            margin-right: 0;
        }

        footer {
            margin-top: 50px;
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            color: #666;
        }
    </style>
</head>
<body>
<header>
<h1><a href="admin_dashboard.php" style="color: #fff; text-decoration: none;">Dashboard</a></h1>
    <div class="profile-menu">
        <!-- <a href="inserthousekeeper.php" class="btn btn-success me-2">Insert Housekeeper</a> -->
        
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="admin_profile.php">View Profile</a></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
        </ul>
    </div>
</header>

<div class="container">
        <div class="card-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($housekeeper = $result->fetch_assoc()): ?>
                    <div class="card">
                        <img src="<?= htmlspecialchars($housekeeper['ppic']); ?>" alt="Housekeeper Image">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($housekeeper['name']); ?></h5>
                            <p class="card-text">
                                <strong>Best Comment:</strong> <?= htmlspecialchars($housekeeper['specialization']); ?><br>
                                <strong>Hourly Rate:</strong> BDT <?= htmlspecialchars($housekeeper['hourly_rate']); ?><br>
                                <strong>Experience:</strong> <?= htmlspecialchars($housekeeper['experience_years']); ?> years
                            </p>
                            <div class="stars">
                                <?php
                                $total_ratings = array_sum([
                                    $housekeeper['stars_1'], 
                                    $housekeeper['stars_2'], 
                                    $housekeeper['stars_3'], 
                                    $housekeeper['stars_4'], 
                                    $housekeeper['stars_5']
                                ]);

                                if ($total_ratings > 0) {
                                    $average_stars = (
                                        1 * $housekeeper['stars_1'] + 
                                        2 * $housekeeper['stars_2'] + 
                                        3 * $housekeeper['stars_3'] + 
                                        4 * $housekeeper['stars_4'] + 
                                        5 * $housekeeper['stars_5']
                                    ) / $total_ratings;
                                    $average_stars = round($average_stars);
                                } else {
                                    $average_stars = 0;
                                }

                                for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star<?= $i <= $average_stars ? '' : '-o'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <div class="contact-btn">
                                <a href="https://wa.me/<?= htmlspecialchars($housekeeper['phone_number']); ?>" target="_blank">WhatsApp</a>
                                <a href="tel:<?= htmlspecialchars($housekeeper['phone_number']); ?>">Call</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No housekeepers available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        &copy; <?= date('Y'); ?> ToLet for Bachelor. All Rights Reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>