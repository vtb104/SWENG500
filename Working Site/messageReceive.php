h
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

require_once('message.php');
require_once("database.php");
class messageReceive  extends message{
    //put your code here
    public $last_received_msg;
    function receive_message($message)
    {
       //set variable to undecoded message recieved 
       $this->last_received_msg = $message;
       
       
       // $this->last_received_msg = $this->decode_json_message($message);
       // echo "<script>console.log('".$message."')
    }
}

$handler = new messageReceive;
$decode = $handler->decode_json_message($_POST['dataMsg']);

//echo var_dump($decode);

echo $db->create_point($decode->user, $decode->lat, $decode->lng, '25', time(), 'From FU');

?>
