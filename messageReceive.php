<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of messageReceive
 *
 * @author Shane
 */

require_once('message.php');
require_once("phpcommon.php");

class messageReceive  extends message{
    //put your code here
    public $last_received_msg;
    function receive_message($message)
    {
       //set variable to undecoded message recieved 
       $this->last_received_msg = $message;
       
       
       // $this->last_received_msg = $this->decode_json_message($message);
       // echo "<script>console.log('".$message."')
    }
}

if(isset($_POST['dataMsg'])){
	$data = $_POST['dataMsg'];
	$theTime = $data['sentTime'] / 1000;
	echo $db->create_point($data['user'], $data['lat'], $data['lng'], '25', $theTime, 'From FU');
	die();
};

if(isset($_POST['userRequest'])){
	echo $db->list_users();
	die();
};

//Function returns messages for the user passed.
if(isset($_POST['message_receive'])){
	$data = $_POST['message_receive'];
	$result = array();
	$result = $db->fetch_messages($data['sentTo'], 0 , 0, 0, false);
	for($i = 0; $i < count($result); $i++){
		$result[$i]["sentuser"] = $db->get_user($result[$i]["sentfrom"], false);
		$result[$i]["touser"] = $db->get_user($result[$i]["sentto"], false);
	}
	echo json_encode($result);
	die();
};

//Function marks messages as read
if(isset($_POST['messageread'])){
	if($db->message_read($_POST['messageread'])){
		echo true;	
	}else{
		echo false;
	}
	die();
}

//Function deletes a message
if(isset($_POST['deleteMessage'])){
	echo $db->delete_message($_POST['deleteMessage']);
	die();	
}

//Function checks for new messages for a user
if(isset($_POST['messagecheck'])){
	echo $db->message_check($_POST['userID']);	
	die();
}

//Sets session varialbes for the IC to reply to a message:
if(isset($_POST['messageReply'])){
	$data = $_POST['messageReply'];
	$_SESSION['messageTo'] = $data['to'];
	$_SESSION['messageFrom'] = $data['userID'];
	$_SESSION['messageID'] = $data['msgID'];
	echo json_encode("Good");
	die();	
}

//This function either adds a user to a search or removes them
if(isset($_POST['joinOrLeave'])){
	
	
	$userID = $_POST['userID'];
	$searchID = $_POST['searchID'];
	$joinOrLeave = $_POST['joinOrLeave'];
	
	if($joinOrLeave){
		//Join a search
		$result = $db->user_join_search($userID, $searchID);
		if($result){
			echo "You have successfully joined the search.";	
		}else{
			echo "<span style='color: red'>There was an error. <br/> You are either already part of the search, or something else is wrong.</span>" . $result;	
		}
	}else{
		//Leave a search	
		$result = $db->user_leave_search($userID, $searchID);
		
		if($result){
			echo "You have successfully left the search.";	
		}else{
			echo "<span style='color: red'>There was an error. <br/> You were not part of that search in the first place.</span>";	
		}
	}
	die();
}

?>