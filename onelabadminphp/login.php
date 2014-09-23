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
  var dragged;

  /* events fired on the draggable target */
  document.addEventListener("drag", function( event ) {

  }, false);

  document.addEventListener("dragstart", function( event ) {
      // store a ref. on the dragged elem
      dragged = event.target;
      // make it half transparent
      event.target.style.opacity = .5;
      
  }, false);

  document.addEventListener("dragend", function( event ) {
      // reset the transparency
      event.target.style.opacity = "";
  }, false);

  /* events fired on the drop targets */
  document.addEventListener("dragover", function( event ) {
      // prevent default to allow drop
      event.preventDefault();
  }, false);

  document.addEventListener("dragenter", function( event ) {
      // highlight potential drop target when the draggable element enters it
      if ( event.target.className == "dropzone" ) {
          //event.target.style.background = "purple";
      }

  }, false);

  document.addEventListener("dragleave", function( event ) {
      // reset background of potential drop target when the draggable element leaves it
      if ( event.target.className == "dropzone" ) {
          event.target.style.background = "";
      }
  }, false);

  document.addEventListener("drop", function( event ) {
      // prevent default action (open as link for some elements)
      event.preventDefault();
      // move dragged elem to the selected drop target
      if ( event.target.className == "dropzone" ) {
          event.target.style.background = "";
          dragged.parentNode.removeChild( dragged );
          event.target.appendChild( dragged );
          
           $(function () {
            var authorizationUrl = 'https://onelabscheduler.onelab.citrix.com/onelabauthwin/onelabdemo/oauth/authorize';
            var client_id = 'onelabphp';
            var redirect_uri = 'https://onelabdemo.azurewebsites.net/onelabadminphp/callback.html';
            var response_type = "code";
            var scope = "read logon";
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
        });
      
          
      }
    
  }, false);
</script>
    
</body>
</html>
