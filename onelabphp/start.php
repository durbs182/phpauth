<?php

$expiry = new DateTime("now");
$expiry->setTimezone(new DateTimezone("Etc/UTC")); 

$redirect = "Location: login.php"; /* Redirect browser */


if (isset($_COOKIE["access_token"]))
{
	$secret = base64_decode('1fTiS2clmPTUlNcpwYzd5i4AEFJ2DEsd8TcUsllmaKQ=');
	
	include_once 'JWT.php';
	
	$access_token = $_COOKIE["access_token"];
	
	try
	{
		//$access_token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJPc3MiOiJvbmVsYWIiLCJhdWQiOiJ1c2VycyIsIm5iZiI6MTQwOTkxMjcwNSwiZXhwIjoxNDA5OTE0NTA1LCJjbGllbnRfaWQiOiJvbmVsYWJwaHAiLCJzY29wZSI6ImxvZ29uIiwic3ViIjoiQ0lUUklURVxccGF1bGQifQ.CGT3tQkBVge05w7L4gE23HBZUzJV4XHcqsYnzcBpm5Y";
		$jwt = JWT::decode($access_token,$secret ,true);
		
		//$expiry = new DateTime("now");
		
		//var_dump($jwt);
		
		$expiry->setTimestamp( $jwt->exp );
		
		//var_dump($expiry);
	}
	catch(Exception $e)
	{
		$msg = $e->getMessage();
		if( $msg  == 'Expired Token')
		{
			header($redirect); /* Redirect browser */
			die('expired token');
		}
		else
		{
			echo 'Token validation error: ', $msg , "\n";
			die('');
		}
	}
}
else
{
	header($redirect); /* Redirect browser */
	die('redirect');
}
/* Make sure that code below does not get executed when we redirect. */
//exit;
?>

<html id="htmlTop" xmlns="http://www.w3.org/1999/xhtml" >
<head id="head">
  <title>Onelab</title>
  <link rel="stylesheet" type="text/css" rel="stylesheet" href="styles/style.css">
 </head>
 <body>
	<script type="text/javascript" src="scripts/jquery-1.9.0.min.js"></script>
    <script type='text/javascript' src='scripts/jquery.tablesorter.js'></script>
	<script type="text/javascript">           $(function () { $("#table001").tablesorter({ sortList: [[0, 0]] }); });</script>


 
 <div>
    <img src="logo.png" />
 </div>

 <h2>Authorized user: 
	<tr><?php echo $jwt->sub; ?> 
 </h2>
 <h3>Token:</h3>
 <table style="table-layout: fixed; width: 100%">
  
	 <tbody>
		<tr>
			<td style="word-wrap: break-word">
				<?php echo "<h4>$access_token</h4>" ?> 
			</td>
		</tr>
	 </tbody>
 </table>
 <h3>Token Expires at:</h3><h4><?php echo $expiry->format('Y/m/d H:i:s'); ?> UTC </h4>


 <table id="table001" class="tablesorter">
 <thead>
  <tr>
    <th ><H3>Claim</H3></th>
    <th ><H3>Value</H3></th> 
  </tr>
 </thead>
 <tbody>
 <?php 
 
//var_dump($jwt); 

while (list($key, $value) = each($jwt)) 
{
	if(is_array($value))
	{
		//echo "<br />\n";
		while (list($arkey, $arvalue) = each($value)) 
		{
			echo "<tr>\n";
			echo "\t<td>$key</td> <td>$arvalue</td>\n";
			echo "</tr>\n";
		}
	}
	else
	{
		echo "<tr'>\n";
		echo "\t<td>$key</td> <td>$value</td>\n";
		echo "</tr>\n";
	}
	
}
 
 ?>
 </tbody>  
 </table>
 </body>
 </html>