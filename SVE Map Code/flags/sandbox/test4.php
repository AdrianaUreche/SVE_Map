<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="formatting_code/css/MenuCSS.css" />
</head>
<body>
	<ul class="customStyle">
		<li class="customStyle"><a href="BRCMap_index.php">BRC Map</a></li>
<!--                <li class="dropdown"><a href="javascript:void(0)" class="dropbtn">Game Info</a>
                  <div class="dropdown-content">
		    <a href="instructions.php">How to Play</a>
                    <a href="leaderboard.php">Leaderboard</a>
    		  </div>
		</li>
-->
        <li class="customStyle"><a href="instructions.php">Game Rules</a></li>
        <li class="customStyle"><a href="abilities.php">Abilities</a></li>
		<li class="customStyle"><a href="achievementcheck.php?l=achievements.php">Achievements</a></li>
	<!--	<li class="customStyle"><a href="teams.php">Team Portal</a></li>  //-->
		<li style="float: right;">
<?php
if(isset($teamid)) {
	echo "<a href=\"teams.php\"><i>Action Points</i> (<i>Turns Left</i>): ", $action_points."</a
>";
echo "<li style=\"float: right;\"><a href=\"teams.php\"><i>Impact Factor</i>: ", $impact."</a></li>"
;	
		echo "<li style=\"float: right;\"><a href=\"teams.php\"><i>Team</i>: ", $teamname."<
/a></li>";
} else {
	echo "<a href=\"login.php\">Login</a>";
}
?>
	</ul>
