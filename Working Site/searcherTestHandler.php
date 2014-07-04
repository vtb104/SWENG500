<?php
//Search setup handler
require_once("phpcommon.php");

if(isset($_POST['setup'])){

	$numusers = $_POST['setup'];
	$userNumber = array();
	for($i=0; $i<$numusers; $i++){
		$one = $db->create_user("TestUser" . $i . time(), 'First' . $i, 'Last' . $i, 'Email', 'hello', 'hello', 'tempSearcherCreated');
		$db->user_join_team($one, 1);
		array_push($userNumber, $one);
	};
	
	echo json_encode($userNumber);
};

if(isset($_POST['destroy'])){
	
	$input = json_decode($_POST['data']);
	$return = array();
	foreach($input as $one){
		array_push($return, $db->delete_user($one));
	};
	
	echo json_encode($return);
	
};

?>