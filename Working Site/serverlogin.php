<?php

//Server login page that is referenced by the Ajax call on the login.php page

/*
 *  Function: logs user in if credentials are correct, returns true or false.  
 *  
 *  Be careful if you call this asynchronously, if you test the authentication too soon then it will fail.
 */
 
require_once("phpcommon.php");

$auth = new Authenticate();
 
if(isset($_POST['login'])){
	echo $auth->login($_POST['login'], $_POST['saveit'], $_POST['password'], $db);

}


//Registers a new user, called from the register.php page
if(isset($_POST['register'])){
	
	//Data checking
	
	//Make password and code
	$tempPassword = str_shuffle('POIHDF987');
	$password = $auth->hash_it($tempPassword);
	$userKey = $auth->code();
	
	$check = $db->check_username($_POST['username']);
	if($check){
		echo "That user name is already in use";
		die();
	};
	
	$userId = $db->create_user($_POST['username'], $_POST['fname'], $_POST['lname'], $_POST['email'], $password, $userKey);
	
	if($userId !== false){
		//Send e-mail 
		
		$message = "<!doctype html><html><head><meta charset='utf-8'></head><h3>Search and Rescue Sign Up</h3>Thanks for signing up for the Search and Rescue Application.  Please either click on the link below, or cut and paste into a browser to verify you are who you say you are.  Once you've done that, you can log in and start your search.<br/><br/><a href='https://mapwich.com/ryan/verify.php?code=$userKey&userid=$userId'>Click here</a><br/><br/> Or paste this in a browser:<br/><br/>https://mapwich.com/ryan/verify.php?code=$userKey&userid=$userId<body></body></html>";
		
		$mailer = new Mailer($_POST['email'], 'admin@mapsoup.com', 'SAR Admin', 'E-mail verification', $message);
		$result = $mailer->send_mail();
		
		if($result){
			echo "complete";
		}else{
			echo "There was an error with the verification e-mail, contact support." . __FILE__ . " " . __LINE__;
		}
	}else{
		//Return failure message
		echo 'There was an error with the user creation, contact support';	
	};
}

//Real time checking of the usernames on the register page
if(isset($_POST['usernamecheck'])){
	$check = $db->check_username($_POST['username']);
	if($check){
		echo "That user name is already in use";
	}else{
		echo false;	
	};
	
};

//Real time checking of an existing username on the register page
if(isset($_POST['emailcheck'])){
	$check = $db->check_email($_POST['emailcheck']);
	if($check){
		echo "An account exists when that e-mail address.";
	}else{
		echo false;	
	};
	
};

//Changes user's password from the verify page
if(isset($_POST['changepass'])){

	$userID = $_POST['userid'];

	if($db->check_userkey($userID) === $_POST['userKey']){
		
		$result = $db->change_password($userID, $auth->hash_it($_POST['password']));	
		
		if($result){
			//Set the verify variable to true
			$db->user_verify($userID, 1);
		}	
		
		echo "complete";
	}else{
		echo "There was an error";
	}
	
	
}