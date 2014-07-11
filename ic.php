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

<!-- jQuery UI-->
<script src="jquery-ui-1.11.0/jquery-ui.min.js"></script>
<link rel="stylesheet" href="jquery-ui-1.11.0/jquery-ui.min.css"/>

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

#logo{position: fixed; right: 0px; top: 30px; width: 150px; z-index: 20}

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
#optiondiv .pointoptions select, input{
	width: 175px;	
}

#optiondiv select,input{
	font-size: 16px;
	height: 25px;	
}

.pointoptions{
	height: 28px;
}

#searcherlist{
	position: absolute;
	top: 200px;
	bottom: 40px;
	right: 0px;
	width: 45%;
	background-color: #666;
	overflow: scroll;
}

#searcherlist .searcher{
	padding: 3px;
	background-color: #781F20;	
	height: 20px;
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

.buttonNameStyle{
	float: left;
}

.buttonInfoStyle{
	float: right;	
}

#searchAreaBox{
	border:1px solid white; 
	width:45%; 
	height:300px; 
	position: absolute; 
	top: 400px; 
	background-color:#232323;	
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

.pointData{
	color: black;
	position: fixed;
	left: 5px;	
	z-Index: 20;
}

#pointsLoaded{
	top: 35px;	
}
#pointsShowing{
	top: 50px;l
}

#cursorLocation{
	position: fixed;
	right: 0px;
	bottom: 20px;
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

<script>
<?php
	if(isset($_SESSION['userid']))
	{
		echo "var userID = " . $_SESSION['userid'];
	}else{
		echo "var userID = 0";
	}	
?>

var testFunction = function (){
	$("#testOutput").html("test");
};
</script>
</head>
<body onLoad="initialize()">
<div id="pagewrapper">
    <div id="main">
        
    
    
    <div id="menubar">
    	<a href="searchertest.php" target="_blank">Searcher Test</a>
    	<a href="Messages.html">Messages</a>
    	<a href="fu.php">Field Unit</a>
        <a id="logoutbutton" href="logout.php">Log Out</a>
    </div>
    
      <div id="content">
          <h1 style="text-align: center;">Search and Rescue</h1>
          <h2 style="text-align: center;">Incident Command</h2>
			<div id="optiondiv">
            <table>
            	<tr class="pointoptions">
                	<td>
                    	<span class="optionlabel">Select a Search to View: </span>
                    </td>
                    <td>
                    <select id="currentSearchNumber">
                        <option value="all">All Searches</option>
                        <option value="new">Create new search...</option>
                    </select>
                    </td>
               </tr>
           
               
                	
                    
                <tr class="pointoptions">
                	<td>
                    	<span class="optionlabel">Team Position to View: </span>
                    </td>
                    <td>
                    	<select id="currentTeamNumber">
                        	<option value="all">All Teams</option>
                        	<option value="1">Team 1</option>
                    	</select>
                	</td>
               	</tr>
				<tr class="pointoptions">
               		<td>
                    	<span class="optionlabel">Update Interval: </span>
                    </td>
                    <td>
                        <select id="updateInt">
                            <option value="5000">5s</option>
                            <option value="10000">10s</option>
                            <option value="10000">30s</option>
                            <option value="60000">1 min</option>
                        </select>
                    </td>
                </tr>
                <tr class="pointoptions">
                	<td>
                    	Track History Start Date:
                    </td>
                    <td>
                    	<input id="trackDate" type="text"/><br/>
                    </td>
                </tr>
                <tr>
                	<td>
                    	Track History Start Time:
                    </td>
                    <td>
                    	<input type="time" id="trackTime" value="12:00"/>
                    </td>
                </tr>
           </table>
           	<div id="testTime">Here</div>
           </div>
            
        <div id="searcherlist">
                       
        </div>
         <div id="searchAreaBox">
                            <button type="button" onclick="startNewArea()">Start New Area</button><br>
                            <select id="AreaEditSelector" onchange="updatePointList()">
                                <!--Add list of areas for this search-->
                            </select>
                            <button type="button" onclick="deleteArea()">Delete Area</button>
                            <hr>
                            
                            <div>
                                <form>
                                    <div id="areaBoxContent">
                                       <!-- Area Name
                                        <input type="text" name="AreaName" id="AreaName" value="Area 1"><input type="color" id="area_color" value="#00ff00"><br>-->
                                        <div id="PointsOfArea">

                                        </div>
                                        
                                    </div>
                                    
                                </form>
                                
                            </div>

                        </div>
         <div style="position: absolute; bottom: 20px; left: 0px;">   
             
        <!--<div id="testOutput">Test Code Here</div>
        <button id="testbutton">Test Button</button>-->
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
    <div id="cursorLocation">Cursor Location</div>
	<div id="floatNote">Test</div>
    <div id="pointsLoaded" class="pointData">Points Loaded: <span id="pointsLoadedData">0</span></div>
    <div id="pointsShowing" class="pointData">Points Showing: <span id="pointsShowingData">0</span></div>

<div><!-- Page Wrapper-->
 
</body>
<script>
//Put jQuery button listeners here, don't put too many functions here due to scope issues.
$(function(){
	
	//Setup options
	$("#trackDate").datepicker({ dateFormat: "mm-dd-yy" });
	
	//Change the track length
	$("#trackDate").change(function(){
		updateTrackLength();
	});
	$("#trackTime").change(function(){
		updateTrackLength();
	});
	
	//Change the update interval
	$("#updateInt").change(function(){
		updateIntervalCaller();
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
	
	$("#testbutton").click(function(){
		testFunction();
	});
	
});
</script>
</html>
