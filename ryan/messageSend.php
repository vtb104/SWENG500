<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of messageSend
 *
 * @author Shane
 */
 
require_once("phpcommon.php");
//require_once("message.php");

/*class messageSend extends message{
    //put your code here
}
*/
//$handler = new messageSend;
//echo $db->latest_team_location(1);

//else if the message is a request/send

if(isset($_POST['update_ic_req']))
{
	
	//Unpackage the request
	$data = $_POST['update_ic_req'];

	//If there is a data for a team
	if($data["team"]){
		
		//How old the points should be
		$thetime = time() - $data["theTime"];
		
		//Start an array of people
		$return_array = $db->list_team($data["team"], false);
		$counter = 0;
		
		//Build the array
		foreach($return_array as $one){
			
			$return_array[$counter]["points"] = [];
			$return_array[$counter]["userData"] = $db->get_user($one["userID"], false);
			
			//Push the points on to each user
			$tempArray = $db->get_points($one["userID"], $thetime, 0, 0, $returnJSON = false);
			
			foreach($tempArray as $one){
				array_push($return_array[$counter]["points"], $one);
			}
			
			$counter++;
		}
	}
	

    echo json_encode($return_array);
}



?>
