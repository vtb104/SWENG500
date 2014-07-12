<?php
//Cleans the database of test points
require_once("database.php");
require_once("vars.php");

$db = new MySQLi('localhost', _DB_USERNAME, _DB_PASSWORD, _DB_NAME) or die (mysqli_error() . "Database Error 1");
$query = "DELETE FROM Points WHERE pointNotes = 'Demo Point'";
echo $db->query($query);

$db = new MySQLi('localhost', _DB_USERNAME, _DB_PASSWORD, _DB_NAME) or die (mysqli_error() . "Database Error 1");
$query = "DELETE FROM Points WHERE pointNotes = 'From FU'";
echo $db->query($query);


?>
