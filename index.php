<?php
//This page gives you the option to open the FU or the IC after logging in.

require_once('phpcommon.php');
if(!$auth->authenticate()){
	header("location: login.php");	
}
?>
<html>
<head>
<title>Logged In</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="favicon.ico"/>
<head>
<?php echo _jQuery;?>
<script>
	
</script>
</head>


<!-- Register Page-->
<div data-role="page" id="directorpage">
	<div data-role="header">
    	<a href="#" data-role="button" data-rel="back" data-icon="arrow-l">Back</a><a href="logout.php">Logout</a>
    	<h1>Choose Your Own Adventure</h1>
    </div>
    
	<div data-role="content">
    	<h3 align="center">You are now logged in.  Please select where you would like to go:</h3>
        
    	Incident Command (or IC) is the central location for setting up searches, viewing searchers, and directing teams to search locations.
        <a href="ic.php" data-role="button" data-ajax="false" data-icon="arrow-r" id="ic">Incident Command</a>
       
       	The Field Unit (FU) is the web-based mobile application that allows you to share you to join a search and a team, share your location as you conduct the search and send messages to and receive messages from the IC.
        <a href="fu.php" data-role="button" data-ajax="false" data-icon="arrow-r" id="fu">Field Unit</a>
       
    	
    </div>
</div>