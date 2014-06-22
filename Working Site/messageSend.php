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
	
	$user_location_array = array();
	$user_id_array = json_decode($db->list_users());
	foreach($user_id_array as $one){
		array_push($user_location_array, $db->latest_user_location($one->userID));
	}

    echo json_encode($user_location_array);
}



?>
