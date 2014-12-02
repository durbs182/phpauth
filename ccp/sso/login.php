<?php 

// in case this is called directly if the access_token exists redirect start.php
include_once 'checkfortoken.php';

$callbackUri = 'https://mac-win8.cam.onelab.citrix.com/ccp/sso/callback.html';
$authorizationUri = 'https://localhost/WebHostwin/users/oauth/authorize';
//$authorizationUri = 'https://onelabscheduler.onelab.citrix.com/onelabauthwin/users/oauth/authorize';

session_start();

$time = time();
$rand = rand();

$state="$time$rand"; 

$_SESSION['state']=$state;

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Login</title>
</head>
<body>
    
<script src="Scripts/jquery-2.0.2.js"></script>
    
<script>
	$(document).ready(function () {
		var authorizationuri = '<?php echo $authorizationUri; ?>';
		var client_id = 'ccp';
		var redirect_uri = '<?php echo $callbackUri; ?>';
		var response_type = "code";
		var scope = "logon";
		var state = "<?php echo $state; ?>" ;
		
		var url =
			authorizationuri + "?" + 
			"client_id=" + encodeURI(client_id) + "&" + 
			"redirect_uri=" + encodeURI(redirect_uri) + "&" + 
			"response_type=" + encodeURI(response_type) + "&" + 
			"scope=" + encodeURI(scope) + "&" + 
			"state=" + encodeURI(state);
			
		console.log(url);
		
		sessionStorage["state"] = state;
		window.location.replace(url);
		
		});
</script>
    
</body>
</html>
