<?php
// Initialize the session
session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){

// Define variables and initialize with empty values
$teamname = htmlspecialchars($_SESSION["teamname"]);
}

// Include config file
require_once "config.php";

$sql = "SELECT teamid, player_names FROM teams WHERE teamname = '$teamname'";

if($result = mysqli_query($link, $sql)){
	if(mysqli_num_rows($result) > 0){
		$row = mysqli_fetch_array($result);
		$teamid = $row['teamid'];
		$player_names = $row['player_names'];
	}
	// Free result set
	mysqli_free_result($result);
}

//function getblocks() {

	$sql = "SELECT blockid, time, letter FROM blocks";

	if($result = mysqli_query($link, $sql)){
		if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_array($result)){
				$blockname[$row['blockid']] = $row['time']."&".$row['letter'];
			}
		}
		// Free result set
		mysqli_free_result($result);
	}
//	return $blockname;
//}

?>

<!DOCTYPE html>
<html>

<link rel="stylesheet" type="text/css" href="formatting_code/css/BRCMapCSS.css" />
<link rel="stylesheet" type="text/css" href="formatting_code/css/DropdownCSS.css" />
<link rel="stylesheet" type="text/css" href="formatting_code/css/MenuCSS.css" />

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>


	<ul class="customStyle">
		<li class="customStyle"><a href="#home">Map</a></li>
		<li class="customStyle"><a href="#instructions">Instructions</a></li>
<!-- 		<li class="customStyle"><a href="#abilities">Abilities</a></li>
		<li class="customStyle"><a href="#accomplishments">Accomplishments</a></li> -->
		<li class="customStyle"><a href="#adminContact">Admin Contact</a></li>
		<li class="dropdown">
			<a href="javascript:void(0)" class="dropbtn">Team Info</a>
			<div class="dropdown-content">
				<a href="#abilities">Abilities</a>
				<a href="#accomplishments">Accomplishments</a>
				<a href="#teamReg">Registration</a>
			</div>
		</li>
	</ul>

	</body></html>
