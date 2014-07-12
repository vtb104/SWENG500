navigator.geolocation.getCurrentPosition (function (pos)
{
  var lat = pos.coords.latitude;
  var lng = pos.coords.longitude;
  $("#lat").val (lat);
  $("#lng").val (lng);
});

//Runs on load
function initialize(){
    var timer = setInterval(function(){sendPosition()}, 5000);
    
    return true;
}

function sendPosition(){
    if ( navigator.geolocation ) 
    {
	var sendMsg = new Object();
	sendMsg.user = $_SESSION['userid'];
	sendMsg.lat = $("#lat").val();
	sendMsg.lng = $("#lng").val();
	
	var formattedMsg = JSON.stringify(sendMsg);
	
	$("#info").html("Sending location...");
	$.ajax({
		type: "POST",
		url: "messageReceive.php",
		data: {dataMsg:formattedMsg},
		success: function(msg){
			if(msg){
				$("#info").html($_SESSION['userid']);
			}else{
				$("#info").html($_SESSION['userid']);
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

$( document ).on( "pageinit", "#geoMap", function() {
    var defaultLatLng = new google.maps.LatLng(34.0983425, -118.3267434);  // Default to Hollywood, CA when no geolocation support

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

