var startLat = 36.53170884914869;
var startLng = 127.869873046875;
var mapZoom = 4;
var mapHome = new google.maps.LatLng(39.57, -99.10);
var markerPosition = new google.maps.LatLng(0,0);

//Creates the marker for the input data
var markerOptions = {map: null,  position: markerPosition};
var positionMarker = new google.maps.Marker(markerOptions);

//First to run
var initialize = function(){
	
	//Sets options for the map with vars above
	var myOptions = {zoom: mapZoom, center: mapHome};
	
	//Creates the map
	map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
	
	positionMarker.setMap(map);
	
	//This is the timer that runs the getNewPoint function
	var timer = setInterval(function(){getNewPoints()}, 1000);
            
        //This is the timer that runs the NewMessage query
        var msgtimer = setInerval(function(){RetrieveMessages()}, 1000);
};



//This is the function that runs every 5s from the timer
var getNewPoints = function(){
	$("#floatNote").html("Sending...");
	requestData = "1";
	$.ajax({
        type: "POST",
        url: "messageSend.php",
        data: { update_ic_req:requestData },
		dataType: "json",
        success: function(msg){  
			$("#floatNote").html(msg);                      
			
			var tempPos = new google.maps.LatLng(msg[2], msg[3]);
			positionMarker.setPosition(tempPos);
			
			$("#pantorecent").removeAttr("disabled");
			
			$("#info").html("Marker showing position of mobile unit.  Location is Lat: " + msg[2] + " Lng: " + msg[3]); 
			
         }
     })
};

//Runs when button is pressed to center map
var panMapRecent = function(){
	map.panTo(positionMarker.getPosition());
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

//IC Message function call to server every 5 seconds

 var RetrieveMessages = Parse.Object.extend("NewMessage");
 var query = new Parse.Query(NewMessages);
 query.equalTo("sentTo", "IC");
 query.find({
     success: function(results) {
         alert("New Message(s)" + results.length + " messages.");
         
         //Return messages to UI
         for (var i = 0; i < results.length; i++) {
             var object = results[i];
             alert(object.id + ' - ' + object.get('sentTo'));
             }
     },
     error: function(error) {
         alert("Error: " + error.code + " " + error.message);
        }
     });
 
 //Send messages from IC to all
 function sendToAll(){
     var groupMsg = $("u406").val();
     formAll = document.createElement("formAll");
     formAll.action = "All Users";
     formAll.submit();
     document.getElementByID("formAll").value=" ";
     
 }
 
 //Send messages from IC to team
 function sendToTeam(){
     var teamMsg = $("u407").val();
     formTeam = document.createElement("formTeam");
     formTeam.action = "All Users";
     formTeam.submit();
     document.getElementByID("formTeam").value=" ";
 }
 
 function sendToIndividual(){
     var individualMsg = $("u408").val();
     formIndividual = document.createElement("formIndividual");
     formIndividual.action = "All Users";
     formIndividual.submit();
     document.getElementByID("formIndividual").value=" ";
 }
 
 
$(function(){
	$("#searchnow").click(function(){
		searchNow();
	});
	
	$("#pantorecent").click(function(){
		panMapRecent();
	});
        
        $("#button409").click(function(){
                sendToAll();
        });
        
        $("#button412").click(function(){
                sendToTeam();
        });
        
        $("#button414").click(function(){
                sendToIndividual();
        });

});
