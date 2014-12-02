<?php

function redirect($redirectpage)
{
	$redirect = "Location: " . $redirectpage; 
	header($redirect); /* Redirect browser */
	die('redirect');
}

function validatetoken($redirectpage) 
{
	// get oauth token from cookie
	// if not present redirect to $redirectpage
	// if found check that token is valid by decoding it
	if (isset($_COOKIE["access_token"]))
	{
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
			error_log("validatetoken: file not found: " . $secretkeyfile);
			die("internal error - token validation");
		}
		
		include_once 'JWT.php';
		
		$access_token = $_COOKIE["access_token"];
		
		try
		{
			$jwt = JWT::decode($access_token,$secret ,true);
			return $jwt;
		}
		catch(Exception $e)
		{
			$msg = $e->getMessage();
			echo 'Token validation error: ', $msg , "\n";
			error_log("validatetoken: invalid token : " . $msg);
		}
	}

	setcookie("access_token", "", time()-3600);
	redirect($redirectpage);
}



?>