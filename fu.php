<?php
    require_once('phpcommon.php');
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
  <script src="fu.js"></script>
</head> 
<?php
	if(isset($_SESSION['userid']))
	{
		echo "<script>userID = \"".$_SESSION['userid']."\"</script>";
	}
?>
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
        </div>

        <div data-role="content">
            <div id="infoLoc" style="color: red">Attempting to find current location...</div>
            <input id="lat"/>
            <input id="lng"/>
			<button onclick="panToCurrentLocation()">Pan to Current Location</button>
            <span id="updateLocInfo"></span>
        </div>
    
        <div role="main" class="ui-content" id="map_canvas">
		<!-- map loads here... -->
	</div>
    
        <div data-role="footer" data-position="fixed">
            <div data-role="navbar">
                <ul>
                    <li><a href="#geoMap">Map</a></li>
                    <li><a href="#message">Messages</a></li>
                    <li><a href="#configure">Configure</a></li>
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
            <p>insert help text here</p>
        </div> 

        <div data-role="content">
            <h3>Send and Get Messages</h3>
        </div>

		<div data-role="content">
            <span id="updateMsgInfo"></span>
        </div>
		
        <div data-role="footer" data-position="fixed">
            <div data-role="navbar">
                <ul>
                    <li><a href="#geoMap">Map</a></li>
                    <li><a href="#message">Messages</a></li>
                    <li><a href="#configure">Configure</a></li>
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
			
			<h4>Check for Message Interval</h4>
             <select id="checkMsgInt">
                <option value="5000">5s</option>
                <option value="10000">10s</option>
                <option value="30000">30s</option>
				<option value="60000">30s</option>
            </select>
        </div>

        <div data-role="footer" data-position="fixed">
            <div data-role="navbar">
                <ul>
                    <li><a href="#geoMap">Map</a></li>
                    <li><a href="#message">Messages</a></li>
                    <li><a href="#configure">Configure</a></li>
                </ul>
            </div>
        </div>
</div> 

</body>
</html>