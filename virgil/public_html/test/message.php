<?php
//require_once('includes.php');

include_once 'includes.php';
//if the message is an input/recieve
if(isset($_POST['dataMsg']))
{
    $recieve_handler = new messageReceive();
    $recieve_handler->receive_message($_POST['dataMsg']);
}
//else if the message is a request/send
else if($_POST['update_ic_req'])
{
    $send_handler = new messageSend();
    $send_handler->compose_and_send_message($_POST['update_ic_req']);
}
//catch all
else
{
    trigger_error("Functionality Request not currently supported", E_USER_WARNING);
}




?>
