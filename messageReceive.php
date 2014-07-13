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
	$handler = new messageReceive;
	$decode = $handler->decode_json_message($_POST['dataMsg']);
	echo $db->create_point($decode->user, $decode->lat, $decode->lng, '25', time(), 'From FU');
};

if(isset($_POST['userRequest'])){
	echo $db->list_users();
};
if(isset($_POST['getMessages'])){
    $testMessage = new stdClass();
    $testMessage->to = "Deb";
    $testMessage->from = "Shane";
    $testMessage->subject = "This is a test message";
    $testMessage->body = "Hello there";
    $testMessage->date = time();
    echo json_encode($testMessage);
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
