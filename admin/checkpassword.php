<?php 
$hash = '$2y$10$XSu6EBDDhc.Ee2hrv2q9wux/WcSFW9jp2.z.Od5w5PDsK9q9B2/aS';
$password = 'your_password_here';

if (password_verify($password, $hash)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}
?>