/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var getNewMessage = function(){
    
//This is the timer that runs the getNewMessage query		
//msgtimer = setInerval(function(){getNewMessages()}, 1000);

	requestData = {sentTo: "IC", from: "Usernumber", subject: "Subject", urgency: "Level", date: "Time", body: "Body"}
	
	//Start the AJAX call
	$(id="pamphletu139").html("Refreshing...");
	$.ajax({
        type: "POST",
        url: "messageReceive.php",
        data: { ic_msg_recieve:requestData },
		dataType: "json",
        success: function(msg){ 
                        alert(JSON.stringify(msg));
			//DEB ADD CODE HERE TO HANDLE NEW RECIEVED MESSAGES
                      //  var newMessages = new Refresh();
			$(id="u403").html(objectCount + "New Messages" + "| From:" + msg.from + "| Subject:" + Subject + "| Urgency: " + Level + "| Received:" + Time + "| Message:" + Body); 
                        
         },
         error: function(msg){
             var failedRecipt = new Notice();
             $("#floatNote").html("Messages were not retrieved.")
             
         }
     });
};

var sendNewMessage = function (){
   
     sendData = {sentTo: To, from: From, subject: Subject, urgency: Urgency, date: Time, body: Body}
     
     //Start the AJAX call
     $(id="pamphletu139").html("Sending...");
	$.ajax({
        type: "POST",
        url: "messageSend.php",
        data: { ic_message_send:sendData },
		dataType: "json",
        success: function(msg){ 
                        //DEB ADD CODE HERE TO HANDLE SERVER RESPONSE AFTER SENDING A MESSAGE (will return true)
			var sentMessage = new Refresh();
                        $("#floatNote").html("Message Sent");
                    },
        error: function(msg){
            var failedMessage = new Notice();
            $("#floatNote").html("Message was not sent. Try again later.")
            
     
 }
});
};
