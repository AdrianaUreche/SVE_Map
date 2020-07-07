<?php
// Initialize the session
session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){

// Define variables and initialize with empty values
$teamname = htmlspecialchars($_SESSION["teamname"]);
}

// Include config file
require_once "config.php";

$sql = "SELECT teamid, player_names, email, impact_factor, action_points, action_points_used FROM teams WHERE teamname = '$teamname'";

if($result = mysqli_query($link, $sql)){
	if(mysqli_num_rows($result) > 0){
		$row = mysqli_fetch_array($result);
		list($teamid, $player_names, $email, $impact, $action_points, $action_points_used) = $row;
	}
	// Free result set
	mysqli_free_result($result);
}

function getachievements($link) {

        $sql = "SELECT achid, name, description, flufftext, impact, max_num FROM achievements";

        if($result = mysqli_query($link, $sql)){
                if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_array($result)){
				$achid = $row['achid'];
				list($achid, $achname[$achid], $achdescription[$achid], $achfluff[$achid], $achimpact[$achid], $achmaxnum[$achid]) = $row;
                                $nsql = "SELECT teams.teamname, teams.teamid from achieved, teams where achieved.achid = '$achid' AND achieved.teamid = teams.teamid";
				if($achresult = mysqli_query($link, $nsql)){
		                        while($arow = mysqli_fetch_array($achresult)){
						$ateam = $arow['teamname'];
						list($achteam[$achid][$ateam], $achflag[$achid][$ateam]) = $arow;
					}

				}
				mysqli_free_result($achresult);

                        }
                }
                // Free result set
                mysqli_free_result($result);
        }
        return array($achname, $achdescription, $achfluff, $achimpact, $achmaxnum, $achteam, $achflag);
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

