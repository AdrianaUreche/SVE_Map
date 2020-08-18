<?php include("db.php");?>  <!-- Login Session and database functions -->
<<<<<<< HEAD
<?php include("header.php");?>  <!-- Header. Replace if you want to customize -->
<?php include("menubar.php");?>  <!-- Common top menu bar -->

=======
>>>>>>> origin/devMap
<?php list($achname, $achdescription, $achfluff, $achimpact, $achmaxnum, $achteam)=getachievements($link); 
list($blockname,$blockgeom,$blockown,$blockownid,$blocknext,$blocknextid,$teamids)=getblocks($link);

$sql = "SELECT achid,blockid FROM achievementblocks";

if($result = mysqli_query($link, $sql)){
	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_array($result)) {
			$achblocks[$row['achid']][] = $row['blockid'];
		}
	}
	mysqli_free_result($result);
}

function achieve($link, $aid, $teamid, $newimp) {
//	echo $teamid," ACHIEVED ACHIEVEMENT #",$aid,"<br>";
	$sql = "UPDATE teams SET impact_factor = ".$newimp." WHERE teamid = ".$teamid;
	if (!mysqli_query($link, $sql)) {
<<<<<<< HEAD
		error("Error updating record: " . mysqli_error($link));
=======
		error("Error1 updating record: " . mysqli_error($link));
>>>>>>> origin/devMap
	}

	$sql = "INSERT INTO achieved (achid, teamid) VALUES (".$aid.",".$teamid.")";
	if (!mysqli_query($link, $sql)) {
<<<<<<< HEAD
		error("Error updating record: " . mysqli_error($link));
=======
		error("Error2 updating record: " . mysqli_error($link));
>>>>>>> origin/devMap
        }

	return true;
}

<<<<<<< HEAD
=======

$origimpact=$impact;
$achtext = "";
if(in_array($teamid,$blockownid)) $achblocks[0][0] = array_search($teamid,$blockownid);

>>>>>>> origin/devMap
foreach ($achname as $aid => $aname) {
//	echo "<br>",$aid," -- ",$aname," -- ",sizeof($achteam[$aid])," -- ",$achmaxnum[$aid],"<br>";
	if($achmaxnum[$aid]>sizeof($achteam[$aid]) && !isset($achteam[$aid][$teamid])) {
		switch(true) {
<<<<<<< HEAD
		case ($aid <=28):
=======
		case ($aid >= 0 && $aid <=29):
>>>>>>> origin/devMap
//			echo "<br>ACHID: ",$aid,"; BLOCKS: ";
			$by = 0;
			foreach($achblocks[$aid] as $bid) {
				if($blockownid[$bid]==$teamid)$by++;
//				echo $bid.": ".$blockownid[$bid],", TID: ",$teamid,"<br>";
			}
//			echo "BY: ",$by," (out of ",sizeof($achblocks[$aid]),")<br>";
<<<<<<< HEAD
			if(sizeof($achblocks[$aid])==$by) achieve($link,$aid,$teamid,$impact+$achimpact[$aid]);
			break;
=======
			if(sizeof($achblocks[$aid])==$by) {
				$impact += $achimpact[$aid];
				achieve($link,$aid,$teamid,$impact);
				$achtext .= "\\n".$aname;
			}
			break;
		case ($aid == 30):
			$missingrows = 3;   // Hack for now.  Probably forever.
			list($brs,$bss,$bgrid) = getblockgrid($link);
//			echo "SO: ",sizeof($bgrid).", ",sizeof($bgrid[0]),"<br>";
			for($i=0;$i<sizeof($bgrid)+$missingrows;$i++) {
				for($j=0;$j<sizeof($bgrid[0]);$j++) {
					$bid = $bgrid[$i][$j];
					$tid = $blockownid[$bid];
					if(isset($bgrid[$i][$j]) && $tid!=0 && $tid!=666 && $tid != $teamid) {
						$tcheck[$tid] += 0;
//						echo "(",$i,",",$j,"): ",$tid,"<br>\n";
						if ($tcheck[$tid]==0 && $tid!=0 && $tid!=666) {
							$upt = $blockownid[$bgrid[$i+1][$j]]; 
							$dot = $blockownid[$bgrid[$i-1][$j]];
							$let = $blockownid[$bgrid[$i][$j-1]];
							$rit = $blockownid[$bgrid[$i][$j+1]];
							if($tid==4) echo $upt,",",$dot,",",$let,",",$rit,"<br>";
							if(($upt!=$tid && $upt!=$teamid) || ($dot!=$tid && $dot!=$teamid) || ($let!=$tid && $let!=$teamid) || ($rit!=$tid && $rit!=$teamid)) $tcheck[$tid]++;
						}
//						if($tid!=0 && $tid!=666) echo "TEAM CHECK [",$tid,",",$bid,"]: ",$tcheck[$tid],"<br>";
					}				

				}
			}
			$ctid = 0;
			$ctid = array_search(0,$tcheck);
			echo "CTID:", $ctid,"<br>";
			if(isset($ctid) && $ctid>0) {
//				echo "FINAL TEAM CHECK: ",$ctid,", ",$tcheck[$ctid],"; ",isset($ctid),"<br>";
                                $impact += $achimpact[$aid];
//				echo "New impact: ",$impact,"<br>";
				achieve($link,$aid,$teamid,$impact);
				$achtext .= "\\n".$aname." (for surrounding team ".$teamids[$ctid].")\\n";
				if($achmaxnum[$aid+1]>sizeof($achteam[$aid+1])) {
					$sql = "SELECT impact_factor FROM teams WHERE teamid = ".$ctid;

					if($result = mysqli_query($link, $sql)){
						if(mysqli_num_rows($result)==1) {
							$otimpact = mysqli_fetch_array($result)['impact_factor'];
						}
					}
					mysqli_free_result($result);
					$otimpact += $achimpact[$aid+1];
					achieve($link,($aid+1),$ctid,$otimpact);
				}
			}
			break;
		case ($aid == 31):
			$missingrows = 3;   // Hack for now.  Probably forever.
			$tcheck = 0;
			$last = 0;
                        list($brs,$bss,$bgrid) = getblockgrid($link);
//                      echo "SO: ",sizeof($bgrid).", ",sizeof($bgrid[0]),"<br>";
                        for($i=0;$i<sizeof($bgrid)+$missingrows;$i++) {
                                for($j=0;$j<sizeof($bgrid[0]);$j++) {
                                        $bid = $bgrid[$i][$j];
                                        $tid = $blockownid[$bid];
                                        if(isset($bgrid[$i][$j]) && $tid==$teamid) {  // Wow this is inefficient...  but it was too easy to steal from the previous routine.
//                                              echo "(",$i,",",$j,"): ",$tid,"<br>\n";
                                                        $nar[$blockownid[$bgrid[$i+1][$j]]]++;
                                                        $nar[$blockownid[$bgrid[$i-1][$j]]]++;
                                                        $nar[$blockownid[$bgrid[$i][$j-1]]]++;
                                                        $nar[$blockownid[$bgrid[$i][$j+1]]]++;
//                                                      if($tid==4) echo $upt,",",$dot,",",$let,",",$rit,"<br>";
                                        }

                                }
                        }
			if(sizeof(array_keys($nar))-in_array($teamid,array_keys($nar))==1) {
				$oteam = array_keys($nar)[array_keys($nar)[0]==$teamid];
//                              echo "FINAL TEAM CHECK: ",array_keys($nar)[0],"/",$teamid,", ",$oteam,"<br>";
                                $impact += $achimpact[$aid];
//                              echo "New impact: ",$impact,"<br>";
                                achieve($link,$aid,$teamid,$impact);
                                $achtext .= "\\n".$aname." (for being surrounded by team ".$teamids[$oteam].")\\n";
                                if($achmaxnum[$aid-1]>sizeof($achteam[$aid-1])) {
                                        $sql = "SELECT impact_factor FROM teams WHERE teamid = ".$oteam;
                                        if($result = mysqli_query($link, $sql)){
                                                if(mysqli_num_rows($result)==1) {
                                                        $otimpact = mysqli_fetch_array($result)['impact_factor'];
                                                }
                                        }
                                        mysqli_free_result($result);
                                        $otimpact += $achimpact[$aid-1];
                                        achieve($link,($aid-1),$oteam,$otimpact);
                                }
echo "<br>",$oteam,", ",$otimpact,", ",$aid;

                        }

			break;

>>>>>>> origin/devMap
		}

	}
}
<<<<<<< HEAD

mysqli_close($link);

=======
mysqli_close($link);

if($achtext != "") $_SESSION['achievements'] = "Congratulations, you have earned the following achievements:\\n".$achtext."\\nThis increased your impact factor by ".($impact-$origimpact)."!";

// echo $_SESSION['achievements'];
>>>>>>> origin/devMap
header("Location: BRCMap_index.php");

?>


<!--CONTENT GOES HERE-->

<<<<<<< HEAD
<?php include("tail.php");?>  <!-- Contact inf and end body/html tags->
=======
>>>>>>> origin/devMap

