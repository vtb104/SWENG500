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
 
require_once("database.php");
include_once 'messageClassTemplate.php';
/*class messageSend extends message{
    //put your code here
}
*/
//$handler = new messageSend;
//echo $db->latest_team_location(1);

class messageSend extends messageClassTemplate{
     /**
     * @assert ("1") == true
     * @assert ("All") == true
     * @assert ("asd") == false
     * @assert ("Alpha") == true
     */
    function compose_and_send_message($team)
    {
        //figure out which team's data to get
        //retireve data from database
        //compose data into a JSON string
        //echo data
        
        //cases for teams
        if($team == "All")
        {
            //update all teams
            return true;
        }
        else if($team == "1")
        {
            messageSend::retreive_last_position("1");
            return true;
        }
        else
        {
            messageSend::retreive_last_position("1");
            return false;
        }      
    }
     /**
     * @assert ("1") == true
     * @assert (null) == false
     * @expectedException ErrorException
     */
    function retreive_last_position($team)
    {
        if($team != null)
        {
            echo $this->db->latest_team_location($team);
            return true;
        }
        else
        {
            trigger_error("Cannot save last point without valid team association");
        }
    }
}

?>
