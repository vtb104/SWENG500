<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of messageClassTemplate
 *
 * @author Shane
 */
class messageClassTemplate {
    public $db;
    function __construct()
    {
        $this->db = new Database();
    }
    //functions that will be used in both message types
    function decode_json_message($message2)
    {
        return json_decode($message2); 
    }
    function encode_json_message($message2)
    {
        
    }
    /** this is for phpunit
    * @assert ('hi there') == 'hi there'
    * @assert ('hi there') == 'go away'
    * @assert ('hi') == 'hello'
    */
    function debug_message($message2)
    {
       return $message2;
    }
}
?>