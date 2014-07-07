<?php
//This sheet exists to insert searchers into the database

require_once('phpcommon.php');
if(!$auth->authenticate()){
 	header("location: login.php"); 
 }

?>
<html>
<head>
<meta charset="utf-8">
<title>Searcher Test Page</title>
<link rel="icon" type="image/png" href="favicon.ico"/>

<!-- Libraries-->
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

<!-- jQuery UI-->
<script src="jquery-ui-1.11.0/jquery-ui.min.js"></script>
<link rel="stylesheet" href="jquery-ui-1.11.0/jquery-ui.min.css"/>

<style>

</style>

<script>
var updateInterval = 3000;
var startLat = 38.5309;
var startLng = -99.0527;
var numUsers = 5;
var users = 0;
var addLng = 0;

var initialize = function(){
	window.addEventListener("beforeunload", function(e){
		destroyIt();
	}, false);
};

var testFunction = function (){
	$("#testOutput").html("test");
};

//Sends message to create the users
var createIt = function(){
	
	numUsers = $("#numusers").val();
	if(numusers > 20){
		numusers = 20;	
	}
	
	$.ajax({
		type: "POST",
		url: "searcherTestHandler.php",
		data: "setup=" + numUsers,
		dataType:"json",
		success: function(msg){
			$("#info").html("Searchers with IDs created:<br/> ");
			users = msg
			$.each(users, function(index, value){
				$("#info").append("User: " + value + " created<br/>");
			});
		}
	});
};

//Send message to destroy the users
var destroyIt = function(){
	
	if(timer){
		window.clearTimeout(timer);
	}
	var data = JSON.stringify(users);
	
	$.ajax({
		type: "POST",
		url: "searcherTestHandler.php",
		data: "destroy=true&data=" + data,
		success: function(msg){
			$("#info").html(msg);
		}
	});
};

var startIt = function(){
	timer = setTimeout(function(){sendPosition()}, updateInterval);	
}

var sendPosition = function(){
	
	$("#info").html("Sending...");
	
	addLng = addLng + 0.1; 
	
	if(timer){
		window.clearTimeout(timer);
	}
	var result = false;
	
	timer = setTimeout(function(){sendPosition()}, updateInterval);
	
	$.each(users, function(index, value){
		var sendMsg = new Object();
		sendMsg.user = "" + value;
		sendMsg.lat = startLat + index;
		sendMsg.lng = startLng + addLng;
	
		var forwardMsg = JSON.stringify(sendMsg);
		
		$.ajax({
			type: "POST",
			url: "messageReceive.php",
			data: {dataMsg:forwardMsg},
			success: function(msg){
				$("#info").html("Complete");
			}
		});
	})
		
        
    
}

</script>
</head>
<body onLoad="initialize()">
This page will create searchers that walk East through Kansas.<br/><br/>
Enter number of users to create:<br/>
<input type="number" id="numusers" value="5"/><br/>
<button id="createSearchers">Create Searchers</button><br/><br/>
Click this button to start them moving on the map:<br/>
<button id="startSearchers">Start Searchers</button><br/><br/>
Click this button to stop them from moving across the map and destroy them in the database:<br/>
<button id="destroySearchers">Destroy Searchers</button><br/><br/>
 <div id="info">Test</div>
</body>
<script>
//Put jQuery button listeners here, don't put too many functions here due to scope issues.
$(function(){
	$("#createSearchers").click(function(){
		createIt();
	})
	$("#destroySearchers").click(function(){
		destroyIt();
	});
	
	;$("#startSearchers").click(function(){
		startIt();
	});
	
});
</script>
</html>
