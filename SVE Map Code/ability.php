<?php include("db.php");?>  <!-- Login Session and database functions -->
<?php

function error($msg) {
	$_SESSION['abilityerror'] = $msg;
	header("Location: BRCMap_index.php");
	exit;
}

list($blockname,$blockgeom,$blockown,$blockownid,$blocknext,$blocknextid,$teamids)=getblocks($link);
$teamblocks = array_keys($blockownid, $teamid);
$numteamabil = 0;

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
						echo "BLOCK: ".$team['blockid']."<br>";
						$blockl[] = $team['blockid'];
					}

				}
			}
			mysqli_free_result($nresult);

                }
        }
        mysqli_free_result($result);
	echo "BLOCKLIST: ",$blockl,"<p>";
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
		for($i=$sides;$i>=0;$i--) {
//			echo $i,":",$blockside[$i],"->",$blockside[$i-1],"<br>";
			$blockside[$i+1] = $blockside[$i];
		}
		$blockside[0]=$blockside[$sides+1];
		if ($blockside[0]==0) $blockside[0]=$teamid;
//		echo "BS:",$blockside[1];

		foreach($blockside as $i => $team) {
			if(isset($blockside[$i]) && $i<=$sides) {
				$sql = "INSERT INTO blocksides (teamid, blockid, side) VALUES (".$team.", ".$block.", ".$i.")";

				if (isset($blockside[$i+1])) $sql = "UPDATE blocksides SET teamid = ".$team." WHERE blockid = ".$block." AND side = ".$i;

//				echo "FLIPPING: ",$sql,"<br>";
				if (!mysqli_query($link, $sql)) {
					error("Error updating record: " . mysqli_error($link));
				}
			}
		}

	}
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

if(!$teamabil[$ability]) {
        error("Sorry, you don't have that ability.  Frankly, I'm not sure how you even got this message.  Stop cheating.");
}

if($action_points<=0) {
        error("Sorry, you have no action points left. Solve a puzzle or complete a quest to get more!");
}



switch ($ability) {
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
	while (sizeof($newblock)>$oldblock && sizeof($newblock)<sizeof($blocksel) && $j++<10);
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
        while (sizeof($newblock)>$oldblock && sizeof($newblock)<sizeof($blocksel) && $j++<10);
	if(sizeof($newblock)!=sizeof($blocksel))error("Sorry, the blocks you selected must be adjacent to each other.");
	
//	echo "NBA: ".sizeof($newblock).", BS: ",sizeof($blocksel)."<br>\n";

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
	if($impact<sizeof($blocksel)) error("Sorry, you can only occupy as many blocks as your impact factor, minus the number of blocks you jumped.");

//      echo "NB: ".sizeof($newblock).", BS: ",sizeof($blocksel)."<br>\n";
	occupyblocks($link, $teamid, $blocksel);
	
	break;
case 3:
	error("Sorry, I haven't programmed this yet.  Why don't you try an unoccupied block?");
	break;
case 4:
	if ($blockownid[314]==0) {
		occupyblocks($link, $teamid, Array(314));
		echo "OCCUPY: ",$blockownid[314],"<br>\n";
	} else {
		flipblocks($link, $teamid, Array(314));
	}
	break;
case 5;
	error("Sorry, I haven't programmed this yet.  Why don't you try an unoccupied block?");
        break;
case 6:
        error("Sorry, I haven't programmed this yet.  Why don't you try an unoccupied block?");
        break;
case 7:
	error("Sorry, I haven't programmed this yet.  Why don't you try an unoccupied block?");
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

header("Location: BRCMap_index.php");

//echo "<p>Ability #",$ability," clicked.<p>Blocks clicked:\n";
//echo "<p><ul>";
//foreach($blocksel as $value) {
//	echo "<li>",$value;
//};
//echo "</ul>\n";
//?>


