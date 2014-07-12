<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WeatherHandler
 *
 * @author Shane
 */
class WeatherHandler {
    public $weatherObject;
    function __construct() 
    {
        $json_string = file_get_contents("http://api.wunderground.com/api/ff3e23e766c6adcf/geolookup/conditions/q/IA/Cedar_Rapids.json");
        $weatherObject = json_decode($json_string);
    }
    function updateWeatherObject()
    {
        $json_string = file_get_contents("http://api.wunderground.com/api/ff3e23e766c6adcf/geolookup/conditions/q/IA/Cedar_Rapids.json");
        $weatherObject = json_decode($json_string);
    }
     /**
     * @assert ("F") > 0
     * @assert ("C") > 0
     * @assert ("ASD") == ErrorException
     */
    function getTemperature($format)
    {
       
    }
    /**
     * @assert () > 0
     */
    function getHumidity()
    {
       
    }
        /**
     * @assert () > 0
     */
    function getPrecipitation()
    {
       
    }
    /**
     * @assert () > 0
     */
    function getWindSpeed()
    {
       
    }
    /**
     * @assert () > 0
     */
    function getWindDirection()
    {
       
    }
}
