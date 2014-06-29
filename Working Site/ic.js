var startLat = 36.53170884914869;
var startLng = 127.869873046875;
var map;
var mapZoom = 4;
var mapHome = new google.maps.LatLng(39.57, -99.10);
var markerPosition = new google.maps.LatLng(0,0);
//TEMP vars to be deleted later -shane
var scriptLat = 39.57;
var scriptLng = -99.10;
var scriptLocationHolderArray = new Array();
var scriptMarker;
var scriptPolyLine;
var doOnce = true;
var delayCnt = 0;
var updateInterval = 5000;
var timer = 0;

//First to run
var initialize = function(){
	
	//Sets options for the map with vars above
	var myOptions = {zoom: mapZoom, center: mapHome};
	
	//Creates the map
	map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
	
	//Add a listener that only updates the weather when the map moves
	google.maps.event.addListener(map, 'idle', function() {updateWeather('weather_box','radar_box');} );
	
	//Draw an initial weather box
	updateWeather('weather_box','radar_box');
	
	//Creates the marker for the input data
	//var markerOptions = {map: map,  position: markerPosition};
	//positionMarker = new google.maps.Marker(markerOptions);
	
	//Updates the timer value with the default
	$("#updateInt").val(updateInterval);
	
	//This is the timer that runs the getNewPoint function
	$("#floatNote").html("Connecting...");
	connectionStart = new Date();
	getNewPoints();
};



//This is the function that runs every 5s from the timer
var getNewPoints = function(){
	
	if(timer){
		window.clearTimeout(timer);
	}
	updateInterval = $("#updateInt").val();
	timer = setTimeout(function(){getNewPoints()}, updateInterval);
	requestData = "1";
	$("#floatNote").html("Sending...");
	$.ajax({
        type: "POST",
        url: "messageSend.php",
        data: { update_ic_req:requestData },
		dataType: "json",
        success: function(msg){ 
			var connectionNow = new Date();
			$("#floatNote").html("Connected to server for " + (Math.round((connectionNow.getTime() - connectionStart.getTime())/1000)) + "s");        
			$("#info").html("");
			for(var user_num = 0; user_num < msg.length; user_num++)
			{
				//Check for data
				if(msg[user_num])
				{
					
					//console.log(">>>>"+msg[user_num]);
					//var markerOptions = {map: map,  position: markerPosition};
					//positionMarker = new google.maps.Marker(markerOptions);
					var tempPos = new google.maps.LatLng(msg[user_num][2], msg[user_num][3]);
					//positionMarker.setPosition(tempPos);
					
					
					//var styleMaker1 = new StyledMarker({styleIcon:new StyledIcon(StyledIconTypes.MARKER,{color:"#99FF66",text:msg[user_num][1]}),position:tempPos,map:map});
					//styleMaker1.setMap(map);
	
				
					$("#info").html($("#info").html()+"<br />Marker showing position of mobile unit("+msg[user_num][1]+").  Location is Lat: \t" + msg[user_num][2] + " Lng: \t" + msg[user_num][3]); 
				
				}
			}
			
			//temp call delete or move later -shane
			
			updateUserTrack(3);
                        //update all tracks for the users
                        for(var userCnt = 0; userCnt < userList.length; userCnt++)
                        {
                            updateUserTrack(parseInt(userList[userCnt]));                            
                        }
         }
     })
};
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
    //alert(tempPos);
	/*tempPos = new google.maps.LatLng(scriptLat, scriptLng);
	scriptLocationHolderArray.push(tempPos);
	if(doOnce)
	{
		scriptMarker = new StyledMarker({styleIcon:new StyledIcon(StyledIconTypes.MARKER,{color:"#99FF66",text:"8"}),position:tempPos,map:map});
		doOnce = false;
		
	}
	scriptPolyLine = new google.maps.Polyline({
    path: scriptLocationHolderArray,
    geodesic: true,
    strokeColor: '#FF0000',
    strokeOpacity: 1.0,
    strokeWeight: 2
	});

	scriptPolyLine.setMap(map);
	scriptMarker.setPosition(tempPos);
	//this is temp to mimic change in gps loc
	var tempRand = Math.floor((Math.random() * 2) + 1);
	if(tempRand == 1)
	{
		scriptLat += Math.random();
	}
	else
	{
		scriptLat -= Math.random();
	}
	tempRand = Math.floor((Math.random() * 2) + 1);
	if(tempRand == 1)
	{
		scriptLng += Math.random();
	}
	else
	{
		scriptLng -= Math.random();
	}*/
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

$(function(){
	$("#searchnow").click(function(){
		searchNow();
	});

});
