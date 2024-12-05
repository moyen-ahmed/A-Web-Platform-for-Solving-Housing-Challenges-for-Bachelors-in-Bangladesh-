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
    } else {
        echo "<p class='text-center'>User not found.</p>";
        exit();
    }
} else {
    echo "<p class='text-center'>You are not logged in.</p>";
    exit();
}

// Handle form submission (update logic remains the same for both Google and email login)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $update_fields = [];
    
    // Handle photo upload
    if (isset($_FILES['uploadfile']['name']) && $_FILES['uploadfile']['name'] != '') {
        $filename = $_FILES["uploadfile"]["name"];
        $tempname = $_FILES["uploadfile"]["tmp_name"];
        
        // Determine folder based on login type
        if (isset($_SESSION['user_token'])) {
            $folder = "uploads/" . $filename;  // Directory for Google users
        } else if (isset($_SESSION['email'])) {
            $folder = "image/" . $filename;    // Directory for email users
        }
        
        if (move_uploaded_file($tempname, $folder)) {
            $update_fields[] = "ppic = '$filename'";
        } else {
            echo "Failed to upload image.";
        }
    }

    // Collect other update fields
    if (isset($_POST['first_name']) && !empty($_POST['first_name'])) {
        $first_name = $_POST['first_name'];
        $update_fields[] = "first_name = '$first_name'";
    }

    if (isset($_POST['phone']) && !empty($_POST['phone'])) {
        $phone = $_POST['phone'];
        $update_fields[] = "phone = '$phone'";
    }

    if (isset($_POST['dob']) && !empty($_POST['dob'])) {
        $dob = $_POST['dob'];
        $update_fields[] = "dob = '$dob'";
    }

    if (isset($_POST['nationality']) && !empty($_POST['nationality'])) {
        $nationality = $_POST['nationality'];
        $update_fields[] = "nationality = '$nationality'";
    }

    if (isset($_POST['gender']) && !empty($_POST['gender'])) {
        $gender = $_POST['gender'];
        $update_fields[] = "gender = '$gender'";
    }

    if (isset($_POST['address']) && !empty($_POST['address'])) {
        $address = $_POST['address'];
        $update_fields[] = "address = '$address'";
    }

    // Update user data based on login type
    if (!empty($update_fields)) {
        if (isset($_SESSION['user_token'])) {
            $sql_update = "UPDATE google SET " . implode(', ', $update_fields) . " WHERE token = '$token'";
        } else if (isset($_SESSION['email'])) {
            $sql_update = "UPDATE registration SET " . implode(', ', $update_fields) . " WHERE email = '$email'";
        }

        if ($con->query($sql_update) === TRUE) {
            echo "Profile updated successfully!";
            header("Refresh:0");
        } else {
            echo "Error updating profile: " . $con->error;
        }
    }
}

// Close the database connection
$con->close();
?>


<!doctype html>
<html lang="en">

<head>
    <title>Account Settings</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            background: #000;
        }
        .account-sidebar {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .edit-link {
            cursor: pointer;
            color: #007bff;
        }
    </style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">BACHELOR HOBE</a>
    <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?= (isset($_SESSION['user_token'])) ? "uploads/$user_picture" : "image/$user_picture" ?>" alt="Profile" class="rounded-circle" width="30" height="30"> <?= $user_name ?>
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="home.php">Profile</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="account-sidebar p-3">
                <h4>Settings</h4>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="#">Personal Details</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Customization Preferences</a></li>
                </ul>
            </div>
        </div>

        <!-- Content -->
        <div class="col-md-8">
            <div class="account-content">
                <h4 class="mb-4">Personal Details</h4>
                <form method="POST" enctype="multipart/form-data">
                    <!-- Profile Picture -->
                    <div class="item mb-3">
                        <div class="label">Profile Picture</div>
                        <img src="<?= (isset($_SESSION['user_token'])) ? "uploads/$user_picture" : "image/$user_picture" ?>" alt="Profile" class="rounded-circle" width="100" height="100">
                        <input type="file" name="uploadfile" id="profilePicture" class="form-control mt-2">
                    </div>

                    <!-- Display Name -->
                    <div class="item mb-3">
                        <div class="label">Display Name</div>
                        <input type="text" class="form-control" name="first_name" value="<?= $user['first_name'] ?? '' ?>" readonly id="displayNameField">
                        <span class="edit-link" onclick="toggleEdit('displayNameField')">Edit</span>
                    </div>

                    <!-- Phone -->
                    <div class="item mb-3">
                        <div class="label">Phone Number</div>
                        <input type="tel" class="form-control" name="phone" value="<?= $user['phone'] ?? '' ?>" readonly id="phoneField">
                        <span class="edit-link" onclick="toggleEdit('phoneField')">Edit</span>
                    </div>

                    <!-- Date of Birth -->
                    <div class="item mb-3">
                        <div class="label">Date of Birth</div>
                        <input type="date" class="form-control" name="dob" value="<?= $user['dob'] ?? '' ?>" readonly id="dobField">
                        <span class="edit-link" onclick="toggleEdit('dobField')">Edit</span>
                    </div>

                    <!-- Nationality -->
                    <div class="item mb-3">
                        <div class="label">Nationality</div>
                        <input type="text" class="form-control" name="nationality" value="<?= $user['nationality'] ?? '' ?>" readonly id="nationalityField">
                        <span class="edit-link" onclick="toggleEdit('nationalityField')">Edit</span>
                    </div>

                    <!-- Gender -->
                    <div class="item mb-3">
                        <div class="label">Gender</div>
                        <select class="form-control" name="gender" disabled id="genderField">
                            <option <?= $user['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                            <option <?= $user['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                            <option <?= $user['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                        <span class="edit-link" onclick="toggleEdit('genderField')">Edit</span>
                    </div>

                    <!-- Address -->
                    <div class="item mb-3">
                        <div class="label">Address</div>
                        <input type="text" class="form-control" name="address" value="<?= $user['address'] ?? '' ?>" readonly id="addressField">
                        <span class="edit-link" onclick="toggleEdit('addressField')">Edit</span>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleEdit(fieldId) {
    const field = document.getElementById(fieldId);
    field.readOnly = !field.readOnly;
    field.focus();
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
