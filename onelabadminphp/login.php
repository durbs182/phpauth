<?php 
$time = time();
$rand = rand();

$state="$time$rand"; ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>
</head>
<body>
    <script src="Scripts/jquery-2.0.2.js"></script>
    <script>
        $(function () {
            var authorizationUrl = 'https://localhost/WebHostwin/users/oauth/authorize';
            var client_id = 'implicitclient';
            var redirect_uri = 'https://onelabdemo.azurewebsites.net/onelabadminphp/callback.html';
            var response_type = "token";
            var scope = "read";
            var state = "<?php echo $state; ?>";
			
			var url =
				authorizationUrl + "?" + 
				"client_id=" + encodeURI(client_id) + "&" + 
				"redirect_uri=" + encodeURI(redirect_uri) + "&" + 
				"response_type=" + encodeURI(response_type) + "&" + 
				"scope=" + encodeURI(scope) + "&" + 
				"state=" + encodeURI(state);
			sessionStorage["state"] = state;
			window.location = url;
        });

    </script>
</body>
</html>
