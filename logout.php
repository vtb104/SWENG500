<?php  
//MQF, logs user out by killing all cookies and session variables.

require_once('phpcommon.php');

$auth->log_out();

header("location: index.php");

?>