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

function getblocks($link) {

	$sql = "SELECT blockid, time, letter, geometry FROM blocks";

	if($result = mysqli_query($link, $sql)){
		if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_array($result)){
				$blockname[$row['blockid']] = $row['time']."&".$row['letter'];
				$blockgeom[$row['blockid']] = $row['geometry'];
			}
		}
		// Free result set
		mysqli_free_result($result);
	}
	return array($blockname, $blockgeom);
}

?>

