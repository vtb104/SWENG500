<html>
<head>
<style>
.back{
	display: inline-block;
	width: 1px;
	height: 1px;
}
</style>
</head>
<body style="position: absolute; width: 100%;">
<?php
$var = 20 ;
$step = 20;
for($x=0;$x<=$var;$x = $x + $step)
{
	for($y=0;$y<=$var;$y = $y + $step)
	{
		for($z=0;$z<=$var;$z = $y + $step)
		{
			echo "<div class='back' style='background-color: rgb(" . $x . "," . $y . "," . $z .")'></div>";
		}
	}
}

?>
</body>