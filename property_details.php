<?php 
// Start session and database connection
session_start();

$server = 'localhost';
$username = 'root';
$password = '';
$database = 'tolet_for_bachelor';

$con = new mysqli($server, $username, $password, $database);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Determine login type and fetch user details
$user_name = $user_email = $user_picture = $user_phone = '';
if (isset($_SESSION['user_token'])) {
    $token = $_SESSION['user_token'];
    $sql = "SELECT * FROM google WHERE token = '$token'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_name = $user['full_name'];
        $user_email = $user['email'];
        $user_picture = $user['ppic'];
        $user_phone = $user['phone'] ?? 'Not provided';
    } else {
        echo "<p class='text-center'>User not found.</p>";
        exit();
    }
} elseif (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $sql = "SELECT * FROM registration WHERE email = '$email'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_name = $user['first_name'];
        $user_email = $user['email'];
        $user_picture = $user['ppic'] ?? 'default-avatar.png';
        $user_phone = $user['phone'] ?? 'Not provided';
    } else {
        echo "<p class='text-center'>User not found.</p>";
        exit();
    }
}
// Check if property_id is passed
if (!isset($_GET['property_id'])) {
    echo "<p class='text-center'>Invalid property selected.</p>";
    exit();
}

$property_id = intval($_GET['property_id']); // Sanitize input

// Fetch property details
$sql = "SELECT * FROM properties WHERE property_id = $property_id";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) === 1) {
    $property = mysqli_fetch_assoc($result);
    $phn = $property['phn'];

} else {
    echo "<p class='text-center'>Property not found.</p>";
    exit();
}

// Fetch host details based on `pro_email`
$pro_email = $property['pro_email'];
$host_sql = "
    SELECT 
        id, first_name, ppic 
    FROM google 
    WHERE email = '$pro_email'
    UNION 
    SELECT 
        id, first_name, ppic 
    FROM registration 
    WHERE email = '$pro_email'
";
$host_result = mysqli_query($con, $host_sql);
$host = mysqli_fetch_assoc($host_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Details</title>
    <!-- Swiper.js Styles -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <style>
        
/* Header */
/* Navbar Styles */
header {
    background-color: transparent; /* Slightly transparent white */
    padding: 15px 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed; /* Fixed position to stay at the top */
    top: 0;
    width: 100%;
    z-index: 1000;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(5px);
     /* Glassmorphism effect */
}

header ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
    gap: 20px; /* Adjust spacing between nav items */
}

header li {
    position: relative;
}

header li a {
    color: #333;
    text-decoration: none;
    font-weight: bold;
    font-size: 16px;
    padding: 10px 15px;
    border-radius: 5px;
    transition: all 0.3s ease;
}

header li a:hover {
    background-color: #ff6600;
    color: #fff;
    transform: scale(1.05);
}

.logo {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    text-transform: uppercase;
    text-decoration: none;
}

.logo:hover {
    color: #ff6600;
}

/* Dropdown Styles */
.dropdown-content {
    display: none;
    position: absolute;
    top: 120%; /* Position below the dropdown button */
    right: 0;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    overflow: hidden;
    white-space: nowrap;
}

.dropdown-content a {
    color: #333;
    padding: 10px 20px;
    text-decoration: none;
    display: block;
    transition: all 0.3s ease;
}

.dropdown-content a:hover {
    background-color: #f9f9f9;
    color: #ff6600;
}

.dropdown:hover .dropdown-content {
    display: block;
}

/* Profile Icon */
.icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    border: 2px solid #ddd;
    transition: border-color 0.3s ease;
}

.icon:hover {
    border-color: #ff6600;
}
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
          
        
        }

        .swiper-container {
            width: 100%;
            height: 50vh;
          
        }

        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .property-container {
            max-width: 900px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            
        }

        .property-header {
            padding: 20px;
            border-bottom: 1px solid #ddd;
        }

        .property-header h1 {
            margin: 0;
            font-size: 28px;
        }

        .property-header p {
            color: #555;
        }

        .property-section {
            padding: 20px;
        }

        .property-section h2 {
            font-size: 22px;
            margin-bottom: 15px;
        }

        .property-overview {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .property-overview-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .property-overview-item img {
            width: 24px;
            height: 24px;
        }

        .property-overview-item span {
            font-size: 16px;
            color: #444;
        }

        .host-info {
            display: flex;
            align-items: center;
            margin-top: 20px;
            padding: 15px;
            background: #f0f0f0;
            border-radius: 8px;
        }

        .host-info img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .action-button {
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .call-button {
            background-color: #28a745;
        }

        .whatsapp-button {
            background-color: #25D366;
        }

        .call-button:hover, .whatsapp-button:hover {
            opacity: 0.9;
        }
        @media (max-width: 768px) {
    header {
        flex-direction: column;
        align-items: flex-start;
    }

    header ul {
        flex-direction: column;
        gap: 10px;
    }

    .dropdown-content {
        top: 100%; /* Adjust for mobile */
        right: auto;
        left: 0;
    }
}
    </style>
</head>
<body>
<header>
    <a href="index.php" class="logo">ᗷᗩᑕᕼᗴᒪᗝᖇ ᕼᗝᗷᗴ</a>
    <ul>
        <li><a href="blog.php">Blog</a></li>
        <li><a href="guide.php">Guides</a></li>
        <li><a href="services.php">Services</a></li>
        <li><a href="contact.php">Contact</a></li>
        <?php if (isset($_SESSION['user_token']) || isset($_SESSION['email'])): ?>
            <li><a href="host.php" class="host-btn">Host</a></li>
            <li class="dropdown">
                <img src="<?= isset($_SESSION['user_token']) ? "uploads/$user_picture" : "image/$user_picture" ?>" alt="Profile" class="icon">
                <div class="dropdown-content">
                    <a href="viewprofile.php">View Profile</a>
                    <a href="mngpersonal.php">Manage Profile</a>
                    <a href="viewproperty.php">Properties</a>
                    <a href="logout.php">Logout</a>
                </div>
            </li>
        <?php else: ?>
            <li><a href="register.php">Register</a></li>
            <li><a href="index.php">Login</a></li>
        <?php endif; ?>
    </ul>
</header>

    <div class="property-container">
        <!-- Swiper Image Carousel -->
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php
                $images = explode(',', $property['ppic']);
                foreach ($images as $image): ?>
                    <div class="swiper-slide">
                        <img src="<?php echo htmlspecialchars($image); ?>" alt="Property Image">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Property Details -->
        <div class="property-header">
            <h1><?php echo htmlspecialchars($property['property_type']); ?></h1>
            <p><?php echo htmlspecialchars($property['city'] . ', ' . $property['thana']); ?></p>
        </div>

        <div class="property-section">
            <h2>Overview</h2>
            <div class="property-overview">
                <div class="property-overview-item">
                    <img src="pictures/bed.png" alt="Bedrooms">
                    <span><?php echo htmlspecialchars($property['number_of_bedrooms']); ?> Bedrooms</span>
                </div>
                <div class="property-overview-item">
                    <img src="pictures/bath.png" alt="Bathrooms">
                    <span><?php echo htmlspecialchars($property['number_of_bathrooms']); ?> Bathrooms</span>
                </div>
                <div class="property-overview-item">
                    <img src="pictures/gst.png" alt="Parking">
                    <span><?php echo htmlspecialchars($property['number_of_guests']); ?> Num of Geust</span>
                </div>
                <div class="property-overview-item">
                    <img src="pictures/taka.png" alt="Area">
                    <span><?php echo htmlspecialchars($property['price']); ?> Taka</span>
                </div>
            </div>
        </div>

        <div class="property-section">
            <h2>Description</h2>
            <h5>Hose Policies :</h5>
            <p><?php echo nl2br(htmlspecialchars($property['house_policies'])); ?></p>
        
     
            <h5>Cancellation Policies :</h5>
            <p><?php echo nl2br(htmlspecialchars($property['house_policies'])); ?></p>
        </div>

        <div class="property-section">
            <h2>Host Details</h2>
            <?php if ($host): ?>
                <div class="host-info">
                    <img src="pictures/men.jpeg" alt="Host Picture">
                    <div>
                        <p><strong><?php echo htmlspecialchars($host['first_name']); ?></strong></p>
                    </div>
                </div>
            <?php else: ?>
                <p>Host information not available.</p>
            <?php endif; ?>
        </div>

        <div class="property-section">
            <h2>Contact</h2>
            <div class="action-buttons">
                <a href="tel:<?php echo htmlspecialchars($phn); ?>" class="action-button call-button">Call</a>
                <a href="https://wa.me/<?php echo htmlspecialchars($phn); ?>" target="_blank" class="action-button whatsapp-button">WhatsApp</a>
            </div>
        </div>
    </div>

    <!-- Swiper.js Script -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper-container', {
            slidesPerView: 1,
            spaceBetween: 10,
            loop: true,
            autoplay: {
                delay: 2000,
                disableOnInteraction: false,
            },
        });
    </script>
</body>
</html>
