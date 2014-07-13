var map;

//Points loaded into the arrays
var pointsLoaded = 0;

//Points showing based on the time requested to show from
var pointsShowing = 0;

var timer = 0;
var cookieDuration = 24 * 30;	//30 days

var usaCoord = new google.maps.LatLng(39.57, -99.10);
var startLat = 39.57;  //America
var startLng = -100;

//shane  add 7-6-2014
var poly;
var pointCount = 0;
var pointArray = [];
var areaArray = [];
var currentAreaPoly;
var areaData;
var polyStorage = [];
var areaNameData;

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

//Value and update fucntion for the current team selected
var currentTeamNumber = 1;
var updateTeamNumber = function(){
	currentTeamNumber = $("#currentTeamNumber").val();	
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
	$("#testTime").html(trackHistoryStart.toString());
	
	//Write the cookie
	writeCookie("sar.trackStart", trackHistoryStart.getTime(), cookieDuration);
	
	//Show the trails in relation to that value
	pointsShowing = 0;
	users.updateTrails();
};

var currentSearch = 1;
if(readCookie("sar.currentSearch")){
	currentSearch = readCookie("sar.currentSearch");
}

var updateCurrentSearch = function(){
	currentSearch = $("#currentSearchNumber").val();
	writeCookie("sar.currentSearch", currentSearch, cookieDuration);
	getNewPoints();
}

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
	google.maps.event.addListener(map, 'mousemove', function(event) {
		$("#cursorLocation").html("Lat: " + Math.round4(event.latLng.lat()) + " Lng: " + Math.round4(event.latLng.lng()))
	});
	
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
	
	//Start the timer to get new points
	getNewPoints();
        //this event listener will be used for area creation <<<< Start Shane Edit
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
    document.getElementById("PointsOfArea").innerHTML += '<input type="radio" name="point' + pointCount + '">Point '+pointCount+'<br>';
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
            var selectMenu = document.getElementById("AreaEditSelector");
            selectMenu.options.length = 0;
            selectMenu.options.add(new Option("Select Area", "Select Area"));
            for(var cnt=0; cnt< msg.length; cnt++)
            {
                selectMenu.options.add(new Option(msg[cnt].areaName, msg[cnt].areaName));
            }
        }});
}
function udpateAreaObjectMenu(areaName)
{
    //TODO: get points of area
    //update area name
    var areaName = document.getElementById("AreaName");
    var pointsDiv = document.getElementById("PointsOfArea");
    pointsDiv.innerHTML = "";
}
//this function is called when the user selects a different area
function updatePointList()
{
    //load area selected points
     $.ajax({
        type: "POST",
        url: "AreaHandler.php",
        data: {getAreaPoints:document.getElementById("AreaEditSelector").value},//change search ID for multiple searches
        dataType: "json",
        success: function(areaData){
            pointArray = [];
            areaNameData = areaData[0].areaName;
            document.getElementById("areaBoxContent").innerHTML = "";
            document.getElementById("areaBoxContent").innerHTML += 'Area Name: <b><label id="AreaName">'+areaData[0].areaName+' </b></label><input type="color" id="area_color" value="#00ff00"><br>';
            for(var cnt=0; cnt < areaData.length; cnt++)
            {
                document.getElementById("areaBoxContent").innerHTML += 'Point '+cnt+':Lat '+areaData[cnt].lat+' | Lng: '+areaData[cnt].lng+'<br>';
                pointArray.push(new google.maps.LatLng(areaData[cnt].lat,areaData[cnt].lng));
            }
            document.getElementById("areaBoxContent").innerHTML += '<div id="PointsOfArea"></div><button type="button" onclick="showAreaOnMap()">Show Area On Map</button><button type="button" onclick="removeAreaFromMap()">Remove Area From Map</button>';
            //fillPoly(pointsArray);
            //alert(JSON.stringify(areaData[0]));
        }});
}
//this funstion is called when "start new area button is clicked
function startNewArea()
{ 
    document.getElementById("areaBoxContent").innerHTML = 'Area Name: <input type="text" name="AreaName" id="AreaName" value="Enter an Area Name"><input type="color" id="area_color" value="#00ff00"><br><div id="tempMsg">Please click on the map to create area boundaries.</div><div id="PointsOfArea"></div><button type="button"  onclick="saveAreaButton()">Save</button>';
    //TODO check if listener exists already
    google.maps.event.addListener(map, 'click', addLatLng);
    currentAreaPoly = [];
}
//this function deletes the given area
function deleteArea()
{
     $.ajax({
        type: "POST",
        url: "AreaHandler.php",
        data: {deleteArea:document.getElementById("AreaEditSelector").value},
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
    fillPoly(areaNameData,pointArray);
}
function removeAreaFromMap()
{
    var areaIndex = checkArrayForName(polyStorage, areaNameData);
    if(areaIndex >= 0)
    {
        polyStorage[areaIndex].area.setMap(null);
        polyStorage.splice(areaIndex,1);
    }
}
//fills the poly and completes last border
function fillPoly(areaName, arrayOfPoints)
{
    //check if area exists on map
    if(checkArrayForName(polyStorage, areaName) == -1)
    {
        //create the "fill polygon"
        var polyObj = new Object();
        var areaFill = new google.maps.Polygon(
        {
            paths: arrayOfPoints,
            strokeColor: document.getElementById("area_color").value,
            strokeOpacity: 0.8,
            strokeWeight: 3,
            fillColor: document.getElementById("area_color").value,
            fillOpacity: 0.35
        });
        areaFill.setMap(map);
        polyObj.name = areaName;
        polyObj.area = areaFill;
        polyStorage.push(polyObj);
        document.getElementById("PointsOfArea").innerHTML += "Area is: "+google.maps.geometry.spherical.computeArea(areaFill.getPath())/4046.86+" acres";
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
    fillPoly(areaNameData,currentAreaPoly);

    google.maps.event.clearListeners(map, 'click');
    //send area to database
    areaData = new Object();
    areaData.name = document.getElementById("AreaName").value;
    areaData.userID = userID;
    areaData.points = currentAreaPoly;
    $.ajax({
        type: "POST",
        url: "AreaHandler.php",
        data: { createArea:JSON.stringify(areaData) },
		dataType: "json",
        success: function(msg){ }});
    //reset all data for next area
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
		$("#searcherlist").append('<div id="user' + value.userID + '" class="searcher" userID="' + value.userID + '" style="background-color:' + value.userColor +'"><span class="buttonNameStyle">' + value.userID +' ' + value.username + '</span> <span class="buttonInfoStyle" id="userTrail' + value.userID + '">Dist: -</span></div>');
		$(".searcher").on("click", function(e){
			users.panToPerson($(this).attr("userID"));
		});
		
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

Users.prototype.panToPerson = function(input){
	$.each(this.userArray, function(index, value){
		if(value.userID === input){
			value.panToPerson();
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
	this.userColor = randomColor();
	this.styleOptions = new StyledIcon(StyledIconTypes.MARKER,{color:this.userColor,text:input.userID});
	this.currentMarker = new StyledMarker({styleIcon:this.styleOptions,map:map});
	this.pointArray = [];
	this.showTrail = true;
	this.trailArray = [];
	this.trailLength = 0;
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
			userCaption = "Last update: " + monthName(tempDate.getMonth()) + "-" + tempDate.getDate() + " " + leadingZero(tempDate.getHours()) + ":" + leadingZero(tempDate.getMinutes());	
		}
		
		
	}else if(this.pointArray.length === 1){
		var tempPos = new google.maps.LatLng(this.pointArray[0].lat, this.pointArray[0].lng);
		this.currentMarker.setPosition(tempPos);
		this.currentMarker.setMap(map);
		var tempDate = new Date(this.pointArray[0].dateCreated * 1000);
		userCaption = "Last update: " + monthName(tempDate.getMonth()) + "-" + tempDate.getDate() + " " + leadingZero(tempDate.getHours()) + ":" + leadingZero(tempDate.getMinutes());
		
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
Person.prototype.setTeam = function(teamID){
	this.teamID = teamID;	
	return true;
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

/****************************************Search Management**********************************/

//This function grabs the searches that are in the database
var updateSearches = function(){
	$("#currentSearchNumber").html("");
	$.ajax({
        type: "POST",
        url: "messageSend.php",
        data: "updateSearches=true",
		dataType: "json",
        success: function(msg){ 
			$.each(msg, function(index, value){
				$("#currentSearchNumber").append("<option value='" + value.searchID + "'>" + value.searchName + "</option>");
			});
			$("#currentSearchNumber").val(currentSearch);
		}
	});
	$("#currentSearchNumber").append("<option value='all'>All Searches</option>");
}

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
			$("#newsearchinfo").html(e.searchID);
			
		}
	});
	updateSearches();
	$("#currentSearchNumber").val(newSearchNumber);
	getNewPoints();
}

/*********************************************Cookie code***********************************/
function writeCookie(name, value, hours)
{
  var expire = "";
  if(hours != null)
  {
    expire = new Date((new Date()).getTime() + hours * 3600000);
    expire = "; expires=" + expire.toGMTString();
  }
  document.cookie = name + "=" + escape(value) + expire;
}

function readCookie(name)
{
  var cookieValue = "";
  var search = name + "=";
  if(document.cookie.length > 0)
  { 
    offset = document.cookie.indexOf(search);
    if (offset != -1)
    { 
      offset += search.length;
      end = document.cookie.indexOf(";", offset);
      if (end == -1) end = document.cookie.length;
      cookieValue = unescape(document.cookie.substring(offset, end))
    }
  }
  return cookieValue;
}

/******************************************Other random functions***************************/
var convertSeconds = function(input){
	if(input < 60){
		return "1 min";
	}else if (input < 3600){
		return Math.round(input / 60) + " mins";
	}else if (input < 86400){
		return Math.round(input / 3600) + " hours";	
	}else if (input < 604800){
		return Math.round(input / 86400) + " days";
	}else{
		return Math.round(input / 604800) + " weeks";
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
	
	
	//Finds the distance between two points
function findDistance(startPos, endPos){
	var R = 6371000; // meters
	var dLat = (endPos.lat()-startPos.lat()) * Math.PI / 180;
	var dLon = (endPos.lng()-startPos.lng()) * Math.PI / 180;
	var lat1 = startPos.lat() * Math.PI / 180;
	var lat2 = endPos.lat() * Math.PI / 180;
	var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
			Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(lat1) * Math.cos(lat2); 
	var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
	d = R * c;
	return Math.round(d);
}


/*************************Math*******************************/
Math.round4 = function (input){return Math.round(input * 10000) / 10000;};
Math.round3 = function (input){return Math.round(input * 1000) / 1000;};
Math.round2 = function(input){return Math.round(input * 100) / 100;};
Math.round1 = function(input){return Math.round(input * 10) / 10;};
Math.degrees = function(rad){return rad*(180/Math.PI);}
Math.radians = function(deg){return deg * (Math.PI/180);}
function mToFeet(input){return input * 3.28084};

function leadingZero(input){
	if(input < 10){
		return "0" + input;
	}else{
		return input;
	}
}

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

function monthName(input){
	switch (input){
		case 0:
			return "Jan";
		case 1:
			return "Feb";
		case 2:
			return "Mar";
		case 3:
			return "Apr";
		case 4:
			return "May";
		case 5:
			return "Jun";
		case 6:
			return "Jul";
		case 7:
			return "Aug";
		case 8:
			return "Sep";
		case 9: 
			return "Oct";
		case 10:
			return "Nov";
		case 11:
			return "Dec";
		default:
			return "error";
	}	
}