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

// Initialize variables for form fields
$name = $phone_number = $email = $vehicle_type = $hourly_rate = $experience_years = "";
$ppic = null;
$stars_1 = $stars_2 = $stars_3 = $stars_4 = $stars_5 = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data and sanitize input
    $name = $con->real_escape_string($_POST['name']);
    $phone_number = $con->real_escape_string($_POST['phone_number']);
    $email = $con->real_escape_string($_POST['email']);
    $vehicle_type = $con->real_escape_string($_POST['vehicle_type']);
    $hourly_rate = floatval($_POST['hourly_rate']);
    $experience_years = intval($_POST['experience_years']);
    
    // Handle profile picture upload (optional)
    if (!empty($_FILES['ppic']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['ppic']['name']);
        if (move_uploaded_file($_FILES['ppic']['tmp_name'], $target_file)) {
            $ppic = $target_file;
        } else {
            echo "Error uploading profile picture.";
        }
    }

    // Insert data into the database
    $sql = "INSERT INTO van_truck_driver (name, phone_number, email, vehicle_type, hourly_rate, experience_years, stars_1, stars_2, stars_3, stars_4, stars_5, ppic) 
            VALUES ('$name', '$phone_number', '$email', '$vehicle_type', $hourly_rate, $experience_years, $stars_1, $stars_2, $stars_3, $stars_4, $stars_5, '$ppic')";
    
    
}

$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Van/Truck Driver</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            background: #f3f4f6;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Form Container */
        form {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
            margin: 20px;
            animation: fadeIn 0.5s ease-in-out;
        }

        /* Form Heading */
        form h1 {
            font-size: 1.8rem;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            border-bottom: 2px solid #00b4d8;
            display: inline-block;
            padding-bottom: 5px;
        }

        /* Form Labels and Inputs */
        form label {
            font-size: 1rem;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }

        form input,
        form select,
        form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1rem;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        form input:focus,
        form select:focus {
            border-color: #00b4d8;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 180, 216, 0.5);
        }

        /* File Upload Styling */
        form input[type="file"] {
            padding: 5px;
        }

        /* Submit Button */
        form button {
            background: #00b4d8;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        form button:hover {
            background: #0077b6;
        }

        /* Star Rating */
        .star-rating {
            display: flex;
            gap: 5px;
            justify-content: center;
            margin-bottom: 15px;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            font-size: 1.5rem;
            color: #ccc;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #FFD700; /* Gold color */
        }

        /* Form Responsiveness */
        @media (max-width: 768px) {
            form {
                padding: 15px;
            }

            form h1 {
                font-size: 1.5rem;
            }

            form input,
            form select,
            form button {
                font-size: 0.9rem;
            }
        }

        /* Keyframes for Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <form action="insert_driver.php" method="post" enctype="multipart/form-data">
        <h1>Add a New Driver</h1>
        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email">

        <label for="vehicle_type">Vehicle Type:</label>
        <select id="vehicle_type" name="vehicle_type" required>
            <option value="Van">Van</option>
            <option value="Truck">Truck</option>
        </select>

        <label for="hourly_rate">Hourly Rate (BDT):</label>
        <input type="number" step="0.01" id="hourly_rate" name="hourly_rate" required>

        <label for="experience_years">Years of Experience:</label>
        <input type="number" id="experience_years" name="experience_years" required>

        <label for="ppic">Profile Picture:</label>
        <input type="file" id="ppic" name="ppic">

        <label>Rate Driver:</label>
        <div class="star-rating">
            <input type="radio" id="star5" name="rating" value="5">
            <label for="star5">&#9733;</label>
            <input type="radio" id="star4" name="rating" value="4">
            <label for="star4">&#9733;</label>
            <input type="radio" id="star3" name="rating" value="3">
            <label for="star3">&#9733;</label>
            <input type="radio" id="star2" name="rating" value="2">
            <label for="star2">&#9733;</label>
            <input type="radio" id="star1" name="rating" value="1">
            <label for="star1">&#9733;</label>
        </div>

        <button type="submit">Add Driver</button>
    </form>
</body>
</html>
