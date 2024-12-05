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

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $con->real_escape_string($_POST['name']);
    $phone_number = $con->real_escape_string($_POST['phone_number']);
    $email = $con->real_escape_string($_POST['email']);
    $hourly_rate = $con->real_escape_string($_POST['hourly_rate']);
    $experience_years = $con->real_escape_string($_POST['experience_years']);
    $specialization = $con->real_escape_string($_POST['specialization']);
    $stars_1 = $con->real_escape_string($_POST['stars_1']);
    $stars_2 = $con->real_escape_string($_POST['stars_2']);
    $stars_3 = $con->real_escape_string($_POST['stars_3']);
    $stars_4 = $con->real_escape_string($_POST['stars_4']);
    $stars_5 = $con->real_escape_string($_POST['stars_5']);

    // Handle image upload
    $image_name = '';
    if (isset($_FILES['ppic']['name']) && $_FILES['ppic']['name'] !== '') {
        $target_dir = "uploads/"; // Ensure this directory exists
        $image_name = basename($_FILES['ppic']['name']);
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES['ppic']['tmp_name'], $target_file)) {
            $image_name = $target_file;
        } else {
            $message = "Image upload failed!";
        }
    }

    // Insert data into the database
    $sql = "INSERT INTO housekeeper (name, phone_number, email, hourly_rate, experience_years, specialization, stars_1, stars_2, stars_3, stars_4, stars_5, ppic) 
            VALUES ('$name', '$phone_number', '$email', '$hourly_rate', '$experience_years', '$specialization', '$stars_1', '$stars_2', '$stars_3', '$stars_4', '$stars_5', '$image_name')";

    if ($con->query($sql) === TRUE) {
        $message = "Housekeeper added successfully!";
    } else {
        $message = "Error: " . $sql . "<br>" . $con->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Housekeeper</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Insert New Housekeeper</h1>
    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form action="insertHousekeeper.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="hourly_rate" class="form-label">Hourly Rate</label>
            <input type="number" step="0.01" name="hourly_rate" id="hourly_rate" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="experience_years" class="form-label">Years of Experience</label>
            <input type="number" name="experience_years" id="experience_years" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="specialization" class="form-label">Specialization</label>
            <textarea name="specialization" id="specialization" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label for="stars" class="form-label">Star Ratings</label>
            <div class="row g-3">
                <div class="col">
                    <label for="stars_1">1 Star</label>
                    <input type="number" name="stars_1" id="stars_1" class="form-control" required>
                </div>
                <div class="col">
                    <label for="stars_2">2 Stars</label>
                    <input type="number" name="stars_2" id="stars_2" class="form-control" required>
                </div>
                <div class="col">
                    <label for="stars_3">3 Stars</label>
                    <input type="number" name="stars_3" id="stars_3" class="form-control" required>
                </div>
                <div class="col">
                    <label for="stars_4">4 Stars</label>
                    <input type="number" name="stars_4" id="stars_4" class="form-control" required>
                </div>
                <div class="col">
                    <label for="stars_5">5 Stars</label>
                    <input type="number" name="stars_5" id="stars_5" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="ppic" class="form-label">Upload Picture</label>
            <input type="file" name="ppic" id="ppic" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Add Housekeeper</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
