<?php  
//MQF, logs user out by killing all cookies and session variables.

require_once('phpcommon.php');

session_unset();
session_destroy();
session_write_close();
session_regenerate_id(true);
setcookie(_LOGINCOOKIE, "", time() - 1000);

?>

<html>
You are now logged out.
</html>