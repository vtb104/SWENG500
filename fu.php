<?php
    require_once('phpcommon.php');
	require_once("messageSend.php");
    if(!$auth->authenticate()){
 	header("location: login.php"); 
    }

?>
        
<html> 
<head> 
  <title>Field Unit v 1.0</title>
  <link rel="icon" type="image/png" href="favicon.ico"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.css">
    <link rel="stylesheet" type="text/css" href="fu.css">
  <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
  <script src="https://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?sensor=true"></script>
  <script src="sharedJS.js"></script>
  <script src="fu.js"></script>
  <script src="messages.js"></script>
<style>
	.cachedPoints{
		font-size: 8px;	
	}
</style>
<script>
<?php
	if(isset($_SESSION['userid']))
	{
		echo "userID = '".$_SESSION['userid']."';";
	}
?>

</script>
</head> 
<body onLoad="initialize()">
<div data-role=page id=geoMap>
        <div data-role=header>
            <a href="#geoMapHelp" class="ui-btn ui-btn-inline ui-corner-all ui-shadow">Help</a><a href="logout.php">Logout</a>
            <h1>Geo Location Map</h1>
            
        </div>
    
        <div data-role="panel" id="geoMapHelp"> 
                <h2>Geo Location Map Help</h2>
                <p>This screen provides the user with a local area map with an icon denoting their current location.</p>
                <p>In addition, the longitude and latitude are displayed along with a red text counter that iterates as a new set of coordinates are sent to the server.</p>
				<p>Navigation is capable at the bottom of the window to the Messages and Configure screens.</p>
				<p>You can close the help panel by clicking outside the panel, pressing the Esc key or by swiping.</p>
				
				<div id="infoLoc" style="color: red">Attempting to find current location...</div>
				<input id="lat"/>
				<input id="lng"/>
				<span id="updateLocInfo"></span>
        </div>

        <div data-role="content">
			<button onclick="panToCurrentLocation()">Pan to Current Location</button>
        </div>
    
        <div role="main" class="ui-content" id="map_canvas">
		<!-- map loads here... -->
		</div>
    
        <div data-role="footer" data-position="fixed">
            <div data-role="navbar">
                <ul>
                    <li><a href="#geoMap">Map</a></li>
                    <li><a href="#message">Messages</a></li>
                    <li><a href="#configure" class=".configure">Configure<span class="cachedPoints"></a></li>
                </ul>
            </div>
        </div>
</div>
    
<div data-role="page" id="message">
        <div data-role="header">
            <a href="#msgHelp" class="ui-btn ui-btn-inline ui-corner-all ui-shadow">Help</a><a href="logout.php">Logout</a>
            <h1>Messages</h1>
        </div>

        <div data-role="panel" id="msgHelp">
            <h2>Message Help</h2>
            <p>You can close the help panel by clicking outside the panel, pressing the Esc key or by swiping.</p>
            <p>Only the last ten messages are displayed. This holds true for sent and received messages.</p>
            <span id="updateMsgInfo"></span>
        </div> 

		<div data-role="content">
			<div role="main" class="ui-content" id="container">
			<div class = "msgContainer" id="msgWindow">
				<p>Here's our chat data</p>
			</div>
			
			<div id = "sendMsg">
				<textarea rows = "2" id="messageBody">Enter your message here...</textarea>
				<button id="messageSend">Send</button>
			</div>
			</div>
		</div>
		
        <div data-role="footer" data-position="fixed">
            <div data-role="navbar">
                <ul>
                    <li><a href="#geoMap">Map</a></li>
                    <li><a href="#message">Messages</a></li>
                    <li><a href="#configure" class=".configure">Configure<span class="cachedPoints"></a></li>
                </ul>
            </div>
        </div>
</div> 

<div data-role="page" id="configure">
        <div data-role="header">
            <a href="#confHelp" class="ui-btn ui-btn-inline ui-corner-all ui-shadow">Help</a><a href="logout.php">Logout</a>
            <h1>Configure</h1>
            
		</div>
        <div data-role="panel" id="confHelp"> 
            <h2>Configuration Help</h2>
            <p>You can close the help panel by clicking outside the panel, pressing the Esc key or by swiping.</p>
            <p>insert help text here</p>
        </div> 

        <div data-role="content">
            <h3>Configure the SAR Mobile App</h3>

             <h4>Update Location Interval</h4>
             <select id="updateLocInt">
                <option value="1000">1s</option>
                <option value="5000">5s</option>
                <option value="30000">30s</option>
				<option value="60000">1min</option>
            </select>

            <br/>
            <h3 align="center">Join a search</h3>
            <!--Updated via Javascript-->
           	<button id="refreshSearches">Refresh Search List</button>
            <div class="ui-grid-a">
            	<div class="ui-block-a"><select id="currentSearchNumber"></select></div>
                <div class="ui-block-b"><select id="joinOrLeave"><option value="1">Join</option><option value="0">Leave</option></select></div>
            </div>
            <button id="currentSearchButton">Save</button>
            <div align="center" id="joinOrLeaveStatus"></div>
        </div>

        <div data-role="footer" data-position="fixed">
            <div data-role="navbar">
                <ul>
                    <li><a href="#geoMap">Map</a></li>
                    <li><a href="#message">Messages</a></li>
                    <li><a href="#configure" class=".configure">Configure<span class="cachedPoints"></span></a></li>
                </ul>
            </div>
        </div>
</div> 

</body>
<script>
$(function(){
	$("#currentSearchButton").click(function(){
		updateCurrentSearch();
		joinOrLeave();
	});
	$("#refreshSearches").click(function(){
		updateSearches();
	});
	
	$(".configure").click(function(){
		$("#joinOrLeaveStatus").html("");
		$("#currentSearchNumber").selectmenu('refresh');
	});
    $("#messageSend").click(function(){
		sendMessage(currentSearch, userID, 'From FU', $("#messageBody").val(), '1');
	});
});
</script>
</html>