<?php
//This is the login page

require_once("phpcommon.php");

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Search and Rescue Register</title>
<link rel="icon" type="image/png" href="favicon.ico"/>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php echo _jQuery;?>

<script>
var onLoad = function(){
	//Nothing here...	
}
</script>
</head>
<body onLoad="onLoad()">
<div data-role="page" id="registerpage">
	<div data-role="header">
    	<a href="#" data-rel="back" data-icon="arrow-l">Cancel</a>
    	<h1>Register For New Account</h1>
    </div>
    
	<div data-role="content">
    	<h2 id="info" align="center" style="color: red"></h2>
    	<h2 align="center">Please enter your information below:</h2>
        <p>
        	<div class="ui-grid-a">
                <div class="ui-block-a">E-mail Address:<span id="emailcheck" style="color: red"></span></div><div class="ui-block-b"><input id="email" type="email" placeholder="name@you.com"/></div>
                <div class="ui-block-a">User Name:<span style="color: red"id="usernameinfo"></span></div><div class="ui-block-b"><input type="text" id="username"/></div>
            	<div class="ui-block-a">First Name:</div><div class="ui-block-b"><input type="text" id="fname"/></div>
                <div class="ui-block-a">Last Name:</div><div class="ui-block-b"><input type="text" id="lname"/></div>
                
         
            </div>
            <a href="#" data-role="button" data-icon="check" id="submitbutton">Sign Me Up</a>       
        </p>
    	<div class="info"> </div>
    </div>
</div>
</body>
<script>
$(function() {
	$("#submitbutton").click(function(){
		
		$("#info").html("");
		$("#usernameinfo").html("");
		
		
		
			$.ajax({
				type: "POST",
				url: "serverlogin.php",
				data: "register=true&" + 
					  "email=" + $("#email").val() + "&" + 
					  "fname=" + $("#fname").val() + "&" +
					  "lname=" + $("#lname").val() + "&" +
					  "username=" + $("#username").val(),
				async: false,
				success: function(result){
					result = $.trim(result);
					if(result == "complete"){
						window.location.href = "confirm.php";
					}else{
						$("#info").html(result);
					}
				}
			});
		
	});
	
	$("#username").keyup(function(){
		checkUsername();
	});
	
	$("#username").focusout(function(e) {
        checkUsername();
    });
	
	function checkUsername(){
		$.ajax({
			type: "POST",
			url: "serverlogin.php",
			data: "usernamecheck=true&" + 
				  "username=" + $("#username").val(),
			async: true,
			success: function(result){
				if(result){
					$("#usernameinfo").html(result);
				}else{
					$("#usernameinfo").html("");	
				}
			}
		});	
	}
	
	$("#email").focusout(function(){
		emailCheck();
	});	
	
	$("#email").keyup(function(){
		emailCheck();	
	});

	function emailCheck(){
		/*$("#emailcheck").html("");
		$.ajax({
			type: "POST",
			url: "serverlogin.php",
			data: "emailcheck=&" + $("#email").val(),
			async: true,
			success: function(result){
				$("#info").html(result); 
				if(result){
					$("#emailcheck").html(result);
				}else{
					$("#emailcheck").html("");	
				}
			}
		});	*/
	}


});
</script>
</html>




