<?php
date_default_timezone_set("UTC"); 
//Database test page
require_once("phpcommon.php");

/*if(!$auth->authenticate()){
	header("Good Auth");	
}*/
echo time() . "<br/>";
echo date("Y-m-d H:i", time()) . "<br/>";
echo date("Y-m-d H:i", 1407040200);

die();

$var = $db->get_points(2, $limit = 10200);
echo count($var);

//$db_obj = new MySQLi('localhost', _DB_USERNAME, _DB_PASSWORD, _DB_NAME) or die (mysqli_error() . "Database Error 1");
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


/*var_dump($db->list_team(1, false));


$user_id_array = array(4,3,2,5);
$user_location_array = array();
for($cnt = 0; $cnt < count($user_id_array); $cnt++)
{
	if($db->latest_user_location($user_id_array[$cnt]) != null)
	{
		array_push($user_location_array, $db->latest_user_location($user_id_array[$cnt]));
	}
}
echo date("c", $user_location_array[0][5]) . "<BR/>";
echo date("c", $user_location_array[1][5]) . "<BR/>";
echo date("c", $user_location_array[2][5]) . "<BR/>";
echo date("c", $user_location_array[3][5]) . "<BR/>";

var_dump($user_location_array);

die();
		
		
$username = "testname" . time();
$fname = "first";
$lname = "last";
$email = "email";
$password = "password";
$userKey = "userkey";
$role = "searcher";


$query = "INSERT INTO Users (username, fname, lname, email, password, userKey, role) VALUES ('$username', '$fname', '$lname', '$email', '$password', '$userKey', '$role')";
echo $query;
$result = $db_obj->query($query);
echo $result;

die();


$query = $db_obj->prepare('INSERT INTO Users (username, fname, lname, email, password, userKey, role) VALUES (?, ?, ?, ?, ?, ?, ?)');
$query->bind_param('sssssss', $username, $fname, $lname, $email, $password, $userKey, $role);
$query->execute();
$query->bind_result($result);
$query->fetch();
echo var_dump($result);

die();


$query = 'SELECT * FROM Users WHERE username = "ryan"';
$result = $db->query($query);
var_dump($result->fetch_row());


		
		

//First define input variables
/*
$id = 184; 
$days = 60;*/

die();
/*$query = "SELECT Points.pointID,Points.userID,Points.lat,Points.lng,Points.dateCreated,MAX(Points.dateCreated) FROM Points JOIN Users ON Points.userID=Users.userID JOIN TeamMembers ON Users.userID=TeamMembers.userID WHERE teamID = '1'";


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


*/
?>