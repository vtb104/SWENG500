<?php
//This is the login page

require_once("phpcommon.php");

//Get the name of the sending URL to send back
if(isset($_GET['return'])){
	$return = $_GET['return'];
}else{
	$return = "index.php";
}

if($auth->authenticate()){
	header("location: " . $return);
};

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Search and Rescue Login</title>
<?php echo _jQuery;?>

<script>
var onLoad = function(){
	//Nothing here...	
}
</script>
</head>
<body onLoad="onLoad()">
<div data-role="page">
	<div data-role="header">
		<h1>Search and Rescue Login Page</h1>
	</div>

	<h2 data-role="content" id="info" align="center" style="color:red"></h2>

	<div data-role="content">
    
        <p>
        	E-mail Address <b>or</b> Username:<br/>
            <input name="username" id="username"/><br/>
            Password:<br/>
            <input name="password" type="password" id="password"/>
            
            <div class="ui-grid-a">
            	<div class="ui-block-a">
                    Keep me logged in for two months:<br/>
                    <select data-role="slider" id="saveit">
                        <option value="0">Off</option>
                        <option value="1">On</option>
                    </select>
                </div>
                <div class="ui-block-b">
                    <button id="submitbutton">Submit</button>
                </div>
			</div>
			<br/><br/>
       </p>
        <a href="verify.php?forgot=true" data-role="button">Forgot password?</a>
        <br/>
        <br/>
		<a href="register.php" data-role="button" data-ajax="false">Register For New Account</a>
	</div>
</div>


</body>
<script>
$(function() {
	//Log in the user via ajax
	$("#submitbutton").click(function(){
		$("#info").html("");
		$.ajax({
			type: "POST",
			url: "serverlogin.php",
			data: "login=" + $("#username").val() + "&" + "password=" + $("#password").val() + "&" + "saveit=" + $("#saveit").val(),
			async: true,
			success: function(result){
				if(result){
					window.location.href = "<?php echo $return;?>";
					//$("#info").html(result);
				}else{
					$("#info").html("Login information was incorrect");
				}
			}
		});
	});
});
</script>
</html>




