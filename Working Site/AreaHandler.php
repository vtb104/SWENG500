<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('message.php');
require_once('phpcommon.php');
/**
 * Description of AreaHandler
 *
 * @author Shane
 */
if(isset($_POST['createArea'])){
        $handler = new message();
	$decodedMessage = $handler->decode_json_message($_POST['createArea']);
        $pointsArray = $decodedMessage->points;
        for($cnt =0; $cnt < count($pointsArray); $cnt++)
        {
            $db->create_area($decodedMessage->name, $pointsArray[$cnt]->k, $pointsArray[$cnt]->B);
        }
};
if(isset($_POST['searchID'])){
        echo $db->list_areas($_POST['searchID']);
};
if(isset($_POST['getAreaPoints'])){
        echo $db->list_points_in_area($_POST['getAreaPoints']);
};
if(isset($_POST['deleteArea'])){
        echo $db->delete_area($_POST['deleteArea']);
};
?>
