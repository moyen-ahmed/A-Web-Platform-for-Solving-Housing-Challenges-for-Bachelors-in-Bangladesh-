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
    

    <title> Rentals</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <link rel="stylesheet" href="styleee.css">
</head>
<body>
<!-- <div id="loading">
    <div class="loader"></div>
</div> -->

<!-- Header Section -->
<header>
    <div class="logo">ᗷᗩᑕᕼᗴᒪᗝᖇ ᕼᗝᗷᗴ</div>
    <ul class="nav-links">
        <li><a href="allproperties.php">All Properties</a></li>
        <li class="dropdown">
            <a href="#">Accessories</a>
            <div class="dropdown-content">
                <a href="viewhousekeeper.php">Housekeeper</a>
                <a href="viewdriver.php">Van/Truck</a>
                <!-- <a href="accessories3.php"></a> -->
            </div>
        </li>
        <li><a href="guide.php">Guides</a></li>
        <li><a href="service.php">Services</a></li>
        <li><a href="about.php">About</a></li>
        <?php if (isset($_SESSION['user_token']) || isset($_SESSION['email'])): ?>
            <li><a href="host.php" class="host-btn">Host</a></li>
            <li class="dropdown">
                <img src="<?= isset($_SESSION['user_token']) ? "uploads/$user_picture" : "image/$user_picture" ?>" alt="Profile" class="icon">
                <div class="dropdown-content">
                    <a href="viewprofile.php">View Profile</a>
                    <a href="mngpersonal.php">Manage Profile</a>
                    <a href="logout.php">Logout</a>
                    <a href="viewproperty.php">Properties</a>
                </div>
            </li>
        <?php else: ?>
            <li><a href="index.php">Host</a></li>
            <li><a href="index.php">Login</a></li>
        <?php endif; ?>
    </ul>
</header>
<!-- Second Navigation Bar -->
<!-- <nav class="secondary-nav">
    <ul class="secondary-nav-links">
        <li><a href="accessories.php">Accessories</a></li>
        <li><a href="housekeeper.php">Housekeeper</a></li>
    </ul>
</nav> -->

<!-- Banner Section -->
<section class="banner">
    
</section>

<!-- Search Bar -->
<form method="POST" class="search-bar">
    <input type="text" name="search_location" placeholder="Search location" value="<?= htmlspecialchars($search_location); ?>">
    <select name="room_type">
        <option value="">Select Room Type</option>
        <option value="single-room" <?= $search_room_type === 'single-room' ? 'selected' : ''; ?>>Single Room</option>
        <option value="master-room" <?= $search_room_type === 'master-room' ? 'selected' : ''; ?>>Master Room</option>
    </select>
    <button type="submit">Search</button>
</form>

<!-- Property Cards -->
<div class="cards-container">
    <?php if ($properties_result->num_rows > 0): ?>
        <?php while ($property = $properties_result->fetch_assoc()): ?>
            <div class="property-card">
                <div class="property-images">
                    <?php foreach (explode(',', $property['ppic']) as $index => $image): ?>
                        <img src="<?= htmlspecialchars($image); ?>" alt="Property Image" class="property-image" style="animation-delay: <?= $index * 1.5; ?>s;">
                    <?php endforeach; ?>
                </div>
                <div class="property-details">
                    <h3><?= htmlspecialchars($property['property_type']); ?></h3>
                    <p><strong>Location:</strong> <?= htmlspecialchars($property['city'] . ', ' . $property['thana']); ?></p>
                    <div class="property-icons">
                        <span>
                            <img src="pictures/bed.png" alt="Bedrooms" class="icon">
                            <?= htmlspecialchars($property['number_of_bedrooms']); ?>
                        </span>
                        <span>
                            <img src="pictures/bath.png" alt="Bathrooms" class="icon">
                            <?= htmlspecialchars($property['number_of_bathrooms']); ?>
                        </span>
                        <!-- <span>
                            <img src="path/to/size-icon.png" alt="Property Size" class="icon">
                          
                        </span> -->
                    </div>
                    <p><strong>Price: BDT</strong> <?= htmlspecialchars($property['price']); ?></p>
                    <div class="star-rating">
                        <!-- Static stars for now, replace with dynamic PHP logic later -->
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <span class="star">&#9733;</span>
                        <?php endfor; ?>
                    </div>
                    <a href="property_details.php?property_id=<?= $property['property_id']; ?>" class="view-details-button">View Details</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No properties available at the moment.</p>
    <?php endif; ?>
</div>

<h1>Most Famouse Rentals Hause in Bangladesh</h1>
<div class="slider-container">
    <div class="slick-slider">
        <div class="slide">
            <img src="p4.gif" alt="House 1">
            <div class="slide-text">Beautiful House In Basundhora</div>
        </div>
        <div class="slide">
            <img src="p2.jpeg" alt="House 2">
            <div class="slide-text">Modern Apartment in the City</div>
        </div>
        <div class="slide">
            <img src="p3.jpg" alt="House 3">
            <div class="slide-text">Cozy Cottage by the Lake</div>
        </div>
        <div class="slide">
            <img src="p1.jpg" alt="House 4">
            <div class="slide-text">Luxurious Mansion</div>
        </div>
    </div>
</div>


<footer>
  <div class="footer-container">
    <div class="footer-section">
      <h3>Bachelor Hobea</h3>
      <ul>
        <li><a href="#">About us</a></li>
        <li><a href="#">Careers</a></li>
        <li><a href="#">Investors</a></li>
        <li><a href="#">HomeToGo stock</a></li>
        <li><a href="#">App</a></li>
        <li><a href="#">Product features</a></li>
        <li><a href="#">Insights</a></li>
        <li><a href="#">Inspiration</a></li>
      </ul>
    </div>
    <div class="footer-section">
      <h3>Contact</h3>
      <ul>
        <li><a href="#">Help Center and contact</a></li>
        <li><a href="#">List your home</a></li>
        <li><a href="#">Become an affiliate partner</a></li>
        <li><a href="#">Press</a></li>
      </ul>
    </div>
    <div class="footer-section">
      <h3>Legal policies</h3>
      <ul>
        <li><a href="#">Terms of Service</a></li>
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="#">Legal</a></li>
        <li><a href="#">How the platform works</a></li>
        <li><a href="#">Security</a></li>
        <li><a href="#">Content Guidelines</a></li>
      </ul>
    </div>
    <div class="footer-section">
      <h3>Follow us</h3>
      <div class="social-icons">
        <a href="#"><img src="pictures/inst.png" alt="Instagram"></a>
        <a href="#"><img src="pictures/fb.png" alt="Facebook"></a>
        <a href="#"><img src="pictures/lnk.png" alt="LinkedIn"></a>
        <a href="#"><img src="pictures/tktk.png" alt="TikTok"></a>
      </div>
      <h3>Download our apps</h3>
      <div class="app-buttons">
        <img src="pictures/aple.png" alt="App Store">
        <img src="pictures/and.png" alt="App Store">
      </div>
      <select class="language-select">
        <option>English (US)</option>
      </select>
    </div>
  </div>
  <div class="payment-section">
  <div class="payment-title">Pay With</div>
  <div class="payment-logos">
    <!-- Add each logo as an image -->
    <img src="pictures/visa.png" alt="Visa" />
    <img src="pictures/mscard.png" alt="MasterCard" />
    <img src="pictures/amce.png" alt="American Express" />
    <img src="pictures/bkash.png" alt="bKash" />
    <img src="pictures/nagad.png" alt="Nagad" />
    <img src="pictures/rocket.png" alt="Rocket" />
    <img src="pictures/dbbl.jpg" alt="DBBL" />
    <!-- Add more payment logos as needed -->
  </div>
  <div class="verified-by">
    <span>Verified By</span>
    <img src="pictures/as.jpeg" alt="SSLCommerz" />
  </div>
</div>

  <div class="footer-bottom">
    <p>Bachelor Hobe_</p>
  </div>
</footer>


<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Slick Slider Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

    <!-- Initialize Slick Slider -->
    <script>
        $(document).ready(function () {
            $('.slick-slider').slick({
                dots: true,               // Enable dots for navigation
                arrows: false,            // Disable navigation arrows
                autoplay: true,           // Enable autoplay
                autoplaySpeed: 3000,      // Set autoplay speed (3 seconds)
                fade: true,               // Enable fade transition
                cssEase: 'linear',        // Smooth fade effect
            });
        });
    </script>
</body>


</html>
