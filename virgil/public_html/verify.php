<?php
//This is the verify page, and the forgot password page.

require_once('phpcommon.php');

$result = "Sorry, something went wrong.  Please go to the <a href='contact.php'>Contact Page</a> and tell us what happened.";
$userID = 0;
$userKey = 0;


if(isset($_GET['forgot'])){
	$result = "Please enter your e-mail address for a verification e-mail:<br/><br/>
				E-mail:<br/>
				<form method='post' action='verify.php'>
				<input type='text' name='email' id='email'/><br/>
				<button type='submit'>Submit</button>
				</form>";
}

if(isset($_POST['email'])){
	
	$email = $_POST['email'];
	$result = $db->check_email($_POST['email']);
	echo "->" . $result . "<-";
	die();
	
	
	if($result){
		$userID = $result;
		$userKey = $auth->code();
		
		//Update the code in the user's profile
		$db->update_user_key($userID, $userKey);
		
	
		//Send the e-mail for verification
		$mail = new Mailer($email, "admin@mapsoup.com", "SAR Admin","Password reset", "<!doctype html>
			<html>
			<head>
			<meta charset='utf-8'>
			</head>
			<h3>Search and Rescue Password Reset</h3>
			We do not save passwords in plain-text, so you'll need to make a new one.  Please either click on the link below, or cut and paste into a browser.<br/><br/>
			<a href='https://mapwich.com/ryan/verify.php?code=$userKey&id=$userID'>Click here</a><br/><br/>
			Or paste this in a browser:<br/><br/>
			https://mapwich.com/ryan/verify.php?code=$userKey&id=$userID
			<body>
			</body>
			</html>");
	}
	$result = "An e-mail has been sent to the address you submitted.  Please click on the link in the e-mail to reset your password.  The e-mail has been sent from 'admin@mapsoup.com', so make sure to check your junk or spam folders.";	
	
}


//If this is a verificaiton code, then ask the user to update their password
if(isset($_GET['code'])){
	$userKey = $_GET['code'];
	$userID = $_GET['userid'];
	
	if($db->check_userKey($userID) === $userKey){
		
		//The user is now logged in, so set their values
		$_SESSION['userid'] = $userID;
		$_SESSION['code'] = $auth->code();
		
		//Now put script in so they can set a password
		$result = "You've been authenticated. Please set your password below:<br/><br/> 
		 <div class='ui-grid-a'>
            <div class='ui-block-a'>Password: </div>
            <div class='ui-block-b'>
            	<input type='password' id='pass1'/>
            </div>
        </div>
        <div class='ui-grid-a'>
            <div class='ui-block-a'>Password again:</div>
            <div class='ui-block-b'>
            	<input type='password' id='pass2'/>
            </div>
        </div>
		<a href='#' data-role='button' id='submit' >Create Password</a>";	
	}
};


?>

<html>
<head>
<title>Verification Page</title>
<head>
<?php echo _jQuery;?>
<script>
	userid = <?php echo $userID;?>
</script>
</head>
<!-- Register Page-->
<body>
<div data-role="page" id="registerpage">
	<div data-role="header">
    	<a href="index.php" data-role="button" data-icon="arrow-l">Back</a>
    	<h1>Verify Account</h1>
    </div>
    
	<div data-role="content">
    	<h3 align="center">
        	<?php echo $result;?>
            <div id="info"></div>
        </h3>
        
    	
    </div>
</div>
</body>
<script>
$(function(){
	$("#submit").click(function(){
		$("#info").html("");
		//Check the passwords
		if($("#pass1").val() !== $("#pass2").val()){
			$("#info").html("Passwords don't match.");
		}else{
			$.ajax({
				type: "POST",
				url: "serverlogin.php",
				data: "changepass=true&userid=" + userid + "&" + "password=" + $("#pass1").val() + "&userKey=<?php echo $userKey?>",
				async: false,
				success: function(result){
					$("#info").html(result);
					if(result === "complete"){
						$("#info").html("Your password has been reset <a href='index.php' data-ajax='false'>Go Log In</a>");
					}else{
						$("#info").html(result);
					}
				}
			});
		};
	});
	
	
});
</script>
</html>

