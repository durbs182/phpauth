<?php 

session_start();

$time = time();
$rand = rand();

$state="$time$rand"; 

$_SESSION['state']=$state;

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>
</head>
<body>
    
    <script src="Scripts/jquery-2.0.2.js"></script>
    

<script>
	$(function () {
		//var authorizationUrl = 'https://localhost/WebHostwin/users/oauth/authorize';
		var authorizationUrl = 'https://onelabscheduler.onelab.citrix.com/onelabauthwin/users/oauth/authorize';
		var client_id = 'onelabazure';
		var redirect_uri = 'https://onelabdemo.azurewebsites.net/onelabphp/callback.html';
		var response_type = "code";
		var scope = "read write logon";
		var state = "<?php echo $state; ?>" ;
		
		var url =
			authorizationUrl + "?" + 
			"client_id=" + encodeURI(client_id) + "&" + 
			"redirect_uri=" + encodeURI(redirect_uri) + "&" + 
			"response_type=" + encodeURI(response_type) + "&" + 
			"scope=" + encodeURI(scope) + "&" + 
			"state=" + encodeURI(state);
			
		console.log(url);
			
		sessionStorage["state"] = state;
		window.location = url;
		});
</script>
    
</body>
</html>
