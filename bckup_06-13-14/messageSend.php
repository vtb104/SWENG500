<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of messageSend
 *
 * @author Shane
 */
 
require_once("database.php");
//require_once("message.php");

/*class messageSend extends message{
    //put your code here
}
*/
//$handler = new messageSend;
echo $db->latest_team_location(1);

?>
