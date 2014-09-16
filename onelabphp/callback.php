
<?php

	$secret = base64_decode('1fTiS2clmPTUlNcpwYzd5i4AEFJ2DEsd8TcUsllmaKQ=');
	ini_set('display_errors', 'On');
	
   //echo $_GET['code'];  //Output: myquery
   
   //$code = $_GET['code']
   
   if((include_once 'JWT.php'))
   {
	 //echo 'ok';
   }
   
   
   if(count($_GET) > 0 && isset($_GET['code']))
   {
		//$data = array('grant_type' => 'authorization_code', 'code' => $_GET['code'], 'redirect_uri' => 'https://localhost/testphp/callback.php');
		//$data = array('grant_type' => 'authorization_code', 'code' => $_GET['code'], 'redirect_uri' => 'https://mac-win8.eng.citrite.net/testphp/callback.php');
		$data = array('grant_type' => 'authorization_code', 'code' => $_GET['code'], 'redirect_uri' => 'https://onelabdemo.azurewebsites.net/onelabphp/callback.php');

		$basic = base64_encode('onelabphp:secret');
		//$basic = base64_encode('codeclient:secret');
		
		// use key 'http' even if you send the request to https://...
		$options = array(
			'http' => array(
				'header'  => "Content-Type: application/x-www-form-urlencoded\r\nAuthorization: Basic $basic\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data),
			),
		);
		$context  = stream_context_create($options);
		//$result = file_get_contents('http://localhost/WebHost/onelab/oauth/token', false, $context);
		//$result = file_get_contents('http://localhost/WebHost/users/oauth/token', false, $context);
		$result = file_get_contents('http://onelabscheduler.onelab.citrix.com/onelabauth/onelabdemo/oauth/token', false, $context);
		//$result = file_get_contents('http://onelabscheduler.onelab.citrix.com/onelabauth/onelab/oauth/token', false, $context);


		try
		{
			$json = json_decode($result);
		}
		catch(Exception $e)
		{
			$msg = $e->getMessage();
			header('HTTP/1.1 500 Internal Server Error', true, 500);
			die('Internal Server Error');
		}
		
		var_dump($json);
		
		$access_token = $json->access_token;

		try
		{
			$jwt = JWT::decode($access_token,$secret ,true);
			
			var_dump($jwt);
			
			$expiry = time() + $json->expires_in;
			
			setcookie( "access_token", $access_token, $expiry , '', '', true, true);
			//setcookie( "access_token", $access_token, $expiry , '/testphp', '.eng.citrite.net', true, true);

			
			header("Location: /onelabphp/index.php"); 
			die('redirect');
		}
		catch(Exception $e)
		{
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	else
	{
		header('HTTP/1.1 401 Unauthorized', true, 401);
		die('Unauthorized');
	}
	
?>
