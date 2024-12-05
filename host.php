<?php 
session_start(); 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database logic...
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
            background-color: #f4f4f4;
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

        .checkbox-group {
            display: flex;
            gap: 15px;
        }

        button {
            padding: 10px 20px;
            background-color: orange;
            color: white;
            border: none;
            cursor: pointer;
            margin-right: 10px;
        }

        button.prev-btn {
            background-color: #555;
        }

        button.submit-btn {
            background-color: green;
        }

        button:hover {
            opacity: 0.8;
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






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Submission</title>
    <style>
        .step { display: none; }
        .step.active { display: block; }
        .sidebar ul li.active { font-weight: bold; }
    </style>
</head>
<body>
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
        <!-- Sidebar Navigation -->
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

        <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm();">
            <div class="main-content">
                <!-- Step 1: The Property Type -->
                <div class="step active" id="step1">
                    <h2>Step 1: The Property Type</h2>
                    <label for="propertyType">Select Property Type</label>
                    <select id="propertyType" name="property_type">
                       <option value="apartment">Apartment</option>
                        <option value="semi_master">Semi Master</option>
                        <option value="master_room">Master Room</option>
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

    <script>
        let currentStep = 1;

        function showStep(step) {
            document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));
            document.getElementById(`step${step}`).classList.add('active');
            document.querySelectorAll('.sidebar ul li').forEach(el => el.classList.remove('active'));
            document.getElementById(`sidebar-step${step}`).classList.add('active');
        }

        function nextStep(step) {
            currentStep = step;
            showStep(currentStep);
        }

        function prevStep(step) {
            currentStep = step;
            showStep(currentStep);
        }

        function validateForm() {
            // Basic validation to ensure required fields are filled in
            let country = document.getElementById('country').value;
            if (country === '') {
                alert("Country is required.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>