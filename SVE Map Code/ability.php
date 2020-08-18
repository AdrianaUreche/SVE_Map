<?php include("db.php");?>  <!-- Login Session and database functions -->
<?php

list($blockname,$blockgeom,$blockown,$blockownid,$blocknext,$blocknextid,$teamids)=getblocks($link);
$teamblocks = array_keys($blockownid, $teamid);
$numteamabil = 0;

<<<<<<< HEAD
=======

// I think this isn't used.  Probably doesn't even work.
>>>>>>> origin/devMap
function getneighbors($link, $bid) {

        $sql = "SELECT begin_row, end_row, begin_sec, end_sec FROM blocks WHERE blockid = ".$bid;

//echo "<br>In ISNEIGHBOR(".$bid."): ".$newblock[$bid]."<br>";

        if($result = mysqli_query($link, $sql)){
                if(mysqli_num_rows($result) > 0){
                        list($br, $er, $bs, $es) = mysqli_fetch_array($result);
                        $nsql = "SELECT blocksides.blockid, blocksides.teamid FROM blocks, blocksides WHERE blocksides.side = 0 AND (";
                        for($i=$bs;$i<=$es;$i++) {
                                if ($i!=$bs) $nsql .= " OR ";
                                $nsql .= "(blocks.end_sec >= ".$i." AND blocks.begin_sec <= ".$i." AND (blocks.begin_row = ".($er+1)." OR blocks.end_row = ".($br-1).") AND blocks.blockid = blocksides.blockid)";
                        }
                        for($i=$br;$i<=$er;$i++) {
                                $nsql .= "OR (blocks.end_row >= ".$i." AND blocks.begin_row <= ".$i." AND (blocks.begin_sec = ".($es+1)." OR blocks.end_sec = ".($bs-1).") AND blocks.blockid = blocksides.blockid)";
                        }
                        $nsql.=")";
//                      echo $nsql;
                        if($nresult = mysqli_query($link, $nsql)){
                                if(mysqli_num_rows($nresult) > 0){
                                        while($team=mysqli_fetch_array($nresult)){
<<<<<<< HEAD
						echo "BLOCK: ".$team['blockid']."<br>";
=======
//						echo "BLOCK: ".$team['blockid']."<br>";
>>>>>>> origin/devMap
						$blockl[] = $team['blockid'];
					}

				}
			}
			mysqli_free_result($nresult);

                }
        }
        mysqli_free_result($result);
<<<<<<< HEAD
	echo "BLOCKLIST: ",$blockl,"<p>";
=======
//	echo "BLOCKLIST: ",$blockl,"<p>";
>>>>>>> origin/devMap
        RETURN $blockl;
}

function isneighbor($link, $bid, $teamid, $newblock) {

        $sql = "SELECT begin_row, end_row, begin_sec, end_sec FROM blocks WHERE blockid = ".$bid;

//echo "<br>In ISNEIGHBOR(".$bid."): ".$newblock[$bid]."<br>";

        if($result = mysqli_query($link, $sql)){
                if(mysqli_num_rows($result) > 0){
			list($br, $er, $bs, $es) = mysqli_fetch_array($result);
			$nsql = "SELECT blocksides.blockid, blocksides.teamid FROM blocks, blocksides WHERE blocksides.side = 0 AND (";
			for($i=$bs;$i<=$es;$i++) {
				if ($i!=$bs) $nsql .= " OR ";
				$nsql .= "(blocks.end_sec >= ".$i." AND blocks.begin_sec <= ".$i." AND (blocks.begin_row = ".($er+1)." OR blocks.end_row = ".($br-1).") AND blocks.blockid = blocksides.blockid)";
			}
			for($i=$br;$i<=$er;$i++) {
				$nsql .= "OR (blocks.end_row >= ".$i." AND blocks.begin_row <= ".$i." AND (blocks.begin_sec = ".($es+1)." OR blocks.end_sec = ".($bs-1).") AND blocks.blockid = blocksides.blockid)";
			}
			$nsql.=")";
//			echo $nsql;
			if($nresult = mysqli_query($link, $nsql)){
				if(mysqli_num_rows($nresult) > 0){
					while($team=mysqli_fetch_array($nresult)){
//						echo "TEAMID: ",$team['teamid'],"; BLOCKID: ",$team['blockid'],"<br>";
						if($team['teamid']==$teamid || $newblock[$team['blockid']]==$teamid){
						       		mysqli_free_result($result);
								mysqli_free_result($nresult);
								return TRUE;
						}
					}
				}
			}
		} 
	}
	mysqli_free_result($result);
	mysqli_free_result($nresult);
	RETURN FALSE;
}

function occupyblocks($link, $teamid, $newblock) {

	$sqlroot = "UPDATE blocksides SET teamid = ".$teamid." WHERE blockid = ";
	
	foreach($newblock as $block) {
		$sql = $sqlroot.$block." AND side = 0";
		if (!mysqli_query($link, $sql)) {
			error("Error updating record: " . mysqli_error($link));
		}
	}
	return TRUE;
}

function flipblocks($link, $teamid, $newblock) {

	$sqloneroot = "SELECT num_sides FROM blocks WHERE blockid = ";
	$sqltworoot = "SELECT side,teamid FROM blocksides WHERE blockid = ";

        foreach($newblock as $block) {
	
                $sql = $sqloneroot.$block;
//		echo $sql,"<br>";
		if($result = mysqli_query($link, $sql)) {
			if(mysqli_num_rows($result)==1) {
				$sides = mysqli_fetch_array($result)['num_sides'];
			}
		}
		mysqli_free_result($result);

		$blockside = Array();

		$sql = $sqltworoot.$block;
//		echo $sql,"<br>";

                if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
				while($row=mysqli_fetch_array($result)){
					$blockside[$row['side']] = $row['teamid'];
//					echo "BS:",$blockside[$row['side']],"[",$row['side'],"]<br>";
				}
                        }
                }
//		echo "SIDES: ",$sides;
//	   	if($sides<2)error("Sorry, at least one of the blocks you selected (".$blockname[$block].") has one (or fewer) sides and can't be flipped.  Please consult a topologist.");

		for($i=$sides-1;$i>=0;$i--) {
//			echo $i,":",$blockside[$i],"->",$blockside[$i-1],"<br>";
			$blockside[$i+1] = $blockside[$i];
		}
		$blockside[0]=$blockside[$sides];
		if ($blockside[0]==0) $blockside[0]=$teamid;
//		echo "BS:",$blockside[1];

		foreach($blockside as $i => $team) {
			if(isset($blockside[$i]) && $i<=$sides-1) {
				$sql = "INSERT INTO blocksides (teamid, blockid, side) VALUES (".$team.", ".$block.", ".$i.")";

				if (isset($blockside[$i+1])) $sql = "UPDATE blocksides SET teamid = ".$team." WHERE blockid = ".$block." AND side = ".$i;

//				echo "FLIPPING: ",$sql,"<br>";
				if (!mysqli_query($link, $sql)) {
					error("Error updating record: " . mysqli_error($link));
				}
			}
		}

	}
	return TRUE;
}


function paths($r,$er,$s,$es,$grid, $c,$min) {
//	echo $grid[$r][$s],"(",$r,",",$s,");[",$er,",",$es,"] ";
        if($c>=$min) {
//		echo $c,"<br>";
		return $min;
	}
	if($r==$er && $s==$es) {
//		echo $c,"<br>";
		if ($c<$min) $min=$c;
	return $min;
	}
	if($r!=$er) {
//		echo "CHECK CHECK: ",$grid[$r+($er-$r)/abs($er-$r)][$s],"==",$grid[$r][$s],"<br>";
		if ($grid[$r+($er-$r)/abs($er-$r)][$s]!=$grid[$r][$s]) $c++;
		$min = paths($r+($er-$r)/abs($er-$r),$er,$s,$es,$grid,$c,$min);
	}
	if($s!=$es) {
//		echo "CHECK CHECK: ",$grid[$r][$s+($s-$es)/abs($s-$es)],"==",$grid[$r][$s],"<br>";

		if ($grid[$r][$s+($s-$es)/abs($s-$es)]!=$grid[$r][$s]) $c++;
		$min = paths($r,$er,$s+($es-$s)/abs($es-$s),$es,$grid,$c,$min);
	}
	return $min;
}




if(isset($teamid)){
	list($abilname,$abilfluff,$abildesc,$teamabil)=getabilities($link, $teamid);
	foreach($teamabil as $on){
		if($on)$numteamabil++;
	}
} else {
	header("Location: login.php");
}

$ability = $_POST['ability'];
$blocksel = json_decode($_POST['blocksclicked'], true);

if(!$teamabil[$ability] && $ability!=0) {
        error("Sorry, you don't have that ability.  Frankly, I'm not sure how you even got this message.  Stop cheating.");
}

if($action_points<=0) {
        error("Sorry, you have no action points left. Solve a puzzle or complete a quest to get more!");
}



switch ($ability) {
case 0:
<<<<<<< HEAD
	error ("Sorry, I haven't programmed this yet.");
=======
        if(sizeof($blocksel)==0) error("You didn't select any blocks!");
        if(sizeof($blocksel)>1) error("You can only select one block on which to start (or restart) the game.");
	if($blocksel[0]==0 || $blocksel[0]==314) error("Sorry, you can't occupy The Man or The Temple as your starting position.");

	if($blockownid[$blocksel[0]]!=0) error("Sorry, you can only start the game on an unoccupied playa block.");
        occupyblocks($link, $teamid, $blocksel);

>>>>>>> origin/devMap
	break;
case 1:
	if(sizeof($blocksel)==0) error("You didn't select any blocks!");
	if($impact<sizeof($blocksel)) error("Sorry, you can only occupy as many blocks as your impact factor.");	
	do {
		$oldblock=sizeof($newblock);
		foreach($blocksel as $blockid) {
			if($blockownid[$blockid]!=0) error("Sorry, you can only use the \\\"Occupy Adjacent Blocks\\\" ability on unowned playa blocks.");
//			echo "<br>".$blockid."  NEWBLOCK=",$newblock[$blockid]."<br>";
			if(isneighbor($link, $blockid, $teamid, $newblock))$newblock[$blockid]=$teamid;
//			echo "Number of blocks converted = ".sizeof($newblock)." (Oldblock = ".$oldblock.")<br>";
		}
	}
	while (sizeof($newblock)>$oldblock && sizeof($newblock)<sizeof($blocksel) && $j++<=sizeof($blocksel));
	if(sizeof($newblock)!=sizeof($blocksel))error("Sorry, you are not adjacent to the blocks you selected.");
//	echo "NB: ".sizeof($newblock).", BS: ",sizeof($blocksel)."<br>\n";
	occupyblocks($link, $teamid, $blocksel);
	break;

case 2:
	if(sizeof($blocksel)==0) error("You didn't select any blocks!");
//	echo "BLOCKSEL[0] = ",$blocksel[0];
	$newblock[$blocksel[0]]=$teamid;
        do {
                $oldblock=sizeof($newblock);
                foreach($blocksel as $blockid) {
                        if($blockownid[$blockid]!=0) error("Sorry, you can only use the \\\"Occupy Non-Adjacent Blocks\\\" ability on unowned playa blocks.");
//                      echo "<br>".$blockid."  NEWBLOCK=",$newblock[$blockid]."<br>";
			if(isneighbor($link, $blockid, $teamid, $newblock))$newblock[$blockid]=$teamid;   //		       	error("Sorry, you cannot occupy a block adjacent to one of yours with \\\"Occupy Non-Adjacent Blocks\\\"");
//                      echo "Number of blocks converted = ".sizeof($newblock)." (Oldblock = ".$oldblock.")<br>";
                }
        }
        while (sizeof($newblock)>$oldblock && sizeof($newblock)<sizeof($blocksel) && $j++<=sizeof($blocksel));
	if(sizeof($newblock)!=sizeof($blocksel))error("Sorry, the blocks you selected must be adjacent to each other.");
	
//	echo "NBA: ".sizeof($newblock).", BS: ",sizeof($blocksel)."<br>\n";
<<<<<<< HEAD

        $sql = "SELECT blockid, begin_row, end_row, begin_sec, end_sec FROM blocks";
//	echo $sql;
	if($result = mysqli_query($link, $sql)){
		if(mysqli_num_rows($result) > 0){
		       while(list($bid, $br, $er, $bs, $es) = mysqli_fetch_array($result)) {
			       $brs[$bid] = $br;
			       $bss[$bid] = $bs;
//			       echo $bid,"BRS/BSS: ",$brs[$bid],", ",$bss[$bid],"<br>";
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
=======
        
	list($brs,$bss,$bgrid) = getblockgrid($link);
>>>>>>> origin/devMap

	$minimum=$impact;
	foreach($newblock as $blockid => $value) {
//		echo "NB: ",$blockid,$value,"<br>";
		if(isneighbor($link, $blockid, $teamid, Array())) error("You must select blocks that are not adjacent to blocks you already own.");
//		                        paths(3,1,3,1,$bgrid,0,$impact);

		foreach($teamblocks as $tb) {
//			echo "<p>TB: ",$tb,"; BSS: ",$bss[$tb]."/ BRS: ",$brs[$tb],"<br>";
		    	$localmin = paths($brs[$blockid],$brs[$tb],$bss[$blockid],$bss[$tb],$bgrid,0,$impact); 
			if($minimum>$localmin) $minimum=$localmin;
//			echo "MINIMUM PATH LENGTH FOUND = ",$minimum,"<br>";

		}
	}
	$impact -= ($minimum-1);

	if($impact<sizeof($blocksel)) error("Sorry, you can only occupy as many blocks as your impact factor, minus the number of blocks you jumped (note that it takes three blocks to reach The Man from Esplanade).");

//      echo "NB: ".sizeof($newblock).", BS: ",sizeof($blocksel)."<br>\n";
	occupyblocks($link, $teamid, $blocksel);
	
	break;
case 3:
	if(sizeof($blocksel)==0) error("You didn't select any blocks!");
        if(0.5+$impact/2<sizeof($blocksel)) error("Sorry, you can only flip as many blocks as your impact factor divided by two (rounding up).");
        do {
                $oldblock=sizeof($newblock);
                foreach($blocksel as $blockid) {
//                        if($blockownid[$blockid]!=0) error("Sorry, you can only use the \\\"Occupy Adjacent Blocks\\\" ability on unowned playa blocks.");
//                      echo "<br>".$blockid."  NEWBLOCK=",$newblock[$blockid]."<br>";
                        if(isneighbor($link, $blockid, $teamid, array()) || $blockownid[$blockid]==$teamid)$newblock[$blockid]=$teamid;
//                      echo "Number of blocks converted = ".sizeof($newblock)." (Oldblock = ".$oldblock.")<br>";
                }
        }
        while (sizeof($newblock)>$oldblock && sizeof($newblock)<sizeof($blocksel) && $j++<=sizeof($blocksel));
        if(sizeof($newblock)!=sizeof($blocksel))error("Sorry, you are not adjacent to the blocks you selected.");

	foreach ($newblock as $blockid => $i) {
//        echo "FLIP: ",$blockid," => ",$i,"<br>";

                $sqlroot = "SELECT num_sides,flippable FROM blocks WHERE blockid = ";
		if($blockownid[$blockid]==666)error("Sorry, at least one block you selected ".$blockname[$blockid].") has been destroyed. Radiation abounds. Bring marshmallows.");

                $sql = $sqlroot.$blockid;
//              echo $sql,"<br>";
                if($result = mysqli_query($link, $sql)) {
                        if(mysqli_num_rows($result)==1) {
                                list($sides,$flippable) = mysqli_fetch_array($result);
				if($sides<2)error("Sorry, at least one of the blocks you selected (".$blockname[$blockid].") has one (or fewer) sides and can't be flipped.  Please consult a topologist.");
				if(!$flippable)error("Sorry, at least one block you selected (".$blockname[$blockid].") is asymmetrical and not traditionally flippable.  You might try the Plaza Transport ability.");
//				echo "SIDE: ",$sides,"; ",$flippable."<br>";
			} else {
				error("ARGH, there are the wrong number of blocks with ID=".$blockid." in the database. There should be one. Please report this to an administrator.");
			}
                }
                mysqli_free_result($result);
	}
	flipblocks($link, $teamid, array_keys($newblock));

	break;
case 4:
	if ($blockownid[314]==0) {
		occupyblocks($link, $teamid, Array(314));
//		echo "OCCUPY: ",$blockownid[314],"<br>\n";
	} else {
		flipblocks($link, $teamid, Array(314));
	}
	break;
case 5;
	$dist = [1,2,2,3,4,4,5];
//echo "IMPACT:",$impact,"<br>";
	if(sizeof($blocksel)==0) error("You didn't select any blocks!");
	$flippable = 0;
	$teamsector = array();
	foreach($teamblocks as $blockcheck) {
		$sql = "SELECT time,end_row FROM blocks WHERE flippable=0 AND blockid = ".$blockcheck;
		if($result = mysqli_query($link, $sql)){
			if(mysqli_num_rows($result) == 1){
				$row = mysqli_fetch_array($result);
				$teamsector[$blockcheck]=$dist[substr($row['time'],0,1)-3]+($row['end_row']<8)*0.5;
//				echo "TS: ",$teamsector[$blockcheck],"<br>";
			}
		}
	}

	if(sizeof($teamsector)==0)error("You have to be adjacent to a plaza, plaza road, or Center Camp to use it as a transport");

	foreach($blocksel as $blockid) {
		$sql = "SELECT time,letter,begin_sec,begin_row,num_sides FROM blocks WHERE flippable=0 AND blockid = ".$blockid;
		$min = 6;
		if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) == 1){
				list($time, $letter, $sec, $row, $sel_sides) = mysqli_fetch_array($result);
				if ($sel_sides<2) error("At least one block you selected (".$time."&".$letter.") cannot be flipped it has only one side or less.  Weird, right?  Probably, it's permified.");

				$oppendsec = 31-$sec;
				$oppsql = "SELECT num_sides,blockid FROM blocks WHERE end_sec = ".$oppendsec." AND begin_row = ".$row;                 
				if($nresult = mysqli_query($link, $oppsql)){
					if(mysqli_num_rows($nresult) == 1){
						list($num_sides,$oppblock) = mysqli_fetch_array($nresult);
						if (in_array($oppblock, $blocksel)) error("You shouldn't select both a block and its opposite with which to be switched.");
						$oppblocks[$blockid]=$oppblock;
					}
				}
				mysqli_free_result($nresult);

				if ($num_sides<2) error("At least one block you selected (".$time."&".$letter.") cannot be flipped with the opposite side because the opposite block has only one side or less.  Weird, right?  Probably, it's permified or destroyed.");
				foreach($teamsector as $s) {
//					echo "S: ",$s,"<br>";
					$d=abs($dist[substr($time,0,1)-3]-intval($s))+abs(($row<8)-($s-intval($s))*2);
//					echo "BS:".$blockid."--Old min: ",$min,"; D: ",$d,"; DIST: ",$dist[substr($time,0,1)-3],"; EXT: ",($row<8),"  ",($s-intval($s))*2,"<br>";
					if($min>$d)$min=$d;
				}
			} else {
				error("At least one of the blocks you selected was not next to a plaza.");
			}
		}
		mysqli_free_result($result);
		$impact-=($min+1);
//		echo "IMPACT: ",$impact,"<br>";
	}
//	echo "FINAL IMPACT: ".$impact."<br>";
	if($impact<0) error("You need as much impact factor as the number of blocks to be transported (including the opposing blocks), minus one for each plaza between any of your plaza-adjacent blocks and each of those selected.");
	flipblocks($link, $teamid, array_values($blocksel));
	flipblocks($link, $teamid, array_values($oppblocks));
	foreach($blocksel as $block) {
		$sql = "UPDATE blocksides SET blockid = 999 WHERE blockid = ".$block;
		if (!mysqli_query($link, $sql)) {
			error("Error updating record: " . mysqli_error($link));
		}
		$sql = "UPDATE blocksides SET blockid = ".$block." WHERE blockid = ".$oppblocks[$block];
		if (!mysqli_query($link, $sql)) {
			error("Error updating record: " . mysqli_error($link));
		}      
		$sql = "UPDATE blocksides SET blockid = ".$oppblocks[$block]." WHERE blockid = 999";
		if (!mysqli_query($link, $sql)) {
			error("Error updating record: " . mysqli_error($link));
		}
        }
	
        break;
case 6:
        if(sizeof($blocksel)==0) error("You didn't select any blocks!");
        if($impact-1<sizeof($blocksel)) error("Sorry, you can only permify as many blocks as your impact factor minus one.");
	foreach($blocksel as $blockid) {
		$sql = "UPDATE blocks SET num_sides = 0 WHERE blockid = ".$blockid;
		if (!mysqli_query($link, $sql)) {
			error("Error updating record: " . mysqli_error($link));
		}
	}
        break;
case 7:
	if(sizeof($blocksel)==0) error("You didn't select a block!");
        if(sizeof($blocksel)!=1) error("You can only destroy one block per turn.");

	$sql = "SELECT value FROM blocks WHERE blockid = ".$blocksel[0];

	if($result = mysqli_query($link, $sql)){
		if(mysqli_num_rows($result) == 1){
			$value=mysqli_fetch_array($result)['value'];
                }
	}
	if($impact<$value) error("You must have an impact factor (".$impact.") greater than the value of the block (".$value.") you would like to destroy.");

	$sql = "UPDATE blocks SET num_sides = 0 WHERE blockid = ".$blocksel[0];
	if (!mysqli_query($link, $sql)) {
		error("Error updating record: " . mysqli_error($link));
	}
	$sql = "UPDATE blocksides SET teamid = 666 WHERE side = 0 AND blockid = ".$blocksel[0];
	if (!mysqli_query($link, $sql)) {
                error("Error updating record: " . mysqli_error($link));
        }
        $sql = "DELETE FROM blocksides WHERE side > 0 AND blockid = ".$blocksel[0];
	if (!mysqli_query($link, $sql)) {
                error("Error updating record: " . mysqli_error($link));
        }

	break;
case 8:
	$sql = "UPDATE teams SET impact_factor = ".(2+$impact)." WHERE teamid = ".$teamid;
	if (!mysqli_query($link, $sql)) {
		error("Error updating record: " . mysqli_error($link));
	}
        break;
}

$sql = "UPDATE teams SET action_points = ".(--$action_points)." WHERE teamid = ".$teamid;

//echo $sql."<br>";

if (!mysqli_query($link, $sql)) {
	error("Error updating record: " . mysqli_error($link));
}
mysqli_close($link);

header("Location: achievementcheck.php");

//echo "<p>Ability #",$ability," clicked.<p>Blocks clicked:\n";
//echo "<p><ul>";
//foreach($blocksel as $value) {
//	echo "<li>",$value;
//};
//echo "</ul>\n";
//?>


