<?php

// in case this is called directly if the access_token exists redirect start.php
if (isset($_COOKIE["access_token"]))
{
	$redirectpage = 'start.php';
	$redirect = "Location: " . $redirectpage; 
	header($redirect);
	exit;
}


?>