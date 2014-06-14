<?php
//Site-wide PHP Common functions

session_start();

//Files included on everypage
require_once("vars.php");
require_once("cookie.php");
require_once("mailer.php");
require_once("database.php");

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

//Authenticates a user by ensuring the proper session variabels are set.
function authenticate(){
	if(isset($_SESSION['userid'])){
		$userid = $_SESSION['userid'];
		
		//Set the session 'type' to admin if it is one of the team members
		if($userid == 2 || $userid == 3 || $userid == 4 || $userid == 4){
			$_SESSION['type'] = 'admin';	
		}else{
			$_SESSION['type'] = 'normal';	
		}
		
	}else{
		//User is not logged in
		header("location: login.php");	
		die();
	}
	
}


//Runs the login function to verify a person
function login($name, $setcookie, $password, $db){
	
	$userpassword = $db->get_user_password($name);
	
	if(hash_it($password) === $userpassword){
		$userid = $db->get_user_id($name);
		$_SESSION['userid'] = $userid;
		$_SESSION['code'] = code();
		
		if($setcookie){
			$array = array("code" => code(_COOKIEHASH), "userid" => $userid);
			
			//Sets the cookie for the cookie duration
			setcookie(_LOGINCOOKIE, json_encode($array), (time() + _COOKIE_DURATION) );
		}
		
		return true;
	}else{
		sleep(1);
		return false;
	}	
}

//A hash with two salts
function hash_it($input){
	return sha1(_HASH1 . $input . _HASH2);
}

//The custom code used for the $_SESSION['code'] variable
function code($input = "1"){
	return hash_it(($_SERVER['HTTP_USER_AGENT'] . $input));
}

//Creates a random code used for verification
function random_code($input = "1"){
	return  str_shuffle("OAISFJNSDONSDOK98A7987SDFJKDJSF98OD");
}
	
//function that returns true if the browser is IE 8.0 or IE 7.0
function verIE(){
		if(strstr($_SERVER['HTTP_USER_AGENT'], "MSIE 8.0") || strstr($_SERVER['HTTP_USER_AGENT'], "MSIE 7.0")){
			return true;
		}else{
			return false;
		};
}


?>