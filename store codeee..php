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

// Check if Google login or regular login
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
        $user_phone = $user['phone'] ?? 'Not provided'; // Handle optional phone field
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
        $user_phone = $user['phone'] ?? 'Not provided'; // Handle optional phone field
    } else {
        echo "<p class='text-center'>User not found.</p>";
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Vacation Rentals with Map</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Vacation Rentals with Map</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <style>
  /* General Enhancements */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    overflow-x: hidden;
    color: #333;
}

/* Header */
header {
    background-color: #fff;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #eee;
    position: fixed;
    width: 98%;
    top: 0;
    z-index: 1000;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.logo {
    font-size: 28px;
    font-weight: bold;
    color: #333;
    transition: color 0.3s ease;
}

.logo:hover {
    color: #ff6600;
}

.nav-links {
    list-style: none;
    display: flex;
    align-items: center;
    gap: 20px;
}

.nav-links a {
    color: #333;
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

/* Host Button */
.host-btn {
    background-color: #ff8800;
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.host-btn:hover {
    background-color: #ff6600;
}

/* Login and Register Buttons */
.nav-links li a {
    background-color: #4a00e0;
    color: white;
    padding: 8px 16px;
    font-weight: bold;
    border-radius: 25px;
    text-decoration: none;
    transition: background-color 0.3s ease, transform 0.2s ease;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.nav-links li a:hover {
    background-color: #684F35;
    transform: scale(1.05);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
}

/* Dropdown */
.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #fff;
    min-width: 150px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 5px;
    z-index: 1000;
    left: 0; /* Aligns dropdown to the left edge by default */
    transform: translateX(-50%); /* Centers dropdown relative to the toggle button */
}

.dropdown-content a {
    color: #333;
    padding: 10px 15px;
    text-decoration: none;
    display: block;
    transition: background-color 0.3s ease;
}

.dropdown-content a:hover {
    background-color: #f9f9f9;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
}

/* Banner Section */
.banner {
    height: 600px;
    background-image: url('uploads/75.jpg');
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
    padding: 0 20px;
    box-shadow: inset 0 0 0 2000px rgba(0, 0, 0, 0.5);
}

.banner h1 {
    font-size: 48px;
    line-height: 1.3;
    color: #fff;
    text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);
}

/* Search Bar */
.search-bar {
    display: flex;
    gap: 10px;
    padding: 12px 20px;
    background-color: rgba(255, 255, 255, 0.9);
    border-radius: 25px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    position: absolute;
    top: 70%;
    left: 50%;
    transform: translate(-50%, -50%);
    max-width: 80%;
}

.search-bar input,
.search-bar select,
.search-bar button {
    padding: 10px;
    border-radius: 20px;
    border: 1px solid #ddd;
    font-size: 16px;
}

.search-bar button {
    background-color: #4a00e0;
    color: white;
    cursor: pointer;
    border: none;
    transition: background-color 0.3s ease;
}

.search-bar button:hover {
    background-color: #684F35;
}

/* Map Section */
#map {
    height: 500px;
    margin: 40px 0;
    width: 100%;
}

/* Cards Section */
.cards-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    padding: 20px;
    max-width: 1200px;
    margin: auto;
}

.card {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-10px);
}

.card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.card-content {
    padding: 15px;
    text-align: left;
}

.card-content h3 {
    font-size: 18px;
    margin: 0;
    color: #333;
}

.card-content p {
    font-size: 14px;
    color: #666;
    margin-top: 5px;
}

/* Footer */
footer {
    background-color: #4a00e0;
    color: white;
    padding: 15px;
    text-align: center;
    font-size: 14px;
}

footer a {
    color: white;
    text-decoration: none;
}

/* Media Queries */
@media (max-width: 768px) {
    header {
        padding: 10px;
    }

    .nav-links {
        flex-direction: column;
        gap: 10px;  
    }

    .search-bar {
        flex-direction: column;
        width: 90%;
        top: 60%;
        padding: 15px;
    }

    .banner h1 {
        font-size: 36px;
    }
}

    </style>
</head>
<body>

    <!-- Header Section -->
    <header>
        <div class="logo">ᗷᗩᑕᕼᗴᒪᗝᖇ ᕼᗝᗷᗴ</div>
        <ul class="nav-links">
            <?php if (isset($_SESSION['user_token']) || isset($_SESSION['email'])): ?>
                <li><a href="host.php" class="host-btn">Host</a></li>
                <li class="dropdown">
                    <img src="<?= isset($_SESSION['user_token']) ? "uploads/$user_picture" : "image/$user_picture" ?>" alt="Profile" class="icon">
                    <div class="dropdown-content">
                        <a href="viewprofile.php">View Profile</a>
                        <a href="mngpersonal.php">Manage Profile</a>
                        <a href="logout.php">Logout</a>
                        <a href="viewproperty.php">properties</a>

                    </div>
                </li>
            <?php else: ?>
                <li><a href="register.php">Register</a></li>
                <li><a href="index.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </header>

    <!-- Banner Section with Background Image -->
    <section class="banner">
        <h1>বিয়া না করেই এখন বাসা পাও সহজে</h1>
    </section>

    <!-- Search Bar Section -->
    <div class="search-bar">
        <input type="text" placeholder="Search location">
        <input type="date">
        <select>
            <option value="single-room">Single Room</option>
            <option value="master-room">Master Room</option>
        </select>
        <button>Search</button>
    </div>

    <!-- Map Section -->
    <div id="map">
        <iframe src="https://my.atlist.com/map/bece83e1-9116-4adf-a1dd-dbaefd8ca8db?share=true" allow="geolocation 'self' https://my.atlist.com" width="100%" height="400px" loading="lazy" frameborder="0" scrolling="no" allowfullscreen></iframe>
    </div>

    <!-- Cards Section -->
    <div class="cards-container">
        <?php for ($i = 1; $i <= 20; $i++): ?>
            <div class="card">
                <img src="https://via.placeholder.com/400x200" alt="Property Image">
                <div class="card-content">
                    <h3>Property Title <?= $i ?></h3>
                    <p>Location: City, Country</p>
                    <p>Price: $<?= rand(100, 500) ?>/night</p>
                </div>
            </div>
        <?php endfor; ?>
    </div>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 Bachelor Hobe Naki | <a href="#">Privacy Policy</a></p>
    </footer>

</body>
</html>
 


11/166

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
$properties_sql = "SELECT * FROM properties";
$properties_result = mysqli_query($con, $properties_sql);
// Check if Google login or regular login
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
        $user_phone = $user['phone'] ?? 'Not provided'; // Handle optional phone field
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
        $user_phone = $user['phone'] ?? 'Not provided'; // Handle optional phone field
    } else {
        echo "<p class='text-center'>User not found.</p>";
        exit();
    }
}
// Search functionality
$search_location = '';
$search_room_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch search inputs
    $search_location = mysqli_real_escape_string($con, $_POST['search_location']);
    $search_room_type = mysqli_real_escape_string($con, $_POST['room_type']);

    // Construct SQL query based on user input
    $properties_sql = "SELECT * FROM properties WHERE 1";

    // Add location filter if provided
    if (!empty($search_location)) {
        $properties_sql .= " AND (city LIKE '%$search_location%' OR thana LIKE '%$search_location%')";
    }

    // Add room type filter if selected
    if (!empty($search_room_type)) {
        $properties_sql .= " AND property_type = '$search_room_type'";
    }
} else {
    // Default query if no search is performed
    $properties_sql = "SELECT * FROM properties";
}

$properties_result = mysqli_query($con, $properties_sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Vacation Rentals with Map</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Vacation Rentals with Map</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <style>
  /* General Enhancements */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    overflow-x: hidden;
    color: #333;
}
/* Loading Animation */
#loading {
    position: fixed;
    width: 100%;
    height: 100%;
    background: #fff;
    top: 0;
    left: 0;
    z-index: 2000;
    display: flex;
    justify-content: center;
    align-items: center;
}

.loader {
    width: 50px;
    height: 50px;
    border: 6px solid #ccc;
    border-top: 6px solid #7442D7FF;
    border-radius: 50%;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Header */
header {
    background-color: #fff;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #eee;
    position: fixed;
    width: 98%;
    top: 0;
    z-index: 1000;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.logo {
    font-size: 28px;
    font-weight: bold;
    color: #333;
    transition: color 0.3s ease;
}

.logo:hover {
    color: #ff6600;
}

.nav-links {
    list-style: none;
    display: flex;
    align-items: center;
    gap: 20px;
}

.nav-links a {
    color: #333;
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

/* Host Button */
.host-btn {
    background-color: #ff8800;
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.host-btn:hover {
    background-color: #ff6600;
}

/* Login and Register Buttons */
.nav-links li a {
    background-color: #4a00e0;
    color: white;
    padding: 8px 16px;
    font-weight: bold;
    border-radius: 25px;
    text-decoration: none;
    transition: background-color 0.3s ease, transform 0.2s ease;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.nav-links li a:hover {
    background-color: #684F35;
    transform: scale(1.05);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
}

/* Dropdown */
.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #fff;
    min-width: 150px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 5px;
    z-index: 1000;
    left: 0; /* Aligns dropdown to the left edge by default */
    transform: translateX(-50%); /* Centers dropdown relative to the toggle button */
}

.dropdown-content a {
    color: #333;
    padding: 10px 15px;
    text-decoration: none;
    display: block;
    transition: background-color 0.3s ease;
}

.dropdown-content a:hover {
    background-color: #f9f9f9;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
}

/* Banner Section */
.banner {
    height: 600px;
    background-image: url('uploads/75.jpg');
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
    padding: 0 20px;
    box-shadow: inset 0 0 0 2000px rgba(0, 0, 0, 0.5);
}

.banner h1 {
    font-size: 48px;
    line-height: 1.3;
    color: #fff;
    text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);
}

/* Search Bar */
.search-bar {
    display: flex;
    gap: 10px;
    padding: 12px 20px;
    background-color: rgba(255, 255, 255, 0.9);
    border-radius: 25px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    position: absolute;
    top: 70%;
    left: 50%;
    transform: translate(-50%, -50%);
    max-width: 80%;
}

.search-bar input,
.search-bar select,
.search-bar button {
    padding: 10px;
    border-radius: 20px;
    border: 1px solid #ddd;
    font-size: 16px;
}

.search-bar button {
    background-color: #4a00e0;
    color: white;
    cursor: pointer;
    border: none;
    transition: background-color 0.3s ease;
}

.search-bar button:hover {
    background-color: #684F35;
}

/* Map Section */
#map {
    height: 500px;
    margin: 40px 0;
    width: 100%;
}

.cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .property-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .property-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        }

        .property-images {
            display: flex;
            overflow: hidden;
            border-radius: 12px 12px 0 0;
        }

        .property-images img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .property-details {
            padding: 20px;
        }

        .property-details h3 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        .property-details p {
            font-size: 16px;
            color: #555;
            margin: 5px 0;
        }

        @media (max-width: 768px) {
            .cards-container {
                padding: 20px;
            }

            .property-details h3 {
                font-size: 20px;
            }

            .property-details p {
                font-size: 14px;
            }
        }
/* Media Query for Cards Section */


/* Footer */
footer {
    background-color: #4a00e0;
    color: white;
    padding: 15px;
    text-align: center;
    font-size: 14px;
}

footer a {
    color: white;
    text-decoration: none;
}

/* Media Queries */
@media (max-width: 768px) {
    header {
        padding: 10px;
    }

    .nav-links {
        flex-direction: column;
        gap: 10px;  
    }

    .search-bar {
        flex-direction: column;
        width: 90%;
        top: 60%;
        padding: 15px;
    }

    .banner h1 {
        font-size: 36px;
    }
}

    </style>
</head>
<body>
<div id="loading">
        <div class="loader"></div>
    </div>

    <!-- Header Section -->
    <header>
        <div class="logo">ᗷᗩᑕᕼᗴᒪᗝᖇ ᕼᗝᗷᗴ</div>
        <ul class="nav-links">
            <?php if (isset($_SESSION['user_token']) || isset($_SESSION['email'])): ?>
                <li><a href="host.php" class="host-btn">Host</a></li>
                <li class="dropdown">
                    <img src="<?= isset($_SESSION['user_token']) ? "uploads/$user_picture" : "image/$user_picture" ?>" alt="Profile" class="icon">
                    <div class="dropdown-content">
                        <a href="viewprofile.php">View Profile</a>
                        <a href="mngpersonal.php">Manage Profile</a>
                        <a href="logout.php">Logout</a>
                        <a href="viewproperty.php">properties</a>

                    </div>
                </li>
            <?php else: ?>
                <li><a href="register.php">Register</a></li>
                <li><a href="index.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </header>

    <!-- Banner Section with Background Image -->
    <section class="banner">
        <h1>বিবাহ না করেই এখন বাসা পাও সহজে</h1>
    </section>

    <!-- Search Bar Section -->
<form method="POST" class="search-bar">
    <input type="text" name="search_location" placeholder="Search location" value="<?= htmlspecialchars($search_location); ?>">
    
    <select name="room_type">
        <option value="">Select Room Type</option>
        <option value="single-room" <?= $search_room_type === 'single-room' ? 'selected' : ''; ?>>Single Room</option>
        <option value="master-room" <?= $search_room_type === 'master-room' ? 'selected' : ''; ?>>Master Room</option>
    </select>
    <button type="submit">Search</button>
</form>


    <!-- Map Section -->
    <!-- <div id="map">
    <iframe src="https://www.google.com.qa/maps/d/embed?mid=1or6NfMWchXN7ZQ2K1YJDE1Gn4YciYKY&ehbc=2E312F" width="100%" height="400px" loading="lazy" frameborder="0" scrolling="no"></iframe>

    </div> -->

    <!-- Cards Section -->
    <div class="cards-container">
    <?php if (mysqli_num_rows($properties_result) > 0): ?>
        <?php while ($property = mysqli_fetch_assoc($properties_result)): ?>
            <div class="property-card">
                <div class="property-images">
                    <?php 
                    $images = explode(',', $property['ppic']);
                    foreach ($images as $image): ?>
                        <img src="<?php echo htmlspecialchars($image); ?>" alt="Property Image">
                    <?php endforeach; ?>
                </div>
                <div class="property-details">
                    <h3><?php echo htmlspecialchars($property['property_type']); ?></h3>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($property['city'] . ', ' . $property['thana']); ?></p>
                    <p><strong>Guests:</strong> <?php echo htmlspecialchars($property['number_of_guests']); ?></p>
                    <p><strong>Bedrooms:</strong> <?php echo htmlspecialchars($property['number_of_bedrooms']); ?></p>
                    <p><strong>Bathrooms:</strong> <?php echo htmlspecialchars($property['number_of_bathrooms']); ?></p>
                    <p><strong>Amenities:</strong> <?php echo htmlspecialchars($property['amenities']); ?></p>
                    <p><strong>Check-in Date:</strong> <?php echo htmlspecialchars($property['check_in_date']); ?></p>
                    <p><strong>Check-out Date:</strong> <?php echo htmlspecialchars($property['check_out_date']); ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="no-properties">No properties available at the moment.</p>
    <?php endif; ?>
</div>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 Bachelor Hobe Naki | <a href="#">Privacy Policy</a></p>
    </footer>

</body>
<script>
    window.addEventListener("load", function() {
        document.getElementById("loading").style.display = "none";
    });
</script>

</html>



















card <!-- Property Cards -->
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
                    <p><strong>Guests:</strong> <?= htmlspecialchars($property['number_of_guests']); ?></p>
                    <p><strong>Bedrooms:</strong> <?= htmlspecialchars($property['number_of_bedrooms']); ?></p>
                    <p><strong>Bathrooms:</strong> <?= htmlspecialchars($property['number_of_bathrooms']); ?></p>
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