// Declaration of global vars
var updateLocationInterval = 5000;
var updateCheckMsgInterval = 5000;
var updateSendLocationInterval = 60000;

var locTimer = 0;
var msgTimer = 0;
var sendLocTimer = 0;

var firstLoop = true;
var currentLoc = new google.maps.LatLng(0, 0);
var arrayGeoLocation = [];
var uploadingGeoLocation = false;

var currentSearch = 1;

if(readCookie("sar.currentSearchFU")){
	currentSearch = readCookie("sar.currentSearchFU");
}

var updateCurrentSearch = function(){
	currentSearch = $("#currentSearchNumber").val();
	writeCookie("sar.currentSearchFU", currentSearch, cookieDuration);
}

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
function initialize()
{
	$("#updateLocInt").val(updateLocationInterval);
	sendPosition();
	
	getMessage();
	
	$("#sendLocInt").val(updateSendLocationInterval);
	sendGeoLocations();
	
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
	
    locTimer = setTimeout(function(){sendPosition()}, updateLocationInterval);
	msgTimer = setTimeout(function(){getMessage()}, updateCheckMsgInterval);
	sendLocTimer = setTimeout(function(){sendGeoLocations()}, updateSendLocationInterval);
	
	marker = new google.maps.Marker({
		position: latlng,
		map: map,
		title: "SAR Map"
	});
	
	//Fills the searches selector
	updateSearches();
	
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

	// If the geo location points are being uploaded, then do not collect a new point
	if (!uploadingGeoLocation)
	{
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
				var dateObject = new Date();
				sendMsg.time = ""+dateObject.getTime();
				
				arrayGeoLocation.push(sendMsg);
				
				$("#infoLoc").html("msg created. timestamp: " + sendMsg.time);
				
				result = true;
			}

			function fail(error) 
			{
				// Failed to find location, do nothing
			}
            //Second call to check if there is a new area available from IC
            checkForNewAreaFromIC();
		}
		else
		{
			$("#info").html("Location not allowed for this application.  Please allow location sharing to enable this feature.");
		}
	}
	else
	{
		result = true;
	}
	return result;
}

var cnt=0;
function checkForNewAreaFromIC()
{
	var result = false;

    if(cnt < 150)
    {
        cnt++;
		$.ajax({
            type: "POST",
            url: "AreaHandler.php",
            data: {checkForArea:""+userID},
            success: function(msg)
			{
                //alert("msg leng"+msg.length);
                if(msg.length > 3)
                {
                    var pointArray = [];
                    var dataObj = JSON.parse(msg);
                    //create array of points
                    for(var cnt=0; cnt < dataObj.length; cnt++)
                    {
                        pointArray.push(new google.maps.LatLng(dataObj[cnt].lat,dataObj[cnt].lng));
                    }
                    fillPoly(msg.areaName, pointArray);
                }
                else
                {
                    //no areas for user
                    removeAllPolysFromMap();
                }
				
				result = true;
            }
		});
    }
	return result;
}

function sendMessage()
{
	var result = false;

	// get the message
	var messageData = new Object();
    messageData.msgTo = ""+currentSearch;
    messageData.msgFrom = ""+userID;
    messageData.msgSubject = "Message from FU id " + userID;
    messageData.msgUrgency = "Medium";
	var dateObject = new Date();
    messageData.msgDate = ""+dateObject.getTime();
    messageData.msgBody = $('textarea').val();

    if (messageData.msgBody)
    {
		if (currentSearch > 0)
		{
			//Start the AJAX call
			$.ajax({
				type: "POST",
				url: "messageSend.php",
				data: { fu_message_send:messageData },
				dataType: "json",
				success: function(msg){ 
					$('.msgContainer').append('<p>' + messageData.msgBody + '</p>');
					result = true;
				},
				error: function(msg){
					$('.msgContainer').append('<p>Message failed to send...</p>');
					result = false;
				}
			});
		}
		else
		{
			$('.msgContainer').append('<p>You must be part of a search to send a message</p>');
			result = false;
		}
	}
	
	return result;
}

function getMessage()
{
	if(msgTimer)
	{
		window.clearTimeout(msgTimer);
	}
	
	$("#updateMsgInfo").html("Updating every " + (updateCheckMsgInterval / 1000) + " seconds");
	msgTimer = setTimeout(function(){getMessage()}, updateCheckMsgInterval);
	
	var result = false;
	var messageData = ""+userID;
    
	//GET MESSAGE CODE
    $.ajax({
        type: "POST",
        url: "messageReceive.php",
        data: { fu_message_receive:messageData },
		dataType: "json",
        success: function(msg){ 
            //VIRGIL ADD HANDLER HERE (messageRecieve.php will return JSON formatted message) 
            alert(JSON.stringify(msg));
			result = true;
        },
		error: function(msg){
		}
	});
	
    return result;
}

var msgSendCount = 0;
var sendGeoLocationsResult = false;
function sendGeoLocations()
{
	// set the global var to stop gathering geo location points
	uploadingGeoLocation = true;
	
	if(sendLocTimer)
	{
		window.clearTimeout(sendLocTimer);
	}

	sendLocTimer = setTimeout(function(){sendGeoLocations()}, updateSendLocationInterval);
	
	// send all cached geo locations
	while(arrayGeoLocation.length > 0) 
	{ 
		var currentGeoLocation = arrayGeoLocation.shift();
		var forwardMsg = JSON.stringify(currentGeoLocation);
				
		$("#infoLoc").html("Sending location...");
					
		$.ajax({
			type: "POST",
			url: "messageReceive.php",
			data: {dataMsg:forwardMsg},
			success: function(msg)
			{
				if(msg){
					$("#infoLoc").html(msg);
				}else{
					$("#infoLoc").html(msg);
				}
				// Message was sent
				sendGeoLocationsResult = true;
			},
			error: function(msg){
				sendGeoLocationsResult = false;
			}
		});
		
		if (false == sendGeoLocationsResult)
		{
			arrayGeoLocation.unshift(currentGeoLocation);
			break;
		}
	}
	
	/*
	for (i = 0; i < arrayGeoLocation.length; i++) 
	{ 
		msgSendCount++;
		var forwardMsg = JSON.stringify(arrayGeoLocation[i]);
				
		$("#infoLoc").html("Sending location...");
					
		$.ajax({
			type: "POST",
			url: "messageReceive.php",
			data: {dataMsg:forwardMsg},
			success: function(msg)
			{
				if(msg){
					$("#infoLoc").html(msg);
				}else{
					$("#infoLoc").html(msg);
				}
				// Message was sent
				sendGeoLocationsResult = true;
			},
			error: function(msg){
				sendGeoLocationsResult = false;
			}
		});
		
		if (false == sendGeoLocationsResult)
		{
			break;
		}
	}

	// clear array of geo locations up to the point 
	while(arrayGeoLocation.length > 0)
	{
		arrayGeoLocation.pop();
	}
	
	msgSendCount = 0;
	*/
	
	// allow the gathering of geo location points
	uploadingGeoLocation = false;
	
	return sendGeoLocationsResult;
}

//Either adds the user to a search or removes them
var joinOrLeave = function(){
	 var passThis = $("#joinOrLeave").val();
	 $("#joinOrLeaveStatus").html("Saving...");
	 $.ajax({
		type: "POST",
		url: "messageReceive.php",
		data: "joinOrLeave=" + passThis + "&userID=" + userID + "&searchID=" + currentSearch,
		success: function(msg)
		{
			$("#joinOrLeaveStatus").html(msg);
		}
	});	
	
}