<?php
//Database test page
require_once("phpcommon.php");

authenticate();

//$key = md5(_HASH1 . "hash" . _HASH2);
//$userID = $db->create_user('ryan' . time(), 'Ryan', 'Young', 'ryanessonyoung@gmail.com', 'admin', 'hello', $key);

//echo "UserID: " . $userID . "<br/>";

//$pointID = $db->create_point($userID, '36.22','128.35','400', time(), 'New point');
//echo "New Point ID: " . $pointID . "<br/>";




?>
<html>
<head>
	<title>Database Demo Page</title>
 <style>
 	body{font-family:Consolas, "Andale Mono", "Lucida Console", "Lucida Sans Typewriter", Monaco, "Courier New", monospace;};
	h2{color: red;}
	h3{color: gray;}
</style>
    
</head>
<body>
<h1>Database Demo and Test Page</h1> 
<a href="logout.php">Logout</a>
<p class="intro">
	In order to use these database calls, include "require_once("database.php");" at the top of the page.  This will create the "$db" object for the following methods.  All output are JSON arrays.
</p>
<p>
	<h2>Create a point</h2>
    <h3>Call: $db->create_point(2, '36.22','128.35','400', time(), 'demo');</h3>
    Point Created with ID: <?php echo $db->create_point('2', '36.22','128.35','400', time(), 'Demo Point');?>
 </p>
<p>
	<h2>Latest "Team 1" location (the point created above)</h2>
    <h3>Call: $db->latest_team_location(1);</h3>
    <h3>Return:</h3><?php echo $db->latest_team_location(1);?><br/>
</p>
<p>
	<h2>Database point dump of points made within the last hour</h2>
	<h3>Call:  $db->get_points(time() - 3600);</h3>
    <h3>Return:</h3> <?php echo $db->get_points(time() - 3600); ?><br/>
</p>
<p>
	<h2>List teams</h2>
	<h3>Call:  $db->list_teams($lat, $lng, $dist) (Optional coords for location listing within a distance) </h3>
    <h3>Return: </h3> <?php echo $db->list_teams(); ?><br/>
</p>
<p>
	<h2>List searches</h2>
	<h3>Call:  $db->list_searches($lat, $lng, $dist) (Optional coords for location within a distance) </h3>
    <h3>Return: </h3> <?php echo $db->list_teams(); ?><br/>
</p>
<p>
	<h2>List teams</h2>
	<h3>Call:  $db->list_teams($lat, $lng, $dist) (Optional coords for location listing within a distance) </h3>
    <h3>Return: </h3> <?php echo $db->list_teams(); ?><br/>
</p>
<p>
	<h2>Retrieve User Password</h2>
	<h3>Call:  $db->get_user_password('ryan')</h3>
    <h3>Return: </h3> <?php echo $db->get_user_password('ryan'); ?><br/>
</p>
<p>
	<h2>Delete a user</h2>
	<h3>Call:  $db->delete_user()</h3>
    <h3>Return: </h3> <?php echo $db->delete_user('0'); ?><br/>
</p>
<p>
	<h2>Update a user's info</h2>
	<h3>Call:  $db->update_user_info($userID, $dataname, $datavalue)</h3>
    <h3>Return: </h3> <?php echo $db->update_user_info(2, 'fname', 'ryan' . time()); ?><br/>
</p>
<p>
	<h2>User joins a search</h2>
	<h3>Call:  $db->user_join_search($searchID)</h3>
    <h3>Return: </h3> <?php echo $db->user_join_search(2, 2); ?><br/>
</p>
<p>
	<h2>User joins a team</h2>
	<h3>Call:  $db->user_join_team($userID)</h3>
    <h3>Return: </h3> <?php echo $db->user_join_team(2, 2); ?><br/>
</p>
<p>
	<h2>A team joins a search</h2>
	<h3>Call:  $db->team_join_search($searchID)</h3>
    <h3>Return: </h3> <?php echo $db->team_join_search(2, 2); ?><br/>
</p>
<p>
	<h2>User leaves a search</h2>
	<h3>Call:  $db->user_leave_search($userID, $searchID)</h3>
    <h3>Return: </h3> <?php echo $db->user_leave_search(2, 2); ?><br/>
</p>
<p>
	<h2>User leaves a team</h2>
	<h3>Call:  $db->user_leave_team($userID, $teamID)</h3>
    <h3>Return: </h3> <?php echo $db->user_leave_team(2, 2); ?><br/>
</p>
<p>
	<h2>Team leaves a search</h2>
	<h3>Call:  $db->team_leave_search($teamID, $searchID)</h3>
    <h3>Return: </h3> <?php echo $db->team_leave_search(2, 2); ?><br/>
</p>
<p>
	<h2>Team owner disbands a team</h2>
	<h3>Call:  $db->team_disband($userID, $teamID)</h3>
    <h3>Return: </h3> <?php echo $db->team_disband(2, 2); ?><br/>
</p>
<p>
	<h2>Create a message</h2>
	<h3>Call:  $db->create_message($from, $to, $title, $message, $pointID = 0)</h3>
    <h3>Return: </h3> <?php echo $db->create_message(2, 3, 'Hello', 'Where are you?', 50); ?><br/>
</p>
<p>
	<h2>Fetch Message</h2>
	<h3>Call:  $db->fetch_messages($userID = 0, $teamID = 0, $searchID = 0)</h3>
    <h3>Return: </h3> <?php echo $db->fetch_messages(2, 0, 0); ?><br/><h3>Return: </h3> <?php echo $db->fetch_messages(0, 2, 0); ?><br/><h3>Return: </h3> <?php echo $db->fetch_messages(0, 0, 2); ?><br/>
</p>
<p>
	<h2>Update team info</h2>
	<h3>Call:  $db->update_team_info($teamID, $update)</h3>
    <h3>Return: </h3> <?php echo $db->update_team_info(2, "We found the person!"); ?><br/>
<p>
	<h2>Fetch team info</h2>
	<h3>Call:  $db->fetch_team_info($teamID)</h3>
    <h3>Return: </h3> <?php echo $db->fetch_team_info(2); ?><br/>
</p>
</body>
</html>
 
	