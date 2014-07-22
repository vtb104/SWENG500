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
};

if(isset($_POST['userRequest'])){
	echo $db->list_users();
};

//Function returns messages for the user passed.
if(isset($_POST['message_receive'])){
	$data = $_POST['message_receive'];
	echo $db->fetch_messages($data['sentTo']);
};


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