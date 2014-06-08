<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of messageReceive
 *
 * @author Shane
 */
require_once 'database.php';
include_once 'messageClassTemplate.php';
class messageReceive extends messageClassTemplate{
    
    //put your code here
    public $last_received_msg;
     /**
     * @assert ('{"user":"2","lat":"42.116296999999996","lng":"-80.0362004"}') == true
     * @assert ('{"user":"","lat":"42.116296999999996","lng":"-80.0362004"}') == true
     * @assert ('{"user":"2","lat":"","lng":"-80.0362004"}') == true
     * @assert ('{"user":"2","lat":"42.116296999999996","lng":""}') == true
     * @expectedException ErrorException
     */
    function receive_message($message)
    {

       $this->last_received_msg = $message;
       $decoded_msg = $this->decode_json_message($message);
       //check format/data is valid
       if($decoded_msg->user != NULL && $decoded_msg->lat != NULL && $decoded_msg->lng != NULL)
       {
          //if valid format           
           echo $this->db->create_point($decoded_msg->user, $decoded_msg->lat, $decoded_msg->lng, '25', time(), 'From FU');
           return true;
       }
       //else throw exception
       else
       {
           //throw exception
           trigger_error("message input not valid", E_USER_ERROR);
           return false;
       }
    }
    
    function store_point($point_object)
    {
        
    }
}

//$handler = new messageReceive;
//$decode = $handler->decode_json_message($_POST['dataMsg']);

//echo var_dump($decode);

//echo $db->create_point($decode->user, $decode->lat, $decode->lng, '25', time(), 'From FU');

?>
