<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TeamHandler
 *
 * @author Shane
 */
class TeamHandler 
{
    public $teams_object;
            
    /**
     * @assert ("1") == true
     * @assert ("2") == true
     * @assert (null) == ErrorException
     */
    function addTeam($teamNumber)
    {
        if($teamNumber != null)
        {
            $newTeam = new Team();
            $newTeam->teamNumber = $teamNumber;
            $this->teams_object[$teamNumber] = $newTeam;
            return true;
        }
        else
        {
            trigger_error("Must have a valid team number to create a new team");
            return false;
        }
    }
    /**
     * @assert ("user1","1") == true
     * @assert ("user2","1") == true
     * @assert ("user3","2") == false
     * @assert (null,null) == ErrorException
     */
    function addUserToTeam($userID, $teamNumber)
    {
        $this->teams_object[$teamNumber]->users.push($userID);
    }
    /**
     * @assert ("321","123","1") == true
     * @assert (null,"123","1") == false
     * @assert ("321",null,"1") == false
     * @assert ("321","123",null) == false
     */
    function updateTeamLocation($lat, $lng, $teamNumber)
    {
        $this->teams_object[$teamNumber]->currentLat = $lat;
        $this->teams_object[$teamNumber]->currentLng = $lng;
    }
    /**
     * @assert ("90","1") == true
     * @assert (null,"1") == false
     * @assert ("90",null) == false
     */
    function updateTeamBearing($bearing, $teamNumber)
    {
        $this->teams_object[$teamNumber]->currentBearing = $bearing;
    }
}
