<?php
//Database object and interface

//Database variables
define("_DB_NAME", 'sar');
define("_DB_USERNAME", 'team6');
define("_DB_PASSWORD", 'SWENG500');

//Instantiate the object to access the database
class Database
{
	//Database object
	private $db_obj;

	//Constructor that opens database
	function __construct(){
		$this->db_obj = new MySQLi('localhost', _DB_USERNAME, _DB_PASSWORD, _DB_NAME) or die (mysqli_error() . "Database Error 1");
	}
	
	/**Inputs user data into User database
	 *
	 * @param CHAR 128 username (must be unique, or the insert will fail)
	 * @param CHAR 128 fname, lname, email
	 * @param CHAR role
	 * @return INT -> User's ID that was just created
	 */
	public function create_user($username, $fname, $lname, $email, $password, $userKey, $role = 'searcher'){
		
		//Below is working...but there is a binding failure that I can't figure out right now...
		
/*		$query = $this->db_obj->prepare('INSERT INTO Users (username, fname, lname, email, password, userKey, role) VALUES (?, ?, ?, ?, ?, ?, ?)');
		$query->bind_param('sssssss', $username, $fname, $lname, $email, $password, $userKey, $role);
		$query->execute();
		$query->bind_result($result);
		$query->fetch();*/
		
		//This is the insecure code:
		$query = "INSERT INTO Users (username, fname, lname, email, password, userKey, role) VALUES ('$username', '$fname', '$lname', '$email', '$password', '$userKey', '$role')";
		$result = $this->db_obj->query($query);

		if($result){
			return $this->get_last_id();
		}else{
			return FALSE;
		}
	}
	
	//Checks for the existence of a username for the registration page
	public function check_username($username){
		$query = $this->db_obj->prepare('SELECT userId FROM Users WHERE username = ?');
		$query->bind_param('s', $username);
		$query->execute();
		$query->bind_result($userID);
		$query->fetch();
		
		if($userID){
			return true;
		}else{
			return false;	
		}
		
	}
	
	//Returns the userID for an e-mail address, used for checking if one exists, and for forgotten e-mails.
	public function check_email($email){

		$query = $this->db_obj->prepare('SELECT userId FROM Users WHERE email = ?');
		$query->bind_param('s', $email);
		$query->execute();
		$query->bind_result($userID);
		$query->fetch();
	
		return $userID;
	}
	
	//Returns the user's key for checking
	public function check_userKey($userId){

		$query = $this->db_obj->prepare('SELECT userKey FROM Users WHERE userId = ?');
		$query->bind_param('s', $userId);
		$query->execute();
		$query->bind_result($userKey);
		$query->fetch();
	
		return $userKey;
	}


	public function update_userKey($userID, $userKey){
		$query = $this->db_obj->prepare('INSERT INTO Users (userKey) VALUES (?) WHERE userId = ?');
		$query->bind_param('ss', $userID, $userKey);
		$query->execute();
		$query->bind_result($result);
		$query->fetch();
		
		if($result){
			return true;
		}else{
			return false;	
		}
		
	}
	
	//Changes a user's password
	public function change_password($userID, $password){
/*		$query = $this->db_obj->prepare('UPDATE Users SET password=? WHERE userID = ?)');
		$query->bind_param('ss', $userID, $password);
		$query->execute();
		$query->bind_result($result);
		$query->fetch();*/
		
		$query = "UPDATE Users SET password='$password' WHERE userID = '$userID'";
		$result = $this->db_obj->query($query);
		
		if($result){
			return true;
		}else{
			return false;	
		}
		
	}

	//This function runs to set a user's verify variable to either true or false for the first time authentication
	//$verity = boolean
	public function user_verify($userID, $verify){
		$query = "UPDATE Users SET verify=1 WHERE userId = '$userID'";
		$result = $this->db_obj->query($query);
		
		if($result){
			return true;
		}else{
			return false;	
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
		$result = $this->db_obj->query($query);
		if($result){
			return $this->get_last_id();
		}else{
			return FALSE;
		}
	}
	
	/**Returns Points
	 *
	 * @param OPTIONAL int $start = time, defaults to return all points.  If Start is passed, function will return all points newer than the time passed.
	 * @param OPTIONAL int $userID = the user ID for the points to return.
	 *@return json array of all points 
	 */
	public function get_points($start = 0, $userID = 0){
		if($userID){
			$query = "SELECT * FROM Points WHERE dateCreated > " . $start . " AND userID = " . $userID . " ORDER BY Points.dateCreated DESC";	
		}else{
			$query = "SELECT * FROM Points WHERE dateCreated > " . $start  . " ORDER BY Points.dateCreated DESC";
		}
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
	
	
	 /** List teams
	 *
	 */
	 public function list_teams($lat = 0, $lng = 0, $dist = 0){

		 return 'Fail';
	 }
	 
	 /** List searches
	 *
	 */
	 public function list_searches($lat = 0, $lng = 0, $dist = 0){

		 return 'Fail';
	 }
	 
	 /** List users
	 *
	 */
	 public function list_users($lat = 0, $lng = 0, $dist = 0){

		 return 'Fail';
	 }

	 /** Returns a user's password
	 *
	 */
	 public function get_user_password($name){
		$query = $this->db_obj->prepare('SELECT password FROM Users WHERE username=? OR email=?');
		$query->bind_param('ss', $name, $name);
		$query->execute();
		$query->bind_result($password);
		$query->fetch();
		return $password;
	 }
	 
	 /** Returns a user's username from their 
	 *
	 */
	 public function get_user_id($usernameoremail){
		//Needs to use either username or e-mail to log someone in
		 return 'Fail';
	 }
	
	/**Deletes a user
	 *
	 */
	 public function delete_user($userID){
		
		return 'Fail'; 
	 }
	 
	 /**Modifies a user's data
	  *
	  */
	  
	  public function update_user_info($userID, $dataname, $datavalue){
		
		return 'Fail';  
	  }
	  
	/** User joins search
	 *
	 */
	 public function user_join_search($userID, $searchID){
		 
		 return 'Fail';
	 }
	 
	 /** User joins a team
	 *
	 */
	 public function user_join_team($userID, $teamID){
		 
		 return 'Fail';
	 }
	 
	 /** Team joins a search
	 *
	 */
	 public function team_join_search($teamID, $searchID){
		 
		 return 'Fail';
	 }
	 
	/** User leaves search
	 *
	 */
	 public function user_leave_search($userID, $searchID){
		 
		 return 'Fail';
	 }
	 
	 /** User leaves a team
	 *
	 */
	 public function user_leave_team($userID, $teamID){
		 
		 return 'Fail';
	 }
	 
	 /** Team leaves a search
	 *
	 */
	 public function team_leave_search($teamID, $searchID){
		 
		 return 'Fail';
	 }
	 
	 /** Team owner disbands the team
	  *
	  */
	public function team_disband($userID, $teamID){
		
		return 'Fail';
	}
	 
	 /** Create message
	 *
	 */
	 public function create_message($from, $to, $title, $message, $pointID = 0){
		 
		 return 'Fail';
	 }
	 
	 /** Fetch messages for a user, team, or search
	 *
	 */
	 public function fetch_messages($userID = 0, $teamID = 0, $searchID = 0){
		 
		 return 'Fail';
	 }
	 
	 
	 /** Update team notes
	 *
	 */
	 public function update_team_info($teamID, $notes){
		 
		 return 'Fail';
	 }

	 /** View team notes
	 *
	 */
	 public function fetch_team_info($teamID){
		 
		 return 'Fail';
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