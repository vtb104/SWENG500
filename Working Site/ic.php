<?php
    require_once('phpcommon.php');
    if(!$auth->authenticate()){
 	header("location: login.php"); 
    }

?>
<html>
<head>
<meta charset="utf-8">
<title>Command Center</title>
<link rel="icon" type="image/png" href="favicon.ico"/>

<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=true"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=weather"></script>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="ic.js"></script>
<script type="text/javascript" src="StyledMarker.js"></script>
<script type="text/javascript" src="../lib/jQueryRotate.js"></script>
<style>

ul
{
	list-style-type: none;
	margin: 2;
	padding: 2;
	overflow: hidden;
	text-align: right;
}
li
{
	float: inherit;
	text-align: left;
}
a:link,a:visited
{
display:inline;
width:180px;
font-weight:bold;
color:#FFFFFF;
background-color:#232323;
text-align:center;
padding:4px;
text-decoration:none;
text-transform:uppercase;
}
a:hover,a:active
{
	background-color: #232323;
	color: #4C507E;
	font-size: 14px;
}
body {
	font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12; color: #ECEDF3;
	
}

#main{
	position: absolute;
	right: 0px;
	top: 0px;
	bottom: 0px;
	width: 40%;
	min-width: 300px;
	padding: 5px;
	z-index: 11;
	background-color: #232323;
}

#map_canvas{
	position: absolute;
	left: 0px;
	bottom: 0px;
	height: 100%;
	width: 60%;
	min-width: 700px;
	z-index: 10;
}

#floatNote{
	position: fixed;
	right: 0px;
	bottom: 0px;
	color: red;	
	z-Index: 50;
	Padding: 3px;
}

#searchform{
	float: right;
}

</style>
</head>
<?php
	if(isset($_SESSION['userid']))
	{
		echo "<script>var userID = \"".$_SESSION['userid']."\"</script>";
	}
?>
<body onLoad="initialize()">

<div id="main">
	<div id="searchform" action="#"><input type="text" id="searchbox"/><button id="searchnow">Map Search</button></div>
    <a id="logoutbutton" style='position: absolute; right: -40px; top: 40px;' href="logout.php">Log Out</a>

  <img src="images/med_logo.png" />
  <div id="content">
  <h1 style="text-align: center;">Search and Rescue</h1>
  <h2 style="text-align: center;">Command Central</h2>

  <ul>
    <li><a href="Messages.html">Messages</a></li>
    <br>
    <li><a href="Activate GPS.html">Activate GPS</a></li>
    <br>
    <li><a href="Locator.html">Locate Personel</a></li>
  </ul>
  <div id="info">Info Here</div> 	
  Update Interval:
 		<select id="updateInt">
        	<option value="1000">1s</option>
            <option value="5000">5s</option>
            <option value="60000">1 min</option>
        </select>
		<div id="outer_weather_box">
			<div id="weather_box" style="width:200px; height:400px; background-color:#232323; border:1px solid black; float: left;">
				Weather Box
				<br />
			</div>	
			<div id="radar_box" style="float:right;"></div>
			<script>

			</script>				
			</div>
		</div>
</div>

<div id="map_canvas"></div>
<div id="floatNote">Test</div>



</body>
<script>
	$(function(){
		$("#updateInt").change(function(){
			getNewPoints();
		});
	});
</script>
</html>
