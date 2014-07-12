<?php
require_once('phpcommon.php');

//We'll need to make this better, but it is a placeholder for now.


?>
<html>
<head>
<title>Contact Page</title>
<?php echo _jQuery;?>
</head>

<body>
<!-- Contact Page -->
<div data-role="page" id="contactpage">
	<div data-role="header">
    <a href="#" data-rel="back" data-icon="arrow-l">Back</a>
    	<h1>Contact</h1>
    </div>
    <div data-role="content">
    <div id="info" style="color: red"></div>
    <form method="post" action="contact.php">
    	<p>Please fill out the information below if you found an error. This form doesn't actually do anything...but, it will.</p>
		<label>Name:</label>
        <input type="text" name="inputname"/>
        Request/Feedback:
        <textarea id="inputtext" name="inputtext"></textarea>
        <button type="submit" name="submit">Submit</button>
     </form>
        <a href="#" data-rel="back" data-role="button">Cancel</a>
	</div>
</div>

</body>