<?php
//Database object and interface

//Database variables

//define("_DB_NAME", 'sar');
//define("_DB_USERNAME", 'team6');
//define("_DB_PASSWORD", 'SWENG500');

//creates the object in order to open the database
//$db = new Database;


//Instantiate the object to access the database
class Database
{
	//Database object

        private $db_name = 'sar';
        private $db_user = 'team6';
        private $db_pwd = 'SWENG500';

	private $db_obj;

	//Constructor that opens database
	function __construct(){

		$this->db_obj = new MySQLi('localhost', $this->db_user, $this->db_pwd, $this->db_name) or die (mysqli_error() . "Database Error 1");

	}
	
	/**Inputs user data into User database
	 *
	 * @param CHAR 128 username (must be unique, or the insert will fail)
	 * @param CHAR 128 fname, lname, email
	 * @param CHAR role
	 * @return INT -> User's ID that was just created
	 */
	public function create_user($username, $fname, $lname, $email, $role, $password, $userKey){
		//Check for the username
		$check = $this->db_obj->query("SELECT * FROM Users WHERE username = '" . $username . "'");
		if($check->num_rows){
			return "Duplicate Entry";
		}
		$result = $this->db_obj->query("INSERT INTO Users (username, fname, lname, email, role, password, userKey) VALUES ('$username', '$fname', 'lname', '$email', '$role', '$password', '$userKey')");
		if($result){
			return $this->get_last_id();
		}else{
			return FALSE;
		}
	}

	/**Creates Points
	 *
	 * @param INT userID -> must match an existing user, or input will fail
	 * @param DOUBLE lat, lng
	 * @param INT alt
	 * @param INT dateCreated
	 * @param TEXT pointNotes
	 * @return INT -> New point's ID number in database after auto-increment
	 */
	
	public function create_point($userID, $lat, $lng, $alt, $dateCreated, $pointNotes){


            $query = 'INSERT INTO Points (userID, lat, lng, alt, dateCreated, pointNotes) VALUES ("' . $userID .'", "' . $lat . '", "' . $lng . '", "' . $alt . '", "' . $dateCreated . '", "' . $pointNotes . '")';
		$result = $this->db_obj->query($query) or trigger_error($this->db_obj->error);
		if($result){
			return $this->get_last_id();
		}else{
			return 'failed to create point in db';

		}
	}
	
	/**Returns Points
	 *
	 *@param int $start = time, defaults to return all points.  If Start is passed, function will return all points newer than the time passed.
	 *@return json array of all points 
	 */
	public function get_points($start = 0){
		$query = "SELECT * FROM Points WHERE dateCreated > " . $start;
		$result = $this->db_obj->query($query);
		return $this->return_json($result);
	}
	
	/**Returns the most recent point from a Team
	 *
	 *@param int $team = the team number
	 *@return json array of point
	 */
	
	public function latest_team_location($team){
		$query = "SELECT Points.* FROM Points JOIN Users ON Points.userID=Users.userID JOIN TeamMembers ON Users.userID=TeamMembers.userID WHERE teamID = '" . $team . "' ORDER BY Points.dateCreated DESC";
		$result = $this->db_obj->query($query);
		$result2 = $result->fetch_row();
		return json_encode($result2);
	}
	
	/***********************************************************************/
	/*Below this line are private functions that work on the public methods*/
	/***********************************************************************/
	
	
	//Returns the last ID number created for an auto-incremented input
	private function get_last_id(){
		$result = $this->db_obj->query("SELECT LAST_INSERT_ID();");
		$result2 = $result->fetch_row();
		return $result2[0];
	}
	
	//Takes a resulting MYSQL output and turns it into a JSON String
	private function return_json($input){
		$return = array();
		while($one = $input->fetch_array(MYSQLI_ASSOC)){
			array_push($return, $one);
		};
		return json_encode($return);
	}
	
	//This function prints all the results of a query.  Used for testing. 
	private function print_results($input){
		if ($input) {
			$rows = mysqli_num_rows($input);
			$cols = mysqli_num_fields($input);
			echo "Row count: " . $rows  . "<br/>";
			while($row = mysqli_fetch_array($input)) {
				
				for($i=0; $i < $cols; $i++){
					echo $row[$i] . " ";
				}
					"<br/>";
			}
		}else{
			echo "No results";	
		}	
				
	}
	
};


?>