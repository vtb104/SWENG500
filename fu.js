// Declaration of global vars
var updateLocationInterval = 5000;
var updateCheckMsgInterval = 5000;

var locTimer = 0;
var sendLocTimer = 5000;

var firstLoop = true;
var currentLoc = new google.maps.LatLng(0, 0);
var arrayGeoLocation = [];
var cachedPoints = 0;

var newMessages = '';

// The FU is part of a search if it is a value > 0.
// For DEBUG purposes only, set it to a value > 0, otherwise set it to 0.
var currentSearch = 1;
var nearByUsersIconArray = new Array();
var baseIcon;

var searchInfo;

if(readCookie("sar.currentSearchFU")){
	currentSearch = readCookie("sar.currentSearchFU");
}

var updateCurrentSearch = function(){
	currentSearch = $("#currentSearchNumber").val();
	getSearchInfo(currentSearch);
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
	
	sendLocations = setInterval(sendGeoLocations,   sendLocTimer);

	//Start testing for new messages
	newMessageTimer = setInterval(function(){checkMessages(userID);}, 1000);
	
	var lat = $("#lat").val ();
    var lng = $("#lng").val ();
    var latlng = new google.maps.LatLng (lat, lng);
    
    //Sets options for the map with vars above
    var myOptions = {
        zoom: 15, 
        center: latlng,
        mapTypeControl: true,
		mapTypeId : google.maps.MapTypeId.TERRAIN,
		scaleControl: true
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
	
	marker = new google.maps.Marker({
		position: latlng,
		map: map,
		title: "SAR Map"
	});
	
	//Fills the searches selector
	updateSearches();
	
	//Get the info for the current search
	getSearchInfo(currentSearch);
	
    return true;
}

// Update locTimer information
// if geo service is available, send the current location to the server
function sendPosition()
{
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
				sendMsg.user = userID;
				sendMsg.lat = $("#lat").val();
				sendMsg.lng = $("#lng").val();
				var dateObject = new Date();
				sendMsg.sentTime = dateObject.getTime();
				sendMsg.sent = false;
				
				arrayGeoLocation.push(sendMsg);
				
				$("#infoLoc").html("msg created. timestamp: " + sendMsg.sentTime);
			}

			function fail(error) 
			{
				// Failed to find location, do nothing
			}
            //Second call to check if there is a new area available from IC
            //checkForNewAreaFromIC();
		}
		else
		{
			$("#info").html("Location not allowed for this application.  Please allow location sharing to enable this feature.");
		}
                
                checkForNewAreaFromIC();
}

var cnt=0;
pointArray = new Array();
polyStorage = new Array();
function checkForNewAreaFromIC()
{
    removeAllAreasOnMap();
    $.ajax({
        type: "POST",
        url: "AreaHandler.php",
        data: {checkForArea:""+userID},
        success: function(msg)
        {
            var areasAssigned = JSON.parse(msg);
            for(var cnt = 0; cnt < areasAssigned.length; cnt++)
            {
                getAreaPoints(areasAssigned[cnt].areaID);
            }
            //alert(areasAssigned[0].areaID);
        }
    });
    
}
function getAreaPoints(areaID)
{
//load area selected points
     $.ajax({
        type: "POST",
        url: "AreaHandler.php",
        data: {getAreaPoints:areaID},//change search ID for multiple searches
        dataType: "json",
        success: function(areaData){
            pointArray = [];
            var tempPointsArray = JSON.parse(areaData[0].areaPoints);
            for(var cnt=0; cnt < tempPointsArray.length; cnt++)
            {
                pointArray.push(new google.maps.LatLng(tempPointsArray[cnt].k,tempPointsArray[cnt].B));
            }
            fillPoly(areaID, pointArray);
        }});
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
        var areaFill = new google.maps.Polygon(
        {
            paths: arrayOfPoints,
            strokeColor: tempColor,
            strokeOpacity: 0.8,
            strokeWeight: 3,
            fillColor: tempColor,
            fillOpacity: 0.35
        });
        areaFill.setMap(map);
        polyObj.name = areaID;
        polyObj.area = areaFill;
        polyStorage.push(polyObj);
    }
}
//removes all areas on map
function removeAllAreasOnMap()
{
    for(var cnt = 0; cnt < polyStorage.length; cnt++)
    {
        polyStorage[cnt].area.setMap(null);
    }
    polyStorage = [];
}
//Function iterates through the arrayGeoLocation and sends messages that aren't sent yet.
function sendGeoLocations()
{
	cachedPoints = 0;
	// Go through the array and make an AJAX call for each that isn't sent yet.
	$.each(arrayGeoLocation, function(index, value){
		if(!value.sent){
			cachedPoints++;
			$.ajax({
				type: "POST",
				url: "messageReceive.php",
				data: {dataMsg: value},
				dataType: "json",
				success: function(msg){
					if(msg && msg !== " "){
						value.sent = true;	
						cachedPoints = cachedPoints - 1;
					}
					if(cachedPoints){
						$(".cachedPoints").html(" <br/>Points cached: " + cachedPoints);
					}else{
						$(".cachedPoints").html("");
					}
				},
				error: function(msg){
					value.sent = false;	
					if(cachedPoints){
						$(".cachedPoints").html(" <br/>Points cached: " + cachedPoints);
					}else{
						$(".cachedPoints").html("");
					}
				}
			})
		}
		
	});
	showNearbyUsers();
        shopBaseOnMap();
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

//Handles the messages when the AJAX calls finish
var messageSendHandler = function(msg, messageData){
	messageClear();
	$("#msgStatus").html("Message sent");
	var timer = setTimeout(function(){$("#msgStatus").html("");}, 2000);
}

var messageGetHandler = function(msg){
	
	if(msg){
		$("#msgContainer").html("");
		$.each(msg, function(index, value){
			
			//First check to see if the message has been read
			if(value.status === "1"){
				ifNew = "<span id='newMsg"+ value.messageID +"' style='color: #F00'></span>";
			}else{
				ifNew = "<span id='newMsg"+ value.messageID +"' style='color: #F00'>New </span>";
			}
			
			var theDate = new Date(value.dateSent * 1000);
			$("#msgContainer").append('<div id="msgID' + value.messageID + '" msgID="'+value.messageID+'" class="onemessage" data-role="content"><span style="float: left">From: '+ value.sentuser[0].username +'</span><div align="center">"'+ ifNew + value.subject +'"</div>Sent: '+theDate.toDateString() + " " + leadingZero(theDate.getHours()) + ":" + leadingZero(theDate.getMinutes())+'<div id="msgBox' + value.messageID + '" style="display: none"><br/><div id="fullMsg'+ value.messageID+'">'+value.message+'</div><div delMsg="' + value.messageID + '" style="float: right" class="msgdel msgButton">Delete</div><div replyUser="' + value.sentfrom + '" msgID="' + value.messageID + '" style="float: right;" class="msgReply msgButton">Reply</div></div></div>');	
		})
		
		$(".onemessage").on("click", function(){
			var msgID =  $(this).attr("msgID");
			$("#msgBox" + msgID).toggle();
			
			var newCheck = $("#newMsg" + msgID).html();
			if(newCheck){
				markAsRead(msgID);
				newMessages--;
			}
			$("#newMsg" + msgID).html("");
		});
		
		$(".msgdel").on("click", function(){
			var msgID = $(this).attr("delMsg");
			$("#msgID" + msgID).html("Deleted");
			setTimeout(function(){
				$("#msgID" + msgID).hide("slow");
				deleteMessage(msgID);
				}, 1000);
			
		});
		
		$(".msgReply").on("click", function(){
			newMessageShow();
			var replyTo = $(this).attr("replyUser");
			var msgID = $(this).attr("msgID");
			$("#sendTo").val(replyTo).selectmenu("refresh", true);
			$("#messageBody").val("\n Original Message:\n" + $("#fullMsg" + msgID).html()).focus();
		});
		
	}
	
}

var checkMessageHandler = function(result){
	if(result != newMessages){
		getMessage(userID);
	}
	if(result !== "0"){
		$(".newMsg").html(" New Mail");
	}else{
		$(".newMsg").html("");
	}
	newMessages = result;
}

var deleteMessageHandler = function(result){
	//$("#msgStatus").html("Message deleted");	
	getMessage(userID);
}

var newMessageShow = function(){
	$(".newMessageShow").toggle("fast");	
}

var messageClear = function(){
	$("#messageBody").val("");
	$(".newMessageShow").toggle("fast");
}


function showNearbyUsers()
{
    var userList = new Array();
    //to do add functionality to have a max distance from person
    var maxDistance = 1;
    //get users
     $.ajax({
        type: "POST",
        url: "AreaHandler.php",
        data: {getNearbyUsers:maxDistance},//change search ID for multiple searches
        dataType: "json",
        success: function(usersData){
            
            nearByUsersIconArray = new Array();
            for(var cnt = 0; cnt < usersData.length; cnt++)
            {
              //create new iconJSON.parse(usersData[0]).userID
              var tempPos = new google.maps.LatLng(JSON.parse(usersData[cnt]).lat,JSON.parse(usersData[cnt]).lng);
              nearByUsersIconArray[cnt] = new StyledMarker({styleIcon:new StyledIcon(StyledIconTypes.MARKER,{color:"#99FF66",text:""+JSON.parse(usersData[cnt]).userID}),position:tempPos,map:map});
            }
            //get each users current location
                       
        }});
    
    
}

function shopBaseOnMap()
{
    //get users
     $.ajax({
        type: "POST",
        url: "AreaHandler.php",
        data: {getBaseLocation:currentSearch},//change search ID for multiple searches
        dataType: "json",
        success: function(searchDataReturn){
           //alert(JSON.stringify(searchDataReturn));   
           //add base to map
           var baseLoc = new google.maps.LatLng(42.0137122,-80.2995007);
           
             var image = {
                url: 'images/baseIcon.png',
                // This marker is 20 pixels wide by 32 pixels tall.
                size: new google.maps.Size(32, 32),
                // The origin for this image is 0,0.
                origin: new google.maps.Point(0,0),
                // The anchor for this image is the base of the flagpole at 0,32.
                anchor: new google.maps.Point(0, 32)
            };
           
           baseIcon = new google.maps.Marker({
            position: baseLoc,
            map: map,
            icon: image,
    });
           
        }});
}

var errorHandler = function(msg){
	if(deBug){
		alert(JSON.stringify(msg));
	}
}	

var getSearchInfoHandler = function(msg){
	if(msg){
		searchInfo = msg;
		$("#goToLeaderButton").show();
	}else{
		$("#goToLeaderButton").hide();	
	}
		
};

var goToSearchLeader = function(){
	
	//Check to make sure search info has been loaded
	if(searchInfo){
		temp = new google.maps.LatLng(searchInfo.ownerInfo[0].lat, searchInfo.ownerInfo[0].lng);
		map.panTo(temp);
	}
}