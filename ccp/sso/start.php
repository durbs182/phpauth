<?php


include_once 'validatetoken.php';
include_once 'ccp.php';

$jwt = validatetoken("login.php");

// sub is domain\username get just username
$sub = explode("\\", $jwt->sub);
$username = $sub[1];


$username = 'shalomb';

$ccpEndpoint = "http://cloud2.cam.onelab.citrix.com:8080/client";
$ApiEndPoint =  $ccpEndpoint . "/api";


//$ApiEndPoint = "http://mac-win8.cam.onelab.citrix.com:8080/client/user.php";


// TODO get users configured domains
$domainid = "";

if(count($_GET) > 0 && isset($_GET['domainid']))
{
	$domainid = $_GET['domainid'];
}
else
{
	$domains = getCcpUserDomains($username, $ApiEndPoint);
	
	if(count($domains) > 1 )
	{
		// show list
		//var_dump(array_keys($domains));
		//var_dump(array_values($domains));
		
		echo'<!DOCTYPE html><html><body><a href="start.php"><img src="logo_open.png" /></a><p><b>Note:</b> Select the required CloudPlatform domain and click submit to continue login.</p><form action="start.php">';
		
		$checked = 'checked';
		
		foreach (array_keys($domains) as $domainid) 
		{
			echo'<input ' . $checked . ' type="radio" name="domainid" value="' . $domainid . '">' . $domains[$domainid] . '<br>';
			
			$checked = '';
		}
		echo'<input type="submit" value="Submit"></form></body></html>'; 	
		die('');
	}
	else
	{
		$keys = array_keys($domains);
		$domainid = $keys[0];
	}
}

// TODO if more than 1 domain offer list for selection
$url = getCcpLoginUrl($ApiEndPoint, $username, $domainid);
		
		///var_dump($url);
		
		
getCcpLoginResponse($url);

header("Location: " . $ccpEndpoint ); 

die('redirect');

?>






