/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function send_sample_msg()
{
    var testMsg = new Object();
    testMsg.team = "Alpha";
    testMsg.lat = "123.456";
    testMsg.long = "654.321";
    testMsg.bearing = "270";
    
    var formattedMsg = JSON.stringify(testMsg);
    
    $.ajax(
    {
        type: "POST",
        url: "index.php",
        data: { dataMsg:formattedMsg },
        success: function(msg){                        
         }
     })
     .done(function(data) {
    alert( "response: " + data);
  })
}
function send_sample_update_request()
{
    var requestData = "All";
    $.ajax(
    {
        type: "POST",
        url: "index.php",
        data: { update_ic_req:requestData },
        success: function(msg){                        
         }
     })
     .done(function(data) {
    alert( "response: " + data);
  })
}
