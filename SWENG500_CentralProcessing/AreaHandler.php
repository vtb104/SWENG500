<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AreaHandler
 *
 * @author Shane
 */
class AreaHandler {
    //put your code here
    public $areas_object;
    /**
     * @assert ("1") == true
     * @assert ("2") == true
     * @assert (null) == 0
     */
    function addArea($areaID)
    {
        if($areaID != null)
        {
            $newTeam = new Area();
            $newTeam->teamNumber = $areaID;
            $this->teams_object[$areaID] = $newTeam;
            return true;
        }
        else
        {

        }
    }
}
?>
