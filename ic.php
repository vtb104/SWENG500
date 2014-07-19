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
<link rel="stylesheet" href="ic.css"/>

<!-- Libraries-->
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=true&libraries=weather"></script>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

<!-- jQuery UI-->
<script src="jquery-ui-1.11.0/jquery-ui.min.js"></script>
<link rel="stylesheet" href="jquery-ui-1.11.0/jquery-ui.min.css"/>
<script src="jquerytools.js"></script>

<!--Color chooser-->
<script type="text/javascript" src="spectrum.js"></script>
<link rel="stylesheet" href="spectrum.css"/>

<script type="text/javascript" src="StyledMarker.js"></script>
<script type="text/javascript" src="lib/jQueryRotate.js"></script>

<!-- Page JavaScript -->
<script src="sharedJS.js"></script>
<script src="ic.js"></script>
<script src="messages.js"></script>



<style></style>

<script>
<?php
	if(isset($_SESSION['userid']))
	{
		echo "var userID = " . $_SESSION['userid'] . ";";
	}else{
		echo "var userID = 0";
	}	
?>

var testFunction = function (){
	$("#testOutput").html(users.userArray[0].userID);
	users.userArray[0].changeColor("#FFF");
};
</script>
</head>
<body onLoad="initialize()">
<div id="pagewrapper">
    <div id="main">
        
    
    
    <div id="menubar">
    	<a href="searchertest.php" target="_blank">Searcher Test</a>
    	<a href="Messages.php">Messages</a>
    	<a href="fu.php" target="_blank">Field Unit</a>
        <a id="logoutbutton" href="logout.php">Log Out</a>
    </div>
    
      <div id="content">
          <h1 style="text-align: center;">Search and Rescue</h1>
          <h2 style="text-align: center;">Incident Command</h2>
			<div id="optiondiv">
            	<table class="defaulttable" id="pointoptionstable">
            	<tr>
                	<td>
                    	<span class="optionlabel">Select a Search to View: </span>
                    </td>
                    <td>
                    <!--Populated by function updateSearches-->
                    <select id="currentSearchNumber"></select>
                    </td>
               </tr>
               <tr>
               		<td>
                    	
                    </td>
                    <td>
                    	<button rel="#newsearchoverlay" id="newsearch">New Search</button>
                    	<button id="deletesearch">Delete Search</button>
                    </td>
               </tr>
                <tr>
                	<td>
                    	<span class="optionlabel">Team Listing: </span>
                    </td>
                    <td>
                    	<select id="currentTeamNumber"></select>
                	</td>
                </tr>
                <tr>
                    <td></td>
                        <td>
                    	<button rel="#newteamoverlay" id="newteam">New Team</button>
                    	<button id="deleteteam">Delete Team</button>
                    </td>
                   
               	</tr>
				<tr>
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
                <tr>
                	<td>
                    	Track History Start Date:
                    </td>
                    <td>
                    	<input id="trackDate" class="datepicker dateSince" type="text"/><br/>
                    </td>
                </tr>
                <tr>
                	<td>
                    	Track History Start Time:
                    </td>
                    <td>
                    	<input type="time" id="trackTime" class="dateSince" value="12:00"/>
                    </td>
                </tr>
           </table><div id="testTime">Here</div> 
           	</div>
           	           
        <!--Where all the users are displayed-->
        <div id="searcherwrapper">
        	<h4 align="center">List of searchers</h4>
        	<div id="searcherlist"></div>
		</div>
        <div style="position: fixed; bottom: 0px; right: 500px;">    
            <div id="testOutput">Test Code Here</div>
            <button id="testbutton">Test Button</button>
        </div>
	</div>  <!-- Content Div-->    

    <!--Items below this line are absolute or fixed, and not in line with the rest of the document-->
        
        <div id="info" style="color: white; z-index: 10000;">Info Here</div>
        <div id="outer_weather_box">
            <div align="center" id="showweather" class="weathercursor">Click to show weather</div>
            <div align="center"	id="hideweather" style="display: none" class="weathercursor" >Click to hide weather</div>
            <div class="weathershow" id="weather_box" style="width:145px; height:400px; background-color:#232323; border:1px solid black; float: left;">Weather Box</div>
            
            <div class="weathershow" id="radar_box" style="float:right;"></div>
                                        
        </div>
        
        <div id="searchAreaBox">
        	<div align="center" id="showareabox" class="weathercursor">Click to show area control</div>
             <div align="center" id="hideareabox" style="display: none" class="weathercursor" >Click to hide area control</div><br/>
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
        
        
        
        
    </div><!--Main Div-->
    
    <!--Items on map-->
    <img id="logo" src="images/med_logo.png" />
    <div id="searchform" action="#"><input type="text" id="searchbox"/><button id="searchnow">Map Search</button></div>
    <div id="map_canvas"></div>
    <div id="cursorLocation">Cursor Location</div>
	<div id="floatNote">Test</div>
    <div id="pointsLoaded" class="pointData">Points Loaded: <span id="pointsLoadedData">0</span></div>
    <div id="pointsShowing" class="pointData">Points Showing: <span id="pointsShowingData">0</span></div>

</div><!-- Page Wrapper-->
 
 <!--Overlays-->
 
<div class="overlay" id="newsearchoverlay">
	<span class="close">Cancel</span>
	<h3 align="center">Create a New Search</h3>
    <br/>
    <p>
    	<table id="newsearchtable" class="defaulttable">
        	<tr>
            	<td>
                	Search Name:
                </td>
                <td>
                	<input id="newsearchname" class="newsearchclass" type="text"/>
                </td>
            </tr>
            <tr>
            	<td>
                	Search Start Date:
                </td>
                <td>
                	<input class="datepicker" class="newsearchclass" id="newsearchdate" type="text"/>
                </td>
            </tr>
            <tr>
            	<td>
                	Search Start Time:
                </td>
                <td>
                	<input id="newsearchtime" class="newsearchclass" type="time" val="0900"/>
                </td>
            </tr>
            <tr>
            	<td>
                	Search Notes:
                </td>
            	<td>
                	<textarea id="newsearchnotes" class="newsearchclass" style="width: 100%; height: 100px;"></textarea>
                <td>
            </tr>
        </table><br/>
         <button id="savenewsearch" style="float: right">Save New Search</button>
         <h2 align="center" style="color: red" id="newsearchinfo"></h2>
    </p>
</div>

 <div class="overlay" id="newteamoverlay">
	<span class="close">Cancel</span>
	<h3 align="center">Create a New Team</h3>
    <br/>
    <p>
    	<table id="newteamtable" class="defaulttable">
        	<tr>
            	<td>
                	Team Name:
                </td>
                <td>
                	<input id="newteamname" class="newteamclass" type="text"/>
                </td>
            </tr>
            <tr>
            	<td>
                	Team Leader:
                </td>
                <td>
                	<select id="teamleader"></select>
                </td>
            </tr>
            <tr>
            	<td>
                	Team Notes:
                </td>
            	<td>
                	<textarea id="newteamnotes" class="newteamclass" style="width: 200px; height: 100px;"></textarea>
                <td>
            </tr>
            <tr>
            	<td>
                	Team/Background Color:
                </td>
                <td>
                	<input type="color" name="color" value="#FF0000" id="newteamcolor"/>
                </td>
            </tr>
            <tr>
                <td>
                    Font Color:
                </td>
                <td>
                    <input type="color" name="color" value="#000000" id="newteamfontcolor"/>
                </td>
            </tr>
        </table><br/>
         <button id="savenewteam" style="float: right">Save New Team</button>
         <h2 align="center" style="color: red" id="newteaminfo"></h2>
    </p>
</div>
 
</body>
<script>
//Put jQuery button listeners here, don't put too many functions here due to scope issues.
$(function(){
	
	//Setup options
	$(".datepicker").datepicker({ dateFormat: "mm-dd-yy" });
	
	//Overlay options
	overlayvar = {mask: {color: '#ccc',loadSpeed: 100, opacity: 0.7}, closeOnClick: false};
	$("input[rel], button[rel], div[rel]").overlay(overlayvar);
	
	//Color chooser
	$("#teamcolor").spectrum({flat: true, showInput: true});
	
	//Change the track length
	$(".dateSince").change(function(){
		updateTrackLength();
	});
	
	//Change the update interval
	$("#updateInt").change(function(){
		updateIntervalCaller();
	});
	
	//Start a new search
	$("#newsearch").click(function(){
		$(".newsearchclass").val("");
		$("#newsearchinfo").html("");
	});
	$("#savenewsearch").click(function(){
		saveNewSearch();
		//$(".button[rel]").overlay().close();
	});
    //Start a new team
	$("#newteam").click(function(){
		$(".newteamclass").val("");
		$("#newteaminfo").html("");
		//Fill the drop down with all the users
		$.each(users.userArray, function(index, value){
			$("#teamleader").append("<option value='" + value.userID + "'>" + value.username + "</option>");	
		})
		
	});
	$("#savenewteam").click(function(){
		saveNewTeam();
		//$("#savenewteam").overlay().close();
	});
	//Delete a search
	$("#deletesearch").click(function(){
		deleteSearch();
	});
	//Delte a team
	$("#deleteteam").click(function(){
		deleteTeam();
	});
	
	//Show and hide the weather box
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
	
	//Show and hide the area creation and management box
	$("#showareabox").click(function(){
		$(this).hide();
		$("#hideareabox").show();
		$("#searchAreaBox").animate({bottom: "0px"}, 400, function(){});
	});
	
	$("#hideareabox").click(function(){
		$(this).hide();
		$("#showareabox").show();
		$("#searchAreaBox").animate({bottom: "-375px"}, 400, function(){});
	});
	
	
	$("#searchnow").click(function(){
		searchNow();
	});
	
	$("#testbutton").click(function(){
		testFunction();
	});
	
	$("#currentSearchNumber").change(function(){
		updateCurrentSearch();
	});
    $("#currentTeamNumber").change(function(){
		updateCurrentTeam();
	});
});
</script>
</html>
