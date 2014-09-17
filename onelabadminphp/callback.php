
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


if(count($_GET) > 0 && isset($_GET['state']))
{
	$instate = $_GET['state'];
	$state = $_SESSION['state'];
	
	if($state == $instate)
	{
		die("match");
	}
	else
	{
		die("no match");
	}
}  
   

  
if(isset($_SERVER["HTTP_AUTHORIZATION"]))
{
  $bearer = $_SERVER["HTTP_AUTHORIZATION"];
  
  $bearerStr = "Bearer ";
  
  if(startsWith($bearer, $bearerStr))
  {
      $pieces = explode($bearerStr, $bearer);
  
      //print_r($pieces[1]);
      $access_token = $pieces[1];
  }
}


if( $access_token != "")
{
  try
  {
	  $jwt = JWT::decode($access_token,$secret ,true);
			
    //var_dump($jwt);
			
	  $expiry = $jwt->exp;
			
	  setcookie( "access_token", $access_token, $expiry , '', '', true, true);
    
	  die('index.php');
    
  }
  catch(Exception $e)
  {
	echo 'Caught exception: ',  $e->getMessage(), "\n";
    //die("cc");
  }
}

header('HTTP/1.1 401 Unauthorized', true, 401);
die('Unauthorized');


 
?>
