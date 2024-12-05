<?php
session_start(); 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database logic...
}
// Database and logic code remain unchanged
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

        .navbar {
            background-color: #333;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            margin-right: 15px;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        .container {
            display: flex;
        }

        .sidebar {
            width: 20%;
            background-color: #fff;
            padding: 20px;
            border-right: 1px solid #ddd;
            height: 100vh;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 10px 0;
            color: #333;
            cursor: pointer;
        }

        .sidebar ul li.active {
            font-weight: bold;
            color: orange;
        }

        .main-content {
            width: 80%;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h2 {
            color: #333;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        input[type="checkbox"] {
            width: auto;
        }

        button {
            padding: 10px 20px;
            background-color: orange;
            color: white;
            border: none;
            cursor: pointer;
            margin-right: 10px;
        }

        button:hover {
            opacity: 0.8;
        }

        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

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







if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $property_type = $_POST['property_type'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $thana = $_POST['thana'];
    $road_number = $_POST['road_number'];
    $house_number = $_POST['house_number'];
    $postal_code = $_POST['postal_code'];
    $number_of_guests = $_POST['number_of_guests'];
    $number_of_bedrooms = $_POST['number_of_bedrooms'];
    $number_of_bathrooms = $_POST['number_of_bathrooms'];
    $amenities = implode(',', $_POST['amenities']);
    $check_in_date = $_POST['check_in_date'];
    $check_out_date = $_POST['check_out_date'];
    $house_policies = $_POST['house_policies'];
    $cancellation_policies = $_POST['cancellation_policies'];
    $price = $_POST['price'];
    $phn = $_POST['phn'];

    // Handle multiple file uploads
    $uploaded_files = [];
    $upload_dir = 'uploads/';

    foreach ($_FILES['ppic']['tmp_name'] as $key => $tmp_name) {
        $file_name = basename($_FILES['ppic']['name'][$key]);
        $target_file = $upload_dir . $file_name;

        // Move the file to the uploads directory
        if (move_uploaded_file($tmp_name, $target_file)) {
            $uploaded_files[] = $target_file; // Store the file path in an array
        }
    }

    // Join all file paths as a comma-separated string for database storage
    $ppic = implode(',', $uploaded_files);

    // Prepare SQL statement with `ppic`
    $stmt = $con->prepare("INSERT INTO properties (property_type, country, city, thana, road_number, house_number, postal_code, number_of_guests, number_of_bedrooms, number_of_bathrooms, amenities, check_in_date, check_out_date, house_policies, cancellation_policies, ppic,pro_email,price,phn) VALUES (?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");

    // Check if prepare was successful
    if (!$stmt) {
        die("Prepare failed: " . $con->error);
    }

    // Adjust bind_param to match the exact number of placeholders
    $stmt->bind_param("ssssssiisssssssssii", $property_type, $country, $city, $thana, $road_number, $house_number, $postal_code, $number_of_guests, $number_of_bedrooms, $number_of_bathrooms, $amenities, $check_in_date, $check_out_date, $house_policies, $cancellation_policies, $ppic,$user_email,$price,$phn);

    // Execute and check for success
    if ($stmt->execute()) {
        echo "Property details inserted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
}
$con->close();
?>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">
            <a href="home.php">🏠 Home</a>
        </div>
        <div class="links">
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <ul>
                <li id="sidebar-step1" class="active">1. The Property Type</li>
                <li id="sidebar-step2">2. General Information</li>
                <li id="sidebar-step3">3. Property Indoor Details</li>
                <li id="sidebar-step4">4. Amenities</li>
                <li id="sidebar-step5">5. Availability & Policies</li>
                <li>6. Submit</li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h2>Property Listing Form</h2>
            <p>Follow the steps on the left to complete your property listing.</p>

            <!-- Dynamic content from PHP -->
            <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm();">
                <!-- Steps and content from your original form code -->
                <!-- Step 1 -->
                <div class="step active" id="step1">
                    <h3>Step 1: Property Type</h3>
                    <label for="propertyType">Select Property Type</label>
                    <select id="propertyType" name="property_type">
                        <option value="apartment">Apartment</option>
                        <option value="house">House</option>
                        <option value="single_room">Single Room</option>
                    </select>
                    <button type="button" onclick="nextStep(2)">Next</button>
                </div>
                 <!-- Step 2: General Information -->
        <div class="step" id="step2">
                    <h2>Step 2: General Information</h2>
                    <label for="country">Country</label>
                    <select id="country" name="country">
                        <option value="">Select Country</option>
                <option value="Bangladesh">Bangladesh</option>
            </select>

            <label for="City">Thana</label>
            <select id="city" name="city">
                <option value="">Select City</option>

                    <!-- Dhaka Division Cities -->
                    <optgroup label="Dhaka Division">
                        <option value="Dhaka">Dhaka</option>
                        <option value="Gazipur">Gazipur</option>
                        <option value="Narayanganj">Narayanganj</option>
                        <option value="Tongi">Tongi</option>
                        <option value="Savar">Savar</option>
                        <option value="Narsingdi">Narsingdi</option>
                        <option value="Faridpur">Faridpur</option>
                        <option value="Manikganj">Manikganj</option>
                        <option value="Kishoreganj">Kishoreganj</option>
                        <option value="Gopalganj">Gopalganj</option>
                    </optgroup>

                    <!-- Chittagong Division Cities -->
                    <optgroup label="Chittagong Division">
                        <option value="Chittagong">Chittagong</option>
                        <option value="Cox's Bazar">Cox's Bazar</option>
                        <option value="Comilla">Comilla</option>
                        <option value="Feni">Feni</option>
                        <option value="Noakhali">Noakhali</option>
                        <option value="Lakshmipur">Lakshmipur</option>
                        <option value="Brahmanbaria">Brahmanbaria</option>
                        <option value="Rangamati">Rangamati</option>
                        <option value="Khagrachari">Khagrachari</option>
                        <option value="Bandarban">Bandarban</option>
                    </optgroup>

                    <!-- Sylhet Division Cities -->
                    <optgroup label="Sylhet Division">
                        <option value="Sylhet">Sylhet</option>
                        <option value="Moulvibazar">Moulvibazar</option>
                        <option value="Habiganj">Habiganj</option>
                        <option value="Sunamganj">Sunamganj</option>
                    </optgroup>

                    <!-- Barishal Division Cities -->
                    <optgroup label="Barishal Division">
                        <option value="Barishal">Barishal</option>
                        <option value="Patuakhali">Patuakhali</option>
                        <option value="Pirojpur">Pirojpur</option>
                        <option value="Bhola">Bhola</option>
                        <option value="Jhalokathi">Jhalokathi</option>
                        <option value="Barguna">Barguna</option>
                    </optgroup>

                    <!-- Khulna Division Cities -->
                    <optgroup label="Khulna Division">
                        <option value="Khulna">Khulna</option>
                        <option value="Jessore">Jessore</option>
                        <option value="Satkhira">Satkhira</option>
                        <option value="Narail">Narail</option>
                        <option value="Bagerhat">Bagerhat</option>
                        <option value="Chuadanga">Chuadanga</option>
                        <option value="Kushtia">Kushtia</option>
                    </optgroup>

                    <!-- Rajshahi Division Cities -->
                    <optgroup label="Rajshahi Division">
                        <option value="Rajshahi">Rajshahi</option>
                        <option value="Natore">Natore</option>
                        <option value="Bogra">Bogra</option>
                        <option value="Pabna">Pabna</option>
                        <option value="Sirajganj">Sirajganj</option>
                        <option value="Joypurhat">Joypurhat</option>
                        <option value="Naogaon">Naogaon</option>
                    </optgroup>

                    <!-- Rangpur Division Cities -->
                    <optgroup label="Rangpur Division">
                        <option value="Rangpur">Rangpur</option>
                        <option value="Dinajpur">Dinajpur</option>
                        <option value="Gaibandha">Gaibandha</option>
                        <option value="Kurigram">Kurigram</option>
                        <option value="Lalmonirhat">Lalmonirhat</option>
                        <option value="Nilphamari">Nilphamari</option>
                        <option value="Thakurgaon">Thakurgaon</option>
                    </optgroup>

                    <!-- Mymensingh Division Cities -->
                    <optgroup label="Mymensingh Division">
                        <option value="Mymensingh">Mymensingh</option>
                        <option value="Jamalpur">Jamalpur</option>
                        <option value="Sherpur">Sherpur</option>
                        <option value="Netrokona">Netrokona</option>
                    </optgroup>
                </select>


                <label for="thana">Thana</label>
                    <select id="thana" name="thana">
                        <option value="">Select Thana</option>
                        <option value="Vatara(Basundhara R/A)">Vatara(Basundhara R/A)</option>
                    <option value="Badda">Badda</option>
                    <option value="Gulshan">Gulshan</option>
                    <option value="Mirpur">Mirpur</option>
                    <option value="Banani">Banani</option>
                    <option value="Mohammadpur">Mohammadpur</option>
                    <option value="Dhanmondi">Dhanmondi</option>
                    <option value="Uttara">Uttara</option>
                    <option value="Tejgaon">Tejgaon</option>
                    <option value="Shyamoli">Shyamoli</option>
                    <option value="Rampura">Rampura</option>
                    <option value="Baridhara">Baridhara</option>
                    <option value="Motijheel">Motijheel</option>
                    <option value="Jatrabari">Jatrabari</option>
                    <option value="Malibagh">Malibagh</option>
                    <option value="Paltan">Paltan</option>
                    <option value="Khilgaon">Khilgaon</option>
                    <option value="Khilkhet">Khilkhet</option>
                    <option value="Mohakhali">Mohakhali</option>
                    <option value="Mugda">Mugda</option>
                    <option value="Savar">Savar</option>
                    <option value="Gazipur">Gazipur</option>
                    <option value="Farmgate">Farmgate</option>
                    <option value="Azimpur">Azimpur</option>
                    <option value="Lalmatia">Lalmatia</option>
                    <option value="Bashundhara">Bashundhara</option>
                    <option value="Hazaribagh">Hazaribagh</option>
                    <option value="Kamrangirchar">Kamrangirchar</option>
                    <option value="Tongi">Tongi</option>
                    <option value="Kawran Bazar">Kawran Bazar</option>
                    <option value="Old Dhaka">Old Dhaka</option>
                    <option value="Nawabganj">Nawabganj</option>
                    <option value="Sutrapur">Sutrapur</option>
                    <option value="Shahbagh">Shahbagh</option>
                    <option value="Banasree">Banasree</option>
                    <option value="Kuril">Kuril</option>
                    <option value="Aftabnagar">Aftabnagar</option>
                    <option value="Demra">Demra</option>
                    <option value="Adabor">Adabor</option>
                    <option value="Agargaon">Agargaon</option>
                    <option value="Taltola">Taltola</option>
                </select>


                <label for="road_number">Road Number</label>
                    <input type="text" id="road_number" name="road_number" placeholder="Enter road number">

                    <label for="house_number">House Number</label>
                    <input type="text" id="house_number" name="house_number" placeholder="Enter house number">

                    <label for="postal_code">Postal Code</label>
                    <input type="text" id="postal_code" name="postal_code" placeholder="Enter postal code">

                    <label for="propertyImage">Upload Property Images</label>
                    <input type="file" id="propertyImage" name="ppic[]" multiple>

                    <button type="button" onclick="prevStep(1)">Previous</button>
                    <button type="button" onclick="nextStep(3)">Next</button>
                </div>

                <!-- Step 3: Property Indoor Details -->
                <div class="step" id="step3">
                    <h2>Step 3: Property Indoor Details</h2>
                    <label for="guests">Number of Guests</label>
                    <input type="number" id="guests" name="number_of_guests" value="2">
                    <label for="bedrooms">Number of Bedrooms</label>
                    <input type="number" id="bedrooms" name="number_of_bedrooms" value="1">
                    <label for="bathrooms">Number of Bathrooms</label>
                    <input type="number" id="bathrooms" name="number_of_bathrooms" value="1">

                    <button type="button" onclick="prevStep(2)">Previous</button>
                    <button type="button" onclick="nextStep(4)">Next</button>
                </div>

                <!-- Step 4: Amenities -->
                <div class="step" id="step4">
    <h2>Step 4: Amenities</h2>
    
    <input type="checkbox" name="amenities[]" value="Wi-Fi"> Wi-Fi<br>
    <input type="checkbox" name="amenities[]" value="Air Conditioning"> Air Conditioning<br>
    <input type="checkbox" name="amenities[]" value="Heating"> Heating<br>
    <input type="checkbox" name="amenities[]" value="Parking"> Parking<br>
    <input type="checkbox" name="amenities[]" value="Laundry"> Laundry<br>
    <input type="checkbox" name="amenities[]" value="Gym"> Gym<br>
    <input type="checkbox" name="amenities[]" value="Swimming Pool"> Swimming Pool<br>
    <input type="checkbox" name="amenities[]" value="Pet Friendly"> Pet Friendly<br>
    <input type="checkbox" name="amenities[]" value="Garden"> Garden<br>
    <input type="checkbox" name="amenities[]" value="Furnished"> Furnished<br>
    
    <button type="button" onclick="prevStep(3)">Previous</button>
    <button type="button" onclick="nextStep(5)">Next</button>
</div>

                <!-- Step 5: Availability & Policies -->
                <div class="step" id="step5">
                    <h2>Step 5: Availability & Policies</h2>
                    <label for="Price">Price</label>
                    <textarea id="price" name="price" rows="3"></textarea>
                    <label for="Phone Number">Phone Number</label>
                    <textarea id="phn" name="phn" rows="3"></textarea>
                    <label for="checkIn">Check-In Date</label>
                    <input type="date" id="checkIn" name="check_in_date">
                    <label for="checkOut">Check-Out Date</label>
                    <input type="date" id="checkOut" name="check_out_date">
                    <label for="housePolicies">House Policies</label>
                    <textarea id="housePolicies" name="house_policies" rows="3"></textarea>
                    <label for="cancellationPolicies">Cancellation Policies</label>
                    <textarea id="cancellationPolicies" name="cancellation_policies" rows="3"></textarea>
                    <!-- <label for="add">Add</label>
                    <input type="date" id="checkOut" name="created_at	"> -->

                    <button type="button" onclick="prevStep(4)">Previous</button>
                    <button type="submit">Submit</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 ToLet for Bachelor | All Rights Reserved</p>
    </div>

    <script>
        // JavaScript to handle navigation between steps
        function nextStep(step) {
            document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));
            document.getElementById(`step${step}`).classList.add('active');
            document.querySelectorAll('.sidebar ul li').forEach(el => el.classList.remove('active'));
            document.getElementById(`sidebar-step${step}`).classList.add('active');
        }

        function prevStep(step) {
            nextStep(step);
        }
    </script>
</body>

</html>






















index///
<?php
require_once 'config.php';

if (isset($_SESSION['user-token'])) {
  header("Location: welcome.php");
} else {
  $googleLoginUrl = $client->createAuthUrl();
}
$googleLoginUrl = $client->createAuthUrl();
?>






<!doctype html>
<html lang="en">

<head>
  <title>Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <?php include 'connection.php'; ?>
  <style>
    body {
      background: url('black.jpg') no-repeat center center; /* Center the GIF */
    background-size:cover; /* Ensure the GIF fills the background proportionally */

  
    }

    .card {
      background: transparent;
      border-radius: 20px;
      border: none;
      padding: 30px;
      backdrop-filter: blur(5px);
      border-bottom: 1px solid rgba(255, 255, 255, 010000); /* Subtle border */
    }

    .form-control {
      border-radius: 25px;
    }

    .btn-custom {
      background-color:white;
      color: white;
      font-weight: bold;
      border-radius: 30px;
      padding: 10px;
      width: 100%;
    }

    .btn-custom:hover {
      background-color: #444;
      color: white;
    }

    .btn-google {
      background-color: white;
      color: #444;
      border: 1px solid #ddd;
      border-radius: 30px;
      font-weight: 600;
      padding: 10px;
      width: 100%;
    }

    .btn-google:hover {
      background-color: #f8f8f8;
      color:white;
    }

    .btn-google .bi-google {
      color: #DD5A39FF;
    }

    .h1 {
      font-weight: 700;
      color: white;
    }
    .form-label {
  color: white;
}
.btn-custom {
  color: black;
}
.text-center{
  color: White;
}
a.text-decoration{
  color:white;
}
  </style>
</head>

<body>
<?php
if (isset($_POST['login'])) {
    if (empty($_POST['email']) && empty($_POST['password'])) {
        echo "<script>alert('Please fill in both Email and Password');</script>";
        exit;
    } elseif (empty($_POST['password'])) {
        echo "<script>alert('Please fill in Password');</script>";
        exit;
    } elseif (empty($_POST['email'])) {
        echo "<script>alert('Please fill in Email');</script>";
        exit;
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Check admin table first
        $sql_admin = "SELECT * FROM admin WHERE email = '$email'";
        $result_admin = mysqli_query($con, $sql_admin);
        $admin_count = mysqli_num_rows($result_admin);

        if ($admin_count > 0) {
            // Admin login process
            $admin_data = mysqli_fetch_assoc($result_admin);
            $db_pass = $admin_data['password'];

            if ($password === $db_pass) { // Plain text password check
                $_SESSION['admin_email'] = $admin_data['email'];
                $_SESSION['admin_name'] = $admin_data['name'];
                header("Location: admin_dashboard.php"); // Redirect to admin dashboard
                exit;
            } else {
                echo "<script>alert('Invalid Password for Admin');</script>";
            }
        } else {
            // Check registration table for regular users
            $sql_user = "SELECT * FROM registration WHERE email = '$email'";
            $result_user = mysqli_query($con, $sql_user);
            $user_count = mysqli_num_rows($result_user);

            if ($user_count > 0) {
                $user_data = mysqli_fetch_assoc($result_user);
                $db_pass = $user_data['password'];
                $_SESSION['email'] = $user_data['email'];

                if (password_verify($password, $db_pass)) { // Hashed password check
                    header("Location: home.php"); // Redirect to user home
                    exit;
                } else {
                    echo "<script>alert('Invalid Password for User');</script>";
                }
            } else {
                echo "<script>alert('No such user or admin found');</script>";
            }
        }
    }
}
?>


<section class="vh-100">
  <div class="container h-100 d-flex justify-content-center align-items-center">
    <div class="col-md-6">
      <div class="card shadow-lg">
        <p class="text-center h1 fw-bold mb-4">Login</p>
        <form action="" method="post">
          <!-- Google Login Button -->
          <div class="mb-4">
            <a href="<?= $googleLoginUrl ?>" class="btn btn-google d-flex align-items-center justify-content-center">
              <i class="bi bi-google me-2"></i> Login with Google
            </a>
          </div>

          <!-- Email input -->
          <div class="mb-4">
            <label class="form-label" for="form1Example13"><i class="bi bi-envelope-fill"></i> Email</label>
            <input type="email" id="form1Example13" class="form-control form-control-lg" name="email" autocomplete="off" placeholder="Enter your email" />
          </div>

          <!-- Password input -->
          <div class="mb-4">
            <label class="form-label" for="form1Example23"><i class="bi bi-lock-fill"></i> Password</label>
            <input type="password" id="form1Example23" class="form-control form-control-lg" name="password" autocomplete="off" placeholder="Enter your password" />
          </div>

          <!-- Submit button -->
          <div class="d-flex justify-content-center mb-4">
            <input type="submit" value="Sign in" name="login" class="btn btn-custom btn-lg" />
          </div>
        </form>
        <p class="text-center"> <a href="forget.php" class="text-decoration" style="font-weight:700;">Forgot password</a></p>
        <p class="text-center">Don't have an account? <a href="register.php" class="text-decoration-none" style="font-weight:600;">Register Here</a></p>
      </div>
    </div>
  </div>
</section>

<!-- Bootstrap JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>