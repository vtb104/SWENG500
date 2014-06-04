<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of message
 *
 * @author Shane
 */

include_once 'includes.php';
class message {
    //functions that will be used in both message types
    function decode_json_message($message)
    {
        return json_decode($message); 
    }
    function encode_json_message($message)
    {
        
    }
    function debug_message($message)
    {
       // echo "<script>console.log('Debug msg: ".$message."')</script>";
    }
}

?>
