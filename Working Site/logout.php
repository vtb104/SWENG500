<?php  
//MQF, logs user out by killing all cookies and session variables.

require_once('phpcommon.php');

$auth->log_out();

?>

<html>
You are now logged out.<br/><br/>

Return to <a href="index.php">Index.php</a>
</html>