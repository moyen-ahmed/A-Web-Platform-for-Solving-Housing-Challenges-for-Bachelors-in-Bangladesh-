<!doctype html>
<html lang="en">

<head>
  <title>Create an Account</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: url('black.jpg') no-repeat center center/cover;
      height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .form-container {
      background:rgba(0, 0, 0, 0);
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
      width: 100%;
      max-width: 400px;
      backdrop-filter: blur(5px);
    }

    .btn-warning-custom {
      background: #FF7F50;
      color: white;
      font-weight: bold;
      border: none;
    }

    .btn-warning-custom:hover {
      background: #FF6347;
    }

    .form-label{
      color: white;
    }
    h2{
      color: white;
    }
    .form-text {
      font-size: 0.85rem;
      color: #DBEAF7FF;
    }
  </style>
</head>

<body>

  <div class="form-container">
    <h2 class="text-center mb-4">Create an Account</h2>
    <form action="" method="post" onsubmit="return validateForm();">
      <!-- Name -->
      <div class="mb-3">
        <label for="name" class="form-label">Your Name *</label>
        <input type="text" id="name" class="form-control" name="first_name" autocomplete="off" placeholder="Enter your name" required>
      </div>

      <!-- Email -->
      <div class="mb-3">
        <label for="email" class="form-label">Email *</label>
        <input type="email" id="email" class="form-control" name="email" autocomplete="off" placeholder="Enter your email" required>
      </div>

      <!-- Phone -->
      <div class="mb-3">
        <label for="phone" class="form-label">Mobile Number *</label>
        <input type="text" id="phone" class="form-control" name="phone" autocomplete="off" placeholder="Enter your mobile number (+880...)" required>
      </div>

      <!-- Password -->
      <div class="mb-3">
        <label for="password" class="form-label">Password *</label>
        <div class="input-group">
          <input type="password" id="password" class="form-control" name="password" autocomplete="off" placeholder="Enter your password" required>
          <button class="btn btn-outline-secondary" type="button" id="togglePassword">👁</button>
        </div>
        <small class="form-text">Your password must be 8-12 characters long.</small>
      </div>

      <!-- Confirm Password -->
      <div class="mb-3">
        <label for="confirmPassword" class="form-label">Confirm Password *</label>
        <div class="input-group">
          <input type="password" id="confirmPassword" class="form-control" name="confirm_password" autocomplete="off" placeholder="Re-enter your password" required>
          <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">👁</button>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="d-grid">
        <button type="submit" class="btn btn-warning-custom btn-lg">Create an Account</button>
        <p class="text-center"><a href="index.php" class="text-decoration-none"
        style="font-weight:600;">Already have an account!</a></p>
      </div>
    </form>
  </div>

  <script>
    // Validate form input
    function validateForm() {
      // Validate phone number
      const phone = document.getElementById("phone").value;
      const phoneRegex = /^(?:\+88|01)[3-9]\d{8}$/;
      if (!phoneRegex.test(phone)) {
        alert("Please enter a valid Bangladesh mobile number.");
        return false;
      }

      // Validate passwords match
      const password = document.getElementById("password").value;
      const confirmPassword = document.getElementById("confirmPassword").value;
      if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false;
      }

      return true;
    }

    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function () {
      const passwordField = document.getElementById('password');
      passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
      const confirmPasswordField = document.getElementById('confirmPassword');
      confirmPasswordField.type = confirmPasswordField.type === 'password' ? 'text' : 'password';
    });
  </script>
<?php
if ($_POST) {
    $server = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'tolet_for_bachelor';

    // Database connection
    $con = new mysqli($server, $username, $password, $database);

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Sanitize and validate user inputs
    $first_name = $con->real_escape_string(trim($_POST['first_name']));
    $email = $con->real_escape_string(trim($_POST['email']));
    $phone = $con->real_escape_string(trim($_POST['phone']));
    $password = $con->real_escape_string(trim($_POST['password']));
    $confirm_password = $con->real_escape_string(trim($_POST['confirm_password']));
   

    // Check for empty fields
    if (empty($first_name) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
        echo "<script>alert('All fields are required.');</script>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');</script>";
    } elseif ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match.');</script>";
    } else {
        // Check if email is already registered
        $emailCheck = "SELECT * FROM registration WHERE email='$email'";
        $result = $con->query($emailCheck);
        if ($result->num_rows > 0) {
            echo "<script>alert('This email is already registered.');</script>";
        } else {
            // Hash password before storing
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert data into the database
            $sql = "INSERT INTO registration (first_name, email, phone, password) VALUES ('$first_name', '$email', '$phone', '$hashed_password')";

            if ($con->query($sql) === TRUE) {
                echo "<script>alert('Registration successful!');</script>";
            } else {
                echo "Error: " . $sql . "<br>" . $con->error;
            }
        }
    }

    // Close the connection
    $con->close();
}
?>
</body>

</html>
