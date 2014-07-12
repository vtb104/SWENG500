// Declaration of global vars
var updateLocationInterval = 5000;
var locTimer = 0;
var msgTimer = 0;
var firstLoop = true;
var currentLoc = new google.maps.LatLng(0, 0);

// decompose the position values into items for display
// set the new marker
// pan to the new marker if this is the first time this function is called
function positionCallback(position)
{
	$("#lat").val(position.coords.latitude);
	$("#lng").val(position.coords.longitude);
	currentLoc = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
	marker.setPosition(currentLoc);
	
	if (firstLoop)
	{
		firstLoop = false;
		map.panTo(currentLoc);
	}
}

// pan the map to the current marker location
function panToCurrentLocation()
{
	map.panTo(currentLoc);
}

// get the current location
navigator.geolocation.getCurrentPosition (function (pos)
{
  curPosition = pos;
  var lat = pos.coords.latitude;
  var lng = pos.coords.longitude;
  $("#lat").val (lat);
  $("#lng").val (lng);
});

// Runs on load
function initialize(){
	$("#updateLocInt").val(updateLocationInterval);
	sendPosition();
	getMessage();
	
	var lat = $("#lat").val ();
    var lng = $("#lng").val ();
    var latlng = new google.maps.LatLng (lat, lng);
    
    //Sets options for the map with vars above
    var myOptions = {
        zoom: 15, 
        center: latlng,
        mapTypeControl: true,
		mapTypeId : google.maps.MapTypeId.TERRAIN
    };
	
    //Creates the map
    map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
	
    var markerOptions = {
        map: null,  
        position: latlng
    };

    var positionMarker = new google.maps.Marker(markerOptions);
    positionMarker.setMap(map);
	
    var locTimer = setTimeout(function(){sendPosition()}, updateLocationInterval);
	
	marker = new google.maps.Marker({
		position: latlng,
		map: map,
		title: "SAR Map"
	});
	
    return true;
}

// Update locTimer information
// if geo service is available, send the current location to the server
function sendPosition()
{
	var result = false;
	
	if(locTimer)
	{
		window.clearTimeout(locTimer);
	}
	
	updateLocationInterval = $("#updateLocInt").val();
	$("#updateLocInfo").html("Updating every " + (updateLocationInterval / 1000) + " seconds");
	locTimer = setTimeout(function(){sendPosition()}, updateLocationInterval);

    if ( navigator.geolocation ) 
    {
		var geoOptions = {
		  enableHighAccuracy: true,
		  timeout: 2000,
		  maximumAge: 0
		};
		
		navigator.geolocation.getCurrentPosition(success, fail, geoOptions);

		function success(pos)
		{
			// Location found, update the coords, move the map and the marker.
			positionCallback(pos);
			var sendMsg = new Object();
			sendMsg.user = ""+userID;
			sendMsg.lat = $("#lat").val();
			sendMsg.lng = $("#lng").val();
	
			var forwardMsg = JSON.stringify(sendMsg);
			
			$("#infoLoc").html("Sending location...");
			
			$.ajax({
				type: "POST",
				url: "messageReceive.php",
				data: {dataMsg:forwardMsg},
				success: function(msg){
					if(msg){
						$("#infoLoc").html(msg);
					}else{
						$("#infoLoc").html(msg);
					}
				}
			});
			result = true;
		}

		function fail(error) 
		{
			// Failed to find location, do nothing
		}
    }
    else
    {
        $("#info").html("Location not allowed for this application.  Please allow location sharing to enable this feature.");
    }
	return result;
}

function sendMessage(msg){
    if (msg)
    {
        return true;
    }
    else
    {
        return false;
    }
	
	// use the sendposition as an example of object and ajax call methodology once the server side is setup.
}

function getMessage(){
	updateCheckMsgInterval = $("#checkMsgInt").val();
	
	if(msgTimer)
	{
		window.clearTimeout(msgTimer);
	}
	
	updateCheckMsgInterval = $("#checkMsgInt").val();
	$("#updateMsgInfo").html("Updating every " + (updateCheckMsgInterval / 1000) + " seconds");
	msgTimer = setTimeout(function(){getMessage()}, updateCheckMsgInterval);
	
    msg = "";
	
	// use the sendposition as an example of object and ajax call methodology once the server side is setup.
    return msg;
}

