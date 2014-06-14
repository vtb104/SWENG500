<?php

//Cookie Handler

/** This object will handle all cookie functions on the server.
 */

 $ch = new CookieHandler();
 
 class CookieHandler
 {
	 function __construct(){
		 //Cookie constructor goes here
	 }
	 
	 //Checks for properly encoded cookie
	 function check_cookie(){
		 
		//Checks for a cookie
		if(isset($_COOKIE[_LOGINCOOKIE])){
			
			//If there is a site cookie, then grab the data
			$array = json_decode($_COOKIE[_LOGINCOOKIE]);
			
			//If the cookie is correctly encoded, then log the user in with their userid
			if($array->code == code(_COOKIEHASH)){
				$_SESSION['userid'] = $array->userid;
				$_SESSION['code'] = code();
				return true;
			}else{
				
				//Unset cookie if it is found, but invalid
				setcookie(_LOGINCOOKIE, "", time() - 1000);	
				return false;
			}
		}else{
			return false;
		}
	 }
	 
 };