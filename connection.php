<?php
  $server = 'localhost';
  $username = 'root';
  $password = '';
  $database = 'tolet_for_bachelor';

  if (isset($_POST))

    $con = new mysqli($server, $username, $password, $database);
  if ($con) {
    // echo 'Server Connected Success';
  } else {
    die(mysqli_error($con));
  }

  ?>