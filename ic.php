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
var deBug = <?php echo _DEBUG;?>;

var testFunction = function (){
	if(deBug){
		$("#test").html("Running...");
		getSearchInfo(currentSearch);
	}
};
</script>
</head>
<body onLoad="initialize()">
<div id="pagewrapper">
    <div id="main">
        <div id="menubar">
            <a class="button1" href="searchertest.php" target="_blank">Searcher Test</a>
            <a class="button1"href="Messages.php">Messages</a>
            <a class="button1"href="fu.php" target="_blank">Field Unit</a>
            <a class="button1" id="logoutbutton" href="logout.php">Log Out</a>
        </div>
        
        <div id="title">
          <h1>Search and Rescue</h1>
          <h2>Incident Command</h2>
          <br/>
          <button class="button1 sbutton" id="optionbuttonshow">← Show Search Option Panel</button>
          <button class="button1 sbutton" id="optionbuttonhide" style="display: none" >→ Hide Search Option Panel</button>
    
        </div>
        <!--Where all the users are displayed-->
        <div id="searcherwrapper">
        	<h4 align="center">List of searchers</h4>
        	<div id="searcherlist"></div>
		</div>
        <?php 
		if(_DEBUG){echo '<div style="position: fixed; bottom: 0px; right: 200px; height: 32px;">    
            <button id="testbutton">Test Button</button></div>';
		}
		?>
	</div>  <!-- Main Div-->    
        
    <!-- Pop out menu for search options-->
    <div id="optiondiv">
        <table class="defaulttable" id="pointoptionstable">
            <tr>
                <td>
                    <span class="optionlabel">Select a Search to View </span>
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
                    <button class="button1" rel="#newsearchoverlay" id="newsearch">New Search</button>
                    <button class="button1" id="deletesearch">Delete Search</button>
                </td>
           </tr>
           <tr class="newline">
           		<td>
                	Search Info
                </td>
               	<td>
                	<div id="currentSearchInfo">
                    </div>
                </td>
            <tr>
                <td>
                    <span class="optionlabel">Team Listing </span>
                </td>
                <td>
                    <select id="currentTeamNumber"></select>
                </td>
            </tr>
            <tr>
                <td></td>
                    <td>
                    <button class="button1" rel="#newteamoverlay" id="newteam">New Team</button>
                    <button class="button1" id="deleteteam">Delete Team</button>
                </td>
               
            </tr>
             <tr>
                <td>
                    <span class="optionlabel">Area Listing </span>
                </td>
                <td>
                    <select id="currentAreas"></select>
                </td>
            </tr>
            <tr class="newline">
                <td></td>
                    <td>
                        <div id="areaOptionsDiv"></div>
                    <button class="button1" id="newarea">New Area</button>
                    <button class="button1"  rel="#areaassignmentoverlay" id="areaAssign">Assign Area</button>
                    <button class="button1" id="deletearea">Delete Area</button>
                </td>
               
            </tr>
            <tr class="newline">
                <td>
                    <span class="optionlabel">Update Interval </span>
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
                    Track History Start Date
                </td>
                <td>
                    <input id="trackDate" class="datepicker dateSince" type="text"/><br/>
                </td>
            </tr>
            <tr >
                <td>
                    Track History Start Time
                </td>
                <td>
                    <input type="time" id="trackTime" class="dateSince" value="12:00"/>
                </td>
            </tr>
  		</table>
    </div>
        
        <div id="info" style="color: white; z-index: 10000;"></div>
        <div id="outer_weather_box" class="bottompopups">
            <div align="center" id="showweather" class="weathercursor">Click to show weather</div>
            <div align="center"	id="hideweather" style="display: none" class="weathercursor" >Click to hide weather</div>
            <div class="weathershow" id="weather_box" style="width:145px; height:400px; background-color:#232323; border:1px solid black; float: left;">Weather Box</div>
            
            <div class="weathershow" id="radar_box" style="float:right;"></div>
                                        
        </div>
        
        <div id="searchAreaBox" class="bottompopups">
        	<div align="center" id="showareabox" class="weathercursor">Click to show area control</div>
             <div align="center" id="hideareabox" style="display: none" class="weathercursor" >Click to hide area control</div><br/>
            <hr>
            
            <div>
                <form>
                    <div id="areaBoxContent" style="font-size: 14px; margin-left: 10px;">
                       <!-- SEE startsearch() for div elements-->
                       
                    </div>
                    <div style="font-size: 14px; margin-left: 10px;">
                        <h5>Assign Area to a team:</h5>
                        <select id="teamList" style="font-size: 14px;"></select>
                        <br><br>
                        Please click on the map to create area boundaries. Once completed click "Save".
                        <br><button class="button1" type="button" onclick="saveAreaButton()" style="font-size: 20px; width:100px;" rel="#alertOverlay">Save</button>
                    </div>
                </form>
                
            </div>
    
        </div>
        
        
        
        
    </div><!--Main Div-->
    
    <!--Items on map-->
    <img id="logo" src="images/med_logo.png" />
    <div id="searchform" action="#"><input type="text" id="searchbox"/><button id="searchnow">Map Search</button></div>
    <div id="map_canvas"></div>
    <div id="cursorLocation"></div>
    <div id="pointsLoaded" class="pointData">Points Loaded: <span id="pointsLoadedData">0</span></div>
    <div id="pointsShowing" class="pointData">Points Showing: <span id="pointsShowingData">0</span></div>

</div><!-- Page Wrapper-->
 
 
 
 
 
 <!--Overlays-->
 
<div class="overlay" id="newsearchoverlay">
	<span class="close">Close / Cancel</span>
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
                	<input id="newsearchtime" class="newsearchclass" type="time" value="09:00"/>
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
         <button class="button1" id="savenewsearch" style="float: right">Save New Search</button>
         <h2 align="center" style="color: red" id="newsearchinfo"></h2>
    </p>
</div>

 <div class="overlay" id="newteamoverlay">
	<span class="close">Close / Cancel</span>
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
         <button class="button1" id="savenewteam" style="float: right">Save New Team</button>
         <h2 align="center" style="color: red" id="newteaminfo"></h2>
    </p>
</div>
 
 <div class="overlay2" id="areaassignmentoverlay">
	<span class="close">Close / Cancel</span>
	<h3 align="center">Assign Team to Area</h3>
    <br/>
    <p>
    	<table id="newteamtable" class="defaulttable">
        	<tr>
            	<td>
                	Team:
                </td>
                <td>
                	<select id="assignTeamList"></select>
                </td>
            </tr>
            <tr>
            	<td>
                	Area:
                </td>
                <td>
                	<select id="assignAreaList"></select>
                </td>
            </tr>
         </table><br/>
         <button class="button1" id="saveareaassignment" style="float: right">Save Area Assignment</button>
         <!--<h2 align="center" style="color: red" id="newteaminfo"></h2>-->
    </p>
</div> 
 <div class="alertOverlay" id="alertOverlay">
	
	<h3 align="center">Message</h3>
    <br/>
    <p>
    	<table id="newteamtable" class="defaulttable">
        	<tr>
            	<td>
                    <div id="alertMessageText">Test message!</div>
                </td>
                <td>
                	
                </td>
            </tr>
         </table><br/>
         <span class="close">Ok</span>
         <!--<h2 align="center" style="color: red" id="newteaminfo"></h2>-->
    </p>
</div> 

<?php 
if(_DEBUG){
	echo '<div id="test" style="position: fixed; right: 0px; bottom: 0px; color: white; z-index: 1000;">Test Line</div>';
};
?>
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
		$("#newsearchname").val("");
		theTime = new Date();
		$("#newsearchdate").datepicker("setDate", theTime);
		$("#newsearchtime").val(leadingZero(theTime.getHours()) + ":" + leadingZero(theTime.getMinutes()));
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
		$("#teamleader").html("");
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
        //Delete an area
	$("#deletearea").click(function(){
		deleteArea();
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
        startNewArea();
	});
	
	$("#hideareabox").click(function(){
		hideAreaBox();
	});
	
    $("#newarea").click(function(){
		$("#showareabox").hide();
                $("#hideareabox").show();
		$("#searchAreaBox").animate({bottom: "0px"}, 400, function(){});
                startNewArea();
	});
	$("#searchnow").click(function(){
		searchNow();
	});
        $("#saveareaassignment").click(function(){
            assignArea(document.getElementById("assignAreaList").value, document.getElementById("assignTeamList").value);
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
    $("#currentAreas").change(function(){
    	updatePointList();
        addAreaOptions();
	});
	$("#optionbuttonshow").click(function(){
		$(this).hide();
		$("#optionbuttonhide").show();
		$("#optiondiv").animate({right: "400px"}, 400, function(){}).show();
	});
	$("#optionbuttonhide").click(function(){
		$(this).hide();
		$("#optionbuttonshow").show();
		$("#optiondiv").animate({right: "0px"}, 400, function(){});
	});

        
});
</script>
</html>
