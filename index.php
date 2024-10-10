<?php
session_start();
?>
<!doctype html>
<html lang="en">

<head>
  <title>Login</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <link rel="stylesheet" href="style.css">
  <?php include 'connection.php'; ?>
</head>

<body>

  <?php
  if (isset($_POST['login'])) {

    // Correct variable names for POST data and check properly
    if (empty($_POST['email']) && empty($_POST['password'])) {
      echo "<script>alert('Please Fill Email and Password');</script>";
      exit;
    } elseif (empty($_POST['password'])) {
      echo "<script>alert('Please Fill Password');</script>";
      exit;
    } elseif (empty($_POST['email'])) {  // Corrected 'email' to 'email'
      echo "<script>alert('Please Fill Email');</script>";  // Closing quote for alert corrected
      exit;
    } else {
      $email = $_POST['email'];  // Corrected 'email' to 'email'
      $password = $_POST['password'];

      // Assuming $con is defined in 'connection.php'
      $sql = "SELECT * FROM registration WHERE email ='$email'";
      $result = mysqli_query($con, $sql);
      $email_count = mysqli_num_rows($result);
      if ($email_count) {
        $email_pass = mysqli_fetch_assoc($result);
        $db_pass = $email_pass['password'];
        $_SESSION['email']=$email_pass['email'];
        $pass_decode = password_verify($password, $db_pass);
        if ($pass_decode) {
          echo "<script>alert('Sucess');</script>";
        } else {
          echo "<script>alert('Invalid Password');</script>";
        }
      } else {
        echo "<script>alert('No such user found');</script>";  // Added condition for no matching email

      }
    }
  }

  ?>

  <section class="vh-100">
    <div class="container py-5 h-100">
      <div class="row d-flex align-items-center justify-content-center h-100">
        <div class="col-md-8 col-lg-7 col-xl-6">
          <img src="login.png" class="img-fluid" alt="Phone image" height="300px" width="600px">
        </div>
        <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
          <!-- Removed the second <form> tag that was nested -->
          <form action=" " method="post">
            <p class="text-center h1 fw-bold mb-4 mx-1 mx-md-3 mt-3">Login</p>

            <!-- Social login buttons -->
            <div class="d-flex justify-content-between mb-4">
              <a href="#" class="btn btn-lg btn-outline-danger w-45 d-flex align-items-center justify-content-center" style="border-radius: 30px;">
                <i class="bi bi-google me-2"></i> Login via Gmail
              </a>
              <a href="#" class="btn btn-lg btn-outline-primary w-45 d-flex align-items-center justify-content-center" style="border-radius: 30px; background-color: #1877f2; color: white;">
                <i class="bi bi-facebook me-2"></i> Login via Facebook
              </a>
            </div>
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
            <!-- Email input -->
            <div class="form-outline mb-4">
              <label class="form-label" for="form1Example13"> <i class="bi bi-person-circle"></i> Username</label>
              <input type="email" id="form1Example13" class="form-control form-control-lg py-3" name="email" autocomplete="off" placeholder="Enter your e-mail" style="border-radius: 25px;" />
            </div>

            <!-- Password input -->
            <div class="form-outline mb-4">
              <label class="form-label" for="form1Example23"><i class="bi bi-chat-left-dots-fill"></i> Password</label>
              <input type="password" id="form1Example23" class="form-control form-control-lg py-3" name="password" autocomplete="off" placeholder="Enter your password" style="border-radius: 25px;" />
            </div>

            <!-- Submit button -->
            <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
              <input type="submit" value="Sign in" name="login" class="btn btn-warning btn-lg text-light my-2 py-3" style="width: 100%; border-radius: 30px; font-weight: 600;" />
            </div>

          </form>
          <br>
          <p align="center">I don't have an account <a href="register.php" class="text-warning" style="font-weight:600;text-decoration:none;">Register Here</a></p>
        </div>
      </div>
    </div>
  </section>

  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>
</body>

</html>