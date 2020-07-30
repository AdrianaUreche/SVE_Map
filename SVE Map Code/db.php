<?php
// Initialize the session
ini_set('session.gc_maxlifetime', 86400);

session_set_cookie_params(86400);
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start([
		'cookie_lifetime' => 86400
	]);
}

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
	$teamname = htmlspecialchars($_SESSION["teamname"]);
	if(isset($_SESSION["abilityerror"])) {
		$alert = $_SESSION["abilityerror"];
		echo "<script>alert(\"",$alert,"\");</script>";

		unset($_SESSION["abilityerror"]);
	}
}

// Include config file
require_once "config.php";

if(isset($teamname)) {

	$sql = "SELECT teamid, player_names, email, impact_factor, action_points, action_points_used FROM teams WHERE teamname = '$teamname'";

	if($result = mysqli_query($link, $sql)){
		if(mysqli_num_rows($result) > 0){
			$row = mysqli_fetch_array($result);
			list($teamid, $player_names, $email, $impact, $action_points, $action_points_used) = $row;
		}
		// Free result set
		mysqli_free_result($result);
	}
}

function getabilities($link, $teamid) {

	$sql = "SELECT abilityid, abilityname, ability_fluff_text, ability_desc FROM abilities";

        if($result = mysqli_query($link, $sql)){
                if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_array($result)){
                                $abilid = $row['abilityid'];
                                list($abilid, $abilname[$abilid], $abilfluff[$abilid], $abildesc[$abilid]) = $row;
				$teamabil[$abilid] = FALSE;;

                        }
                }
                // Free result set
                mysqli_free_result($result);

	}

	$sql = "SELECT abilityid from abilityactive where teamid = '$teamid'";

        if($result = mysqli_query($link, $sql)){
                if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_array($result)){
                                $abilid = $row['abilityid'];
				$teamabil[$abilid] = TRUE;
			}
		}
		// Free result set
                mysqli_free_result($result);
	}

        return array($abilname, $abilfluff, $abildesc, $teamabil);
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
				$blockid = $row['blockid'];
				$blockname[$blockid] = $row['time']."&".$row['letter'];
				$blockgeom[$blockid] = $row['geometry'];
				$bsql = "SELECT blocksides.side, blocksides.teamid, teams.teamname FROM blocksides, teams WHERE blocksides.blockid = '$blockid' AND teams.teamid = blocksides.teamid";
				if($bresult = mysqli_query($link, $bsql)){
					if(mysqli_num_rows($bresult) > 0){
						while($brow = mysqli_fetch_array($bresult)){
							if($brow['side']==0){
								$blockownid[$blockid] = $brow['teamid'];
								$blockown[$blockid] = $brow['teamname'];
							} else {
								$blocknextid[$blockid] = $brow['teamid'];
								$blocknext[$blockid] = $brow['teamname'];
							}
							$teamids[$brow['teamid']] = $brow['teamname'];
						}

					} else {
						$blockownid[$blockid] = 0;
						$blockown[$blockid] = "Playa";
						$teamids[0] = "Playa";
					}
				}
				mysqli_free_result($bresult);
			}
		}
		// Free result set
		mysqli_free_result($result);
	}
	return array($blockname, $blockgeom, $blockown, $blockownid, $blocknext, $blocknextid, $teamids);
}

?>

