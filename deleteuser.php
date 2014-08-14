<?php
//Database test page
require_once("phpcommon.php");

if(isset($_GET['userID'])){
	$userID = $_GET['userID'];
	if($userID !== '2' && $userID !== '3' && $userID !== 4 && $userID !== 5){
		echo $db->delete_user($userID);
	}else{
		echo "Nope";
	}
}

?>
<form method="get">
<input id='userID' name='userID' type="number"/>
<button type="submit">Go</button>
</form>