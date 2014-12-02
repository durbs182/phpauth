<?php

// in case this is called directly if the access_token exists redirect start.php
include_once 'checkfortoken.php';


session_start();

function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}


$unauthorized = "unauthorized.php";
$start = "start.php";

ini_set('display_errors', 'On');

$access_token = "";
  
if(isset($_SERVER["HTTP_AUTHORIZATION"]))
{
  $bearer = $_SERVER["HTTP_AUTHORIZATION"];
  
  $bearerStr = "Bearer ";
  
  if(startsWith($bearer, $bearerStr))
  {
      $pieces = explode($bearerStr, $bearer);
      $access_token = $pieces[1];
  }
}

if(count($_GET) > 0 && isset($_GET['state']))
{
	$instate = $_GET['state'];
	$state = $_SESSION['state'];
	
	if($state == $instate)
	{
		//error_log("callback.php: states match");
	}
	else
	{
		error_log("callback.php: states do not match");
		$access_token = "";
	}
} 

session_destroy();

if($access_token != "")
{
  try
  {
	include_once 'JWT.php';
	
	$secretkeyfile = 'oauth.txt';
	$secret = "";
	
	// read oauth shared secret from local file
	if(is_file($secretkeyfile))
	{
		$lines = file($secretkeyfile);
		
		foreach($lines as $line) 
		{
			$secret = base64_decode($line);
			break;
		}
	}
	else
	{
		error_log("callback.php: file not found: " . $secretkeyfile);
		die("internal error - token validation");
	}
  
	$jwt = JWT::decode($access_token,$secret ,true);
      
	$expiry = $jwt->exp;
			
	setcookie( "access_token", $access_token, $expiry , '/', '.cam.onelab.citrix.com', true, true);
    
	// return start.php to call
	die($start);
  }
  catch(Exception $e)
  {
	echo 'Caught exception: ',  $e->getMessage(), "\n";
  }
}

header('Location: ' . $unauthorized,true,302);

 
?>
