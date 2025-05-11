<?php
// Use this script to generate password hashes
$password = "123456";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo "Original password: $password<br>";
echo "Hashed password: $hashed_password";
?>