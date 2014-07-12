/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
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