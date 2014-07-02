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
<link rel="stylesheet" href="boilerplate.css"/>

<!-- Libraries-->
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=true"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=weather"></script>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="StyledMarker.js"></script>
<script type="text/javascript" src="lib/jQueryRotate.js"></script>

<!-- Page JavaScript -->
<script src="ic.js"></script>
<script src="cookies.js"></script>


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
font-weight:bold;
color:#FFFFFF;
background-color:#232323;
text-align:center;
text-decoration:none;
text-transform:uppercase;
}
a:hover,a:active
{
	color: #4C507E;
}
body {
	font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12; color: #ECEDF3; background-color: #232323;
	
}

#logo{position: fixed; left: 0px; top: 0px; width: 100px;}

#main{
	position: absolute;
	right: 0px;
	top: 0px;
	bottom: 0px;
	width: 49%;
	min-width: 300px;
	padding: 5px;
	z-index: 11;
	background-color: #232323;
}

#menubar{
	padding-top: 3px;
	position: absolute;
	height: 24px;
	right: 0px;
	width: 100%;	
	border-bottom: 2px #999 solid;
}

#menubar a{
	padding: 4px;
	background-color: #494242;	
	border: 1px solid #847272;
	border-radius: 3px;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
		
}

#menubar a:hover{
	background-color: #ccc;
}


#content{
	padding: 10px;	
	position: absolute;
	top: 35px;
	bottom: 20px;
	width: 95%;
}

#optiondiv{
	position: absolute;
	left: 0px;	
}
#optiondiv .pointoptions select{
	width: 100px;	
}
.pointoptions{
	margin-bottom: 5px;	
}

#searcherlist{
	position: absolute;
	top: 200px;
	bottom: 40px;
	right: 0px;
	width: 200px;
	background-color: #666;
}

#searcherlist .searcher{
	padding: 3px;
	background-color: #781F20;	
	text-align: center;
	margin: 2px;
	border-radius: 4px;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
}
#searcherlist .searcher:hover{
	cursor: pointer;
	background-color: #CC4E50;	
}



/*Non fixed items*/
#floatNote{
	position: fixed;
	right: 0px;
	bottom: 0px;
	color: red;	
	z-Index: 50;
	Padding: 3px;
}

#searchform{
	position: absolute;
	left: 0px;
	z-index: 20;
	padding: 1px;
	background-color: #232323;
}

#map_canvas{
	position: absolute;
	left: 0px;
	bottom: 0px;
	height: 100%;
	width: 50%;
	z-index: 10;
}

/*Weather stuff*/

#outer_weather_box{
	position: fixed;
	z-index: 20;
	background-color: #444;
	height: 300px;
	width: 500px;
	left: 50px;
	bottom: -275px;
	border-radius: 5px;
}

.weathershow{
	display: none;
}

.weathercursor:hover{
	cursor: pointer;	
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
<div id="pagewrapper">
    <div id="main">
        
    
    
    <div id="menubar">
    	<a href="Messages.html">Messages</a>
    	<a href="fu.php">Field Unit</a>
        <a id="logoutbutton" href="logout.php">Log Out</a>
    </div>
    
      <div id="content">
          <h1 style="text-align: center;">Search and Rescue</h1>
          <h2 style="text-align: center;">Incident Command</h2>
			<div id="optiondiv">
                <div class="pointoptions"><span class="optionlabel">Select a Search to View: </span>
                    <select id="currentSearchNumber">
                        <option value="all">All Searches</option>
                        <option value="new">Create new search...</option>
                    </select>
                </div>  
                <div class="pointoptions"><span class="optionlabel">Team Position to View: </span>
                    <select id="currentTeamNumber">
                        <option value="all">All Teams</option>
                        <option value="1">Team 1</option>
                    </select>
                </div>
                <div class="pointoptions"><span class="optionlabel">Update Interval: </span>
                    <select id="updateInt">
                        <option value="1000">1s</option>
                        <option value="5000">5s</option>
                        <option value="60000">1 min</option>
                    </select>
                </div>
               <div class="pointoptions"><span class="optionlabel">Track History Length:</span>
                    <select id="updateTrackLength">
                        <option value="60">Last Minute</option>
                        <option value="1800">30 mins</option>
                        <option value="3600">1 hour</option>
                        <option value="86400">1 day</option>
                        <option value="604800">1 Week</option>
                        <option value="1209600">2 Weeks</option>
                    </select>
                </div>
           	</div>
            
        <div id="searcherlist">
                       
        </div>
            
            
        </div>  <!-- Content Div-->    

    <!--Items below this line are absolute or fixed, and not in line with the rest of the document-->
        
        <div id="info">Info Here</div>
        <div id="outer_weather_box">
            <div align="center" id="showweather" class="weathercursor">Click to show weather</div>
            <div align="center"	id="hideweather" style="display: none" class="weathercursor" >Click to hide weather</div>
            <div class="weathershow" id="weather_box" style="width:200px; height:400px; background-color:#232323; border:1px solid black; float: left;">Weather Box</div>	
            <div class="weathershow" id="radar_box" style="float:right;"></div>
                                        
        </div>
    </div><!--Main Div-->
    
    <!--Items on map-->
    <img id="logo" src="images/med_logo.png" />
    <div id="searchform" action="#"><input type="text" id="searchbox"/><button id="searchnow">Map Search</button></div>
    <div id="map_canvas"></div>
	<div id="floatNote">Test</div>

<div><!-- Page Wrapper-->
 
</body>
<script>
//Put jQuery button listeners here, don't put too many functions here due to scope issues.
$(function(){
	
	//Change the update interval
	$("#updateInt").change(function(){
		updateIntervalCaller();
	});
	
	//Change the update track length
	$("#updateTrackLength").change(function(){
		updateTrackLength();
	});
	
	//Change the team number to view
	$("#currentTeamNumber").change(function(){
		updateTeamNumber();
	}); 
	
	$("#showweather").click(function(){
		$(this).hide();
		$("#hideweather").show();
		$(".weathershow").show("fast");
		$("#outer_weather_box").animate({bottom: "0px"}, 400, function(){});
	});
	
	$("#hideweather").click(function(){
		$(this).hide();
		$("#showweather").show();
		$(".weathershow").hide("fast");
		$("#outer_weather_box").animate({bottom: "-275px"}, 400, function(){});
	});
	
	$("#searchnow").click(function(){
		searchNow();
	});
	
});
</script>
</html>
