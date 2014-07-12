<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Team
 *
 * @author Shane
 */
class Team {
    public $teamNumber;
    public $users;
    public $currentLat;
    public $currentLng;
    public $currentBearing;
    function __construct() {
        $this->users = array();
    }
}
?>
