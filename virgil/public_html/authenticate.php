<?php

/** Authentication Handler, to include cookies
 */

require_once('vars.php');

class Authenticate
{
	function __construct(){
		
	}
	 
	 //Authenticates a user by ensuring the proper session variabels are set and checking for a cookie.
	function authenticate(){
		if(isset($_SESSION['userid'])){
			$userid = $_SESSION['userid'];
			
			//Set the session 'type' to admin if it is one of the team members
			if($userid == 2 || $userid == 3 || $userid == 4 || $userid == 4){
				$_SESSION['type'] = 'admin';	
			}else{
				$_SESSION['type'] = 'normal';	
			}
			
			//Set a code for authentication
			$_SESSION['code'] = $this->code();
			
			return true;
			
		}else if($this->check_cookie()){
			
			$_SESSION['code'] = $this->code();
			
			return true;
			
		}else{
			
			//User is not logged in	
			return false;
		}
		
	}
	 
	//Runs the login function to verify a person
	public function login($name, $setcookie, $password, $db){
		
		$userpassword = $db->get_user_password($name);
		
		if($this->hash_it($password) === $userpassword){
			
			$userid = $db->get_user_id($name);
			$_SESSION['userid'] = $userid;
			$_SESSION['code'] = $this->code();
			
			if($setcookie){
				$array = array("code" => $this->code(_COOKIEHASH), "userid" => $userid);
				
				//Sets the cookie for the cookie duration
				setcookie(_LOGINCOOKIE, json_encode($array), (time() + _COOKIE_DURATION) );
			}
			
			return true;
			
		}else{
			//sleep(3);
			return false;
		}	
	} 
	 
	//Checks for properly encoded cookie
	public function check_cookie(){
	 
		//Checks for a cookie
		if(isset($_COOKIE[_LOGINCOOKIE])){
			
			//If there is a site cookie, then grab the data
			$array = json_decode($_COOKIE[_LOGINCOOKIE]);
			
			//If the cookie is correctly encoded, then log the user in with their userid
			if($array->code == $this->code(_COOKIEHASH)){
				$_SESSION['userid'] = $array->userid;
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
	
	public function log_out(){
		session_unset();
		session_destroy();
		session_write_close();
		session_regenerate_id(true);
		setcookie(_LOGINCOOKIE, "", time() - 1000);	
	}
	 
	//A hash with two salts
	public function hash_it($input){
		return sha1(_HASH1 . $input . _HASH2);
	}
	
	//The custom code used for the $_SESSION['code'] variable
	public function code($input = "1"){
		return $this->hash_it(($_SERVER['HTTP_USER_AGENT'] . $input));
	}
	
	//Creates a random code used for verification
	public function random_code($input = "1"){
		return  str_shuffle("OAISFJNSDONSDOK98A7987SDFJKDJSF98OD");
	}
	 
	 
 };