/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var getNewMessage = function(){
    
//This is the timer that runs the getNewMessage query		
msgtimer = setInerval(function(){getNewMessages()}, 1000);

	requestData = {sentTo: IC, from: Usernumber, subject: Subject, urgency: Level, date: Time, body: Body}
	
	//Start the AJAX call
	$(id="pamphletu139").html("Refreshing...");
	$.ajax({
        type: "POST",
        url: "messageReceive.php",
        data: { update_ic_req:requestData },
		dataType: "json",
        success: function(msg){ 
			var newMessages = new Refresh();
			$(id="u403").html(objectCount + "New Messages" + "| From:" + Usernumber + "| Subject:" + Subject + "| Urgency: " + Level + "| Received:" + Time + "| Message:" + Body); 
                        
         },
         error: function(msg){
             var failedRecipt = new Notice();
             $("#floatNote").html("Messages were not retrieved.")
             
         }
     });
};

var sendToAll = function (){
   
     sendData = {sentTo: All, from: IC, subject: Subject, urgency: Level, date: Time, body: $(id="u406")}
     
     //Start the AJAX call
     $(id="pamphletu139").html("Sending...");
	$.ajax({
        type: "POST",
        url: "messageSend.php",
        data: { update_ic_req:sendData },
		dataType: "json",
        success: function(msg){ 
			var sentMessage = new Refresh();
                        $("#floatNote").html("Message Sent");
                    },
        error: function(msg){
            var failedMessage = new Notice();
            $("#floatNote").html("Message was not sent. Try again later.")
            
     
 }
});
};

var sendToTeam = function (){
   
     sendData = {sentTo: All, from: IC, subject: Subject, urgency: Level, date: Time, body: $(id="u407")}
     
     //Start the AJAX call
     $(id="pamphletu139").html("Sending...");
	$.ajax({
        type: "POST",
        url: "messageSend.php",
        data: { update_ic_req:sendData },
		dataType: "json",
        success: function(msg){ 
			var sentMessage = new Refresh();
                        $("#floatNote").html("Message Sent");
                    },
        error: function(msg){
            var failedMessage = new Notice();
            $("#floatNote").html("Message was not sent. Try again later.")
            
     
 }
});
};

var sendToIndividual = function (){
   
     sendData = {sentTo: All, from: IC, subject: Subject, urgency: Level, date: Time, body: $(id="u408")}
     
     //Start the AJAX call
     $(id="pamphletu139").html("Sending...");
	$.ajax({
        type: "POST",
        url: "messageSend.php",
        data: { update_ic_req:sendData },
		dataType: "json",
        success: function(msg){ 
			var sentMessage = new Refresh();
                        $("#floatNote").html("Message Sent");
                    },
        error: function(msg){
            var failedMessage = new Notice();
            $("#floatNote").html("Message was not sent. Try again later.")
            
     
 }
});
};