<?php
$password = 'your_actual_password_here'; // Palitan ng aktwal na password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
echo $hashedPassword;
?>