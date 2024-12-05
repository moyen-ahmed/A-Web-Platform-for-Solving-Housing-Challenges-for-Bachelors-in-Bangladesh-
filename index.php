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
            background: url('black.jpg') no-repeat center center;
            background-size: cover;
        }

        .card {
            background: transparent;
            border-radius: 20px;
            border: none;
            padding: 30px;
            backdrop-filter: blur(5px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-control {
            border-radius: 25px;
        }

        .btn-custom {
            background-color: white;
            color: black;
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
            color: black;
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

        .text-center {
            color: white;
        }

        a.text-decoration {
            color: white;
        }

        /* Modal Customizations */
        .modal-content {
            background-color: #444;
            color: white;
            border-radius: 20px;
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-footer {
            border-top: none;
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
                        <input type="email" id="form1Example13" class="form-control form-control-lg" name="email"
                               autocomplete="off" placeholder="Enter your email"/>
                    </div>

                    <!-- Password input -->
                    <div class="mb-4">
                        <label class="form-label" for="form1Example23"><i class="bi bi-lock-fill"></i> Password</label>
                        <input type="password" id="form1Example23" class="form-control form-control-lg" name="password"
                               autocomplete="off" placeholder="Enter your password"/>
                    </div>

                    <!-- Submit button -->
                    <div class="d-flex justify-content-center mb-4">
                        <input type="submit" value="Sign in" name="login" class="btn btn-custom btn-lg"/>
                    </div>
                </form>
                <p class="text-center">
                    <a href="#" class="text-decoration" style="font-weight:700;" data-bs-toggle="modal"
                       data-bs-target="#forgotPasswordModal">Forgot password</a>
                </p>
                <p class="text-center">Don't have an account? <a href="register.php" class="text-decoration-none"
                                                                 style="font-weight:600;">Register Here</a></p>
            </div>
        </div>
    </div>
</section>

<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forgotPasswordModalLabel">Reset Password</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="forgotPasswordForm" method="POST" action="forgot.php">
                    <div class="mb-3">
                        <label for="resetEmail" class="form-label">Enter your email address</label>
                        <input type="email" class="form-control" id="resetEmail" name="email"  placeholder="Email Address" required>
                    </div>
                    <button type="submit" class="btn btn-custom" name="send-reset-link">Send Reset Link</button>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
