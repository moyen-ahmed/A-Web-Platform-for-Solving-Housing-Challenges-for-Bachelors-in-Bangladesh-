<!doctype html>
<html lang="en">

<head>
  <title>Register</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>

<body>

  <section class="vh-100" style="background-color: #eee;">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-lg-12 col-xl-11">
          <div class="card text-black" style="border-radius: 25px;">
            <div class="card-body p-md-2">
              <div class="row justify-content-center">
                <p class="text-center h1 fw-bold mb-4 mx-1 mx-md-3 mt-3">Sign up</p>
                <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                  <!-- Social Login Links -->
                  <div class="d-flex justify-content-center mb-4">
                    <a href="#" class="btn text-white py-2 px-4 mx-2" style="background-color: #DB4437; border-radius: 25px; font-weight:600;">
                      <i class="bi bi-google me-2"></i> Login via Gmail
                    </a>
                    <a href="#" class="btn text-white py-2 px-4 mx-2" style="background-color: #3b5998; border-radius: 25px; font-weight:600;">
                      <i class="bi bi-facebook me-2"></i> Login via Facebook
                    </a>
                  </div>

                  <!-- Registration Form -->
                  <form class="mx-1 mx-md-4" action="" method="post" onsubmit="return validateForm();">
                    <!-- Name input -->
                    <div class="d-flex flex-row align-items-center mb-4">
                      <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        <label class="form-label" for="form3Example1c"><i class="bi bi-person-circle"></i> Your Name</label>
                        <input type="text" id="form3Example1c" class="form-control form-control-lg py-3" name="name" autocomplete="off" placeholder="Enter your name" style="border-radius:25px;" />
                      </div>
                    </div>

                    <!-- Email input -->
                    <div class="d-flex flex-row align-items-center mb-4">
                      <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        <label class="form-label" for="form3Example3c"><i class="bi bi-envelope-at-fill"></i> Your Email</label>
                        <input type="email" id="form3Example3c" class="form-control form-control-lg py-3" name="email" autocomplete="off" placeholder="Enter your email" style="border-radius:25px;" />
                      </div>
                    </div>

                    <!-- Mobile Number input -->
                    <div class="d-flex flex-row align-items-center mb-4">
                      <i class="fas fa-phone fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        <label class="form-label" for="form3Example5c"><i class="bi bi-phone-fill"></i> Mobile Number</label>
                        <input type="text" id="form3Example5c" class="form-control form-control-lg py-3" name="phone" autocomplete="off" placeholder="Enter your mobile number" style="border-radius:25px;" />
                      </div>
                    </div>

                    <!-- Password input -->
                    <div class="d-flex flex-row align-items-center mb-4">
                      <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        <label class="form-label" for="form3Example4c"><i class="bi bi-chat-left-dots-fill"></i> Password</label>
                        <input type="password" id="form3Example4c" class="form-control form-control-lg py-3" name="password" autocomplete="off" placeholder="Enter your password" style="border-radius:25px;" onkeyup="checkPasswordStrength();" />
                        <small id="passwordHelp" class="form-text"></small>
                      </div>
                    </div>

                    <!-- Retype Password input -->
                    <div class="d-flex flex-row align-items-center mb-4">
                      <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        <label class="form-label" for="form3Example4cd"><i class="bi bi-lock-fill"></i> Retype Password</label>
                        <input type="password" id="form3Example4cd" class="form-control form-control-lg py-3" name="confirm_password" autocomplete="off" placeholder="Retype your password" style="border-radius:25px;" />
                      </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                      <input type="submit" value="Register" name="register" class="btn btn-warning btn-lg text-light my-2 py-3" style="width:100%; border-radius: 30px; font-weight:600;" />
                    </div>
                  </form>

                  <p align="center">Already have an account? <a href="index.html" class="text-warning" style="font-weight:600; text-decoration:none;">Login</a></p>
                </div>

                <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">
                  <img src="signup.png" class="img-fluid" alt="Sample image" height="300px" width="500px">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"></script>

  <!-- JavaScript for form validation and password strength -->
  <script>
    function validateForm() {
      // Validate mobile number for Bangladesh format
      const phone = document.getElementById("form3Example5c").value;
      const phoneRegex = /^(?:\+88|01)[13-9]\d{8}$/;
      if (!phoneRegex.test(phone)) {
        alert("Please enter a valid Bangladesh mobile number.");
        return false;
      }

      // Validate password match
      const password = document.getElementById("form3Example4c").value;
      const confirmPassword = document.getElementById("form3Example4cd").value;
      if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false;
      }

      return true;
    }

    // Password strength checker
    function checkPasswordStrength() {
      const password = document.getElementById("form3Example4c").value;
      const passwordHelp = document.getElementById("passwordHelp");
      if (password.length < 6) {
        passwordHelp.textContent = "Weak";
        passwordHelp.style.color = "red";
      } else if (password.length >= 6 && password.length < 12) {
        passwordHelp.textContent = "Good";
        passwordHelp.style.color = "orange";
      } else {
        passwordHelp.textContent = "Strong";
        passwordHelp.style.color = "green";
      }
    }
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
    $name = $con->real_escape_string(trim($_POST['name']));
    $email = $con->real_escape_string(trim($_POST['email']));
    $phone = $con->real_escape_string(trim($_POST['phone']));
    $password = $con->real_escape_string(trim($_POST['password']));
    $confirm_password = $con->real_escape_string(trim($_POST['confirm_password']));

    // Check for empty fields
    if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
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
            $sql = "INSERT INTO registration (name,email, phone, password) VALUES ('$name', '$email', '$phone', '$hashed_password')";

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
