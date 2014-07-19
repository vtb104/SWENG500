//Shared JavaScript for the FU and IC
var cookieDuration = 24 * 30;	//30 days
teamArray = [];

/*************************Specific Functions for search stuff********************************/
function testShared()
{
    alert("Shared works!");
}
var polyStorage = [];
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
            strokeColor: "#00ff00",
            strokeOpacity: 0.8,
            strokeWeight: 3,
            fillColor: "#00ff00",
            fillOpacity: 0.35
        });
        areaFill.setMap(map);
        polyObj.name = areaName;
        polyObj.area = areaFill;
        polyStorage.push(polyObj);
    }
}
function removeAllPolysFromMap()
{
    for(var pcnt = 0; pcnt < polyStorage.length; pcnt++)
    {
        polyStorage[pcnt].area.setMap(null);
    }
    polyStorage = [];
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

//This function grabs the searches that are in the database
var updateSearches = function(){
	$.ajax({
        type: "POST",
        url: "messageSend.php",
        data: "updateSearches=true",
		dataType: "json",
        success: function(msg){ 
			$("#currentSearchNumber").html("<option value='all'>All Searches</option>");
			$.each(msg, function(index, value){
				$("#currentSearchNumber").append("<option value='" + value.searchID + "'>" + value.searchName + "</option>");
			});
			$("#currentSearchNumber").val(currentSearch); //.selectmenu('refresh');
		}
	});
}
//This function grabs the teams that are in the database
var updateTeams = function(){
	$.ajax({
        type: "POST",
        url: "messageSend.php",
        data: "updateTeams=true",
		dataType: "json",
        success: function(msg){ 
			teamArray = msg;
			$("#currentTeamNumber").html("<option value='all'>All Teams</option>");
			$.each(teamArray, function(index, value){
				$("#currentTeamNumber").append("<option value='" + value.teamID + "'>" + value.teamName + "</option>");
				
				//Split up the colors for easy reference
				var tempColors = value.teamInfo.split("&&");
				teamArray[index].backgroundColor = tempColors[0];
				teamArray[index].fontColor = tempColors[1];
			});
    		$("#currentTeamNumber").val(currentTeam);
		}
	});
}

/***************************************Generic functions for any site*****************/

//Returns the name of the month for UI output
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

	
//Finds the distance between two Google Maps Points
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

//Adds a leading 0 to numbers for output
function leadingZero(input){
	if(input < 10){
		return "0" + input;
	}else{
		return input;
	}
}

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
