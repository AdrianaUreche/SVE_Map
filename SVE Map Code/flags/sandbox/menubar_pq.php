	<ul class="customStyle">
		<li class="customStyle"><a href="BRCMap_index.php">BRC Map</a></li>
		<li class="customStyle"><a href="instructions.php">Game Board Rules</a></li>
        <li class="customStyle"><a href="abilities.php">Abilities</a></li>
		<li class="customStyle"><a href="achievements.php">Achievements</a></li>
		<li class="customStyle"><a href="teams.php">Team Portal  (<font color="red"><b>Submit Puzzle/Quest Answer HERE!</b></font>)</a></li>

		<li style="float: right;"><a><?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {echo "<i>Action Points</i> (<i>Turns Remaining</i>): ", $action_points;} else {echo "<i>Action Points</i> (<i>Turns Remaining</i>): N/A";}?></a></li>		
		<li style="float: right;"><a><?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {echo "<i>Impact Factor</i>: ", $impact;} else {echo "<i>Impact Factor</i>: N/A";}?></a></li>	
		<li style="float: right;"><a><?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {echo "<i>Team</i>: ", $teamname;} else {echo "<i>Team</i>: N/A";}?></a></li>
	</ul>

<!--		<li class="customStyle"><a href="register.php">Registration</a></li>
        <li class="customStyle"><a href="#adminContact">Contact Us</a></li>
-->
<!--		<li class="dropdown">
			<a class="dropbtn">Game Information</a>
			<div class="dropdown-content">
				<a href="abilities.php">Abilities</a>
				<a href="achievements.php">Accomplishments</a>
				<a href="register.php">Registration</a>
			</div>
		</li>
-->


