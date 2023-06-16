<?php
include('./db_connect.php');

$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validate password and confirm_password
if ($password != $confirm_password) {
  echo '<script>alert("Passwords do not match.");</script>';
  exit();
}

// Verify if the email exists in the database
$result = $conn->query("SELECT * FROM users WHERE email = '$email'");
if ($result->num_rows > 0) {
  $hashed_password = md5($password);
  $conn->query("UPDATE users SET password = '$hashed_password' WHERE email = '$email'");

  echo '<script>alert("Password reset successful.");</script>';
  echo '<script>window.location.href = "login.php";</script>';
} else {
  echo '<script>alert("Invalid email.");</script>';
  echo '<script>window.location.href = "login.php";</script>';
}


?>