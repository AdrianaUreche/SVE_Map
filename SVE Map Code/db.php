<?php

$rootdir = "";  // CHANGE THIS SUNDAY!

// Initialize the session
if (session_status() !== PHP_SESSION_ACTIVE) {
	ini_set('session.gc_maxlifetime', 86400);
	session_start([
		'cookie_lifetime' => 86400
	]);
	session_set_cookie_params(86400);

}

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
	$teamname = $_SESSION["teamname"];
        if(isset($_SESSION["achievements"])) {
		$achtext .= $_SESSION["achievements"];
//                echo "<script>alert(\"",$achtext,"\");</script>"; 
                unset($_SESSION["achievements"]);
	}
	if(isset($_SESSION["abilityerror"])) {
		$alert = $_SESSION["abilityerror"];
//		echo "<script>alert(\"",$alert,"\");</script>";
		unset($_SESSION["abilityerror"]);
	}
}

// Include config file
require_once "/home/nukees/sve_private/config.php";

if(isset($teamname)) {

	$sql = "SELECT teamid, player_names, email, impact_factor, action_points, action_points_used FROM teams WHERE teamname = '$teamname'";

	if($result = mysqli_query($link, $sql)){
		if(mysqli_num_rows($result) > 0){
			$row = mysqli_fetch_array($result);
			list($teamid, $player_names, $email, $impact, $action_points, $action_points_used) = $row;
			$player_names = htmlspecialchars($player_names);
		}
		// Free result set
		mysqli_free_result($result);
	}
}
$teamname = htmlspecialchars($teamname);

function error($msg, $loc = "BRCMap_index.php") {
	$_SESSION['abilityerror'] = $msg;
	header("Location: ".$loc."?error=".$msg);
	exit;
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

        $sql = "SELECT achid, name, description, flufftext, impact, max_num FROM achievements ORDER BY achid";

        if($result = mysqli_query($link, $sql)){
                if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_array($result)){
				$achid = $row['achid'];
				list($achid, $achname[$achid], $achdescription[$achid], $achfluff[$achid], $achimpact[$achid], $achmaxnum[$achid]) = $row;
                                $nsql = "SELECT teams.teamname, teams.teamid from achieved, teams where achieved.achid = '$achid' AND achieved.teamid = teams.teamid";
				if($achresult = mysqli_query($link, $nsql)){
		                        while($arow = mysqli_fetch_array($achresult)){
						$achteam[$achid][$arow['teamid']] = $arow['teamname'];
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

	$sql = "SELECT blockid, time, letter, geometry, num_sides, value FROM blocks";

	if($result = mysqli_query($link, $sql)){
		if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_array($result)){
				$blockid = $row['blockid'];
				$blockname[$blockid] = $row['time']."&".$row['letter'];
				$blockgeom[$blockid] = $row['geometry'];
				$blockvalue[$blockid] = $row['value'];
				$num_sides = $row['num_sides'];
				$bsql = "SELECT blocksides.side, blocksides.teamid, teams.teamname FROM blocksides, teams WHERE blocksides.blockid = '$blockid' AND teams.teamid = blocksides.teamid ORDER BY blocksides.side";
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
				if($num_sides<=1) $blocknextid[$blockid] = -2;
				mysqli_free_result($bresult);
			}
		}
		// Free result set
		mysqli_free_result($result);
	}
	return array($blockname, $blockgeom, $blockown, $blockownid, $blocknext, $blocknextid, $blockvalue, $teamids);
}

function getblockgrid($link) {
                
        $sql = "SELECT blockid, begin_row, end_row, begin_sec, end_sec FROM blocks";

        if($result = mysqli_query($link, $sql)){
                if(mysqli_num_rows($result) > 0){
                       while(list($bid, $br, $er, $bs, $es) = mysqli_fetch_array($result)) {
                               $brs[$bid] = $br;
                               $bss[$bid] = $bs;
                               for($i=$br;$i<=$er;$i++)
                                for($j=$bs;$j<=$es;$j++)
                                        $bgrid[$i][$j]=$bid;
                       }
               } else {
                       error ("NO RESULTS. Please report this to the admins.");
               }
        } else {
                error("DATABASE ERROR. Please report this to the admins.");
        }
        mysqli_free_result($result);

	return array($brs,$bss,$bgrid);

}

function getpuzzlesforteam($link, $teamid)
{
	// SELECT name, difficulty, status
	// FROM `puzzlesolved`, puzzles
	// WHERE (puzzles.pid = puzzlesolved.pid) AND (puzzlesolved.teamid = 5)

	$sql = "SELECT puzzles.pid, name, difficulty, status, url, released FROM puzzlesolved, puzzles WHERE (puzzles.pid = puzzlesolved.pid) AND (puzzlesolved.teamid = '$teamid') ORDER BY puzzles.pid";

	$num_results = 0;
	$returnpuzzles = null;
	if($result = mysqli_query($link, $sql))
	{
		if(mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_array($result))
			{
				$returnpuzzles[$num_results]['id'] = $row['pid'];
				$returnpuzzles[$num_results]['name'] = $row['name'];
				$returnpuzzles[$num_results]['difficulty'] = $row['difficulty'];
				$returnpuzzles[$num_results]['status'] = $row['status'];
				$returnpuzzles[$num_results]['url'] = $row['url'];
				$returnpuzzles[$num_results]['released'] = $row['released'];

				$num_results++;
			}
		}
		// Free result set
		mysqli_free_result($result);
	}

	return $returnpuzzles;
}

function getquestsforteam($link, $teamid)
{
	// SELECT name, difficulty, status
	// FROM `questsolved`, quests
	// WHERE (quests.qid = questsolved.qid) AND (questsolved.teamid = 5)

	$sql = "SELECT quests.qid, name, difficulty, status, url, released FROM questsolved, quests WHERE (quests.qid = questsolved.qid) AND (questsolved.teamid = '$teamid') ORDER BY quests.qid";

	$num_results = 0;
	$returnquests = null;
	if($result = mysqli_query($link, $sql))
	{
		if(mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_array($result))
			{
				$returnquests[$num_results]['id'] = $row['qid'];
				$returnquests[$num_results]['name'] = $row['name'];
				$returnquests[$num_results]['difficulty'] = $row['difficulty'];
				$returnquests[$num_results]['status'] = $row['status'];
				$returnquests[$num_results]['url'] = $row['url'];
				$returnquests[$num_results]['released'] = $row['released'];
				$num_results++;
			}
		}
		// Free result set
		mysqli_free_result($result);
	}
	return $returnquests;
}


?>

