<?php

session_start();

function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

include_once 'JWT.php';

$secret = base64_decode('1fTiS2clmPTUlNcpwYzd5i4AEFJ2DEsd8TcUsllmaKQ=');
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
		//die("match");
	}
	else
	{
		$access_token = "";
		//die("no match $instate $state");
	}
} 

session_destroy();

if($access_token != "")
{
  try
  {
	  $jwt = JWT::decode($access_token,$secret ,true);
      
	  $expiry = $jwt->exp;
			
	  setcookie( "access_token", $access_token, $expiry , '', '', true, true);
    
	  die('start.php');
  }
  catch(Exception $e)
  {
	echo 'Caught exception: ',  $e->getMessage(), "\n";
    //die("cc");
  }
}

header('Location: unauthorized.php',true,302);
exit;

 
?>
