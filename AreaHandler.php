<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('message.php');
require_once('phpcommon.php');

/*
 * IC CALLS
 */
if(isset($_POST['createArea'])){
        $handler = new message();
	$decodedMessage = $handler->decode_json_message($_POST['createArea']);
        //$pointsArray = $decodedMessage->points;
        //for($cnt =0; $cnt < count($pointsArray); $cnt++)
        //{
            $db->create_area($decodedMessage->name, $decodedMessage->points, $decodedMessage->color);
        //}
};
if(isset($_POST['searchID'])){
        echo $db->list_areas($_POST['searchID']);
};
if(isset($_POST['getAreaPoints'])){
        echo $db->list_points_in_area($_POST['getAreaPoints']);
};
if(isset($_POST['deleteArea'])){
        echo $db->delete_area($_POST['deleteArea']);
};
if(isset($_POST['assignArea'])){
        //TODO
    $handler = new message();
    $decodedMessage = $handler->decode_json_message($_POST['assignArea']);
    if($db->assign_team_to_area($decodedMessage->area, $decodedMessage->team))
    {
        echo true;
    }
    else
    {
        echo false;
    }
};
/*
 * FU CALLS
 */
//call for FU to check if new area has been assigned
if(isset($_POST['checkForArea'])){
        $userIDForArea = $_POST['checkForArea'];
        $teamAssigned = $db->user_team($userIDForArea);
        //check is user has team
        if($teamAssigned)
        {
            $areaAssigned = $db->get_area_team_assignments($teamAssigned);
            //if user has team show area asigned to that team
            if($areaAssigned)
            {
                echo $areaAssigned;
            }
            else
            {
               // echo "no areas";
            }
        }
        else
        {
            echo "no team";
        }
};
//get all users nearby for FU
if(isset($_POST['getNearbyUsers']))
{
    $returnArray = array();
    $userList = $db->list_users(false);
    for($cnt = 0; $cnt < count($userList); $cnt++)
    {
        $userLocation = $db->latest_user_location_simplified($userList[$cnt]['userID']);
        $tempUL = $userLocation->fetch_assoc();
        $tempObj = new stdClass();
        $tempObj->userID = $tempUL['userID'];
        $tempObj->lat = $tempUL['lat'];
        $tempObj->lng = $tempUL['lng'];
        array_push($returnArray, json_encode($tempObj));
    }
 
    echo json_encode($returnArray);
   // echo $userList;
}

?>
