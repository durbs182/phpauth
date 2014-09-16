﻿<?php 
$time = time();
$rand = rand();

$state="$time$rand"; ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>
</head>
<body>
    <p>
        <button id="implicitButton">OAuth Implicit Flow</button>
    </p>
    <script src="Scripts/jquery-2.0.2.js"></script>
    <script>
        $(function () {
            var authorizationUrl = 'http://localhost/WebHostwin/users/oauth/authorize';
            var client_id = 'implicitclient';
            var redirect_uri = 'https://localhost/ImplicitFlow/callback.cshtml';
            var response_type = "token";
            var scope = "read";
            var state = "<?php echo $state; ?>" ;

            $("#implicitButton").click(function () {
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
        });

    </script>
</body>
</html>