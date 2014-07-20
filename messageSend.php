<?php
require_once("phpcommon.php");

//Point return script
if(isset($_POST['update_ic_req']))
{
	
	//Unpackage the request
	$data = $_POST['update_ic_req'];

	//If there is a data for a team
	if($data["currentSearch"]){
		
		//How old the points should be
		$thetime = $data["theTime"];
		
		//Start an array of people, if all is selected, then all the users will be shown
		$return_array = $db->list_searching($data["currentSearch"], false);
		$counter = 0;
		
		if(count($return_array)){
		
			//Divide the number of people returned by the limit and get that many points
			$limit = round(($data["updateInterval"] / count($return_array))); //* 2;
			
			//Build the array
			foreach($return_array as $one){
				
				$return_array[$counter]["limit"] = $limit;
				$return_array[$counter]["points"] = array();
				$return_array[$counter]["userData"] = array();
				$return_array[$counter]["teamID"] = $db->user_team($one["userID"]);
				$tempArray = $db->get_user($one["userID"], false);
				$return_array[$counter]["userData"] = $tempArray[0];
				
				//Push the points on to each user
				$tempArray = $db->get_points($one["userID"], $thetime, 0, 0, $returnJSON = false, $limit);
				
				//if points show up, pull them all
				if(count($tempArray) > 1){
					foreach($tempArray as $one){
						array_push($return_array[$counter]["points"], $one);
					}
				}else{// if (count($tempArray) === 1){
					$tempArray = $db->latest_user_location($one["userID"], false);
					if($tempArray){
						$return_array[$counter]["points"] = array(0=>$tempArray[0]);
					}
				}
				
				$counter++;
			}
			
		//There are no searchers yet
		}else{
			$error = array("error" => "1");
			array_push($return_array, $error);
		}
	}
	
    echo json_encode($return_array);
}


//Creates a new search
if(isset($_POST['newSearchData'])){
	$data = $_POST['newSearchData'];
	
	$return["searchID"] = $db->create_search($data['userID'], $data['searchName'], $data['searchStart'], 1, $data['searchNotes']);
	
	echo json_encode($return);
};
//Creates a new team
if(isset($_POST['newTeamData'])){
	$data = $_POST['newTeamData'];
	$colors = $data['backgroundColor'] . "&&" . $data['fontColor'];
	$return_array = array("teamID"=> $db->create_team($data['teamLeader'], $data['teamName'], $data['teamNotes'], $colors));
	echo json_encode($return_array);
};
//This script returns a list of searches
if(isset($_POST['updateSearches'])){
	echo $db->list_searches();	
}
//This script returns a list of teams
if(isset($_POST['updateTeams'])){
	echo $db->list_teams();	
}
if(isset($_POST['deleteSearch'])){
	echo $db->delete_search($_POST['deleteSearch']); 	
}
if(isset($_POST['joinTeam'])){
	echo $db->user_join_team($_POST['userID'] , $_POST['joinTeam']);	
}
if(isset($_POST['leaveTeam'])){
	echo $db->user_leave_team($_POST['leaveTeam']);	
}
if(isset($_POST['deleteTeam'])){
	echo $db->delete_team($_POST['deleteTeam']);	
}
//This script is used to send a message (places it in database)
if(isset($_POST['ic_message_send'])){
    return true;
}
//This script is used to send a message (places it in database)
if(isset($_POST['fu_message_send'])){
    $data = $_POST['fu_message_send'];
	echo $db->create_message($data['from'], $data['sentTo'], $data['subject'], $data['body'], ($data['date'] / 1000));
}
?>
