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
		
		/*$query = $this->db_obj->prepare('INSERT INTO Users (username, fname, lname, email, password, userKey, role) VALUES (?, ?, ?, ?, ?, ?, ?)');
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
	
	//Returns the user's data in an array
	public function get_user($userID, $returnJSON = true){
		$query = "SELECT username, fname, lname FROM Users WHERE userID = '$userID'";
		$result = $this->db_obj->query($query);
		
		if($result){
			return $this->return_array($result, $returnJSON);
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
		//$query = $this->db_obj->prepare('UPDATE Users SET password=? WHERE userID = ?)');
		//$query->bind_param('ss', $userID, $password);
		//$query->execute();
		//$query->bind_result($result);
		//$query->fetch();
		
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
	public function get_points($userID = 0, $start = 0, $teamID = 0, $searchID = 0, $returnJSON = true, $limit = 5000){
		if($userID){
			$query = "SELECT * FROM Points WHERE dateCreated > " . $start . " AND userID = " . $userID . " ORDER BY Points.dateCreated DESC LIMIT " . $limit;	
		}else{
			$query = "SELECT * FROM Points WHERE dateCreated > " . $start  . " ORDER BY Points.dateCreated DESC LIMIT " . $limit;
		}
		$result = $this->db_obj->query($query);
		return $this->return_array($result, $returnJSON);
	}
	
	/**Returns the most recent point from a Team
	 *
	 *@param int $team = the team number
	 *@return json array of point
	 */
	
	public function latest_team_location($team){
		$query = "SELECT Points.* FROM Points JOIN Users ON Points.userID=Users.userID JOIN TeamMembers ON Users.userID=TeamMembers.userID WHERE teamID = '" . $team . "' ORDER BY Points.dateCreated DESC LIMIT 1";
		$result = $this->db_obj->query($query);
		$result2 = $result->fetch_row();
		return json_encode($result2);
	}
	
	/** Get latest location for a user
	 *  @return a point in json format	
	*/
	public function latest_user_location($userID, $returnJSON = true){
		$query = "SELECT Points.* FROM Points WHERE userID = '".$userID."' ORDER BY dateCreated DESC LIMIT 1";
		$result = $this->db_obj->query($query);
		if($result){
			return $this->return_array($result, $returnJSON);
		}else{
			return false;	
		}
	}
	
	
	 /** List teams
	 *
	 *	@param $lat/$lng , default is 0, finds teams within a certain distance of the point
	 *	@param $lat/$lng/$dist, if all are default, returns a list of all teams
	 *	@reaturn json array of team numbers and names
	 */
	 public function list_teams($lat = 0, $lng = 0, $dist = 0){

		if(!$lat && !$lng && !$dist)
		{
			$query = "SELECT teamID, teamName FROM Teams";
			$result = $this->db_obj->query($query);
			return $this->return_array($result);
		}
		else
	 	{
			return 'Distance searching not enabled yet';
		}
		 
	 }
	 
	  /** Create a Search
	 *
	 *	@param $lat/$lng/$dist, default is 0, finds searches within a certain distance of the point
	 *	@param $lat/$lng/$dist, if all are default, returns a list of all teams
	 *	@reaturn new search number
	 */
	 public function create_search($userID, $searchName, $searchStart, $searchEnd, $searchInfo){
		$query = $this->db_obj->prepare('INSERT INTO Searches (owner, searchName, searchStart, searchEnd, searchInfo) VALUES (?, ?, ?, ?, ?)');
		$query->bind_param('sssss', $userID, $searchName, $searchStart, $searchEnd, $searchInfo);
		$result = $query->execute();
		if($result)
		{
			return $this->get_last_id();
		}
		else
		{
			return "Error ". __LINE__ .  " " . __FILE__;
		}
	 }
	 
	 /** List searches
	 *
	 *	@param $lat/$lng/$dist, default is 0, finds searches within a certain distance of the point
	 *	@param $lat/$lng/$dist, if all are default, returns a list of all teams
	 *	@reaturn json array of search names and numbers
	 */
	 public function list_searches($lat = 0, $lng = 0, $dist = 0){

		if(!$lat && !$lng && !$dist)
		{
			$query = "SELECT owner, searchID, searchName FROM Searches";
			$result = $this->db_obj->query($query);
			return $this->return_array($result);
		}
		else
	 	{
			return 'Distance searching not enabled yet';
		}
	 }
	 
	 /** List searching - Lists all users currently involved in a search
	 *
	 *	@param $searchID
	 *	@return json array
	 */
	 public function list_searching($searchID, $json = true){
		return $this->return_array($this->db_obj->query("SELECT userID FROM Searching WHERE searchID='$searchID'"), $json);
	 }
	 
	 
	 /** List users
	 *
	 *	@param $lat/$lng/$dist, default is 0, finds users within a certain distance.
	 *	@param $lat/$lng/$dist, if all are default, returns a list of all users
	 *	@reaturn json array of search names and numbers
	 */
	 public function list_users($lat = 0, $lng = 0, $dist = 0){
		
		if(!$lat && !$lng && !$dist)
		{
			$query = 'SELECT userID FROM Users';
			$result = $this->return_array($this->db_obj->query($query));
			if($result){
				return $result;	
			}else{
				return false;	
			}
		}
		else
	 	{
			return 'Distance searching is not enabled yet' . __LINE__;
		}
	 }

	 /** Returns a user's password
	 *
	 */
	 public function get_user_password($name){
		$query = $this->db_obj->prepare('SELECT password FROM Users WHERE username=? OR email=? OR userID=?');
		$query->bind_param('sss', $name, $name, $name);
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
		$query = $this->db_obj->prepare('SELECT userID FROM Users WHERE username=? OR email=?');
		$query->bind_param('ss', $usernameoremail, $usernameoremail);
		$query->execute();
		$query->bind_result($id);
		$query->fetch();
		return $id;
	 }
	
	/**Deletes a user
	 *
	 * 	@param $userID, must delete in certain order to ensure table rules are not violated
	 *	@return tru or false depending on 
	 */
	 public function delete_user($userID){
		$query1 = $this->db_obj->query("DELETE FROM Searching WHERE userID = '" . $userID . "'");
		$query2 = $this->db_obj->query("DELETE FROM TeamMembers WHERE userID = '" . $userID . "'");
		$query22 = $this->db_obj->query("DELETE FROM Teams WHERE owner = '" . $userID . "'");
		$query3 = $this->db_obj->query("DELETE FROM Points WHERE userID = '" . $userID . "'");
		$query4 = $this->db_obj->query("DELETE FROM Messages WHERE sentfrom = '" . $userID . "' OR sentto = '" . $userID . "'");
		$query5 = $this->db_obj->query("DELETE FROM Searches WHERE owner = '" . $userID . "'");
		$query6 = $this->db_obj->query("DELETE FROM Users WHERE userID = '" . $userID . "'");
		
		$returnArray = array($query1,$query2,$query22,$query3,$query4,$query5,$query6);
		
		return json_encode($returnArray);
		
	 }
	 
	 /**Modifies a user's data
	  *
	  */
	  
	  public function update_user_info($userID, $dataname, $datavalue){
		
		return '<span style="color: red">Fail ' . __LINE__ . '</span>';  
	  }
	  
	/** User joins search
	 *
	 *	@param $userID and $searchID are obvious, if they don't exists, will return false
	 *	@return true or false if successful or not
	 */
	 public function user_join_search($userID, $searchID){
		$result = $this->db_obj->query("INSERT INTO Searching (userID, searchID) VALUES ('$userID', '$searchID')");
		 return $result;
		 if($result)
		 {
			 return true;
		 }
		 else
		 {
			 return "Fail " . __LINE__ . " " . __FILE__;
		 }
	 }
	 
	 /** Create a Team
	 *
	 *	@param $userID - the owner of the team
	 *	@return - the new teamnumber
	 */
	 public function create_team($userID, $teamName, $teamAssignment, $teamInfo, $searchID = ''){
		$query = $this->db_obj->prepare('INSERT INTO Teams (teamName, teamAssignment, teamInfo, searchID, owner) VALUES (?, ?, ?, ?, ?)');
		$query->bind_param('sssss', $teamName, $teamAssignment, $teamInfo, $searchID, $userID);
		$result = $query->execute();
		
		if($result)
		{
			return $this->get_last_id();
		}
		else
		{
			return "Error ". __LINE__ .  " " . __FILE__;
		}
	 }
	 
	 /** Returns an array of all the team members
	 *
	 *	@param $teamID, 
	 *	@return - array of team member ID
	 */
	 public function list_team($teamID, $returnJSON = true){
		$query = "SELECT userID FROM TeamMembers WHERE teamID = '$teamID'";
		return $this->return_array($this->db_obj->query($query), $returnJSON);
	 }
	 
	 /** User joins a team
	 *
	 */
	 public function user_join_team($userID, $teamID){
		 
		 $result = $this->db_obj->query("INSERT INTO TeamMembers (userID, teamID) VALUES ('$userID', '$teamID')");
		 return $result;
		 if($result)
		 {
			 return true;
		 }
		 else
		 {
			 return "Fail " . __LINE__ . " " . __FILE__;
		 }
	 }
	 
	 /** Team joins a search
	 *
	 */
	 public function team_join_search($teamID, $searchID){
		 
		 $result = $this->db_obj->query("UPDATE Teams SET searchID = '$searchID' WHERE teamID = '$teamID'");
		 return $result;
		 if($result)
		 {
			 return true;
		 }
		 else
		 {
			 return "Fail " . __LINE__ . " " . __FILE__;
		 }
	 }
	 
	/** User leaves search
	 *
	 */
	 public function user_leave_search($userID, $searchID){
		 
		 return '<span style="color: red">Fail ' . __LINE__ . '</span>';
	 }
	 
	 /** User leaves a team
	 *
	 */
	 public function user_leave_team($userID, $teamID){
		 
		 return '<span style="color: red">Fail ' . __LINE__ . '</span>';
	 }
	 
	 /** Team leaves a search
	 *
	 */
	 public function team_leave_search($teamID, $searchID){
		 
		 return '<span style="color: red">Fail ' . __LINE__ . '</span>';
	 }
	 
	 /** Team owner disbands the team
	  *
	  */
	public function team_disband($userID, $teamID){
		
		return '<span style="color: red">Fail ' . __LINE__ . '</span>';
	}
	 
	 /** Create message
	 *
	 */
	 public function create_message($from, $to, $title, $message, $pointID = 0){
		 
		 return '<span style="color: red">Fail ' . __LINE__ . '</span>';
	 }
	 
	 /** Fetch messages for a user, team, or search
	 *
	 */
	 public function fetch_messages($userID = 0, $teamID = 0, $searchID = 0){
		 
		 return '<span style="color: red">Fail ' . __LINE__ . '</span>';
	 }
	 
	 
	 /** Update team notes
	 *
	 */
	 public function update_team_info($teamID, $notes){
		 
		 return '<span style="color: red">Fail ' . __LINE__ . '</span>';
	 }

	 /** View team notes
	 *
	 */
	 public function fetch_team_info($teamID){
		 
		 return '<span style="color: red">Fail ' . __LINE__ . '</span>';
	 }
	/** get areas for a given search id 
          * 
          */
         public function list_areas($searchID)
         {
             $query = 'SELECT DISTINCT areaName FROM areas';
		$result = $this->return_array($this->db_obj->query($query));
		if($result){
			return $result;	
		}else{
			return false;	
		}
         }
	/** get points for a given area 
          * 
          */
         public function list_points_in_area($areaName)
         {
            $query = "SELECT areaName,lat,lng FROM areas WHERE areaName='".$areaName."'";
            $result = $this->return_array($this->db_obj->query($query));
            if($result){
                    return $result;	
            }else{
                    return false;	
            }
         }
         /** create a new area given a name and a set of points
          * 
          */
         public function create_area($areaName, $inlat, $inlng)
         {
             $query = 'INSERT INTO areas (areaName, lat, lng) VALUES ("' . $areaName . '","'.$inlat.'","'.$inlng.'")';
		$result = $this->db_obj->query($query);
		if($result){
			return TRUE;
		}else{
			return FALSE;
		}
         }
         /** create a new area given a name and a set of points
          * 
          */
         public function delete_area($areaName)
         {
             $query = 'DELETE FROM areas WHERE areaName="'.$areaName.'"';
		$result = $this->db_obj->query($query);
		if($result){
			return TRUE;
		}else{
			return FALSE;
		}
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
	
	/**Takes a resulting MYSQL output and turns it into a JSON String
	 * Default is return a JSON string
	 */
	private function return_array($input, $json = true){
		$return = array();
		while($one = $input->fetch_array(MYSQLI_ASSOC)){
			array_push($return, $one);
		};
		
		if($json){
			return json_encode($return);
		}else{
			return $return;
		}
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