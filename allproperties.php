<?php
session_start();

$server = 'localhost';
$username = 'root';
$password = '';
$database = 'tolet_for_bachelor';

$con = new mysqli($server, $username, $password, $database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Filtering Logic
$filter_query = "SELECT * FROM properties";
$filters = [];

if (!empty($_GET['location'])) {
    $location = $_GET['location'];
    $filters[] = "city = '" . $con->real_escape_string($location) . "'";
}
if (!empty($_GET['max_price'])) {
    $max_price = $_GET['max_price'];
    $filters[] = "price <= " . intval($max_price);
}
if (!empty($_GET['min_price'])) {
    $min_price = $_GET['min_price'];
    $filters[] = "price >= " . intval($min_price);
}
if (!empty($_GET['room_type'])) {
    $room_type = $_GET['room_type'];
    $filters[] = "property_type = '" . $con->real_escape_string($room_type) . "'";
}
if (!empty($_GET['sort_by'])) {
    $sort_by = $_GET['sort_by'] == 'low_to_high' ? 'ASC' : 'DESC';
}

if (count($filters) > 0) {
    $filter_query .= " WHERE " . implode(" AND ", $filters);
}

$filter_query .= isset($sort_by) ? " ORDER BY price $sort_by" : "";

$result = $con->query($filter_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Properties</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: whitesmoke;
        }
/* General Header Styling */
.navbar {
    background: transparent; /* Glass effect */
    backdrop-filter: blur(10px); /* Glass blur */
    padding: 10px 20px;
    position: fixed;
    top: 0;
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    z-index: 1000;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Logo Styling */
.logo a {
    font-size: 1.5rem;
    font-weight: bold;
    color: #ff5722; /* Orange color */
    text-decoration: none;
    transition: transform 0.3s ease;
}

.logo a:hover {
    transform: scale(1.1);
}

/* Nav Links */
.nav-links ul {
    list-style: none;
    display: flex;
    gap: 20px;
}

.nav-links .nav-link {
    text-decoration: none;
    font-size: 1rem;
    color: #333;
    transition: color 0.3s ease, border-bottom 0.3s ease;
    position: relative;
}

.nav-links .nav-link:hover {
    color: #ff5722; /* Orange hover effect */
}

.nav-links .nav-link::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: -5px;
    width: 0;
    height: 2px;
    background: #ff5722;
    transition: width 0.3s ease;
}

.nav-links .nav-link:hover::after {
    width: 100%;
}

/* Mobile Menu Icon */
.menu-icon {
    display: none;
    flex-direction: column;
    gap: 4px;
    cursor: pointer;
}

.menu-icon span {
    width: 25px;
    height: 3px;
    background-color: #333;
}

/* Responsive Design */
@media (max-width: 768px) {
    .menu-icon {
        display: flex;
    }

    .nav-links {
        display: none;
    }

    .nav-links ul {
        flex-direction: column;
    }

    .nav-links.active {
        display: flex;
        position: absolute;
        top: 60px;
        left: 0;
        width: 100%;
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
}


        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 80px auto 0;
        }

        .filter-section {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .filter-section input, .filter-section select, .filter-section button {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .filter-section .price-range {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .property-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .property-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .property-card:hover {
            transform: scale(1.02);
        }

        .property-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .property-details {
            padding: 15px;
        }

        .property-details h3 {
            margin: 0;
            font-size: 1.5rem;
            color: #333;
        }

        .property-details p {
            margin: 5px 0;
            color: #555;
        }

        .property-details .icon {
            margin-right: 5px;
            color: #f57c00;
        }

        .property-price {
            margin-top: 10px;
            font-size: 1.2rem;
            color: #28a745;
            font-weight: bold;
        }
    </style>
</head>
<body>

<header class="navbar">
    <div class="logo">
        <a href="#">Bachelor Hobe</a>
    </div>
    <nav class="nav-links">
        <ul>
            <li><a href="home.php" class="nav-link">Home</a></li>
            <li><a href="guide.php" class="nav-link">Guide</a></li>
            <li><a href="service.php" class="nav-link">Services</a></li>
            <li><a href="#contact" class="nav-link">Contact</a></li>
        </ul>
    </nav>
    <div class="menu-icon">
        <!-- Hamburger menu for mobile -->
        <span></span>
        <span></span>
        <span></span>
    </div>
</header>


<div class="container">
    <div class="filter-section">
        <form action="" method="get">
            <input type="text" name="location" placeholder="Location" value="<?php echo htmlspecialchars($_GET['location'] ?? ''); ?>">
            <div class="price-range">
                <input type="number" name="min_price" placeholder="Min Price" value="<?php echo htmlspecialchars($_GET['min_price'] ?? ''); ?>">
                <input type="number" name="max_price" placeholder="Max Price" value="<?php echo htmlspecialchars($_GET['max_price'] ?? ''); ?>">
            </div>
            <select name="room_type">
                <option value="">Select Room Type</option>
                <option value="single_room" <?php if(isset($_GET['room_type']) && $_GET['room_type'] == 'single_room') echo 'selected'; ?>>Single Room</option>
                <option value="master_room" <?php if(isset($_GET['room_type']) && $_GET['room_type'] == 'master_room') echo 'selected'; ?>>Master Room</option>
                <option value="semi_master" <?php if(isset($_GET['room_type']) && $_GET['room_type'] == 'semi_master') echo 'selected'; ?>>Semi Master</option>
                <option value="apartment" <?php if(isset($_GET['room_type']) && $_GET['room_type'] == 'apartment') echo 'selected'; ?>>Apartment</option>
            </select>
            <select name="sort_by">
                <option value="low_to_high" <?php if(isset($_GET['sort_by']) && $_GET['sort_by'] == 'low_to_high') echo 'selected'; ?>>Price: Low to High</option>
                <option value="high_to_low" <?php if(isset($_GET['sort_by']) && $_GET['sort_by'] == 'high_to_low') echo 'selected'; ?>>Price: High to Low</option>
            </select>
            <button type="submit">Filter</button>
        </form>
    </div>

    <div class="property-list">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($property = $result->fetch_assoc()): ?>
                <div class="property-card">
                    <?php $images = explode(',', $property['ppic']); ?>
                    <img src="<?php echo htmlspecialchars($images[0]); ?>" alt="Property Image">
                    <div class="property-details">
                        <h3><?php echo htmlspecialchars($property['property_type']); ?></h3>
                        <p><span class="icon">&#x1F4CD;</span><?php echo htmlspecialchars($property['city'] . ', ' . $property['thana']); ?></p>
                        <p><span class="icon">&#x1F6CC;</span> Bedrooms: <?php echo htmlspecialchars($property['number_of_bedrooms']); ?></p>
                        <p><span class="icon">&#x1F6BF;</span> Bathrooms: <?php echo htmlspecialchars($property['number_of_bathrooms']); ?></p>
                        <p class="property-price">&#x20B9; <?php echo htmlspecialchars($property['price']); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No properties found matching your criteria.</p>
        <?php endif; ?>
    </div>
</div>

<?php $con->close(); ?>

</body>
</html>
