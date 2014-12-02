<?php 

if(isset($_COOKIE['access_token'])) {
    unset($_COOKIE['access_token']);
    setcookie('access_token', '', time() - 3600,"/",""); // empty value and old timestamp
}

$redirect = "Location: index.html"; /* Redirect browser */
header($redirect); /* Redirect browser */
die('redirect');

?>


