//Send and receive message functions, handlers are in ic.js and fu.js for return calls when AJAX completes

var sendMessage = function(sentTo, sentFrom, subject, message, urgency)
{

	var messageData = new Object();
    messageData.msgTo = sentTo;
    messageData.msgFrom = sentFrom;
    messageData.msgSubject = subject;
    messageData.msgUrgency = urgency;
	var dateObject = new Date();
    messageData.msgDate = dateObject.getTime();
    messageData.msgBody = message;

	$.ajax({
		type: "POST",
		url: "messageSend.php",
		data: { message_send:messageData },
		dataType: "json",
		success: function(msg){ 
			messageSendHandler(msg);
		},
		error: function(msg){
			messageSendHandler(msg);
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
}