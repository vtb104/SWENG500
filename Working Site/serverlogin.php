<?php

//Server login page that is referenced by the Ajax call on the login.php page

/*
 *  Function: logs user in if credentials are correct, returns true or false.  
 *  
 *  THIS CANNOT BE RUN AS AN ASYNCHRONOUS FUNCTION FROM THE CLIENT, but it can still use Ajax
 */
 
 require_once("phpcommon.php");
 
if(isset($_POST['login'])){
	echo login($_POST['login'], $_POST['saveit'], $_POST['password'], $db);

}
