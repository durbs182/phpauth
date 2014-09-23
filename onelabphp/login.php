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
    
    <div id="state">
        <b>State:</b> <?php echo $state; ?>
    </div>
    
    <div id="start" >
        <img src="icon-user.png" id="draggable" draggable="true" ondragstart="event.dataTransfer.setData('text/plain',null)"/>
    </div>
<div>
    <img src="door.png" class="dropzone"/>
</div>

<style>
  #draggable {
    width: 150px;
    height: 150px;
   
  }

  #state {
    border: solid;
    width: 180px;
    height: 30px;
    position: fixed;
    top: 35px;
    left: 15px;
    padding-top: 10px;
    padding-left: 10px;
  }
  
  #start {
    width: 150px;
    height: 150px;
    position: fixed;
    top: 130px;
    left: 30px;
  }
 
  .dropzone {
    position: fixed;
    top: 30px;
    left: 300px;

  }
</style>

<script>
$(function () {
            var authorizationUrl = 'http://localhost/WebHostwin/users/oauth/authorize';
            var client_id = 'codeclient';
            var redirect_uri = 'https://onelabdemo.azurewebsites.net/onelabadminphp/callback.html';
            var response_type = "code";
            var scope = "read write search";
            var state = "<?php echo $state; ?>" ;
            
            var url =
                authorizationUrl + "?" + 
                "client_id=" + encodeURI(client_id) + "&" + 
                "redirect_uri=" + encodeURI(redirect_uri) + "&" + 
                "response_type=" + encodeURI(response_type) + "&" + 
                "scope=" + encodeURI(scope) + "&" + 
                "state=" + encodeURI(state);
            sessionStorage["state"] = state;
            window.location = url;

  
</script>
    
</body>
</html>
