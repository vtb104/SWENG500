<?php
//Test file to build queries
require_once("database.php");
require_once("vars.php");

$db_obj = new MySQLi('localhost', _DB_USERNAME, _DB_PASSWORD, _DB_NAME) or die (mysqli_error() . "Database Error 1");
/*$query = 'INSERT INTO Users (username, fname, lname, email, role, password, userKey) VALUES ("ryan", "Ryan", "Young", "ryanessonyoung@gmail.com", "admin", "hello", "thisisakey")';
echo $query . "<br><br/>";
//$query = 'INSERT INTO Points (userID, lat, lng, alt, dateCreated, pointNotes) VALUES ("2", "2", "3", "4", "222", "5")';
//$query = "SELECT * FROM Users";
$result = $db_obj->query($query);
if($result){
	echo var_dump($result);
}else{
	echo "Error";
}*/
		

//First define input variables
/*
$id = 184; 
$days = 60;*/

$query = "SELECT Points.pointID,Points.userID,Points.lat,Points.lng,Points.dateCreated,MAX(Points.dateCreated) FROM Points JOIN Users ON Points.userID=Users.userID JOIN TeamMembers ON Users.userID=TeamMembers.userID WHERE teamID = '1'";


echo "Query:<br/>" . $query . "<br/>";
$result = $db_obj->query($query);
if ($result) {
	$rows = mysqli_num_rows($result);
	$cols = mysqli_num_fields($result);
	echo "Row count: " . $rows  . "<br/>";
	while($row = mysqli_fetch_array($result)) {
		
		for($i=0; $i < $cols; $i++){
			echo $row[$i] . " ";
		}
			"<br/>";
	}
}else{
	echo "No results";	
}



?>