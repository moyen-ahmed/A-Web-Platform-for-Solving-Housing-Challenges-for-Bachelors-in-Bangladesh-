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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How It Works</title>
    <style>
        /* Styles */
        /* Header */
header {
    background-color: rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(2px); /* Slightly transparent white */
    padding: 10px 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    /* Subtle border */
    position: absolute; /* Fixed position to stay on top */
    top: 0;
    width: 100%; /* Full width for all screen sizes */
    z-index: 1000;
    box-shadow: none;
   /* Adds a glass-like effect */
}
.payment-section {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 1px solid #ddd;
    padding: 10px 20px;
    border-radius: 8px;
    background-color: #f9f9f9;
  }
  
  .payment-title {
    font-size: 16px;
    font-weight: bold;
    color: #007bff;
    margin-right: 10px;
  }
  
  .payment-logos {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    flex: 1;
    width: 50%;
    height: 50%;
  }
  
  .payment-logos img {
    width: 50px; /* Adjust size as needed */
    height: auto;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 5px;
    background-color: #fff;
    transition: transform 0.2s;
  }
  
  .payment-logos img:hover {
    transform: scale(1.1); /* Hover effect */
  }
  
  .verified-by {
    display: flex;
    align-items: center;
    gap: 5px;
  }
  
  .verified-by img {
    width: 100px; /* Adjust size for the verified logo */
    height: auto;
  }
  
.logo {
    font-size: 28px;
    font-weight: bold;
    color: #ffffff;
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
    transition: background-color 0.3s ease;
}

/* Host Button */
.host-btn {
    
    color: white;
    padding: 8px 16px;
   
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.host-btn:hover {
    background-color: #a3978f;
}

/* Login and Register Buttons */
.nav-links li a {
    color: rgb(255, 255, 255);
    padding: 8px 16px;
    font-weight: bold;
    text-decoration: none;
}

.nav-links li a:hover {
    background-color: #8e8b88;
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
    background-color: rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(3px);
    min-width: 150px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 5px;
    z-index: 1000;
    top: 100%; /* Positions below the dropdown button */
    left: 50%; /* Starts aligned to the center of the parent */
    transform: translateX(-50%); /* Centers the dropdown */
    overflow: hidden; /* Prevents content from overflowing */
}
/* Ensure dropdown stays within the viewport */
.dropdown-content[data-align="left"] {
    left: 0;
    transform: translateX(0);
}

.dropdown-content[data-align="right"] {
    left: auto;
    right: 0;
    transform: translateX(0);
}

.dropdown-content a {
    color: #333;
    padding: 10px 15px;
    text-decoration: none;
    display: block;
    transition: background-color 0.3s ease;
    white-space: nowrap;
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

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            margin-top: 70px;
            background-color: #f9f9f9;
        }

        .how-it-works {
            padding: 40px 20px;
            text-align: center;
            background: #f9f9f9;
        }

        .how-it-works h2 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .how-it-works p {
            font-size: 18px;
            margin-bottom: 30px;
            color: #666;
        }

        .toggle-buttons {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .toggle-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            background-color: #ddd;
            color: #333;
            font-size: 16px;
            margin: 0 10px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .toggle-buttons button.active {
            background-color: #007bff;
            color: #fff;
        }

        .content {
            display: none;
            text-align: left;
            max-width: 800px;
            margin: 0 auto;
        }

        .content.active {
            display: block;
        }

        .section {
            margin-bottom: 40px;
            text-align: center;
        }

        .section img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
        }

        .section h3 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        .section p {
            font-size: 16px;
            color: #555;
        }
        
footer {
    background-color: #f0f0f5;
    padding: 20px;
    font-family: Arial, sans-serif;
  }
  
  .footer-container {
    display: flex;
    justify-content: space-between;
    gap: 20px;
  }
  
  .footer-section {
    flex: 1;
  }
  
  .footer-section h3 {
    font-size: 16px;
    color: #333;
    margin-bottom: 10px;
  }
  
  .footer-section ul {
    list-style: none;
    padding: 0;
  }
  
  .footer-section ul li {
    margin-bottom: 8px;
  }
  
  .footer-section ul li a {
    text-decoration: none;
    color: #666;
    font-size: 14px;
  }
  
  .footer-section ul li a:hover {
    color: #000;
  }
  
  .social-icons a img {
    width: 24px;
    height: 24px;
    margin-right: 10px;
  }
  
  .app-buttons img {
    width: 150px;
    margin-right: 10px;
    margin-top: 10px;
  }
  
  .language-select {
    margin-top: 15px;
    padding: 5px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }
  
  .footer-bottom {
    margin-top: 20px;
    text-align: center;
    font-size: 14px;
    color: #666;
  }
  
    </style>
</head>
<body>
<header>
<div class="logo">
    <a href="home.php" style="text-decoration: none; color: inherit;">ᗷᗩᑕᕼᗴᒪᗝᖇ ᕼᗝᗷᗴ</a>
</div>
    <ul class="nav-links">
        <li><a href="allproperties.php">All Properties</a></li>
       
       
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
            <li><a href="register.php">Register</a></li>
            <li><a href="index.php">Login</a></li>
        <?php endif; ?>
    </ul>
</header>

    <div class="how-it-works">
        <h2>How it works</h2>
        <p>Looking for a place? Or renting one out? Here's how our platform brings landlords and tenants together.</p>
        <!-- Toggle Buttons -->
        <div class="toggle-buttons">
            <button id="tenants-btn" class="active">For tenants</button>
            <button id="landlords-btn">For landlords</button>
        </div>

        <!-- Tenants Content -->
        <div id="tenants-content" class="content active">
            <div class="section">
                <img src="pictures/on.png" alt="Tenant Protection">
                <h3>Tenant Protection</h3>
                <p>All bookings include tenant protection, ensuring secure transactions and a safe move-in experience.</p>
            </div>
            <div class="section">
                <img src="pictures/ne.png" alt="Search and Save Alerts">
                <h3>Search, Save, and Set Alerts</h3>
                <p>Find the perfect place with personalized filters and real-time availability updates.</p>
            </div>
            <div class="section">
                <img src="pictures/hare.gif" alt="Chat and Share Documents">
                <h3>Chat and Share Documents Securely</h3>
                <p>Communicate directly with landlords through a secure platform for seamless transactions.</p>
            </div>
        </div>

        <!-- Landlords Content -->
        <div id="landlords-content" class="content">
            <div class="section">
                <img src="pictures/image.png" alt="Manage Listings">
                <h3>Manage Your Listings</h3>
                <p>Update availability, set preferences, and communicate with tenants in real-time.</p>
            </div>
            <div class="section">
                <img src="pictures/Secs.png" alt="Secure Payments">
                <h3>Secure Payments</h3>
                <p>Ensure hassle-free payments with verified tenants and an integrated secure payment system.</p>
            </div>
            <div class="section">
                <img src="pictures/Get-Support.png" alt="Effortless Property Management">
                <h3>Effortless Property Management</h3>
                <p>Streamline everything from tenant screening to rent collection online.</p>
            </div>
        </div>
    </div>

    <script>
        // JavaScript for Toggle Functionality
        document.getElementById('tenants-btn').addEventListener('click', function () {
            document.getElementById('tenants-btn').classList.add('active');
            document.getElementById('landlords-btn').classList.remove('active');
            document.getElementById('tenants-content').classList.add('active');
            document.getElementById('landlords-content').classList.remove('active');
        });

        document.getElementById('landlords-btn').addEventListener('click', function () {
            document.getElementById('landlords-btn').classList.add('active');
            document.getElementById('tenants-btn').classList.remove('active');
            document.getElementById('landlords-content').classList.add('active');
            document.getElementById('tenants-content').classList.remove('active');
        });
    </script>
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
  <div class="footer-bottom">
    <p>Bachelor Hobe_</p>
  </div>
</footer>
</body>
</html>
