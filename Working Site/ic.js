var map;
//Troubleshooting variable
var objectCount = 0;
var pointsShowing = 0;

var timer = 0;
var cookieDuration = 24 * 30;	//30 days

var startLat = 36.53170884914869;  //America
var startLng = 127.869873046875;

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
var trackHistoryLength = (3600 * 24)  //Default is one day
if(readCookie("sar.trackLength")){
	trackHistoryLength = readCookie("sar.trackLength");
};
var updateTrackLength = function(){
	trackHistoryLength = $("#updateTrackLength").val();
	writeCookie("sar.trackLength", trackHistoryLength, cookieDuration);
	pointsShowing = 0;
	users.updateTrails();
};

//Global user singleton (thanks for that team Shane)
var users = new Users;

//Object of Users
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
	
	//First figure out if a user wasn't in the incoming array and remove them if they are not
	$.each(userPointer.userArray, function(index, value){
		found = false
		indexPasser = false;
		$.each(inputUsers, function(index2, value2){
			if(value.userID === value2.userID){
				found = true;
				indexPasser = index2;
			};
		});
		
		if(!found){
			//If the user isn't found on the input, remove them from the map
			value.destroy();
			userPointer.userArray.splice(indexPasser, 1);
		}
	});
	
	
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

//Plots the current user array
Users.prototype.plotPoints = function(){
	$.each(this.userArray, function(index, value){
		value.plotPoints();
	});
};

//Runs when the trail value changes to update the trails.
Users.prototype.updateTrails = function(){
	$.each(this.userArray, function(index, value){
		value.drawTrail();
	});
}



//First to run
var initialize = function(){
	
	//Sets options for the map with vars above
	var myOptions = {zoom: mapZoom, center: mapHome};
	
	//Creates the map
	map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
	
	//Add a listener that only updates the weather when the map moves
	google.maps.event.addListener(map, 'idle', function() {updateWeather('weather_box','radar_box');} );
	
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
	
	
	//Can change these to cookie values to remember the last used data
	
	//Updates the timer value with the default
	$("#updateInt").val(updateInterval);

	//Sets the update value to the variable listed above
	$("#updateTrackLength").val(trackHistoryLength);

	//Start the timer to get new points
	getNewPoints();
	
};



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
	requestData = {team: "1", theTime: trackHistoryLength}
	
	//Start the AJAX call
	$("#floatNote").html("Sending...");
	$.ajax({
        type: "POST",
        url: "messageSend.php",
        data: { update_ic_req:requestData },
		dataType: "json",
        success: function(msg){ 
			var connectionNow = new Date();
			$("#floatNote").html("Connected to server for " + (Math.round((connectionNow.getTime() - connectionStart.getTime())/1000)) + "s | Points Loaded: " + objectCount + " | Points plotted: " + pointsShowing);        
			//$("#info").html(msg);
			
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
			
			//Send the msg object to the user singleton to update or create points.
			users.checkUsers(msg);
			pointsShowing = 0;
			users.plotPoints();	
		}
	});
};

//This function updates the team numbers
var updateTeamNumbers = function(){
	//AJAX call to pull list of teams from the database and update drop down, update on 10 second intervals
	
};



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


function updateWeather(weather_div, radar_box)
{
	var mapCenterPoint = map.getCenter();
	//get weather data
	$.ajax({
	  url : "https://api.wunderground.com/api/ff3e23e766c6adcf/geolookup/conditions/q/"+mapCenterPoint.lat()+","+mapCenterPoint.lng()+".json",
	  dataType : "jsonp",
	  success : function(parsed_json) {
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


//LatLng object inheritor for pointID and pointDate
google.maps.LatLng.prototype.PointData = function(pointID, title, pointDate){
	this.pointID = pointID;
	this.pointDate = pointDate;
	
	//Default values
	this.plotted = false;
}

//This is the object constructor for a person, you need a userID and an array of points to create
function Person(input){
	this.userID = input.userID;
	this.styleOptions = new StyledIcon(StyledIconTypes.MARKER,{color:"#99FF66",text:input.userID});
	this.currentMarker = new StyledMarker({styleIcon:this.styleOptions,map:map});
	this.pointArray = [];
	this.showTrail = true;
	this.trailArray = [];
	this.trail = new google.maps.Polyline({
		map: null,
		geodesic: true,
		strokeColor: '#FF0000',
		strokeOpacity: 1.0,
		strokeWeight: 2
	});
	
	var thisPointer = this;
	$.each(input.points, function(index, value){
		thisPointer.addPoint(value);
	});
	this.userName = input.userData.username;
	this.fname = input.userData.fname;
	this.lname = input.userData.lname;
};

//Plots the points on the map depending on the showTrail value
Person.prototype.plotPoints = function(){
	
	//Move the marker for the most recent position
	if(this.pointArray.length > 1){
		var rightNow = new Date();
		var newerThan = Math.round((rightNow.getTime()/1000)- trackHistoryLength);

		//Draw/Move the first point regardless of how old it is
		var tempPos = new google.maps.LatLng(this.pointArray[0].lat, this.pointArray[0].lng);
		this.currentMarker.setPosition(tempPos);
		this.currentMarker.setMap(map);
		
		//Show the trail 
		if(this.showTrail){
			this.drawTrail();
		}
		
	}else if(this.pointArray.length === 1){
		var tempPos = new google.maps.LatLng(this.pointArray[0].lat, this.pointArray[0].lng);
		this.currentMarker.setPosition(tempPos);
		this.currentMarker.setMap(map);
		
		//Now color the point base on if it is active or not (within the time given)
		/*if(this.pointArray[0].dateCreated >= newerThan){	
			this.currentMarker.setProperty({name: "color", value: "#99FF66"});
		}else{
			//Needs to draw point a different color if stale.	
			this.currentMarker.setProperty({name: "color", value: "#99FF55"});
		}*/
		
	};
	return true;
};

//Draw the trail, based on the input time
Person.prototype.drawTrail = function(){
	this.trail.setMap(null);
	this.trailArray = [];
	var rightNow = new Date();
	var newerThan = Math.round((rightNow.getTime()/1000)- trackHistoryLength);
	var tempTrailArray = [];
	$.each(this.pointArray, function(index, value){
		if(value.dateCreated >= newerThan ){ 
			var tempPoint = new google.maps.LatLng(value.lat, value.lng);
			tempTrailArray.push(tempPoint);
			pointsShowing++;
		}
	});
	this.trailArray = tempTrailArray;
	this.trail.setPath(this.trailArray);
	this.trail.setMap(map);
}

//Adds a point to the array if it doesn't already exist
Person.prototype.addPoint = function(point){
	if(!this.checkPoints(point)){
		objectCount++;
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

/*Cookie code*/

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
