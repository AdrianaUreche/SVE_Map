<?php include("db.php");?>  <!-- Login Session and database functions -->
<?php 
echo "Everywhere";
exit;

list($achname, $achdescription, $achfluff, $achimpact, $achmaxnum, $achteam)=getachievements($link); 
list($blockname,$blockgeom,$blockown,$blockownid,$blocknext,$blocknextid,$blockvalue,$teamids)=getblocks($link);
echo "There";
exit;

function achieve($link, $aid, $teamid, $newimp) {
//	echo $teamid," ACHIEVED ACHIEVEMENT #",$aid,"<br>";
	$sql = "UPDATE teams SET impact_factor = ".$newimp." WHERE teamid = ".$teamid;
	if (!mysqli_query($link, $sql)) {
		error("Error1 updating record: " . mysqli_error($link));
	}

	$sql = "INSERT INTO achieved (achid, teamid) VALUES (".$aid.",".$teamid.")";
	if (!mysqli_query($link, $sql)) {
		error("Error2 updating record: " . mysqli_error($link));
        }

	return true;
}

function addpending($link, $aid, $teamid) {

        $sql = "INSERT INTO achieved (achid, teamid, pending) VALUES (".$aid.",".$teamid.", 1)";
        if (!mysqli_query($link, $sql)) {
                error("Error3 updating record: " . mysqli_error($link));
        }

        return true;
}

function checkpending($link, $aid, $teamid, $newimpact, $aname) {
	$sql = "SELECT pending FROM achieved WHERE pending = TRUE AND teamid = ".$teamid." AND achid = ".$aid;
	if($result = mysqli_query($link, $sql)){
		if(mysqli_num_rows($result)==1) {
			$psql = "DELETE FROM achieved WHERE pending = TRUE AND teamid = ".$teamid." AND achid = ".$aid;
			if (!mysqli_query($link, $psql)) {
				error("Error4 updating record: " . mysqli_error($link));
			}
			mysqli_free_result($result);
			achieve($link,$aid,$teamid,$newimpact);

			return $newimpact;
		}
	}
	mysqli_free_result($result);
	return FALSE;
}

echo "Here";
exit;

if(isset($teamid)) {
$activequests = getquestsforteam($link, $teamid);
$activepuzzles = getpuzzlesforteam($link, $teamid);

if(isset($achtext) && $achtext != "") $carryoverachtext = $achtext;

$sql = "SELECT achid,blockid FROM achievementblocks";

if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_array($result)) {
                        $achblocks[$row['achid']][] = $row['blockid'];
                }
        }
        mysqli_free_result($result);
}

$sql = "SELECT achid FROM achieved WHERE teamid = ".$teamid." AND pending = TRUE";

if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_array($result)) {
                        $pending[$row['achid']] = TRUE;
                }
        }
        mysqli_free_result($result);
}

$origimpact=$impact;
// if(in_array($teamid,$blockownid)) $achblocks[0][0] = array_search($teamid,$blockownid);  // I think this is my achievement #0 hack?
$achtext = "";

foreach ($achname as $aid => $aname) {
	echo "<br>",$aid," -- ",$aname," -- ",sizeof($achteam[$aid])," -- ",$achmaxnum[$aid],"<br>";
	if(($achmaxnum[$aid]>sizeof($achteam[$aid]) && !isset($achteam[$aid][$teamid])) || $pending[$aid]) {
        echo "<br>",$aid," -- ",$aname," -- ",sizeof($achteam[$aid])," -- ",$achmaxnum[$aid],"<br>";

		switch(true) {
		case ($aid == 0):
			if(in_array($teamid,$blockownid)) {
				$impact += $achimpact[$aid];
				achieve($link,$aid,$teamid,$impact);
                                $achtext .= "\n".$aname;
			}
			break;
		case ($aid >= 1 && $aid <= 9):  // Admin-assigned achievements
                        if($newimpact = checkpending($link, $aid, $teamid, ($impact+$achimpact[$aid]), $aname)) {
                                $achtext .= "\n".$aname;
                                $impact = $newimpact;
                        }
			break;
		case ($aid >= 10 && $aid <=39):
//			echo "<br>ACHID: ",$aid,"; BLOCKS: ";
			$by = 0;
			foreach($achblocks[$aid] as $bid) {
				if($blockownid[$bid]==$teamid)$by++;
//				echo $bid.": ".$blockownid[$bid],", TID: ",$teamid,"<br>";
			}
//			echo "BY: ",$by," (out of ",sizeof($achblocks[$aid]),")<br>";
			if(sizeof($achblocks[$aid])==$by) {
				$impact += $achimpact[$aid];
				achieve($link,$aid,$teamid,$impact);
				$achtext .= "\n".$aname;
			}
			break;
		case ($aid == 40):
			if($newimpact = checkpending($link, $aid, $teamid, ($impact+$achimpact[$aid]), $aname)) {
				$achtext .= "\n".$aname;
				$impact = $newimpact;
				break;
			}


			$missingrows = 3;   // Hack for now.  Probably forever.
			list($brs,$bss,$bgrid) = getblockgrid($link);
//			echo "SO: ",sizeof($bgrid).", ",sizeof($bgrid[0]),"<br>";
			for($i=0;$i<sizeof($bgrid)+$missingrows;$i++) {
				for($j=0;$j<sizeof($bgrid[0]);$j++) {
					$bid = $bgrid[$i][$j];
					$tid = $blockownid[$bid];
					if(isset($bgrid[$i][$j]) && $tid!=0 && $tid!=-1 && $tid != $teamid) {
						$tcheck[$tid] += 0;
//						echo "(",$i,",",$j,"): ",$tid,"<br>\n";
						if ($tcheck[$tid]==0 && $tid!=0 && $tid!=-1) {
							$upt = $blockownid[$bgrid[$i+1][$j]]; 
							$dot = $blockownid[$bgrid[$i-1][$j]];
							$let = $blockownid[$bgrid[$i][$j-1]];
							$rit = $blockownid[$bgrid[$i][$j+1]];
							if($tid==4) echo $upt,",",$dot,",",$let,",",$rit,"<br>";
							if(($upt!=$tid && $upt!=$teamid) || ($dot!=$tid && $dot!=$teamid) || ($let!=$tid && $let!=$teamid) || ($rit!=$tid && $rit!=$teamid)) $tcheck[$tid]++;
						}
//						if($tid!=0 && $tid!=-1) echo "TEAM CHECK [",$tid,",",$bid,"]: ",$tcheck[$tid],"<br>";
					}				

				}
			}
			$ctid = 0;
			$ctid = array_search(0,$tcheck);
//			echo "CTID:", $ctid,"<br>";
			if(isset($ctid) && $ctid>0) {
//				echo "FINAL TEAM CHECK: ",$ctid,", ",$tcheck[$ctid],"; ",isset($ctid),"<br>";
                                $impact += $achimpact[$aid];
//				echo "New impact: ",$impact,"<br>";
				achieve($link,$aid,$teamid,$impact);
				$achtext .= "\n".$aname." (for surrounding team ".$teamids[$ctid].")";
				if($achmaxnum[$aid+1]>sizeof($achteam[$aid+1])) {
					addpending($link,($aid+1),$ctid);  // Delayed award until team logs in

//                                      $sql = "SELECT impact_factor FROM teams WHERE teamid = ".$ctid;

//					if($result = mysqli_query($link, $sql)){
//						if(mysqli_num_rows($result)==1) {
//							$otimpact = mysqli_fetch_array($result)['impact_factor'];
//						}
//					}
//					mysqli_free_result($result);
//					$otimpact += $achimpact[$aid+1];
//					achieve($link,($aid+1),$ctid,$otimpact);
				}
			}
			break;
		case ($aid == 41):
			if($newimpact = checkpending($link, $aid, $teamid, ($impact+$achimpact[$aid]), $aname)) {
				$achtext .= "\n".$aname;
				$impact = $newimpact;
                                break;
                        }


			$missingrows = 3;   // Hack for now.  Probably forever.
			$tcheck = 0;
			$last = 0;
                        list($brs,$bss,$bgrid) = getblockgrid($link);
//                      echo "SO: ",sizeof($bgrid).", ",sizeof($bgrid[0]),"<br>";
                        for($i=0;$i<sizeof($bgrid)+$missingrows;$i++) {
///				echo $i." ";
                                for($j=0;$j<sizeof($bgrid[0]);$j++) {
                                        $bid = $bgrid[$i][$j];
                                        $tid = $blockownid[$bid];
//					echo $tid;
                                        if(isset($bgrid[$i][$j]) && $tid==$teamid) {  // Wow this is inefficient...  but it was too easy to steal from the previous routine.
//                                              echo "(",$i,",",$j,"): ",$tid,"<br>\n";
                                                        $nar[$blockownid[$bgrid[$i+1][$j]]]++;
                                                        $nar[$blockownid[$bgrid[$i-1][$j]]]++;
                                                        $nar[$blockownid[$bgrid[$i][$j-1]]]++;
                                                        $nar[$blockownid[$bgrid[$i][$j+1]]]++;
//                                                      if($tid==4) echo $upt,",",$dot,",",$let,",",$rit,"<br>";
                                        }

                                }
//				echo "<br>";
                        }
//			echo "X ".sizeof(array_keys($nar))." - ".in_array($teamid,array_keys($nar));
//			exit;
			if(sizeof(array_keys($nar))-in_array($teamid,array_keys($nar))==1) {
				$oteam = array_keys($nar)[array_keys($nar)[0]==$teamid];   // You are a weird programmer, Darren
//                              echo "FINAL TEAM CHECK: ",array_keys($nar)[0],"/",$teamid,", ",$oteam,"<br>";
				if($oteam != 0) {	
					$impact += $achimpact[$aid];

//                              echo "New impact: ",$impact,"<br>";
					achieve($link,$aid,$teamid,$impact);
					$achtext .= "\n".$aname." (for being surrounded by team ".$teamids[$oteam].")";
					if($achmaxnum[$aid-1]>sizeof($achteam[$aid-1])) {
						addpending($link,($aid-1),$oteam);  
//                                        $sql = "SELECT impact_factor FROM teams WHERE teamid = ".$oteam;
//                                        if($result = mysqli_query($link, $sql)){
//                                                if(mysqli_num_rows($result)==1) {
//                                                        $otimpact = mysqli_fetch_array($result)['impact_factor'];
//                                                }
//                                        }
//                                        mysqli_free_result($result);
//                                        $otimpact += $achimpact[$aid-1];
//                                        achieve($link,($aid-1),$oteam,$otimpact);
					}
// echo "<br>",$oteam,", ",$otimpact,", ",$aid;
				}
                        }

			break;
		case ($aid >= 42 && $aid <= 47):
			$type = array("quest", "quest", "quest", "puzzle", "puzzle", "puzzle")[$aid-42];
			if($result = mysqli_query($link, "SELECT released FROM ".$type."s WHERE released = ".($aid-41)%3))$r = mysqli_num_rows($result);
			$id = 0;
			if ($type == "quest")
			{
				foreach($activequests as $activequest) 
				 	if($activequest['released'] == (($aid-41)%3) && ($activequest['status']%4) == 2) $id++;
			} else {
				foreach($activepuzzles as $activepuzzle) 
                                        if($activepuzzle['released'] == (($aid-41)%3) && ($activepuzzle['status']%4) == 2) $id++;
			}
			if($id>=$r && $r>0) {
				$impact += $achimpact[$aid];
				achieve($link,$aid,$teamid,$impact);
				$achtext .= "\n".$aname;
			}
			break;
		case ($aid >= 48 && $aid <= 50):
			$blocksneeded = array(20,50,100);
			print_r(array_count_values($blockownid));
			if(array_count_values($blockownid)[$teamid]>=$blocksneeded[$aid-48]) {
				$impact += $achimpact[$aid];
				achieve($link,$aid,$teamid,$impact);
				$achtext .= "\n".$aname;
			}
			break;
		}
	}
}
mysqli_close($link);

if(isset($carryoverachtext)) $_SESSION['achievements'] = $carryoverachtext;

//echo "CARRYOVER: ".$carryoverachtext."<br><br>";
//echo "ACHTEXT: ".$achtext."<br><br>";
//echo "SESSION: ".$_SESSION['achievements']."<br><br>";

if($achtext != "") {
	if(isset($carryoverachtext)) $_SESSION['achievements'] .= "\n";
	$_SESSION['achievements'] .= "Congratulations, you have earned the following achievements:".$achtext."\nThis increased your impact factor by ".($impact-$origimpact)."!";
}
}
//echo "CARRYOVER: ".$carryoverachtext."<br><br>";
//echo "ACHTEXT: ".$achtext."<br><br>";
//echo "SESSION: ".$_SESSION['achievements']."<br><br>";
//exit;

echo trim($_GET['l']);
exit;

if(!empty(trim($_GET['l']))) {
	header("Location: ".$_GET['l']);
	exit;
}
	
header("Location: BRCMap_index.php");

?>


<!--CONTENT GOES HERE-->


