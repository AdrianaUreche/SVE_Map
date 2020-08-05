<?php include("db.php");?>  <!-- Login Session and database functions -->
<?php include("header.php");?>  <!-- Header. Replace if you want to customize -->
<?php include("menubar.php");?>  <!-- Common top menu bar -->

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
		error("Error updating record: " . mysqli_error($link));
	}

	$sql = "INSERT INTO achieved (achid, teamid) VALUES (".$aid.",".$teamid.")";
	if (!mysqli_query($link, $sql)) {
		error("Error updating record: " . mysqli_error($link));
        }

	return true;
}

foreach ($achname as $aid => $aname) {
//	echo "<br>",$aid," -- ",$aname," -- ",sizeof($achteam[$aid])," -- ",$achmaxnum[$aid],"<br>";
	if($achmaxnum[$aid]>sizeof($achteam[$aid]) && !isset($achteam[$aid][$teamid])) {
		switch(true) {
		case ($aid <=28):
//			echo "<br>ACHID: ",$aid,"; BLOCKS: ";
			$by = 0;
			foreach($achblocks[$aid] as $bid) {
				if($blockownid[$bid]==$teamid)$by++;
//				echo $bid.": ".$blockownid[$bid],", TID: ",$teamid,"<br>";
			}
//			echo "BY: ",$by," (out of ",sizeof($achblocks[$aid]),")<br>";
			if(sizeof($achblocks[$aid])==$by) achieve($link,$aid,$teamid,$impact+$achimpact[$aid]);
			break;
		}

	}
}

mysqli_close($link);

header("Location: BRCMap_index.php");

?>


<!--CONTENT GOES HERE-->

<?php include("tail.php");?>  <!-- Contact inf and end body/html tags->

