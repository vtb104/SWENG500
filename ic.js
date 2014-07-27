	var map;

//Used to halt timers and fetchers for other actions.
var goVar = true;

//Points loaded into the arrays
var pointsLoaded = 0;

//Points showing based on the time requested to show from
var pointsShowing = 0;

var timer = 0;

var startLat = 39.57;  //America
var startLng = -100;

var poly;
var pointCount = 0;
var pointArray = [];
var areaArray = [];
var currentAreaPoly;
var areaData;
var polyStorage = [];
var areaIDData;
var workingAreaPointArray = [];

if(readCookie("sar.location.lat") && readCookie("sar.location.lng")){
	startLat = readCookie("sar.location.lat");
	startLng = readCookie("sar.location.lng");
}

var mapZoom = 8;
if(readCookie("sar.zoom")){
	mapZoom = parseInt(readCookie("sar.zoom"));
}

var mapHome = new google.maps.LatLng(startLat, startLng);

//Update period varibles
var updateInterval = 5000;  	//Default to 5 seconds
if(readCookie("sar.update")){
	updateInterval = readCookie("sar.update");
};
var updateIntervalCaller = function(){
	updateInterval = $("#updateInt").val();
	writeCookie("sar.update", updateInterval, cookieDuration);
	getNewPoints();	
}

//Value and update fucntion for the current search and team selection
var currentSearch = "all";
var currentTeam = "all";
if(readCookie("sar.currentSearchIC")){
	currentSearch = readCookie("sar.currentSearchIC");
}
if(readCookie("sar.currentTeamIC")){
	//currentTeam = readCookie("sar.currentTeamIC");
}

var updateCurrentSearch = function(){
	currentSearch = $("#currentSearchNumber").val();
	writeCookie("sar.currentSearchIC", currentSearch, cookieDuration);
	getNewPoints();
}
var updateCurrentTeam = function(){
	currentTeam = $("#currentTeamNumber").val();
	writeCookie("sar.currentTeamIC", currentTeam, cookieDuration);
}

//Value and track variable length
var nowTime = new Date();
var trackHistoryStart = new Date();

if(readCookie("sar.trackStart")){
 	var temp = readCookie("sar.trackStart");
	trackHistoryStart.setTime(temp);
}else{
	trackHistoryStart.setTime(nowTime.getTime() - (3600 * 24 * 1000));  //Default one day ago
	
};

var updateTrackLength = function(){
	
	trackHistoryStart = getUITime();
	//$("#testTime").html(trackHistoryStart.toString());
	
	//Write the cookie
	writeCookie("sar.trackStart", trackHistoryStart.getTime(), cookieDuration);
	
	//Show the trails in relation to that value
	pointsShowing = 0;
	users.updateTrails();
};

/*******************First to run, initializes all the points*******************************/
var initialize = function(){
	var overviewOptions = {opened: false};
	var panControlOptions = {position: google.maps.ControlPosition.RIGHT_TOP};
	var scaleControlOptions = {position: google.maps.ControlPosition.LEFT_BOTTOM};
	var zoomControlOptions = {position: google.maps.ControlPosition.RIGHT_CENTER};
	
	//Sets options for the map
	var myOptions = {zoom: mapZoom, 
					 center: mapHome,
					 panControlOptions: panControlOptions,
					 zoomControlOptions: zoomControlOptions,
					 overviewOptions: overviewOptions
					 };
	
	//Creates the map
	map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
	
	//Add a listener that only updates the weather when the map moves
	google.maps.event.addListener(map, 'idle', function() {updateWeather('weather_box','radar_box');} );
	
	//Updates the cursor location for grabbing coords
	//google.maps.event.addListener(map, 'mousemove', function(event) {
		//$("#cursorLocation").html("Lat: " + Math.round4(event.latLng.lat()) + " Lng: " + Math.round4(event.latLng.lng()))
	//});
	
	//Adds listeners for cookie creation
	google.maps.event.addListener(map, "center_changed", function(){
		temp = new google.maps.LatLng();
		temp = map.getCenter();
		writeCookie("sar.location.lat", temp.lat(), cookieDuration);
		writeCookie("sar.location.lng", temp.lng(), cookieDuration);
	});
	
	google.maps.event.addListener(map, "zoom_changed", function(event){
		writeCookie("sar.zoom", map.getZoom(), cookieDuration);
	});
	
	//Draw an initial weather box
	updateWeather('weather_box','radar_box');
	
	//Start testing for new messages
	newMessageTimer = setInterval(function(){checkMessages(userID);}, 1000);
	
	//This is the timer that runs the getNewPoint function
	$("#floatNote").html("Connecting...");
	connectionStart = new Date();
	
	//Update trackLength value
	setUITime(trackHistoryStart);
	
	$("#testTime").html(trackHistoryStart.toString())
	
	//Updates the timer value with the default
	$("#updateInt").val(updateInterval);

	//Pulls the list of searches from the database and updates the list
	updateSearches();
	
	//Pulls all teams
        updateTeams();
        
	//Start the timer to get new points
	getNewPoints();
	
    //this event listener will be used for area creation 
    var polyOptions = 
	{
		strokeColor: "#00ff00",
		strokeOpacity: 1.0,
		strokeWeight: 3
	};
	poly = new google.maps.Polyline(polyOptions);
	poly.setMap(map);      
        //find a place for these
        updateAreaSelectMenu();
        udpateAreaObjectMenu("Area1");
};
/*******************Area Creation Methods*******************************/
//This fucntion is used to add areas to maps
function addLatLng(event) 
{

    var path = poly.getPath();
    // Because path is an MVCArray, we can simply append a new coordinate
    // and it will automatically appear.

    path.push(event.latLng);

    // Add a new marker at the new plotted point on the polyline.
    var styleMaker1 = new StyledMarker({styleIcon:new StyledIcon(StyledIconTypes.MARKER,{color:document.getElementById("area_color").value,text:""+pointCount}),position:event.latLng,map:map});
    workingAreaPointArray.push(styleMaker1);
    //document.getElementById("PointsOfArea").innerHTML += '<input type="radio" name="point' + pointCount + '">Point '+pointCount+'<br>';
    if(document.getElementById("tempMsg") != null)
    {
        document.getElementById("tempMsg").innerHTML = "";
    }
    //add the "click" to the current poly array
    currentAreaPoly.push(event.latLng);
    
    pointCount++;
}
//this section populates the "area creation box"
//get all current area objects and populate select menu
function updateAreaSelectMenu()
{
    //TODO: database call for current areas

    $.ajax({
        type: "POST",
        url: "AreaHandler.php",
        data: {searchID:"1"},//change search ID for multiple searches
        dataType: "json",
        success: function(msg){
            //for each area create a select menu item
            var selectMenu = document.getElementById("currentAreas");
            selectMenu.options.length = 0;
            selectMenu.options.add(new Option("Select Area", "Select Area"));
            for(var cnt=0; cnt< msg.length; cnt++)
            {
                selectMenu.options.add(new Option(msg[cnt].areaName, msg[cnt].areaID));
                document.getElementById("assignAreaList").options.add(new Option(msg[cnt].areaName, msg[cnt].areaID));
            }
        }});
}
function udpateAreaObjectMenu(areaName)
{
    //TODO: get points of area
    //update area name
    var areaName = document.getElementById("AreaName");
    //var pointsDiv = document.getElementById("PointsOfArea");
    //pointsDiv.innerHTML = "";
}
//this function is called when the user selects a different area
function updatePointList()
{
    //load area selected points
     $.ajax({
        type: "POST",
        url: "AreaHandler.php",
        data: {getAreaPoints:document.getElementById("currentAreas").value},//change search ID for multiple searches
        dataType: "json",
        success: function(areaData){
            pointArray = [];
            areaIDData = areaData[0].areaName;
            var tempPointsArray = JSON.parse(areaData[0].areaPoints);
            for(var cnt=0; cnt < tempPointsArray.length; cnt++)
            {
                //gotta split up JSON string
                
                //alert(dataObj[0].k);
                pointArray.push(new google.maps.LatLng(tempPointsArray[cnt].k,tempPointsArray[cnt].B));
            }
        }});
}
//this function is called when "start new area button is clicked
function startNewArea()
{ 
    document.getElementById("areaBoxContent").innerHTML = '<h5>Enter An Area Name: </h5><input type="text" name="AreaName" id="AreaName" value="Enter an Area Name"><h5>Select Color for Area:</h5><input type="color" id="area_color" value="#00ff00"><div id="tempMsg"></div><div id="PointsOfArea"></div>';
    //TODO check if listener exists already
    google.maps.event.addListener(map, 'click', addLatLng);
    currentAreaPoly = [];
}
//this function is used to assign a team to an area
function assignArea(inArea, inTeam)
{
    var assignmentData = new Object();
    assignmentData.area = inArea;
    assignmentData.team = ""+inTeam;
     $.ajax({
        type: "POST",
        url: "AreaHandler.php",
        data: {assignArea:JSON.stringify(assignmentData)},//change search ID for multiple searches
        dataType: "json",
        success: function(areaData){
           //TODO handle successful assignment
        }});    
}
//adds the show area on map and remove area from map buttons
function addAreaOptions()
{
    document.getElementById("areaOptionsDiv").innerHTML = '<button onclick="showAreaOnMap()">Show Area On Map</button><button onclick="removeAreaFromMap()">Remove Area From Map</button>';
}
//this function deletes the given area
function deleteArea()
{
     $.ajax({
        type: "POST",
        url: "AreaHandler.php",
        data: {deleteArea:document.getElementById("currentAreas").value},
        dataType: "json",
        success: function(areaData){
           //remove area from list
           alert(areaData);
        }});
    updateAreaSelectMenu();
    document.getElementById("areaBoxContent").innerHTML = "";
}
//this function is called to show the area on the map
function showAreaOnMap()
{
    fillPoly(areaIDData,pointArray);
}
function removeAreaFromMap()
{
    var areaIndex = checkArrayForName(polyStorage, areaIDData);
    if(areaIndex >= 0)
    {
        polyStorage[areaIndex].area.setMap(null);
        polyStorage.splice(areaIndex,1);
    }
}
//fills the poly and completes last border
function fillPoly(areaID, arrayOfPoints)
{
    //check if area exists on map
    if(checkArrayForName(polyStorage, areaID) == -1)
    {
        //create the "fill polygon"
        var polyObj = new Object();
        var tempColor = "#00ff00";
        if(document.getElementById("area_color") != null)
        {
            tempColor = document.getElementById("area_color").value;
        }
        var areaFill = new google.maps.Polygon(
        {
            paths: arrayOfPoints,
            strokeColor: tempColor,
            strokeOpacity: 0.8,
            strokeWeight: 3,
            fillColor: tempColor,
            fillOpacity: 0.35
        });
        map.panTo(new google.maps.LatLng(arrayOfPoints[0].k,arrayOfPoints[0].B));
        areaFill.setMap(map);
        polyObj.name = areaID;
        polyObj.area = areaFill;
        polyStorage.push(polyObj);
    }
}
function checkArrayForName(inArray, inName)
{
    for(var cnt =0; cnt < inArray.length; cnt++)
    {
        if(inArray[cnt].name == inName)
        {
            return cnt;
        }
    }
   return -1;
}
//this function is called when "save" is clicked after adding an area
function saveAreaButton()
{
    //fillPoly(areaNameData,currentAreaPoly);

    google.maps.event.clearListeners(map, 'click');
    //send area to database
    areaData = new Object();
    areaData.name = document.getElementById("AreaName").value;
    areaData.userID = userID;
    areaData.points = currentAreaPoly;
    areaData.color = document.getElementById("area_color").value;
    
    var returnedAreaID = 0;
    $.ajax({
        type: "POST",
        url: "AreaHandler.php",
        data: { createArea:JSON.stringify(areaData) },
		dataType: "json",
        success: function(msg){ returnedAreaID = parseInt(msg);  //check if team was assigned to area
    if(document.getElementById("teamList").value != "all")
    {   
        assignArea(returnedAreaID,document.getElementById("teamList").value); 
    }}});
    
     
    
    //reset all data for next area
    poly.setMap(null);
    currentAreaPoly = [];
    pointCount = 0;
    var polyOptions = 
    {
            strokeColor: document.getElementById("area_color").value,
            strokeOpacity: 1.0,
            strokeWeight: 3
    };
    poly = new google.maps.Polyline(polyOptions);
    poly.setMap(map);
    updateAreaSelectMenu();
    fillPoly(areaData.name,areaData.points);
    //clear current points on map
    for(var cnt=0; cnt <workingAreaPointArray.length; cnt++)
    {
        workingAreaPointArray[cnt].setMap(null);
    }
  

    
}
var polylineStorage = [];
var markerStorage = [];
//create user tracks
function updateUserTrack(userNumber)
{
    polylineStorage[userNumber] = new google.maps.Polyline({
    path: usersPolyLines[userNumber],
    geodesic: true,
    strokeColor: '#FF0000',
    strokeOpacity: 1.0,
    strokeWeight: 2
	});
    polylineStorage[userNumber].setMap(map);
    //alert(usersPolyLines[userNumber]);
    tempPos = usersPolyLines[userNumber][usersPolyLines[userNumber].length-1];
    
    markerStorage[userNumber] = new StyledMarker({styleIcon:new StyledIcon(StyledIconTypes.MARKER,{color:"#99FF66",text:""+userNumber}),position:tempPos,map:map});
    markerStorage[userNumber].setPosition(tempPos);
   
}


//This is the function that runs every 5s from the timer
var getNewPoints = function(){
	
	//Starts the timer based on the user input
	if(timer){
		window.clearTimeout(timer);
	}
	timer = setTimeout(function(){getNewPoints()}, updateInterval);

	//The request data needs to be modified to request certain teams and searches only
	/*
		team = teamID
		theTime = time in SECONDS for the age of the points
	 */
	requestData = {currentSearch: currentSearch, 
				   theTime: (Math.round(trackHistoryStart.getTime() / 1000)),
				   updateInterval: updateInterval}
	
	//Check if the UI is paused
	if(goVar || true){
		//Start the AJAX call
		$("#floatNote").html("Sending...");
		$.ajax({
			type: "POST",
			url: "messageSend.php",
			data: { update_ic_req:requestData },
			dataType: "json",
			success: function(msg){ 
				var connectionNow = new Date();
				$("#floatNote").html("Connected to server for " + (Math.round((connectionNow.getTime() - connectionStart.getTime())/1000)) + "s");        
				
				//$("#info").html(msg);
				
				//Check if there was an error before plotting
				if(msg[0].error){
					//Error 1 means there were no points, so clear the users
					if(msg[0].error == 1){
						users.destroyArray();
					}else{
						$("#info").html(msg[0].error);	
					};
				}else{
					//Send the msg object to the user singleton to update or create points.
					users.checkUsers(msg);
					users.drawUserButtons();
					users.plotPoints();	
					users.updateTrails();	
				}
				
				/*Object format for the returned JSON string:
					{"userID":"2",
					 "points":
						{"pointID":"49450",
						 "userID":"2",
						 "lat":"64.6790173",
						 "lng":"-147.0894166",
						 "alt":"25",
						 "dateCreated":"1404170736",
						 "pointNotes":"From FU"},
						 ....
					 "userData":
						{"username":"ryan","fname":"Ryan","lname":"lname"}]},
				*/
			}
		});
	}
};


/************************Object of Users****************************************
 *  The User object contains functions that iterate through the userArray value*
 ******************************************************************************/
 
//Global user singleton (thanks for that team Shane)
var users = new Users;

function Users(user){
	this.userArray = [];	
}

//Returns the position of the user if the user already exists in the array
Users.prototype.checkForUser = function(userID){
	var returnValue = false;
	$.each(this.userArray, function(index, value){
		if(value.userID == userID){
			returnValue = index;
		}
	})
	return returnValue;
}

//Checks for users in the input object and adds them to the array if they are not in it yet
Users.prototype.checkUsers = function(inputUsers){
	
	var userPointer = this;
	var indexPasser = false;
	var found = false;
	var toDestroy = 0;
	
	//First figure out if a user wasn't in the incoming array and remove them if they are not
	for(i=userPointer.userArray.length-1; i > -1; i--){
		found = false
		indexPasser = false;
		$.each(inputUsers, function(index2, value2){
			if(userPointer.userArray[i].userID === value2.userID){
				found = true;
				indexPasser = index2;
			};
		});
		
		if(!found){
			//If the user isn't found on the input, remove them from the map
			userPointer.userArray[i].destroy();
			userPointer.userArray.splice(i, 1);
		}
	}
	
	
	//Now the array should be cleaned of everyone not incoming, now add new users
	$.each(inputUsers, function(index, value){
		found = false;
		indexPasser = false
		$.each(userPointer.userArray, function(index2, value2){
			if(value2.userID === value.userID){
				found = true;
				indexPasser = index2;
			};
		});
		
		if(!found){
			//If the user isn't found, build a new Person
			var tempUser = new Person(value);
			tempUser.sortPoints();
			userPointer.userArray.push(tempUser);
		}else{
			//If the incoming user is found, update their points
			$.each(value.points, function(index3, value3){
				userPointer.userArray[indexPasser].addPoint(value3);
			});
			userPointer.userArray[indexPasser].sortPoints();
		}
		
	});
}

//Draws the buttons for active users
Users.prototype.drawUserButtons = function(){
	$("#searcherlist").html("");
	$.each(this.userArray, function(index, value){
		
		var leaderString = '';
		//Check if the user is a team leader to annotate on the button
		$.each(teamArray, function(index2, value2){
			if(value.userID == value2.owner && value.teamID != null){
				leaderString = ", Team Lead";	
			}
		});
		
		if(!value.pointArray.length){
			leaderString = " (No points)";
		}
		
		$("#searcherlist").append('<div id="user' + value.userID + '" class="searcher searcherexpand" userID="' + value.userID + '" style="background-color:' + value.userColor + "; color: " + value.fontColor + '"><span class="buttonNameStyle">' + value.userID +' ' + value.username + leaderString + '</span><select class="teamDrop buttonInfoStyle searcherexpand" userID="' + value.userID + '" id="teamDrop' + value.userID + '"></select> <span class="buttonInfoStyle" id="userTrail' + value.userID + '">-</span></div>');
		
		//Attach functions to newly created buttons
		$(".searcher").on("click", function(e){
			users.panToPerson($(this).attr("userID"));
		});
		$(".teamDrop").on("change", function(e){
			users.joinTeam($(this).attr("userID"), $(this).val());
		});
		
		//Expand user box
		$(".searcherexpand").on("mouseenter", function(e){
			goVar = false; $("#floatNote").html("Updating paused...");
			 });// $(this).animate({height: "100px"}, 100, function(){})});
		//Close
		$(".searcherexpand").on("mouseout", function(e){
			goVar = true; $("#floatNote").html("");});//$(this).animate({height: "20px"}, 100, function(){})});
		
	});
	
	//Add the list of teams to the search assignments
	$(".teamDrop").html("<option value='0'>No Team</option>");
	$.each(teamArray, function(index, value){
		$(".teamDrop").append("<option value='" + value.teamID + "'>" + value.teamName + "</option>");
	});
	
	//Update the drop downs to reflect the database numbers
	users.teamDropUpdate();
}

//Updates the drop downs for the team assignments already assigned
Users.prototype.teamDropUpdate = function(){
	$.each(this.userArray, function(index, value){
		if(value.teamID){
			$("#teamDrop" + value.userID).val(this.teamID);
		}else{
			$("#teamDrop" + value.userID).val("0");
		}
	});
}

//Plots the current user array
Users.prototype.plotPoints = function(){
	pointsLoaded = 0;
	$.each(this.userArray, function(index, value){
		value.plotPoints();
	});
	$("#pointsLoadedData").html(pointsLoaded);
};

//Runs when the trail value changes to update the trails.
Users.prototype.updateTrails = function(){
	pointsShowing = 0;
	$.each(this.userArray, function(index, value){
		value.drawTrail();
		$("#pointsShowingData").html(pointsShowing);
	});
}

//Finds a user and pans to their most current point
Users.prototype.panToPerson = function(input){
	$.each(this.userArray, function(index, value){
		if(value.userID === input){
			value.panToPerson();
			return	
		}
	})
};

//Finds the user in the array and joins a team
Users.prototype.joinTeam = function(input, teamID){
	$.each(this.userArray, function(index, value){
		if(value.userID === input){
			value.joinTeam(teamID);
			return	
		}
	})
};

//Run when no users are on the input array
Users.prototype.destroyArray = function(){
	$.each(this.userArray, function(index, value){
		value.destroy();
	});
	$("#searcherlist").html("");
	this.userArray = [];
}



/*******************************************Person Object****************************************
 *This is the object constructor for a person, you need a userID and an array of points to create
 ***********************************************************************************************/
function Person(input){
	this.userID = input.userID;
	this.pointArray = [];
	this.showTrail = true;
	this.trailArray = [];
	this.trailLength = 0;
	
	//Set up the colors, default is yellow background and black font
	this.userColor = "#FFF";
	this.fontColor = "#000";
	this.teamID = input.teamID;
	
	if(this.teamID){
		var colors = getTeamColors(this.teamID);
		this.userColor = colors.backgroundColor;
		this.fontColor = colors.fontColor;	
	}
	if(input.userID == null)
        {
            input.userID = "00";
        }
	this.styleOptions = new StyledIcon(StyledIconTypes.MARKER,{color:this.userColor,text:input.userID});
	this.currentMarker = new StyledMarker({styleIcon:this.styleOptions,map:map});
	
	
	
	
	this.trail = new google.maps.Polyline({
		map: null,
		geodesic: true,
		strokeColor: this.userColor,
		strokeOpacity: 1.0,
		strokeWeight: 2
	});
	
	//This is set if the point is stale and showing an old point
	this.stale = "";
	
	var thisPointer = this;
	$.each(input.points, function(index, value){
		thisPointer.addPoint(value);
	});
	this.username = input.userData.username;
	this.fname = input.userData.fname;
	this.lname = input.userData.lname;
};

//Plots the points on the map depending on the showTrail value
Person.prototype.plotPoints = function(){
	
	var userCaption = "";
	
	//Move the marker for the most recent position
	if(this.pointArray.length > 1){
		var rightNow = new Date();
		var newerThan = Math.round(trackHistoryStart.getTime()/ 1000);

		//Draw/Move the first point regardless of how old it is
		var tempPos = new google.maps.LatLng(this.pointArray[0].lat, this.pointArray[0].lng);
		this.currentMarker.setPosition(tempPos);
		this.currentMarker.setMap(map);
		
		//Show the trail 
		if(this.showTrail){
			this.drawTrail();
		}
		
		if(this.trailLength){
			userCaption = this.trailLength + "m";	
		}else{
			var tempDate = new Date(this.pointArray[0].dateCreated * 1000);
			userCaption = "Updated: " + monthName(tempDate.getMonth()) + "-" + tempDate.getDate() + " " + leadingZero(tempDate.getHours()) + ":" + leadingZero(tempDate.getMinutes());	
		}
		
		
	}else if(this.pointArray.length === 1){
		var tempPos = new google.maps.LatLng(this.pointArray[0].lat, this.pointArray[0].lng);
		this.currentMarker.setPosition(tempPos);
		this.currentMarker.setMap(map);
		var tempDate = new Date(this.pointArray[0].dateCreated * 1000);
		userCaption = "Updated: " + monthName(tempDate.getMonth()) + "-" + tempDate.getDate() + " " + leadingZero(tempDate.getHours()) + ":" + leadingZero(tempDate.getMinutes());
		
		//Now color the point base on if it is active or not (within the time given)
		/*if(this.pointArray[0].dateCreated >= newerThan){	
			this.currentMarker.setProperty({name: "color", value: "#99FF66"});
		}else{
			//Needs to draw point a different color if stale.	
			this.currentMarker.setProperty({name: "color", value: "#99FF55"});
		}*/
		
	};
	$("#userTrail" + this.userID).html(userCaption);
	return true;
};


//Draw the trail, based on the input time
Person.prototype.drawTrail = function(){
	this.trail.setMap(null);
	this.trailArray = [];
	var rightNow = new Date();
	var newerThan = Math.round(trackHistoryStart.getTime() / 1000);
	var tempTrailArray = [];
	pointsLoaded = pointsLoaded + this.pointArray.length;
	$.each(this.pointArray, function(index, value){
		if(value.dateCreated >= newerThan ){ 
			var tempPoint = new google.maps.LatLng(value.lat, value.lng);
			tempTrailArray.push(tempPoint);
		}
	});
	this.trailArray = tempTrailArray;
	pointsShowing = pointsShowing + this.trailArray.length
	this.trailLength = this.getTrailLength();
	this.trail.setPath(this.trailArray);
	this.trail.setMap(map);
}

//Takes the active trail within the trailArray and outputs the length in meters
Person.prototype.getTrailLength = function(){
	var tempLength = 0;
	if (this.trailArray.length > 1){
		for (var i = 1; i < this.trailArray.length; i++){
			tempLength = tempLength + findDistance(this.trailArray[i - 1], this.trailArray[i]);
			//var resultMiles = Math.round(tempLength * 1000 / 1609) / 1000;
		}
	}
	return tempLength;
}

Person.prototype.panToPerson = function(){
	map.panTo(this.currentMarker.getPosition());
}

//Adds a point to the array if it doesn't already exist
Person.prototype.addPoint = function(point){
	if(!this.checkPoints(point)){
		this.pointArray.push(point);
		return true;
	}
	return false;
};

//Checks the new point to make sure it doesn't already exist in the array
Person.prototype.checkPoints = function(point){
	var found = false;
	$.each(this.pointArray, function(index, value){
		if(value.pointID === point.pointID){
			found = true;
			return;
		};
	});
	return found;
}

//Because the points can be loaded in any order, they need to be sorted after loading to ensure they are in order
Person.prototype.sortPoints = function(){
	this.pointArray.sort(function(a, b){return b.dateCreated - a.dateCreated;});
}

//Sets the value of the person's team
Person.prototype.joinTeam = function(teamID){
	this.teamID = teamID;	
	$.ajax({
        type: "POST",
        url: "messageSend.php",
        data: "joinTeam=" + teamID + "&userID=" + this.userID,
		success: function(reply){
			$("#info").html(reply);
		}
	});
	var colors = getTeamColors(teamID);
	this.changeColor(colors.backgroundColor, colors.fontColor);
}

//Removes a person from a team
Person.prototype.leaveTeam = function(){	
	this.teamID = null;
	$.ajax({
        type: "POST",
        url: "messageSend.php",
        data: "leaveTeam=" + teamID + "&userID=" + this.userID,
		success: function(reply){
			$("#info").html(reply);
		}
	});
	this.changeColor("#FFF", "#000");
}

//Changes the color of the user's marker, line, and control box
Person.prototype.changeColor = function(newColor, fontColor){
	newColor = typeof newColor !== 'undefined' ? newColor : "#FFF";     //Default backgrond color is white
	fontColor = typeof fontColor !== 'undefined' ? fontColor : "#000";  //Default font color is black
	this.userColor = newColor;
	this.fontColor = fontColor;
	this.trail.setOptions({strokeColor: newColor});
	this.styleOptions.set("color", newColor);
	$("#user" + this.userID).css("background-color", newColor).css("color", fontColor);
}

//Removes the person's points and data from the map
Person.prototype.destroy = function(){
	this.currentMarker.setMap(null);
	this.trail.setMap(null);
};


/****************************************************Weather Methods************************/
function updateWeather(weather_div, radar_box)
{
	var mapCenterPoint = map.getCenter();
	//get weather data
	$.ajax({
	  url : "https://api.wunderground.com/api/ff3e23e766c6adcf/geolookup/conditions/q/"+mapCenterPoint.lat()+","+mapCenterPoint.lng()+".json",
	  dataType : "jsonp",
	  success : function(parsed_json) {
		  if(!parsed_json.response.error){
			  var location = parsed_json['location']['city'];
			  var temp_f = parsed_json['current_observation']['temp_f'];
			  var txt=document.getElementById(weather_div);
			  txt.innerHTML="<br>Weather Box<br> Weather for "+parsed_json['location']['city']+", "+parsed_json['location']['state']+
			  "<br> Temperature: "+parsed_json['current_observation']['temp_f']+" &#176;F&nbsp&nbsp&nbsp Humidity: "+parsed_json['current_observation']['relative_humidity']+
			  "<br> "+parsed_json['current_observation']['precip_today_string']+"<br>Wind Speed: "+parsed_json['current_observation']['wind_mph'] +" mph &nbsp&nbsp&nbsp Wind Direction: "+parsed_json['current_observation']['wind_dir'] +" ("+parsed_json['current_observation']['wind_degrees'] +"&#176;)"+
			  "<br> "+parsed_json['current_observation']['observation_time'];
			  update_compass_arrow(weather_div,parsed_json['current_observation']['wind_degrees']);
			  //get radar should put this in it's own div
			  var radarbx = document.getElementById(radar_box)
			  radarbx.innerHTML = '<img src="getRadarImg.php?centerlat='+mapCenterPoint.lat()+'&centerlon='+mapCenterPoint.lng()+'"/>';
		  }
		  }
	  });


}
function recieveimage()
{
	console.log("rec test");
}
function update_compass_arrow(weather_div, inWindDir)
{
	var txt = document.getElementById(weather_div);
	txt.innerHTML+="<br><img id=\"compass_arrow\" src=\"images/compass_arrow.png\"></img>"
	$("#compass_arrow").rotate(inWindDir+180);				
}

/****************************************Search and Team Management**********************************/

//This function runs synchronous AJAX call (not async) in order to ensure the new search is created
var saveNewSearch = function(){
	$("#newsearchinfo").html("Saving...");
	
	var newSearchNumber = 0;
	
	var searchStart = 1;
	
	var newSearchData = {
		userID: userID,
		searchName: $("#newsearchname").val(),
		searchStart: searchStart,
		searchNotes: $("#newsearchnotes").val()
	}
	
	$.ajax({
        type: "POST",
        url: "messageSend.php",
        data: {newSearchData: newSearchData},
		dataType: "json",
		async: false,
        success: function(e){ 
			$("#newsearchinfo").html("New search creaed with ID: " + e.searchID);
			
		}
	});
	updateSearches();
	$("#currentSearchNumber").val(newSearchNumber);
	getNewPoints();
}
var saveNewTeam = function(){
	
	var t = $("#newteamcolor").val()
	var v = $("#newteamfontcolor").val();
	
    var newTeamData = {
		teamName: $("#newteamname").val(),
		teamLeader: $("#teamleader").val(),
		teamNotes: $("#newteamnotes").val(),
		backgroundColor: t, 
		fontColor: v
	}
	
	$.ajax({ 
        type: "POST",
        url: "messageSend.php",
        data: {newTeamData: newTeamData},
		dataType: "json",
		async: false,
        success: function(e){ 
        	$("#newteaminfo").html("New team created with ID: " + e.teamID);
			currentTeam = e.teamID;
			updateTeams();
			users.drawUserButtons();
		}
	});
    
    
}

//Returns the colors for a team
var getTeamColors = function(teamID){
	var colors = {backgroundColor: "#FFF", fontColor: "#000"};
	$.each(teamArray, function(index, value){
		if(value.teamID == teamID){
			colors.backgroundColor = value.backgroundColor;
			colors.fontColor = value.fontColor;
		}
	})
	return colors;
}


var deleteSearch = function(){
	if(window.confirm("Are you sure you want to delete the \"" + $("#currentSearchNumber option[value='" + currentSearch + "']").text() + "\"?")){
		$.ajax({
			type: "POST",
			url: "messageSend.php",
			data: "deleteSearch=" + currentSearch,
			dataType: "json",
			async: false,
			success: function(e){ 
				$("#info").html(e);
				currentSearch = 1;
				updateSearches();
			}
		});
	}
}
var deleteTeam = function(){
	if(window.confirm("Are you sure you want to delete the \"" + $("#currentTeamNumber option[value='" + currentTeam + "']").text() + "\"?")){
		//Remove all current members from the team on the UI
		$.each(users.userArray, function(index, value){
			if(value.teamID == currentTeam){
				value.teamID = null;
				value.changeColor("#FFF", "#000");
				$("#teamDrop" + value.userID).val(0);
			}
		});
		$.ajax({
			type: "POST",
			url: "messageSend.php",
			data: "deleteTeam=" + currentTeam,
			dataType: "json",
			async: false,
			success: function(e){ 
				$("#info").html(e);
				currentTeam = "all";
				updateTeams();
				users.drawUserButtons();
			}
		});
	}
}
var messageSendHandler = function(msg){
	if(msg){
		$("#floatNote").html("Message Sent");	
	}else{
		$("#floatNote").html("Message not sent: " + JSON.stringify(msg));	
	}
}

var messageGetHandler = function(msg){
	var rightNow = new Date();
	$("#messageoutput").html("<tr style='color: #0FF; border-bottom: 1px #0FF solid;'><th>New</th><th>From</th><th>To</th><th>Subject</th><th>Date Sent</th><th>Message</th></tr>");
	//$("#testoutput").html("<br/><br/>Raw message data: <br/><br/>" + JSON.stringify(msg));
	$.each(msg, function(index, value){
		
		//First check to see if the message has been read
		if(value.status === "1"){
			ifNew = "";
		}else{
			ifNew = "<span style='color: #F00'>!</span>";
		}
		var theDate = new Date(value.dateSent * 1000);
		var tempMessage = value.message.substring(0, 20) + "...";
		
		$("#messageoutput").append("<tr id='" + value.messageID + "' class='onemessage'><td class='msg1' id='newmessage" + value.messageID + "'>" + ifNew + "</td><td class='msg2'>" + value.sentuser[0].username + "</td><td class='msg3'>" + value.touser[0].username + "</td><td class='msg4'>" + value.subject + "</td><td class='msg5'>" + theDate.toDateString() + " " + leadingZero(theDate.getHours()) + ":" + leadingZero(theDate.getMinutes()) + "</td><td><span id='shortmessagebodyid" + value.messageID + "'>" + tempMessage + "</span><span id='fullmessagebodyid" + value.messageID + "' style='display: none'>" + value.message + "</span></td></tr>");
	});
	
	$(".onemessage").on("click", function(){
		var id = $(this).prop("id");
		$("#shortmessagebodyid" + id).toggle();
		$("#fullmessagebodyid" + id).toggle();
		$("#newmessage" + id).html("");
		markAsRead(id);
	});
	
}

var checkMessageHandler = function(result){
	if(result !== "0"){
		$("#info").html("<span style='color: #F00'>You have new messages");
		getMessage(userID);
	}else{
		$("#info").html("");	
	}
}


//Searches using the Google search function
function searchNow(){
	var inputstring = "San Diego";
	if ($("#searchbox").val() != ""){
		inputstring = $("#searchbox").val();
	}
	searched = true;
	var geocoderequest = new google.maps.Geocoder();
	var geocoderesult;
	var geocodestatus;
	geocoderequest.geocode({address: inputstring}, function(geocoderesult, geocodestatus){
		if (geocodestatus == "OK"){
			$.each(geocoderesult, function(index, value){
				thisx = this;
				if(index == 0){
					map.fitBounds(value.geometry.viewport);
				}
				
			})
		}
		else if (geocodestatus == "ZERO_RESULTS"){
			
		}
		else
		{
			$("#info").html("Error, try again later");
		}
	});
};

var randomColor = function(){
	return '#' + (function co(lor){   return (lor += [0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f'][Math.floor(Math.random()*16)]) && (lor.length == 6) ?  lor : co(lor); })('');	}

//Sets the UI date, takes a Date object
function setUITime(input){
	input.setTime(input.getTime() + input.getTimezoneOffset());
	var dateString = (input.getMonth() + 1) + "-" + input.getDate() + "-" + input.getFullYear();
	$("#trackDate").datepicker('setDate', dateString);
	var timeString = leadingZero(input.getHours()) + ":" + leadingZero(input.getMinutes());
	$("#trackTime").val(timeString);
}

//Returns a Date Object of the time in the UI
function getUITime(){
	var tempTime = $("#trackTime").val().split(":");
	var tempDate = $("#trackDate").datepicker("getDate").getTime();

	var returnTime = new Date();
	returnTime.setTime(tempDate + (tempTime[0] * 3600 * 1000) + (tempTime[1] * 60 * 1000));
	return returnTime;
}

