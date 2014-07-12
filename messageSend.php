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
		
		//Start an array of people
		$return_array = $db->list_searching($data["currentSearch"], false);
		$counter = 0;
		
		if(count($return_array)){
		
			//Divide the number of people returned by the limit and get that many points
			$limit = ($data["updateInterval"] / count($return_array)) * 2;
			
			//Build the array
			foreach($return_array as $one){
				
				$return_array[$counter]["points"] = array();
				$return_array[$counter]["userData"] = array();
				$tempArray = $db->get_user($one["userID"], false);
				$return_array[$counter]["userData"] = $tempArray[0];
				
				//Push the points on to each user
				$tempArray = $db->get_points($one["userID"], $thetime, 0, 0, $returnJSON = false, $limit);
				
				//if points show up, pull them all
				if(count($tempArray) > 1){
					foreach($tempArray as $one){
						array_push($return_array[$counter]["points"], $one);
					}
				}else{
					$tempArray = $db->latest_user_location($one["userID"], false);
					array_push($return_array[$counter]["points"], $tempArray[0]);	
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


//This script returns a list of searches
if(isset($_POST['updateSearches'])){
	echo $db->list_searches();	
}

?>
