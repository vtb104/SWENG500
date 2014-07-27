//Send and receive message functions, handlers are in ic.js and fu.js for return calls when AJAX completes

var sendMessage = function(sentTo, sentFrom, subject, message)
{

	var messageData = new Object();
    messageData.msgTo = sentTo;
    messageData.msgFrom = sentFrom;
    messageData.msgSubject = subject;
    var dateObject = new Date();
    messageData.msgDate = dateObject.getTime();
    messageData.msgBody = message;

	$.ajax({
		type: "POST",
		url: "messageSend.php",
		data: { message_send:messageData },
		dataType: "json",
		success: function(msg){ 
			messageSendHandler(msg, messageData);
		},
		error: function(msg){
			messageSendHandler(msg, messageData);
		}
	});
	
}

var getMessage = function(inputID){
	var messageData = {sentTo: inputID};
    $.ajax({
        type: "POST",
        url: "messageReceive.php",
        data: {message_receive:messageData },
		dataType: "json",
        success: function(msg){ 
            messageGetHandler(msg);
        },
		error: function(msg){
			messageGetHandler(msg);
		}
	});
};

//Function sends a notification to mark the new message as read
var markAsRead = function(messageID){
    $.ajax({
        type: "POST",
        url: "messageReceive.php",
        data: "messageread=" + messageID,
        success: function(msg){ 
			if(msg){
				$("#floatNote").html("Message read");	
			}
		}
	});
}

//Function checks for messages and returns an integer for the number of new messages for the user.

var checkMessages = function(userID){
	$.ajax({
        type: "POST",
        url: "messageReceive.php",
        data: "messagecheck=1&userID=" + userID,
        success: function(result){ 
			checkMessageHandler($.trim(result));
		}
	});
	
}


//set message delivery criteria
var lastRefresh = new Date();
var messageCount = 0;
var updateInterval = 5000;
var timer = 0;

//pull new messages from server automatically
var messageQueue = function(){
    timer = refresh(function(){messageQueue()}, updateInterval);
    requestData = { messageCount: Total };
    if(messageCount > 0){
		var newerThan = Math.round(lastRefresh.getTime()/ 1000);
                };
               getMessages;
        };
        