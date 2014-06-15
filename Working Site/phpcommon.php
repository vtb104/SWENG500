<?php
//Site-wide PHP Common functions

session_start();

//All constant varialbes are in vars.php
require_once("vars.php");

require_once("database.php");
//creates the object in order to open the database
$db = new Database;

//Authentication page
require_once("authenticate.php");
$auth = new Authenticate();

//The e-mail obeject lives here
require_once("mailer.php");


//If you want to force the URL for all pages, uncomment this line.  
// The FORCE URL function will not force the 8888 port on the localhost in order to test at home.
//force_url();

//I can't figure out how to force secure from the server, so I do it here
function force_url(){
	//Check if unsecure, local, or if the www is in the name
	if(($_SERVER['SERVER_PORT'] != 443 || strstr($_SERVER['HTTP_HOST'], "www")) && $_SERVER['HTTP_HOST'] != '127.0.0.1:8888' && $_SERVER['HTTP_HOST'] != 'localhost:8888'){
		header('location: https://' . _URL . $_SERVER['REQUEST_URI']);
	}
};
	
//function that returns true if the browser is IE 8.0 or IE 7.0
function verIE(){
		if(strstr($_SERVER['HTTP_USER_AGENT'], "MSIE 8.0") || strstr($_SERVER['HTTP_USER_AGENT'], "MSIE 7.0")){
			return true;
		}else{
			return false;
		};
}


?>