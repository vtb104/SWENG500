<?php  
//MQF, logs user out by killing all cookies and session variables.

require_once('phpcommon.php');

$auth->log_out();

?>

<html>
<head>
<title>Logout</title>
<link rel="icon" type="image/png" href="favicon.ico"/>
</head>
You are now logged out.<br/><br/>

Return to <a href="index.php">Index.php</a>
</html>