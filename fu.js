var updateInterval = 5000;
var timer = 0;

function positionCallback(posi)
{
	$("#lat").val(posi.coords.latitude);
	$("#lng").val(posi.coords.longitude);
	var tempLoc = new google.maps.LatLng(posi.coords.latitude, posi.coords.longitude);
	marker.setPosition(tempLoc);
	map.panTo(tempLoc);
}
navigator.geolocation.getCurrentPosition (function (pos)
{
  curPosition = pos;
  var lat = pos.coords.latitude;
  var lng = pos.coords.longitude;
  $("#lat").val (lat);
  $("#lng").val (lng);
});

//Runs on load
function initialize(){
	$("#updateInt").val(updateInterval);
	sendPosition();
	
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
	
    var timer = setTimeout(function(){sendPosition()}, updateInterval);
	
	marker = new google.maps.Marker({
		position: latlng,
		map: map,
		title: "SAR Map"
	});
    
    return true;
}

google.maps.event.addDomListener(window, "load", initialize);

function sendPosition(){
	
	if(timer){
		window.clearTimeout(timer);
	}
	var result = false;
	
	updateInterval = $("#updateInt").val();
	$("#updateMessage").html("Updating every " + (updateInterval / 1000) + " seconds");
	timer = setTimeout(function(){sendPosition()}, updateInterval);
	
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
			
			$("#info").html("Sending location...");
			
			$.ajax({
				type: "POST",
				url: "messageReceive.php",
				data: {dataMsg:forwardMsg},
				success: function(msg){
					if(msg){
						$("#info").html(msg);
					}else{
						$("#info").html(msg);
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
}

function getMessage(){
    msg = "";
    return msg;
}
	
/*$( document ).on( "pageinit", "#geoMap", function() {
    // Default to Hollywood, CA when no geolocation support
	

    if ( navigator.geolocation ) 
    {
		function success(pos)
        {
            // Location found, show map with these coordinates
            drawMap(new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude));
		}

		function fail(error) 
        {
            drawMap(defaultLatLng);  // Failed to find location, show default map
		}

		// Find the users current position.  Cache the location for 5 minutes, timeout after 6 seconds
		navigator.geolocation.getCurrentPosition(success, fail, {maximumAge: 500000, enableHighAccuracy:true, timeout: 6000});
    } 
    else 
    {
		drawMap(defaultLatLng);  // No geolocation support, show default map
    }
});*/

/*
$( document ).on( "pageinit", "#geoMap", function() {
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
});
*/

