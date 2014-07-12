<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of searchHandler
 *
 * @author Shane
 */
class searchHandler {
    public $areaHandler;
    public $weatherHandler;
    public $teamHandler;
    public function __construct() 
    {
        $areaHandler = new AreaHandler();
        $weatherHandler = new WeatherHandler();
        
        //not yet implemented
        //$teamHandler
    }
    
}
