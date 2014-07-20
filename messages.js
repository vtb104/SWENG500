/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*var initialize = function(){
    getNewMessages();
}*/

var getNewMessage = function(){

requestData = {sentTo: "1", type: "search" }
	
	//Start the AJAX call
	$(id="pamphletu139").html("Refreshing...");
	$.ajax({
        type: "POST",
        url: "messageReceive.php",
        data: { ic_msg_recieve:requestData },
		dataType: "json",
        success: function(msg){ 
                        alert(JSON.stringify(msg));
			  var newMessages = new Refresh();
                               if (success){
			$(id="u403").html(objectCount + "New Messages" + "| From:" + msg.from + "| Subject:" + msg.Subject + "| Urgency: " + msg.Level + "| Received:" + msg.Time + "| Message:" + msg.Body); }
                               else{
                                   $("#floatNote").html("Messages were not retrieved.")
                               }
                        
         },
         error: function(msg){
             //var failedRecipt = new Notice();
             $("#floatNote").html("Messages were not retrieved.")
             
         }
     });
};

var sendNewMessage = function (){
   
   	 theDate = new Date();
     sendData = {sentTo: "1", from: "3", subject: "Test", urgency: "High", body: "Test", date: theDate.getTime()}
     
     //Start the AJAX call
     $(id="pamphletu139").html("Sending...");
	$.ajax({
        type: "POST",
        url: "messageSend.php",
        data: { fu_message_send:sendData },
		dataType: "json",
        success: function(msg){ 
                        
                    alert(JSON.stringify(msg));	
                        var sentMessage = new Refresh();
                        $("#floatNote").html("Message sent.")
                        
                    },

        error: function(msg){
           // var failedMessage = new Notice();
            $("#floatNote").html("Message was not sent. Try again later.")
            
     
 }
});
};
