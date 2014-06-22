var updateInterval = 5000;
var timer = 0;

function positionCallback(posi)
{
	$("#lat").val(posi.coords.latitude);
	$("#lng").val(posi.coords.longitude);
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
    var timer = setTimeout(function(){sendPosition()}, updateInterval);
	var map = new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude);
    
    return true;
}

function sendPosition(){
	
	if(timer){
		window.clearTimeout(timer);
	}
	
	updateInterval = $("#updateInt").val();
	$("#updateMessage").html("Updating every " + (updateInterval / 1000) + " seconds");
	timer = setTimeout(function(){sendPosition()}, updateInterval);
	
    if ( navigator.geolocation ) 
    {
	    if ( navigator.geolocation ) 
		{
			navigator.geolocation.getCurrentPosition(positionCallback);

			function success(pos)
			{
				// Location found, move map to coordinates'
				map.panTo(pos);
				
			}

			function fail(error) 
			{
				map.panTo(defaultLatLng);  // Failed to find location, show default map
			}

			// Find the users current position.  Cache the location for 5 minutes, timeout after 6 seconds
			navigator.geolocation.getCurrentPosition(success, fail, {maximumAge: 500000, enableHighAccuracy:true, timeout: 6000});
		} 

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
        return true;
    }
    else
    {
        $("#info").html("Geo Location not possible");
        return false;
    }
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

function registerUser(msg){
    if (msg)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function unregisterUser(msg){
    if (msg)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function drawMap(latlng) 
{
	var myOptions = {
		zoom: 15,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};

	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

	// Add an overlay to the map of current lat/lng
	var marker = new google.maps.Marker({
		position: latlng,
		map: map,
		title: "SAR Map"
	});
}
	
$( document ).on( "pageinit", "#geoMap", function() {
    // Default to Hollywood, CA when no geolocation support
	var defaultLatLng = new google.maps.LatLng(34.0983425, -118.3267434);  

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
});

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

