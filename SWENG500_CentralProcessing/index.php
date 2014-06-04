<?php
include_once 'includes.php';
session_start();
$_SESSION["test"] = "321456";



// put your code here
if(!isset($_SESSION["msgRecv"]))
{
    $_SESSION["msgRecv"] = new messageReceive();
}
$messageReceiver = $_SESSION["msgRecv"];
 $messageReceiver->debug_message("sup dog");
 if(isset($_POST['dataMsg']))
 {
    $some_msg = $_POST['dataMsg'];
    $messageReceiver->receive_message($some_msg);
    
    
    echo "Team: ".$messageReceiver->last_received_msg;
 }
if(isset($_POST['update_ic_req']))
 {
     echo "some IC data:".$messageReceiver->last_received_msg;
 }


?>