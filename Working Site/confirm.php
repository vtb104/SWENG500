<?php
require_once('phpcommon.php');
?>
<html>
<head>
<title>E-mail Sent</title>
<head>
<?php echo _jQuery;?>
<script>
	
</script>
</head>


<!-- Register Page-->
<div data-role="page" id="confirmpage">
	<div data-role="header">
    	<a href="index.php" data-role="button" data-icon="arrow-l">Back</a>
    	<h1>Register</h1>
    </div>
    
	<div data-role="content">
    	<h3 align="center">An e-mail with a link for verification has been sent to your address in order to verify you are who you say you are. 
         Once you click that link, you'll be able to use the application.</h3>
         <div>The e-mail was sent from admin@mapsoup.com (the server's admin e-mail address), so please check your spam and junk mail filter if you don't see the e-mail within 5 mins.</div>
        <a href="index.php" data-role="button" >Back to Homepage</a>
    	
    </div>
</div>